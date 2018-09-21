<?php
$atras="../../";
require_once $atras . 'vendor/autoload.php';
include_once($atras."lib_gym.php");

global $conexion, $raiz;
$raiz = $atras;

//Inclusiones de la libreria en especifico
include_once($atras."ventanas/usuario/librerias_reporte_usuarios.php");

$inicio = @$_REQUEST["actual_row"];
$cantidad = @$_REQUEST["numfilas"];
$search = @$_REQUEST["search"];
$asc_desc = @$_REQUEST["order"];
$campo_ordenar = @$_REQUEST["sort"];

$order = "";
$where_contenedor = array();
if($search){
	$campos_consulta = array('nombres','apellidos','email','celular','tipo','identificacion');
	$cant_campos_consulta = count($campos_consulta);
	$where_search = array();
	for($i=0;$i<$cant_campos_consulta;$i++){
		$where_search[] = $campos_consulta[$i] . " like '%" . $search . "%'";
	}
	
	$where_contenedor[] = " and (" . implode(" or ", $where_search) . ")";
}
if($campo_ordenar){
	$order .= "order by " . $campo_ordenar . " " . $asc_desc;
}

$sql = "select identificacion, nombres, apellidos, email, celular, tipo from usuario where 1=1 " . implode("",$where_contenedor) . " " . $order;
$datos = $conexion -> listar_datos($sql,$inicio,$cantidad);

$arreglo = array();

//Obteniendo el total de registros de la consulta
$sql_cantidad = "select count(*) as cantidad from usuario where 1=1 " . implode("",$where_contenedor);
$datos_cantidad = $conexion -> listar_datos($sql_cantidad);
$arreglo["total"] = $datos_cantidad[0]["cantidad"];
//-----

//----------------

for($i=0;$i<$datos["cant_resultados"];$i++){
	$datos[$i]["acciones_usuario"]=(acciones_usuario($datos[$i]["identificacion"]));
}
//----------------

unset($datos["sql"]);
unset($datos["cant_resultados"]);

$arreglo["rows"] = $datos;

echo(json_encode($arreglo));
?>