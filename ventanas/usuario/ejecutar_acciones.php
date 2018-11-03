<?php
$atras="../../";
require_once $atras . 'vendor/autoload.php';
include_once($atras."lib_gym.php");
global $conexion, $raiz;
$raiz = $atras;

function guardar_usuario_formulario(){
	global $conexion, $atras;
	include_once($atras . "ventanas/librerias/librerias_encriptar.php");

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
		if($llave == 'clave'){
			$campos[] = $llave;
			$valores[] = "'" . metodo_encriptar($val) . "'";
		} else {
			$campos[] = $llave;
			$valores[] = "'" . $val . "'";
		}
	}
	$campos[] = "fecha";
	$valores[] = "date_format('" . date('Y-m-d H:i:s') . "', '%Y-%m-%d %H:%i:%s')";
	
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
	global $conexion, $atras;
	$retorno = array();

	$idusu = @$_REQUEST["idusu"];
	$idanexos = $conexion -> procesar_anexos($idusu, $atras);

	$tabla = 'usuario';
	$valor_guardar[] = "imagen='" . $idanexos . "'";
	$condicion = "idusu=" . $idusu;

	$conexion -> modificar($tabla,$valor_guardar,$condicion,$idusu);

	if($idanexos){
		$imagen = $atras . ALMACENAMIENTO .  $conexion -> obtener_imagen_usuario($idusu);

		$retorno["exito"] = 1;
		$retorno["mensaje"] = 'Imagen guardada';
		$retorno["imagen"] = $imagen;
	} else {
		$retorno["exito"] = 0;
		$retorno["mensaje"] = 'Problemas al guardar la imagen';
	}

	echo json_encode($retorno);
}
function modificar_unico_usuario(){
	global $conexion;
	$retorno = array();

	$nombre = @$_REQUEST["nombre"];
	$valor = @$_REQUEST["valor"];
	$id = @$_REQUEST["id"];
	$tabla = 'usuario';
	$condicion_update = "idusu=" . $id;
	$tipo = @$_REQUEST["tipo"];

	if($tipo == 'texto'){
		$valor = "'" . $valor . "'";
	}

	$valor_guardar = array();
	$valor_guardar[] = $nombre . "=" . $valor;

	$conexion -> modificar($tabla,$valor_guardar,$condicion_update,$id);

	$retorno["exito"] = 1;
	$retorno["mensaje"] = 'Modificacion realizada';

	echo json_encode($retorno);
}
function agregar_mensualidad(){
	global $conexion;
	$retorno = array();

	$fechai = @$_REQUEST["fechai"];
	$fechaf = @$_REQUEST["fechaf"];
	$id = @$_REQUEST["id"];
	$tabla = 'mensualidad';
	$condicion_update = "idusu=" . $id;

	//Parseando arreglo para insertar
	$campos_insertar = array('fechai','fechaf','idusu','estado');
	$valores_insertar = array();
	$valores_insertar[] = "date_format('" . $fechai . "', '%Y-%m-%d')";
	$valores_insertar[] = "date_format('" . $fechaf . "', '%Y-%m-%d')";
	$valores_insertar[] = $id;
	$valores_insertar[] = 1;

	$resultado = $conexion -> insertar($tabla,$campos_insertar,$valores_insertar);

	//Parseando arreglo para modificar en la tabla del usuario
	$valor_guardar = array();
	$valor_guardar[] = "fechai=date_format('" . $fechai . "', '%Y-%m-%d')";
	$valor_guardar[] = "fechaf=date_format('" . $fechaf . "', '%Y-%m-%d')";

	$conexion -> modificar('usuario',$valor_guardar,$condicion_update,$id);

	if($resultado){
		$retorno["mensaje"] = "Mensualidad asignada!";
		$retorno["exito"] = 1;
		$retorno["html"] = $conexion -> obtener_texto_mensualidad($id);
	}else{
		$retorno["exito"] = 0;
		$retorno["mensaje"] = "Problemas en la inserci&oacute;n";
	}
	echo(json_encode($retorno));
}

if(@$_REQUEST["ejecutar"]){
	$_REQUEST["ejecutar"]();
}
?>