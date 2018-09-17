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
?>
<html>
  <head>
	<style>
	.error{
		color:red;
	}
	</style>
	<script type='text/javascript'>
	$().ready(function() {
		// validar los campos del formato
		$('#usuario_add').validate();
	});
	</script>
  </head>
  <body>
	<div class="container">
		<div class="row justify-content-md-center">
			<div class="col-sm-8">
				<form class="text-center border border-light p-4" name="usuario_add" id="usuario_add">
					<p class="h4 mb-4">Registro de Usuario</p>
					<input type="text" id="nombre" name="nombre" class="form-control mb-4 required" placeholder="Nombres">
					<input type="text" id="apellido" name="apellido" class="form-control mb-4 required" placeholder="Apellidos">
					<input type="text" id="email" name="email" class="form-control mb-4 email" placeholder="Email">
					<input type="text" id="celular" name="celular" class="form-control mb-4 number" placeholder="Celular">
					<select class="form-control custom-select mb-4 required" id="tipo_usuario">
						<option value="">Tipo de usuario</option>
						<option value="1" selected>Cliente</option>
						<option value="2">Administrador</option>
					</select>
					<button class="btn btn-info my-4 btn-block" type="submit">Registrar</button>
				</form>
			</div>
		</div>
	</div>
  </body>
</html>