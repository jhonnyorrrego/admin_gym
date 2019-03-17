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
	if(@$_SESSION["dispositivo"] == 'computer'){
		$cadena .= '<div class="list-group">';
		$cadena .= "<button class='btn btn-light ver_usuario list-group-item' idusuario='" . $idusu . "' id='usuario_" . $idusu . "' title='Ficha'><i class='fas fa-address-card'></i></button>";
		$cadena .= "<button class='btn btn-light ver_grafico list-group-item' idusuario='" . $idusu . "' id='usuario_" . $idusu . "' title='Estadisticas'><i class='fas fa-chart-bar'></i></button>";
		$cadena .= "<button class='btn btn-light ingresar_usuario list-group-item' idusuario='" . $idusu . "' id='usuario_" . $idusu . "' title='Ingresar'><i class='fas fa-door-open'></i></button>";
		$cadena .= '</div>';
	} else if(@$_SESSION["dispositivo"] == 'phone'){
		$cadena .= '<div class="text-center">';
		$cadena .= "<button class='btn btn-light ver_usuario' idusuario='" . $idusu . "' id='usuario_" . $idusu . "' title='Ficha'><i class='fas fa-address-card'></i></button>";
		$cadena .= "<button class='btn btn-light ver_grafico' idusuario='" . $idusu . "' id='usuario_" . $idusu . "' title='Estadisticas'><i class='fas fa-chart-bar'></i></button>";
		$cadena .= "<button class='btn btn-light ingresar_usuario' idusuario='" . $idusu . "' id='usuario_" . $idusu . "' title='Ingresar'><i class='fas fa-door-open'></i></button>";
		$cadena .= '</div>';
	}
	return($cadena);
}
function dias_faltantes_usuarios($idusu){
	global $conexion, $raiz;
	$cadena = $conexion -> obtener_dias_faltantes($idusu);

	return($cadena);
}
function ultimoAcceso($idusu){
	global $conexion;
	$ultimo_acceso = $conexion -> ultimo_acceso_usuario($idusu);
	$cadena = '';
	$cadena .= '<div id="capaUltimoAcceso_' . $idusu . '">' . $ultimo_acceso . '</div>';

	return($cadena);
}
function tipoPago($tipo_mensualidad){
	$cadena = '';
	if($tipo_mensualidad == 1){
		$cadena = 'Mensualidad';
	} else if($tipo_mensualidad == 2){
		$cadena = 'Cantidad de d&iacute;as';
	} else {
		$cadena = "<span class='badge badge-danger'>Sin asignaci&oacute;n</span>";
	}

	return($cadena);
}
?>