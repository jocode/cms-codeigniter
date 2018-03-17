<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Esta clase gestiona las plantillas que se usen en la aplicación
*/
class Template {

	protected $CI;
	private $configs;
	private $data;
	private $js;
	private $css;
	private $table;
	private $id;
	private $name;
	private $default_id;
	private $default_name;
	private $message;
	private $panel;

	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->load->config('templates');
		$this->configs = $this->CI->config->item('template');
		
		# Data guardará los datos para las vistas
		$this->data = [];

		$this->js = [];
		$this->css = [];

		# Table guardará el nombre de la tabla de template
		$this->table = 'template';

		$this->id = null; # Guarda el id de la plantilla actual
		$this->name = null; # Guarda el nombre de la plantilla actual
		$this->default_id = null; # Guarda el id de la plantilla por defecto
		$this->default_name = null; # Guarda el nombre de la plantilla por defecto

		$this->panel = $this->CI->admin_panel() ? 'backend' : 'frontend';
	}

	/**
	* Definimos los valores que pasamos a la vista
	*/
	public function set($key, $value){
		if (! empty($key)){
			$this->data[$key] = $value;
		}
	}

	/**
	* Método que cargará las vistas
	*/
	public function render($view = null){
		$template = $this->_route();
		$routes = [];
		
		if ( ! empty($view) ){
			if ( ! is_array($view) ){
				$view = [$view];
			}
			

			foreach ($view as $file) {
				$route = ($this->panel == 'backend') ? 'admin/' : '';
				$route .= $this->name . '/html/' .str_replace('admin/', '', $file);

				if (file_exists(APPPATH . 'views/templates/'.$route.'.php')){
					$routes[] = APPPATH . 'views/templates/'.$route.'.php';
				} else if ( file_exists(APPPATH . 'views/'.$file.'.php') ){
					$routes[] = APPPATH . 'views/'.$file.'.php';
				} else {
					print_r($routes);
					show_error('Error View');
				}
			}
		}

		#  Establecemos los archivos y mensajes que usará la vista
		$this->_set_assets();
		$this->_set_messages();
			# Variable de la ruta de la administración para mostrarla en la vista
		$this->data['_admin_panel_uri'] = $this->CI->admin_panel_uri();
			# Todas las vistas las enviamos en la variable _content para cargarlas en template.php
		$this->data['_content'] = $routes;

		$this->CI->load->view($template, $this->data);
	}

	public function add_js($type, $value, $charset = null, $deffer = null, $async = null){
		$this->_add_asset($type, $value, ['charset' => $charset, 'defer'=>$deffer, 'async'=> $async], 'script');
	}

	public function add_css($type, $value, $media = null){
		$this->_add_asset($type, $value, ['media' => $media], 'style');
	}

	/**
	* Agrega los mensajes desde el controlador
	*/
	public function add_message($message, $type = null){
		$this->_add_message($message, $type);
	}

	public function set_flash_message($message){
		if (sizeof($message > 0))
			$this->CI->session->set_flashdata('_message_', $message);
	}

	/**
	* Método que definirá el template por defecto
	* @return Devuelve la ruta del template
	*/
	private function _route(){
		$route = 'templates/';
		if (empty($this->name)){
			$template = $this->CI->db->select(['id', 'name'])
			->get_where($this->table, ['panel'=> $this->panel, 'default' => 1])->row();
			if (empty($template->name)){
				show_error('Template error');
			}

			$this->name = $template->name;
		}

		$route.= $this->panel == 'backend' ? 'admin/' : '';
		$route.= $this->name.'/template.php';

		if (! file_exists(APPPATH . 'views/'.$route) ){
			show_error('Templated no found');
		}
		return $route;
	}

	private function _add_asset($type, $value, $options = array(), $asset_type){
		if (!empty($type)){
			$asset = [];
			if (is_array($value)){
				foreach ($value as $valor) {
					$asset[] = [
						'type'=>$type,
						'value'=>$valor,
						'options'=>$options
					];
				}
			} else {
				$asset[] = [
					'type'=>$type,
					'value'=>$value,
					'options'=>$options
				];
			}

			# Verificamos el tipo se archivo
			if ($asset_type == 'script'){
				$this->js = array_merge($this->js, $asset);
			} else if ($asset_type == 'style'){
				$this->css = array_merge($this->css, $asset);
			}
		}
	}

	/**
	* Va a construir el html final de los scripts
	*/
	private function _set_assets(){
		# Verificamos si hay una plantilla
		if (!empty($this->name)){

			$panel = $this->panel == 'backend' ? 'backend' : 'frontend';

			# Buscamos los script de las plantillas
			if (isset($this->configs[$panel][$this->name]['scripts']) and count($this->configs[$panel][$this->name]['scripts']) ){
				$scripts = $this->js;
				$this->js = [];

				# Cargar archivos JS del template
				foreach ($this->configs[$panel][$this->name]['scripts'] as $script) {
					$this->_add_asset($script['type'], $script['value'], isset($script['options']) ? $script['options'] : array(), 'script');
				}
				$this->js = array_merge($this->js, $scripts);
			}

			if (isset($this->configs[$panel][$this->name]['styles']) and count($this->configs[$panel][$this->name]['styles']) ){
				$styles = $this->css;
				$this->css = [];

				# Cargar archivos JS del template
				foreach ($this->configs[$panel][$this->name]['styles'] as $style) {
					$this->_add_asset($style['type'], $style['value'], isset($style['options']) ? $style['options'] : array(), 'style');
				}
				$this->css = array_merge($this->css, $styles);
			}
		}

		# Estas variables van a contener el código final
		$_css = $_js = ''; # Hacemos asignacion entre variables, para usar un sóla linea de código.
		$panel = $this->panel == 'backend' ? 'admin/' : '';

		if (count($this->js) > 0){

			foreach ($this->js as $js) {
				$defer = $async = $charset = '';

				if (isset($js['options'])){
					$defer = isset($js['options']['defer']) ? 'defer' : '';
					$async = isset($js['options']['async']) ? 'async' : '';
					$charset = isset($js['options']['charset']) ? 'charset="'.$js['options']['charset'].'"' : '';
				}

				$src = base_url().'assets/scripts/';

				# Determina donde ir a buscar el script
				switch ($js['type']) {
					case 'base':
					$src .= $js['value'] . '.js' ;
					break;
					case 'template':
					$src.= 'templates/'.$panel . $this->name .'/' . $js['value'] . '.js' ;
					break;
					case 'view':
					$src.= 'views/'. $js['value'] . '.js' ;
					break;
					case 'url':
					$src = $js['value'];
					break;
					default:
					$src = '';
					break;
				}

				$_js .= sprintf('<script type="text/javascript" src="%s" %s %s %s></script>', $src, $charset, $defer, $async);

			}
		}

		if (count($this->css) > 0){

			foreach ($this->css as $css) {
				$media = '';

				if (isset($css['options'])){
					$media = isset($css['options']['media']) ? 'media="'.$css['options']['media'].'"' : '';
				}

				$href = base_url().'assets/styles/';

				# Determina donde ir a buscar el script
				switch ($css['type']) {
					case 'base':
					$href.= $css['value'] . '.css';
					break;

					case 'template':
					$href.= 'templates/'.$panel . $this->name .'/' . $css['value'] . '.css' ;
					break;
					case 'view':
					$href.= 'views/'. $css['value'] . '.css' ;
					break;
					case 'url':
					$href = $css['value'] ;
					break;
					default:
					$href = '';
					break;
				}

				$_css .= sprintf('<link type="text/css" rel="stylesheet" href="%s" %s />', $href, $media);

			}
		}

		$this->data['_js'] = $_js;
		$this->data['_css'] = $_css;
	}

	/**
	* Agregamos el mensaje al atributo mensaje de la clase
	*/
	private function _add_message($message, $type = null){
		if (! empty($message)){
			$types = ['warning', 'success', 'error', 'info'];
			
			$check_type = function($_type) use ($types) {
				return (empty($_type) || !in_array($_type, $types) ) ? 'warning' : $_type;
			};

			if (is_array($message)){

				# Arreglo clave valor ['error' => 'Valor']
				foreach ($message as $type => $msg) {
					if (!empty($msg)){
						$type = $check_type($type);

						if (is_array($msg)){
							foreach ($msg as $_msg) {
								if (!empty($_msg))
									$this->message[$type][] = (string) $_msg;
							}
						} else {
							$this->message[$type][] = (string) $msg;
						}
					}
				}
			} else {
				$type = $check_type($type);
				$this->message[$type][] = (string) $message;
			}
		}
	}

	/**
	* Este método define las variables de los mensajes que se van a enviar en la vista
	*/
	private function _set_messages(){
		$this->_add_message($this->CI->session->flashdata('_message_'));
		$this->data['_warning'] = isset($this->message['warning']) ? $this->message['warning'] : [];
		$this->data['_success'] = isset($this->message['success']) ? $this->message['success'] : [];
		$this->data['_error'] = isset($this->message['error']) ? $this->message['error'] : [];
		$this->data['_info'] = isset($this->message['info']) ? $this->message['info'] : [];
	}

}