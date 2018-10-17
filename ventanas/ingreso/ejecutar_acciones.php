<?php
$atras="../../";
require_once $atras . 'vendor/autoload.php';
include_once($atras."lib_gym.php");
include_once($atras."ventanas/librerias/librerias_encriptar.php");

global $conexion, $raiz;
$raiz = $atras;

function validar_ingreso(){
	global $conexion;
	$retorno = array();

	$identificacion = @$_REQUEST["identificacion"];
	$clave = @$_REQUEST["clave"];

	//consulta existencia del usuario
	$sql = "select * from usuario a where a.estado=1 and a.identificacion='" . $identificacion . "'";
	$datos_usuario = $conexion -> listar_datos($sql);
	if($datos_usuario["cant_resultados"]){
		$clave_db = $datos_usuario[0]["clave"];
		if(metodo_encriptar($clave) == $clave_db){
			$retorno["exito"] = 1;
			$retorno["mensaje"] = 'Bienvenido';

			$conexion -> iniciar_variables_sesiones($datos_usuario);

			echo(json_encode($retorno));
		} else {
			$retorno["exito"] = 0;
			$retorno["mensaje"] = 'Clave erronea';

			echo(json_encode($retorno));
		}
	} else {
		$retorno["exito"] = 0;
		$retorno["mensaje"] = 'Usuario no encontrado';

		echo(json_encode($retorno));
	}
}

if(@$_REQUEST["ejecutar"]){
	$_REQUEST["ejecutar"]();
}
?>