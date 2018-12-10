<?php
function tipo_usuario_funcion($tipo){
	$cadena = "";
	if($tipo == 1){
		$cadena = "Cliente";
	}else if($tipo == 2){
		$cadena = "Administrador";
	}
	return($cadena);
}
function estado_funcion($estado){
	$cadena = "";
	if($estado == 1){
		$cadena = "Activo";
	}else if($estado == 2){
		$cadena = "Inactivo";
	}
	return($cadena);
}
function acciones_usuario($idusu){
	global $conexion, $raiz;
	$cadena = "";
	$cadena .= "<button class='btn btn-light ver_usuario' idusuario='" . $idusu . "' id='usuario_" . $idusu . "'><i class='fas fa-address-card'></i></button>";
	$cadena .= "<button class='btn btn-light ver_grafico' idusuario='" . $idusu . "' id='usuario_" . $idusu . "'><i class='fas fa-chart-bar'></i></button>";
	return($cadena);
}

?>