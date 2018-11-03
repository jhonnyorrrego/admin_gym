<?php
@session_start();
include_once("config.php");

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
		$sql = "insert into ".$tabla."(".implode(",",$campos).")values(".htmlentities(implode(",",$valores)).")";
		mysqli_query($this->con,$sql);
		$id=mysqli_insert_id($this->con);
		return($id);
	}
	/*Modificar campo por campo
	$tabla = Nombre de la tabla a modificar
	$valor = Arreglo del(os) Valor(es) nuevo(s)
	$condicion_update = filtro para realizar el update
	*/
	public function modificar($tabla,$valor,$condicion_update,$id){
		$sql = "update " . $tabla . " set " . implode(",", $valor) . " where " . $condicion_update;
		mysqli_query($this->con,$sql);
		return(true);
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
	/*
	funcion pensada a futuro cuando se necesite realizar limite a las consultas sobre otros motores
	La funcion retorna el SQL convertido para ejecutar los limites.
	*/
	private function listar_datos_limite($consulta,$inicio,$cantidad){
		$consulta = $consulta . " LIMIT " . $inicio . "," . $cantidad;
		return($consulta);
	}
	public function obtener_datos_usuario($idusu){
		$consulta = "select * from usuario where idusu=" . $idusu;
		$datos = $this -> listar_datos($consulta);
		
		return($datos);
	}
	public function obtener_imagen_usuario($idusu){
		$consulta = "select b.ruta from usuario a, anexo b where idusu=" . $idusu . " and a.imagen=b.idane";
		$datos = $this -> listar_datos($consulta);

		if($datos["cant_resultados"]){
			return($datos[0]["ruta"]);
		} else {
			return(false);
		}
	}
	public function parsear_ruta_almacenamiento($idusu,$atras){
		$consulta = "select date_format(a.fecha, '%Y-%m-%d') as x_fecha from usuario a where idusu=" . $idusu;
		$resultados = $this -> listar_datos($consulta);

		$datos_fecha = explode("-", $resultados[0]["x_fecha"]);
		$ruta = $datos_fecha[0] . "/" . $datos_fecha[1] . "/" . $datos_fecha[2] . "/" . $idusu . "/";

		$this -> crear_carpetas($atras . ALMACENAMIENTO . $ruta);

		return $ruta;
	}
	public function procesar_anexos($idusu, $atras){
		$idane = array();

		$campos = array('fecha', 'estado', 'etiqueta', 'tamano', 'tipo', 'ruta');
		$hoy = "date_format('" . date('Y-m-d H:i:s') . "', '%Y-%m-%d %H:%i:%s')";
		$estado = 1;

		foreach ($_FILES as $key => $value) {
			$info_anexo = pathinfo($value["name"]);

			$nombre = $value["name"];
			$tamano = $value["size"];
			$ruta_temporal = $value["tmp_name"];
			$extension = $info_anexo["extension"];

			$ruta = $this -> parsear_ruta_almacenamiento($idusu, $atras) . $nombre;

			if(copy($ruta_temporal, $atras . ALMACENAMIENTO . $ruta)){
				unlink($ruta_temporal);
				$idane[] = $this -> insertar('anexo',$campos,array($hoy,$estado,"'" . $nombre . "'",$tamano,"'" . $extension . "'","'" . $ruta . "'"));
			}
		}

		return(implode(",", $idane));
	}
	public function crear_carpetas($destino){
		$arreglo=explode("/",$destino);
	  	if(is_array($arreglo)){
	   		$cont=count($arreglo);
	   		for($i=0;$i<$cont;$i++){
		    	if(!is_dir($arreglo[$i])){
			      	chmod($arreglo[$i-1],PERMISO_CARPETA);
			      	if(!mkdir($arreglo[$i],PERMISO_CARPETA)){
			        	die("no es posible crear la carpeta ".$destino);
			    	} else if(isset($arreglo[($i+1)])){
		        		$arreglo[($i+1)]=$arreglo[$i]."/".$arreglo[($i+1)];
			    	}
	      		} else if(isset($arreglo[($i+1)])){
	        		$arreglo[($i+1)]=$arreglo[$i]."/".$arreglo[($i+1)];
	    		}
	    	}
	  	}
	 	return($destino);
	}
	public function obtener_texto_mensualidad($idusu){
		$consulta = "select date_format(fechai, '%Y-%m-%d') as x_fechai, date_format(fechaf, '%Y-%m-%d') as x_fechaf from mensualidad where idusu=" . $idusu . " order by idmen desc";
		$datos = $this -> listar_datos($consulta,0,1);

		$texto = "";

		if($datos["cant_resultados"]){
			$texto = "<span class='badge badge-success'>Mensualidad asignada desde <b>" . $datos[0]["x_fechai"] . "</b> hasta <b>" . $datos[0]["x_fechaf"] . "</b></span>";
		} else {
			$texto = "<span class='badge badge-danger'>Usuario pendiente por mensualidad</span>";
		}

		return($texto);
	}
	public function sumar_fecha($fecha,$cantidad,$tipo,$formato){
		$actual = strtotime($fecha);
  		$nuevo = date($formato, strtotime($cantidad . " " . $tipo, $actual));
  		return($nuevo);
	}
	public function iniciar_variables_sesiones($datos){
		$_SESSION["usuario" . LLAVE_SESION] = $datos[0]["identificacion"];
		$_SESSION["tipo" . LLAVE_SESION] = $datos[0]["tipo"];
	}
	public function cerrar_sesion(){
		@session_unset();
		@session_destroy();
	}
}
?>