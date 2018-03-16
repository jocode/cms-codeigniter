<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CMS_Controller {

	public function index(){
		$this->template->add_js('template', 'script1', 'utf-8', TRUE, TRUE);
		$this->template->add_js('view', 'script2', 'utf-8', TRUE, FALSE);
		$this->template->add_css('url', 'http://localhost/cms-codeigniter/index.php/test/', 'print');
		$this->template->set('title', 'Test');
		$this->template->set('titulo', 'Mi título');
		$this->template->render('test');
	}

	public function metodo2(){
		echo "<pre>";
		print_r($this->db->get('template')->result());
	}

}

?>