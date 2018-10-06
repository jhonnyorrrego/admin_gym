<?php
$atras="../../";
require_once $atras . 'vendor/autoload.php';
include_once($atras."lib_gym.php");
global $conexion, $raiz;
$raiz = $atras;

include_once ($atras . 'librerias.php');

echo(bootstrap_css());
echo(jquery_js());
echo(bootstrap_js());
echo(notificacion());
echo(jquery_validate());
echo(estilos_generales());

$datos_usuario = $conexion -> obtener_datos_usuario(@$_REQUEST["idusuario"]);

$tipo = array();
$tipo[1] = "Cliente";
$tipo[2] = "Administrador";

$estado = array();
$estado[1] = "Activo";
$estado[2] = "Inactivo";
?>
<html>
  <head>
	<script type='text/javascript'>
	$().ready(function() {
	});
	</script>
  </head>
  <body>
	<div class="container">
		<div class="row justify-content-md-center">
			<div class="col-sm-12">
				<p class="text-center h4 mb-4"><?php echo(ucwords(strtolower($datos_usuario[0]["nombres"] . " " . $datos_usuario[0]["apellidos"]))); ?></p>
				<table class="table table-bordered">
					<tr>
						<td style="width:30%"><b>Identificaci&oacute;n</b></td>
						<td style="width:40%"><?php echo($datos_usuario[0]["identificacion"]); ?></td>
						<td style="width:30%" rowspan="5"><img src="<?php echo($atras); ?>img/sin_foto.png" class="img-fluid rounded"></td>
					</tr>
					<tr>
						<td><b>Email</b></td>
						<td><?php echo($datos_usuario[0]["email"]); ?></td>
					</tr>
					<tr>
						<td><b>Celular</b></td>
						<td><?php echo($datos_usuario[0]["celular"]); ?></td>
					</tr>
					<tr>
						<td><b>Tipo</b></td>
						<td><?php echo($tipo[$datos_usuario[0]["tipo"]]); ?></td>
					</tr>
					<tr>
						<td><b>Estado</b></td>
						<td><?php echo($estado[$datos_usuario[0]["estado"]]); ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
  </body>
</html>