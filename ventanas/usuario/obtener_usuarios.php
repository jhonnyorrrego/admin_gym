<?php
$atras="../../";
require_once $atras . 'vendor/autoload.php';
include_once($atras."lib_gym.php");
global $conexion, $raiz;
$raiz = $atras;

$inicio = @$_REQUEST["actual_row"];
$cantidad = @$_REQUEST["numfilas"];
$search = @$_REQUEST["search"];
$asc_desc = @$_REQUEST["order"];
$campo_ordenar = @$_REQUEST["sort"];

$order = "";
$where = array();
if($search){
	$where[] = " and (nombres like '%" . $search . "%' or apellidos like '%" . $search . "%' or email like '%" . $search . "%' or celular like '%" . $search . "%' or tipo like '%" . $search . "%' or identificacion like '%" . $search . "%')";
}
if($campo_ordenar){
	$order .= "order by " . $campo_ordenar . " " . $asc_desc;
}

$sql = "select identificacion, nombres, apellidos, email, celular, tipo from usuario where 1=1 " . implode("",$where) . " " . $order;
$datos = $conexion -> listar_datos($sql,$inicio,$cantidad);

$arreglo = array();

//Obteniendo el total de registros de la consulta
$sql = "select count(*) as cantidad from usuario where 1=1 " . implode("",$where);
$datos_cantidad = $conexion -> listar_datos($sql);
$arreglo["total"] = $datos_cantidad[0]["cantidad"];
//-----


unset($datos["sql"]);
unset($datos["cant_resultados"]);

$arreglo["rows"] = $datos;

echo(json_encode($arreglo));
?>