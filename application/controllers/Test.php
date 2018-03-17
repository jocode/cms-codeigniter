<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CMS_Controller {

	public function index(){
		$this->template->set('title', 'Test');
		$this->template->set('titulo', 'Mi título');
		$this->template->render('test');
	}

	public function metodo2(){
		$this->template->set_flash_message(
			['success'=>['hola', 'buenas'], 
			'error'=>['hola', 'buenas'],
			'info'=> ['chao', 'dir']
		]);
		redirect();
		//print_r($this->db->get('template')->result());
	}

}

?>