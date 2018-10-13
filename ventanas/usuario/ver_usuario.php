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
		$('#anexar_imagen').click(function(){
			$("#imagen_usuario").click();
		});
		
		$("#imagen_usuario").change(function(){
			var formData = new FormData(document.getElementById("form_imagen_usuario"));
			formData.append('ejecutar', 'guardar_imagen');
			
			$.ajax({
				url : "ejecutar_acciones.php",
				type : "POST",
				dataType: "json",
				async: false,
				cache: false,
				contentType: false,
				processData: false,
				data : formData,
				success : function(respuesta){
					
				}
			});
		});
	});
	</script>
  </head>
  <body>
	<div class="container">
		<div class="card card-small">
			<div class="card-header border-bottom">
				<h6 class="m-0">Informaci&oacute;n de usuario</h6>
			</div>
			<div class="col-sm-12 card-body">
				<table class="table table-bordered" style="font-size:13px">
					<tr>
						<td style="width:30%"><b>Nombres</b></td>
						<td style="width:40%"><?php echo($datos_usuario[0]["nombres"]); ?></td>
						<td style="width:30%" rowspan="7">
							<img src="<?php echo($atras); ?>img/sin_foto.png" class="img-fluid rounded" id="anexar_imagen" style="cursor:pointer">
							<form name="form_imagen_usuario" id="form_imagen_usuario" method="post" enctype="multipart/form-data">
								<input type="file" name="imagen_usuario" id="imagen_usuario" style="display:none">
							</form>
						</td>
					</tr>
					<tr>
						<td><b>Apellidos</b></td>
						<td><?php echo($datos_usuario[0]["apellidos"]); ?></td>
					</tr>
					<tr>
						<td><b>Identificaci&oacuten</b></td>
						<td><?php echo($datos_usuario[0]["identificacion"]); ?></td>
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