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
echo(login_css());
?>
<html>
  <head>
  </head>
  <body>
    <div class="container">
		<div class="row justify-content-md-center">
			<div class="col-sm-6">				
				<form class="text-center border border-light p-4">
					<p class="h4 mb-4">Registro de Ingreso</p>
					<input type="email" id="identificacion" class="form-control mb-4" placeholder="Identificaci&oacute;n">
					
					<input type="email" id="huella" class="form-control mb-4" placeholder="Huella Dactilar">
					<!-- Sign up button -->
					<button class="btn btn-info my-4 btn-block" type="submit">Ingresar</button>
				</form>
			</div>
		</div>
	</div>
  </body>
</html>