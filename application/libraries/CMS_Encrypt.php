<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Librería de Usuarios
*/
class CMS_Encrypt extends CI_Encrypt {

	public function __construct(){
		parent::__construct();
	}

	public function password($data, $algh = 'sha256'){
		if (! $this->CI->config->item('encryption_key')){
			show_error('Encryption key not found');
		}

		$hash = hash_init($algh, HASH_HMAC, $this->CI->config->item('encryption_key'));
		hash_update($hash, $data);
		return hash_final($hash);
	}

}