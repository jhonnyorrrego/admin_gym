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
		
		$("#guardar_usuario_formulario").click(function(){
			var formulario = $("#usuario_add");
			var resultado = formulario.valid();
			
			if(resultado){
				var data = $(formulario).serializeArray(); // convert form to array
				data.push({name: "ejecutar", value: 'guardar_usuario_formulario'});

				$.ajax({
					url : 'ejecutar_acciones.php',
					type : 'POST',
					dataType: 'json',
					data: $.param(data),
					success : function(resultado){
						if(resultado.exito){
							notificacion(resultado.mensaje,'success',5000);
							
							formulario[0].reset();
						}else{
							notificacion(resultado.mensaje,'warning',5000);
						}
					}
				});
			}
		});
		
		$("#identificacion").blur(function(){
			var x_identificacion = $("#identificacion").val();
			if(identificacion){
				$.ajax({
					url : 'ejecutar_acciones.php',
					type : 'POST',
					dataType: 'json',
					data: {ejecutar: 'validar_cedula', identificacion : x_identificacion},
					success : function(resultado){
						if(!resultado.exito){
							//$("#identificacion").focus();
							//$("#identificacion").select();
							notificacion(resultado.mensaje,'warning',5000);
						}
					}
				});
			}
		});
	});
	</script>
  </head>
  <body>
	<div class="container">
		<div class="card card-small">
			<div class="card-header border-bottom">
				<h6 class="m-0">Registro de Usuario</h6>
			</div>
			<div class="col-sm-12 card-body">
				<form class="text-center p-4 formulario_general" name="usuario_add" id="usuario_add">					
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Identificaci&oacute;n*</label>
						<div class="col-9">
							<input type="text" id="identificacion" name="identificacion" class="form-control required number">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Nombres*</label>
						<div class="col-9">
							<input type="text" id="nombre" name="nombres" class="form-control required">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Apellidos*</label>
						<div class="col-9">
							<input type="text" id="apellido" name="apellidos" class="form-control required">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Email</label>
						<div class="col-9">
							<input type="text" id="email" name="email" class="form-control email">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Celular</label>
						<div class="col-9">
							<input type="text" id="celular" name="celular" class="form-control number">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Tipo de usuario*</label>
						<div class="col-9">
							<select class="form-control custom-select required" id="tipo" name="tipo">
								<option value="">Tipo de usuario</option>
								<option value="1" selected>Cliente</option>
								<option value="2">Administrador</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Estado*</label>
						<div class="col-9">
							<select class="form-control custom-select required" id="estado" name="estado">
								<option value="">Estado</option>
								<option value="1" selected>Activo</option>
								<option value="2">Inactivo</option>
							</select>
						</div>
					</div>
					
					<button id="guardar_usuario_formulario" class="mb-2 btn btn-outline-success mr-2" type="button">Registrar</button>
				</form>
			</div>
		</div>
	</div>
  </body>
</html>