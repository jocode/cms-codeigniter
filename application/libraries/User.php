<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# En este caso busca el directorio actual, y carga el ACL
require_once dirname(__FILE__).'/Acl.php';
/**
* Librería de Usuarios
*/
class User {

	private $CI;
	private $table = 'user';
	private $lang;
	private $acl;
	private $errors = [];
	private $user_id;
	private $user_user;
	private $user_name;
	private $user_email;
	private $user_status;
	private $user_active;
	private $pattern = "/^([-a-z0-9_-1])+$/i";

	public function __construct($options = array()){
		$this->CI =& get_instance();

		$this->_set_language(isset($options['lang']) ? $options['lang'] : null);
		$row = null;

		if (isset($options['id']) && ((int) $options['id'] > 0) ){
			$row = $this->_row(['id'=> (int) $options['id'] ]);

			if ( ! $row ){
				show_error($this->CI->lang->line('user_error_invalid_user'));
			}
		} else if ( (int) $this->CI->session->userdata('user_id') > 0){
			$row = $this->_row(['id'=> $this->CI->session->userdata('user_id') ]);

			if ( ! $row || $row->active != 1 || $row->status != 1){
				$this->CI->session->sess_destroy();
				$this->_load(null);
				return;
			}
		}
		$this->_load($row);
	}

	public function __get($name){
		#Lee las propiedades que empiecen por 'user_'
		$property = 'user_' . $name;
		if ( isset($this->$property) ){
			return $this->$property;
		}
	}

	/**
	* Devuelve los errores de la librería
	*/
	public function errors(){
		return $this->errors;
	}

	public function permissions(){
		return $this->acl->permissions;
	}

	public function site_permissions(){
		return $this->acl->site_permissions;
	}

	public function has_permission($name){
		return $this->acl->has_permission($name);
	}

	/**
	* Verifica si el usuario cumple con los requisitos para ingresar al sistema
	*/
	public function login($user, $password, $hash = 'sha256'){

		if (empty($user)){
			$this->errors[] = $this->CI->lang->line('user_error_username');
		}

		if (empty($password)){
			$this->errors[] = $this->CI->lang->line('user_error_empty_password');
		}

		if (count($this->errors)){
			return false;
		}

		# LLama un registro del usuario
		$row = $this->_row(['user'=> $user, 'password'=> sha1($password)]);

		if ( !isset($row) || $row->active != 1 || $row->status != 1){
			$this->errors[] = $this->CI->lang->line('user_error_wrong_credentials');
			return false;
		}
		$this->_load($row);
		return true;
	}

	/**
	* Verifica si el usuario está autenticado en el sistema
	*/
	public function is_logged_in(){
		if ($this->user_id > 0){
			return $this->user_id == (int) $this->CI->session->userdata('user_id');
		}
		return false;
	}

	public function _load($row = null){
		if (! $row){
			$this->user_id = 0;
			$this->user_user = $this->CI->lang->line('cms_general_label_site_visitor_user');
			$this->user_name = $this->CI->lang->line('cms_general_label_site_visitor_name');
			$this->user_email = '';
			$this->user_active = 0;
			$this->user_status = 0;
			# Reinicia la librería ACL
			$this->acl = new ACL(['lang'=>$this->lang]);
			return;
		}

		$this->user_id = $row->id;
		$this->user_user = $row->user;
		$this->user_name =$row->name;
		$this->user_email = $row->email;
		$this->user_active = $row->active;
		$this->user_status = $row->status;

		$this->acl = new ACL(['id'=>$row->id, 'lang'=>$this->lang]);
	}

	/**
	* Carga usuarios desde la base de datos
	*/
	public function _row($where = null, $select = null){
		if (is_array($where)){
			$this->CI->db->where($where);
		}
		if (is_array($select)){
			$this->CI->db->select($select);
		}
		return $this->CI->db->get($this->table)->row();
	}

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

		$this->lang = $lang;
		# Carga el archivo user_lang, para el idioma
		$this->CI->load->language('user', $lang);
	}

}