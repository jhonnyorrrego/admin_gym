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
echo(estilos_generales());
?>
<html>
  <head>
  </head>
  <body>
    <div class="container">
		<div class="card card-small">
			<div class="card-header border-bottom">
				<h6 class="m-0"><b>Registro de Ingreso</b></h6>
			</div>
			<div class="card-body">				
				<form class="text-center p-4 formulario_general">
					<div class="form-group row">
						<div class="col-12">
							<input type="email" id="identificacion" class="form-control mb-4" placeholder="Identificaci&oacute;n">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-12">
							<input type="email" id="huella" class="form-control mb-4" placeholder="Huella Dactilar">
						</div>
					</div>
					<!-- Sign up button -->
					<button class="mb-2 btn btn-outline-success mr-2" type="submit">Ingresar</button>
				</form>
			</div>
		</div>
	</div>
  </body>
</html>