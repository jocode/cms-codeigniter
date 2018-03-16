<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CMS_Controller extends CI_Controller {

	private $admin_panel;

	public function __construct(){
		parent::__construct();
		$this->load->config('cms');

		if (! $this->config->item('cms_admin_panel_uri') ){
			show_error('Configuration error');
		}

		# Con substr (0, -1), le quitamos el slash /
		$this->admin_panel = trim(substr($this->config->item('cms_admin_panel_uri'), 0, -1));
	}

	/**
	* Determina si estamos trabando en el front o back
	*/
	public function admin_panel(){
		return strtolower($this->uri->segment(1)) == $this->admin_panel;
	}

	/**
	* Devuelve la parte de ruta del admin
	*/
	public function admin_panel_uri(){
		return $this->config->item('cms_admin_panel_uri');
	}

}