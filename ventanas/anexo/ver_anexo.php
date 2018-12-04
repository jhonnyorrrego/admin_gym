<?php
$atras="../../";
require_once $atras . 'vendor/autoload.php';
include_once($atras."lib_gym.php");
global $conexion, $raiz;
$raiz = $atras;

include_once ($atras . 'librerias.php');

$idane = @$_REQUEST["idane"];
$consulta = "select * from anexo a where a.idane=" . $idane;
$datos_anexos = $conexion -> listar_datos($consulta);

$extensiones_ver = array('pdf', 'jpg', 'jpeg', 'png');
if(in_array(strtolower($datos_anexos[0]["tipo"]), $extensiones_ver)){
	?>
	<script type="text/javascript">
	window.location="<?php echo($atras . $datos_anexos[0]["ruta"]); ?>";
	</script>
	<?php
} else {
	?>
	<script type="text/javascript">
	window.location="<?php echo($atras . $datos_anexos[0]["ruta"]); ?>";
	</script>
	<?php
}
?>