<?php
$atras="../../";
require_once $atras . 'vendor/autoload.php';
include_once($atras."lib_gym.php");
global $conexion, $raiz;
$raiz = $atras;

function obtener_gestion(){
	global $conexion, $atras;
	$retorno = array();
	$nuevoArreglo = array();
	$nuevoArreglo2 = array();
	$cantidad_mensualidad = 0;
	$cantidad_ingreso = 0;

	$datos_filtro = $_REQUEST;
	
	$data = $conexion -> procesar_filtro_gestion($datos_filtro);

	if($data["datos_mensualidad"]["cant_resultados"]){
		for ($i=0; $i < $data["datos_mensualidad"]["cant_resultados"]; $i++) { 
			$fechaArray = explode("-" , $data["datos_mensualidad"][$i]["fecha"]);
			$nuevoArreglo[$i]["etiquetas"] = substr($conexion -> mes($fechaArray[1]),0,3) . " " . substr($fechaArray[0],-2);
			$nuevoArreglo[$i]["valores"] = $data["datos_mensualidad"][$i]["valor"];

			$cantidad_mensualidad += $data["datos_mensualidad"][$i]["valor"];
		}

		$retorno["datos_mensualidad"] = $nuevoArreglo;
		$retorno["cantidad_mensualidad"] = number_format($cantidad_mensualidad,0,",",".");
	}
	if($data["datos_ingreso"]["cant_resultados"]){
		for ($i=0; $i < $data["datos_ingreso"]["cant_resultados"]; $i++) { 
			$fechaArray = explode("-" , $data["datos_ingreso"][$i]["fecha"]);
			$nuevoArreglo2[$i]["etiquetas"] = $fechaArray[2] . " " . substr($conexion -> mes($fechaArray[1]),0,3) . " " . substr($fechaArray[0],-2);
			$nuevoArreglo2[$i]["valores"] = $data["datos_ingreso"][$i]["cantidad"];

			$cantidad_ingreso += $data["datos_ingreso"][$i]["cantidad"];
		}

		$retorno["datos_ingreso"] = $nuevoArreglo2;
		$retorno["cantidad_ingreso"] = $cantidad_ingreso;
	}

	$retorno["exito"] = 1;

	echo(json_encode($retorno));
}

if(@$_REQUEST["ejecutar"]){
	$_REQUEST["ejecutar"]();
}
?>