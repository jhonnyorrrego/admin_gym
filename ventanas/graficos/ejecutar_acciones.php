<?php
$atras="../../";
require_once $atras . 'vendor/autoload.php';
include_once($atras."lib_gym.php");
global $conexion, $raiz;
$raiz = $atras;

function obtener_unidad_medida(){
	global $conexion, $atras;
	$datos_filtro = $_REQUEST;

	$datos = $conexion -> obtener_filtro_medida_grafico($datos_filtro);
	unset($datos["cant_resultados"]);
	unset($datos["sql"]);

	echo(json_encode($datos));
}
function obtener_json_datos(){
	global $conexion, $atras;
	$campos = array();
	$valores = array();
	$nuevoArreglo = array();

	$datos_filtro = $_REQUEST;
	
	$data = $conexion -> procesar_filtro_medida_grafico($datos_filtro);

	if($data["cant_resultados"]){
		for ($i=0; $i < $data["cant_resultados"]; $i++) { 
			$mes = explode("-" , $data[$i]["fecha"]);
			$nuevoArreglo[$i]["etiquetas"] = $mes[0] . " " . substr($conexion -> mes($mes[1]),0,3);
			$nuevoArreglo[$i]["valores"] = $data[$i]["valor_medida"];
		}
	}

	echo(json_encode($nuevoArreglo));
}

if(@$_REQUEST["ejecutar"]){
	$_REQUEST["ejecutar"]();
}
?>