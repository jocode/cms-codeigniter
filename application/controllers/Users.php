<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CMS_Controller {

	public function login(){

		# El objto input, es para trabajar con datos del formulario. En este caso usaremos los datos que vienen por vía post
		if ($this->input->post('login') == 1){

			# Le indicamos qué campos vamos a validar, y cuáles son las reglas a validar 
			$rules = [
				[
					'field'=>'user',
					'label'=>'lang:cms_general_label_user',
					'rules'=>'required|trim|alpha_dash|max_length[30]'
				],
				[
					'field'=>'password',
					'label'=>'lang:cms_general_label_password',
					'rules'=>'required|trim|max_length[30]'
				],
			];

			# Establecemos las reglas en el form validation
		    $this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE){
				if ($this->user->login($this->input->post('user'), $this->input->post('password')) === TRUE ){

					$this->session->set_userdata('user_id', $this->user->id);
					redirect();
				}
				$this->template->add_message(['error'=>$this->user->errors()]);
			}
		}
		$this->template->set('title', 'Login');
		$this->template->render('users/login');
	}

	public function logout(){
		if ($this->user->is_logged_in()){
			$this->session->sess_destroy();
		}
		redirect();
	}

}