<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CMS_Controller {


	public function index()
	{
		$this->load->library('acl', ['id'=>1]);
		echo '<pre>';
		print_r($this->acl->permissions); exit;

		$this->template->set('title', 'Welcome');
		$this->template->render('welcome/index');
	}

}
