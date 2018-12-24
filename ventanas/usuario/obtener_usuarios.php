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
$search = @$_REQUEST["buscar"];
$asc_desc = @$_REQUEST["order"];
$campo_ordenar = @$_REQUEST["sort"];

$vencidos = @$_REQUEST["vencidos"];
$por_vencerse = @$_REQUEST["por_vencerse"];

$order = "";
$where_contenedor = array();
$hoy = date('Y-m-d');

if($campo_ordenar){
	$order .= "order by " . $campo_ordenar . " " . $asc_desc;
}

if($search){
	$campos_consulta = array('nombres','apellidos','email','celular','tipo','identificacion');
	$cant_campos_consulta = count($campos_consulta);
	$where_search = array();
	for($i=0;$i<$cant_campos_consulta;$i++){
		$where_search[] = $campos_consulta[$i] . " like '%" . $search . "%'";
	}
	
	$where_contenedor[] = " and (" . implode(" or ", $where_search) . ")";
}
if($vencidos){
	$where_contenedor[] = " and date_format(fechaf,'%Y-%m-%d')<'" . $hoy . "'";
	//$order = 'order by fechaf asc';
}
if($por_vencerse){
	$where_contenedor[] = " and date_format(fechaf,'%Y-%m-%d')>'" . $hoy . "'";
	//$order = 'order by fechaf asc';
}

$sql = "select idusu, identificacion, nombres, apellidos, email, celular, tipo, estado, date_format(fechai,'%Y-%m-%d') as x_fechai, date_format(fechaf,'%Y-%m-%d') as x_fechaf from usuario where 1=1 " . implode("",$where_contenedor) . " " . $order;
$datos = $conexion -> listar_datos($sql,$inicio,$cantidad);

$arreglo = array();

//Obteniendo el total de registros de la consulta
$sql_cantidad = "select count(*) as cantidad from usuario where 1=1 " . implode("",$where_contenedor);
$datos_cantidad = $conexion -> listar_datos($sql_cantidad);
$arreglo["total"] = $datos_cantidad[0]["cantidad"];
//-----

//----------------

for($i=0;$i<$datos["cant_resultados"];$i++){
	$datos[$i]["acciones_usuario"]=(acciones_usuario($datos[$i]["idusu"]));
	$datos[$i]["tipo_usuario_funcion"]=(tipo_usuario_funcion($datos[$i]["tipo"]));
	$datos[$i]["estado_funcion"]=(estado_funcion($datos[$i]["estado"]));
	$datos[$i]["dias_faltantes"]=(dias_faltantes_usuarios($datos[$i]["idusu"]));
}
//----------------

unset($datos["sql"]);
unset($datos["cant_resultados"]);

$arreglo["rows"] = $datos;

echo(json_encode($arreglo));
?>