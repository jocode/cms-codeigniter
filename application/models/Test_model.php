<?php

/**
* La clase debe extender de CI_Model, que es la clase base del modelo
*/
class Test_model extends CI_Model {

	# Debe inicializarse la base de datos
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function registro($select = null, $where = null, $fetch = null){
		if (is_array($select)){
			$this->db->select($select);
		}

		if (is_array($where)){
			$this->db->where($where);
		}

		if ($fetch == 'object'){
			return $this->db->get('test')->result();
		}
		return $this->db->get('test')->result_array();
	}

}

?>