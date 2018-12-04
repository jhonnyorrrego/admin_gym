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
		$retorno["idusu"] = $resultado;
	}else{
		$retorno["exito"] = 0;
		$retorno["mensaje"] = "Problemas en la inserci&oacute;n";
	}
	echo(json_encode($retorno));
}
function actualizar_usuario_formulario(){
	global $conexion, $atras;
	$idusu = @$_REQUEST["idusu"];
	$tabla = "usuario";

	$retorno = array();
	$valor_guardar = array();

	$campos_validos = array('tipo','identificacion','nombres','apellidos','email','celular','estado');
	foreach ($campos_validos as $key => $value) {
		if(array_key_exists($value, $_REQUEST)){
			$valor_guardar[] = " " . $value . "='" . @$_REQUEST[$value] . "' ";
		}
	}
	if(@$_REQUEST["clave"]){
		$valor_guardar[] = " clave='" . metodo_encriptar($_REQUEST["clave"]) . "' ";
	}

	$condicion_update = "idusu=" . $idusu;

	$conexion -> modificar($tabla,$valor_guardar,$condicion_update,$idusu);

	$retorno["info_estado"] = $conexion -> obtener_texto_estado_usuario($idusu);
	$retorno["exito"] = 1;
	$retorno["mensaje"] = 'Modificacion realizada';

	echo json_encode($retorno);
}
function validar_cedula(){
	global $conexion;
	$where = "";
	$identificacion = @$_REQUEST["identificacion"];
	if(@$_REQUEST["idusu"]){
		$idusu = $_REQUEST["idusu"];
		$where = "idusu<>" . $idusu;
	}
	$resultado = consultar_existencia($identificacion,1,$where);
	
	echo(json_encode($resultado));
}
function consultar_existencia($identificacion,$tipo_retorno=1,$where=false){
	global $conexion;
	$existe = False;
	
	$sql = "select identificacion from usuario where identificacion=" . $identificacion;
	if($where){
		$sql .= " and " . $where;
	}
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
function guardar_anexos_usuario(){
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
function guardar_anexo(){
	global $conexion, $atras;
	$retorno = array();

	$idusu = @$_REQUEST["idusu"];
	$idanexos = $conexion -> procesar_anexos($idusu, $atras);

	if($idanexos){
		$retorno["exito"] = 1;
		$retorno["mensaje"] = 'Anexo guardado';
	} else {
		$retorno["exito"] = 0;
		$retorno["mensaje"] = 'Problemas al guardar el anexo';
	}

	echo json_encode($retorno);
}
function agregar_mensualidad(){
	global $conexion;
	$retorno = array();

	$fechai = @$_REQUEST["fechai"];
	$fechaf = @$_REQUEST["fechaf"];
	$valor = @$_REQUEST["valor"];
	$id = @$_REQUEST["id"];
	$tabla = 'mensualidad';
	$condicion_update = "idusu=" . $id;

	//Parseando arreglo para insertar
	$campos_insertar = array('fechai','fechaf','valor','idusu','estado');
	$valores_insertar = array();
	$valores_insertar[] = "date_format('" . $fechai . "', '%Y-%m-%d')";
	$valores_insertar[] = "date_format('" . $fechaf . "', '%Y-%m-%d')";
	$valores_insertar[] = "'" . $valor . "'";
	$valores_insertar[] = $id;
	$valores_insertar[] = 1;

	$resultado = $conexion -> insertar($tabla,$campos_insertar,$valores_insertar);

	//Parseando arreglo para modificar en la tabla del usuario
	$valor_guardar = array();
	$valor_guardar[] = "fechai=date_format('" . $fechai . "', '%Y-%m-%d')";
	$valor_guardar[] = "fechaf=date_format('" . $fechaf . "', '%Y-%m-%d')";
	$valor_guardar[] = "valor='" . $valor . "'";

	$conexion -> modificar('usuario',$valor_guardar,$condicion_update,$id);

	if($resultado){
		$retorno["mensaje"] = "Mensualidad asignada!";
		$retorno["exito"] = 1;
		$retorno["info_mensualidad"] = $conexion -> obtener_texto_mensualidad($id);
		$retorno["info_estado"] = $conexion -> obtener_texto_estado_usuario($id);
		$retorno["info_valor"] = $conexion -> obtener_texto_valor($id);
		$retorno["info_dias_faltantes"] = $conexion -> obtener_dias_faltantes($id);
	}else{
		$retorno["exito"] = 0;
		$retorno["mensaje"] = "Problemas en la inserci&oacute;n";
	}
	echo(json_encode($retorno));
}
function listar_anexos(){
	global $conexion, $atras;
	$retorno = array();
	$idusu = @$_REQUEST["idusu"];

	$consulta = "select a.etiqueta, a.idane from anexo a, anexo_usuario b where b.fk_idusu=" . $idusu . " and b.fk_idane=a.idane and a.estado=1";
	$anexos = $conexion -> listar_datos($consulta);

	if($anexos["cant_resultados"]){
		$cadena = "";

		for ($i=0; $i < $anexos["cant_resultados"]; $i++) { 
			$etiqueta = html_entity_decode($anexos[$i]["etiqueta"]);
			if(strlen($etiqueta) > 25){
				$etiqueta = substr($anexos[$i]["etiqueta"], 0, 25) . " ...";
			}

			$cadena .= '<div class="row">
							<div class="col-9">
                          		<strong class="" title="' . $anexos[$i]["etiqueta"] . '">' . $etiqueta . '</strong>
                          	</div>
                          	<div class="col-3 text-right">
                          		<i style="cursor:pointer" class="fas fa-download enlace_anexo" enlace="' . $atras . "ventanas/anexo/ver_anexo.php?idane=" . $anexos[$i]["idane"] . '"></i>
                          		<i style="cursor:pointer" class="far fa-trash-alt enlace_anexo_eliminar" idane="' . $anexos[$i]["idane"] . '"></i>
                          	</div>
                         </div>';
		}
		$cadena .= '';

		$retorno["lista_anexos"] = $cadena;
		$retorno["exito"] = 1;
	} else {
		$retorno["lista_anexos"] = '';
		$retorno["exito"] = 0;
	}

	echo(json_encode($retorno));
}
function eliminar_anexo(){
	global $conexion, $atras;
	$retorno = array();

	$idane = @$_REQUEST["idane"];
	$conexion -> eliminar_anexo_usuario($idane);

	$retorno["exito"] = 1;
	$retorno["mensaje"] = "Anexo eliminado";

	echo(json_encode($retorno));
}

if(@$_REQUEST["ejecutar"]){
	$_REQUEST["ejecutar"]();
}
?>