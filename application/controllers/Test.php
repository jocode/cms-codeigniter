<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function index(){
		echo "<pre>";
		$this->load->model('Test_model', 't_model');
		print_r($this->t_model->registro(['nombre', 'direccion'], ['id'=>2], 'object'));
		exit;

		/**
		* Carga las vistas y se le pasa los datos que llevará la vista
		* Los datos se pasan en un arreglo clave => valor
		* Como tercer parámetro, podemos pasar un valor booleano que por defecto es false, que devuelve la vista en una variable
		*/
		$this->load->view('test/test', array('titulo'=>'Un título'));
	}

}

?>