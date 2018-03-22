<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CMS_Controller {


	public function index()
	{
		$this->load->library('user', ['id'=>1]);
		$this->template->set('title', 'Welcome');
		$this->template->render('welcome/index');
	}

}
