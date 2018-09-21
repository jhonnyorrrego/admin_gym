<?php
function acciones_usuario($identificacion){
	global $conexion, $raiz;
	$cadena = "<button class='btn btn-light' id='usuario_" . $identificacion . "'><i class='fas fa-address-card'></i></button>";
	$cadena .= "<button class='btn btn-light' id='usuario_" . $identificacion . "'><i class='fas fa-user-edit'></i></button>";
	$cadena .= "<button class='btn btn-light' id='usuario_" . $identificacion . "'><i class='fas fa-user-minus'></i></button>";
	return($cadena);
}

?>