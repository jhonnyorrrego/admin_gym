<?php
@session_start();
include_once("config.php");
require_once 'vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';

$conexion=new lib_gym();
class lib_gym{
	public $con;
	public function __construct(){
		$this->con = mysqli_connect(SERVIDOR,USUARIO,CLAVE) or die ('Error en la conexion');
		$db = mysqli_select_db($this->con,DB) or die ('Error en la DB');
	}
	public function __destruct(){
		mysqli_close($this->con);
	}
	/*Lista en una matriz los resultados de una consulta
	$consulta=sql del select
	*/
	public function listar_datos($consulta,$inicio=false,$cantidad=false){
		$retorno=array();
		if($cantidad){
			$consulta = $this -> listar_datos_limite($consulta,$inicio,$cantidad);
		}
		$res=mysqli_query($this->con,$consulta);
		$cantidad=0;
		while($result = mysqli_fetch_array($res,MYSQL_ASSOC)){
			array_push($retorno,$result);
			$cantidad++;
		}
		$retorno["sql"]=$consulta;
		$retorno["cant_resultados"]=$cantidad;
		mysqli_free_result($res);
		return($retorno);
	}
	/*Inserta en base de datos segun los parametros recibidos
	$tabla=Nombre de la tabla a insertar
	$campos=array con el nombre de los campos a insertar
	$valores=array con los valores a insertar, debe estar en el mismo orden del arreglo de campos
	*/
	public function insertar($tabla,$campos,$valores){
		$sql="insert into ".$tabla."(".implode(",",$campos).")values(".htmlentities(implode(",",$valores)).")";
		mysqli_query($this->con,$sql);
		$id=mysqli_insert_id($this->con);
		return($id);
	}
	/*Detecta si el dispositivo en el que se abrio la aplicacion es movil o computador
	*/
	public function detectar_movil($session){
		$detect = new Mobile_Detect;
		$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

		if($session){
		  @$_SESSION["dispositivo"] = $deviceType;
		}
		return($deviceType);
	}
	
	private function listar_datos_limite($consulta,$inicio,$cantidad){
		$consulta = $consulta . " LIMIT " . $inicio . "," . $cantidad;
		return($consulta);
	}
}
?>