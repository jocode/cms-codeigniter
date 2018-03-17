<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language extends CMS_Controller {

	public function change($lang){
		if (in_array($lang, $this->config->item('cms_admin_languages'))){
			$this->session->set_userdata('global_lang', $lang);
		}
		redirect();
	}

}
