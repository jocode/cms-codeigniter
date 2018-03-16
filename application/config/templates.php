<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

# Configuraciones de la plantilla frontent
$config['template']['frontend']['default'] = array(
	'regions' => ['header', 'main_menu', 'side_bar', 'footer'],
	'scripts' => [
		['type' => 'base', 'value'=>'template_script1', 'options'=> ['charset' => 'utf-8', 'defer'=>TRUE, 'async'=> TRUE] ]
	],
	'styles' => [
		['type' => 'base', 'value'=>'template_style1', 'options'=> ['media' => 'screen'] ]
	]
);
# Configuraciones de la plantilla backend
$config['template']['backend']['default'] = array(
	'regions' => ['header', 'main_menu', 'side_bar', 'footer']
);
