<?php
$atras="../../";
require_once $atras . 'vendor/autoload.php';
include_once($atras."lib_gym.php");
global $conexion, $raiz;
$raiz = $atras;

function guardar_usuario_formulario(){
	global $conexion;
	$retorno = array();
	
	$existencia = consultar_existencia($_REQUEST["identificacion"],2);
	
	if($existencia){//Identificacion ya se encuentra registrada
		$retorno["mensaje"] = "Identificacion existente";
		$retorno["exito"] = 0;
		echo(json_encode($retorno));
		die();
	}
	unset($_REQUEST["ejecutar"]);
	
	foreach($_REQUEST as $llave => $val){
		$campos[] = $llave;
		$valores[] = "'" . $val . "'";
	}
	
	$resultado = $conexion -> insertar('usuario',$campos,$valores);
	if($resultado){
		$retorno["mensaje"] = "Usuario registrado!";
		$retorno["exito"] = 1;
	}else{
		$retorno["exito"] = 0;
		$retorno["mensaje"] = "Problemas en la inserci&oacute;n";
	}
	echo(json_encode($retorno));
}
function validar_cedula(){
	global $conexion;
	$identificacion = @$_REQUEST["identificacion"];
	$resultado = consultar_existencia($identificacion);
	
	echo(json_encode($resultado));
}
function consultar_existencia($identificacion,$tipo_retorno=1){
	global $conexion;
	$existe = False;
	
	$sql = "select identificacion from usuario where identificacion=" . $identificacion;
	$datos = $conexion -> listar_datos($sql);
	if($datos["cant_resultados"]){
		$existe = True;
	}
	
	if($tipo_retorno == 1){
		$retorno = array();
		
		if($existe){
			$retorno["mensaje"] = "Identificacion existente";
			$retorno["exito"] = 0;
		}else{
			$retorno["exito"] = 1;
		}
		return($retorno);
	}else if($tipo_retorno == 2){
		return($existe);
	}
}
function guardar_imagen(){
	global $conexion;
	echo("hola");
	print_r($_FILES);
	
}

if(@$_REQUEST["ejecutar"]){
	$_REQUEST["ejecutar"]();
}
?>