<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

# Configuraciones de la plantilla frontent
$config['template']['frontend']['default'] = array(
	'regions' => ['header', 'main_menu', 'side_bar', 'footer'],

	# Scripts
	'scripts' => [
		['type' => 'base', 'value'=>'libraries/jquery/jquery-3.2.1.slim.min'],
		['type' => 'base', 'value'=>'bootstrap/v4/bootstrap.min'],
	],
	/*'scripts' => [
		['type' => 'base', 'value'=>'bootstrap/v4/bootstrap.min', 'options'=> ['charset' => 'utf-8', 'defer'=>TRUE, 'async'=> TRUE] ]
	],*/

	# Estilos
	'styles' => [
		['type' => 'base', 'value'=>'bootstrap/v4/bootstrap.min' ],
		['type' => 'template', 'value'=>'product' ],
	]
	/*'styles' => [
		['type' => 'base', 'value'=>'template_style1', 'options'=> ['media' => 'screen'] ]
	]*/
);
# Configuraciones de la plantilla backend
$config['template']['backend']['default'] = array(
	'regions' => ['header', 'main_menu', 'side_bar', 'footer']
);
