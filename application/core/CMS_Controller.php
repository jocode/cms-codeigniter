<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CMS_Controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->config('cms');

		if (! $this->config->item('cms_admin_panel_uri') ){
			show_error('Configuration error');
		}
		$this->_set_language();
		$this->lang->load('cms_general');

	}

	/**
	* Determina si estamos trabando en el front o back
	* Debe tener el operador identidad para que pueda funcionar
	*/
	public function admin_panel(){
		return (strtolower($this->uri->segment(1)) === $this->config->item('cms_admin_panel_uri'));
	}

	/**
	* Devuelve la parte de ruta del admin
	*/
	public function admin_panel_uri(){
		return $this->config->item('cms_admin_panel_uri'). '/';
	}

	private function _set_language(){
		$lang = $this->session->userdata('global_lang');
		if ($lang && in_array($lang, $this->config->item('cms_languages'))){
			# Cambiamos un valor para la configuración del idioma
			$this->config->set_item('language', $lang);
		}
	}

}