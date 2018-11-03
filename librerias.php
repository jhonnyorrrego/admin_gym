<?php
function estilos_generales(){
	global $raiz; 
	$texto='<link rel="stylesheet" type="text/css" href="' . $raiz . 'css/gym.css">';
	$texto.='<script src="' . $raiz . 'js/gym.js"></script>';
	return($texto);
}
function bootstrap_css(){
	global $raiz; 
	$texto='<link rel="stylesheet" type="text/css" href="' . $raiz . 'vendor/twbs/bootstrap/dist/css/bootstrap.css">';
	return($texto);
}
function bootstrap_js(){
	global $raiz; 
	$texto='<script src="' . $raiz . 'vendor/twbs/bootstrap/dist/js/bootstrap.js"></script>';
	return($texto);
}
function jquery_js(){
	global $raiz; 
	$texto='<script src="' . $raiz . 'vendor/components/jquery/jquery.js"></script>';
	return($texto);
}
function notificacion(){
	global $raiz; 
	//$texto='<script src="' . $raiz . 'js/jquery.growl.js"></script>';
	//$texto.='<link rel="stylesheet" type="text/css" href="' . $raiz . 'css/jquery.growl.css">';

	$texto='<script src="' . $raiz . 'js/pnotify.custom.js"></script>';
	$texto.='<script src="' . $raiz . 'js/librerias_notificacion.js"></script>';
	$texto.='<link rel="stylesheet" type="text/css" href="' . $raiz . 'css/pnotify.custom.css">';
	return($texto);
}
function login_css(){
	global $raiz;
	$texto='<link rel="stylesheet" type="text/css" href="' . $raiz . 'css/login.css">';
	return($texto);
}
function jquery_validate(){
	global $raiz; 
	$texto='<script src="' . $raiz . 'js/jquery.validate.js"></script>';
	return($texto);
}
function bootstrap_table(){
	global $raiz;
	$texto='<script src="' . $raiz . 'js/bootstrap-table.js"></script>';
	$texto.='<script src="' . $raiz . 'js/locale/bootstrap-table-es-ES.js"></script>';
	$texto.='<link rel="stylesheet" type="text/css" href="' . $raiz . 'css/bootstrap-table.css">';
	return($texto);
}
function bootstrap_datepicker(){
	global $raiz;
	$texto='<script src="' . $raiz . 'js/datepicker/bootstrap-datepicker.js"></script>';
	$texto.='<link rel="stylesheet" type="text/css" href="' . $raiz . 'css/datepicker/bootstrap-datepicker.css">';
	$texto.='<script src="' . $raiz . 'js/datepicker/locales/bootstrap-datepicker.es.js"></script>';
	return($texto);
}
function estilos_iconos(){
	global $raiz;
	$texto='<link rel="stylesheet" type="text/css" href="' . $raiz . 'css/css/all.css">';
	return($texto);
}
function estilo_cargando(){
	global $raiz;
	$texto='<link rel="stylesheet" type="text/css" href="' . $raiz . 'css/cargando.css">';
	return($texto);
}
function date_format_jquery(){
	global $raiz;
	$texto='<script src="' . $raiz . 'js/jquery-dateformat.js"></script>';
	return($texto);
}
?>