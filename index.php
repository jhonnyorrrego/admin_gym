<?php
$atras="";
require_once 'vendor/autoload.php';
include_once($atras."lib_gym.php");
global $conexion;

include_once ($atras . 'librerias.php');
echo(jquery_js());

$movil = $conexion -> detectar_movil(1);

if(@$_SESSION["idusu"]){
	$inicio = $atras . "ventanas/usuario/reporte_usuarios.php";
} else {
	$inicio = $atras . "ventanas/ingreso/login.php";
}

?>
<script>
$(document).ready(function(){
  window.open("<?php echo($inicio); ?>", "_self");
});
</script>