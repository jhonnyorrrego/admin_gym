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
echo(bootstrap_table());
?>
<html>
  <head>
  </head>
  <body>
    <div class="container">
		<div class="row justify-content-md-center">
			<div class="col-sm-4">
				<form class="text-center border border-light p-4">
					<p class="h4 mb-4">Usuarios registrados</p>
					
					<div id="toolbar">
					  <button id="remove" class="btn btn-danger" disabled>
						<i class="glyphicon glyphicon-remove"></i> Delete
					  </button>
					</div>
					<table id="table"
						   data-toolbar="#toolbar"
						   data-search="true"
						   data-show-refresh="true"
						   data-show-toggle="true"
						   data-show-columns="true"
						   data-show-export="true"
						   data-detail-view="true"
						   data-detail-formatter="detailFormatter"
						   data-minimum-count-columns="2"
						   data-show-pagination-switch="true"
						   data-pagination="true"
						   data-id-field="id"
						   data-page-list="[10, 25, 50, 100, ALL]"
						   data-side-pagination="server"
						   data-url="obtener_usuarios.php"
						   data-response-handler="responseHandler">
					</table>
				</form>
			</div>
		</div>
	</div>
  </body>
</html>