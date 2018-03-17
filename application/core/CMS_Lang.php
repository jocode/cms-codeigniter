<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Esta clase sobreescribirá el método line, de esta manera sabremos cuál es el elemento que estamos llamando en el idioma, en caso de que esté mal escrito
*/
class CMS_Lang extends CI_Lang {

	public function __construct(){
		parent::__construct();
	}

	/**
	 * Language line
	 *
	 * Fetches a single line of text from the language array
	 *
	 * @param	string	$line		Language line key
	 * @param	bool	$log_errors	Whether to log an error message if the line is not found
	 * @return	string	Translation
	 */
	public function line($line, $log_errors = TRUE)
	{
		$value = parent::line($line, $log_errors);

		// Because killer robots like unicorns!
		if ($value === FALSE){
			return $line;
		}

		return $value;
	}

}