<?php
function mostrarFotoUsuario($idusu){
	global $conexion, $atras;
	$cadena = '';
	$imagen = $conexion -> obtener_imagen_usuario($idusu);
	if(file_exists($atras . $imagen) && $imagen){
		$cadena = '<img class="user-avatar rounded-circle mr-2" src="' . $atras . $imagen . '" style="width:50px">';
	}
	return($cadena);
}
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
	$cadena .= "<button class='btn btn-light ver_usuario' idusuario='" . $idusu . "' id='usuario_" . $idusu . "' title='Ficha'><i class='fas fa-address-card'></i></button>";
	$cadena .= "<button class='btn btn-light ver_grafico' idusuario='" . $idusu . "' id='usuario_" . $idusu . "' title='Estadisticas'><i class='fas fa-chart-bar'></i></button>";
	$cadena .= "<button class='btn btn-light ingresar_usuario' idusuario='" . $idusu . "' id='usuario_" . $idusu . "' title='Ingresar'><i class='fas fa-door-open'></i></button>";
	return($cadena);
}
function dias_faltantes_usuarios($idusu){
	global $conexion, $raiz;
	$cadena = $conexion -> obtener_dias_faltantes($idusu);

	return($cadena);
}
?>