<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Clase de lista de control de acceso
*/
class Acl {

	private $CI;
	private $tables = [
		'user' => 'user',
		'roles'=> 'roles',
		'perms'=> 'permissions',
		'user_perms' => 'user_permissions',
		'role_perms' => 'role_permissions'
	];

	private $user_id;
	private $user_role_id;
	private $user_permissions;
	# Los permisos del sitio, son los permisos del contenido
	private $user_site_permissions;

	/**
	* En el constructor enviamos las opciones para inicializar la librería
	*/
	public function __construct($options = array()){
		$this->CI =& get_instance();
		# Cargamos la configiración de ACL
		$this->CI->load->config('acl');

		$this->user_id = isset($options['id']) ? (int) $options['id'] : 0;

		if ($this->user_id > 0){
			# Cargar el role de usuario
			$user_role = $this->CI->db->select('role')->get_where($this->tables['user'], ['id'=>$this->user_id])->row();

			# Setear id del role
			$this->user_role_id = isset($user_role->role) ? (int) $user_role->role : 0;

		}

	# Establecer el lenguaje
	$this->_set_language(isset($options['lang']) ? $options['lang'] : null);

	# Setear permisos de usuario
	/**
	* Primero se carga los permisos del role, y luego se mezcla con los permisos de usuario
	*/
	$this->user_permissions = array_merge($this->role_permissions(), $this->user_permissions());

	# Setear los permisos del sitio
	$this->user_site_permissions = $this->_permissions('acl_site_permissions', 'public');

	}
		
	/**
	* Desde la instancia del objeto podemos acceder a la lectura de estos atributos privados, para no crear un método para cada atributo.
	*/
	public function __get($name){
		#Lee las propiedades que empiecen por 'user_'
		$property = 'user_' . $name;
		if ( isset($this->$property) ){
			return $this->$property;
		}
	}

	/**
	* Devuelve un arreglo unidimensional con los id de los permisos del role que tiene el usuario asignado
	*/
	public function role_permissions_ids(){
		$ids = [];
		if ($this->user_role_id > 0){
			$perms = $this->CI->db
			->select('permission')
			->get_where($this->tables['role_perms'], ['role'=>$this->user_role_id])
			->result_array();

			# Filtramos los resultados
			$ids = array_map(function($item){
				return $item['permission'];
			}, $perms);

			# Elimina del arreglo los elementos vacíos
			array_filter($perms);
		}
		return $ids;
	}

	public function role_permissions(){
		if ($this->user_role_id > 0){

			$permissions = $this->CI->db 
				->from($this->tables['role_perms'] . ' r')
				->select(['r.permission', 'r.value', 'p.name', 'p.title'])
				->join($this->tables['perms'] . ' p', 'r.permission=p.id')
				->where(['r.role' => $this->user_role_id])
				->get()->result();

		    // Si hay perrmisos, devuelve permisos
			if (count($permissions) > 0 ){
				$data = [];

				foreach ($permissions as $permission) {

		   			// Si uno de los permisos está vacío continua el ciclo
					if (trim($permission->name) == '' ) continue;

					$data[$permission->name] = [
						'permission' => $permission->name,
						'titile' => $permission->title,
						'value' => ($permission->value == 1) ? TRUE : FALSE,
						'inherited' => TRUE,
						'id' => $permission->permission,
					];

				}

				if (sizeof($data)){
					return $data;
				}
			}
		}
		return $this->_permission('public');
	}

	/**
	* El método user permissions puede devolver un arreglo vacío, porque ya los permisos se han establecido en el método role permissions
	*/
	public function user_permissions(){

		$data = [];

		if ($this->user_id > 0 && $this->user_role_id > 0){

			$ids = $this->role_permissions_ids();

			if (count($ids) > 0) {
				$permissions = $this->CI->db 
				->from($this->tables['user_perms'] . ' u')
				->select(['u.permission', 'u.value', 'p.name', 'p.title'])
				->join($this->tables['perms'] . ' p', 'u.permission=p.id')
				->where(['u.user' => $this->user_id])
				->get()->result();


		    	// Si hay perrmisos, devuelve permisos
				if (count($permissions) > 0 ){
					$data = [];

					foreach ($permissions as $permission) {

		   			// Si uno de los permisos está vacío continua el ciclo
						if (trim($permission->name) == '' ) continue;

						$data[$permission->name] = [
							'permission' => $permission->name,
							'titile' => $permission->title,
							'value' => ($permission->value == 1) ? TRUE : FALSE,
							'inherited' => FALSE,
							'id' => $permission->permission,
						];

					}

					if (sizeof($data)){
						return $data;
					}
				}
			}
		}
		return $data;
	}

	/**
	* Devuelve un valor de verdadero o falso dependiendo si el usuario tiene un permiso habilitado o no.
	*/
	public function has_permissions($name){
		if (array_key_exists($name, $this->user_permissions)){
			// Verifica si el permiso está activo
			if ($this->user_permissions[$name]['value'] == TRUE){
				return TRUE;
			}
		}
		return FALSE;
	}


	/**
	* Verifica si el permmiso solicitado está dentro de los permisos definidos en la aplicación
	*/
	private function _permissions($line, $default){
		$permissions = $this->CI->config->item($line);
		$result = [];

		if (is_array($permissions) && count($permissions) > 0){

			foreach ($permissions as $permission) {
				if ($this->has_permissions($permission) === TRUE){
					$result[] = $permission;
				}
			}
		}

		if (count($result) == 0){
			$result[] = $default;
		}
		return $default;
	}

	/**
	* Formatea los permisos
	*/
	private function _permission($name){
		$name = trim($name);

		if (! empty($name)){
			$permission = $this->CI->db->get_where($this->tables['perms'], ['name'=>$name])->row();
			
			if ($permission){
				return [$permission->name = [
					'permission' => $permission->name,
					'titile' => $permission->title,
					'value' => TRUE,
					'inherited' => TRUE,
					'id' => $permission->id,
				] ];
			}
		}
		show_error($this->CI->lang->line('acl_error_permission_not_found'));
	}

	/**
	* Definimos los lenguajes disponibles para esta librería. (ACL)
	*/
	private function _set_language($lang = null){
		$languages = ['english', 'spanish'];

		if (! $lang) {
			# Verifica si el idioma de la aplicación está disponible en el arreglo de la librería
			if (in_array($this->CI->config->item('language'), $languages)) {
				$lang = $this->CI->config->item('language');
			} else {
				# Define el Idioma por defecto
				$lang = $languages[0];
			}
		} else {
			if (! in_array($lang, $languages)){
				$lang = $languages[0];
			}
		}

		$this->CI->load->language('acl', $lang);
	}

}