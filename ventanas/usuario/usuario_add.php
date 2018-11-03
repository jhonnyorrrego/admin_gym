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
  	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
		
		$("#tipo").change(function(){
			var tipo = $(this).val();
			if(tipo == 2){//
				$("#clave").addClass("required");
				$("#clave").parent().show(1000);
				
				$("#clave2").addClass("required");
				$("#clave2").parent().show(1000);
			} else {
				$("#clave").removeClass("required");
				$("#clave").val("");
				$("#clave").parent().hide(1000);
				
				$("#clave2").removeClass("required");
				$("#clave2").val("");
				$("#clave2").parent().hide(1000);
				
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
				<h6 class="m-0"><b>Registro de Usuario</b></h6>
			</div>
			<div class="col-sm-12 card-body">
				<form class="formulario_general" name="usuario_add" id="usuario_add">					
					<div class="form-group">
						<label class="">Tipo de usuario*</label>

						<select class="form-control custom-select required" id="tipo" name="tipo">
							<option value="">Tipo de usuario</option>
							<option value="1" selected>Cliente</option>
							<option value="2">Administrador</option>
						</select>
					</div>
					<div class="form-group">
						<label class="">Identificaci&oacute;n*</label>
						<input type="text" id="identificacion" name="identificacion" class="form-control required number">
					</div>
					<div class="form-group" style="display:none">
						<label class="">Clave*</label>
						<input type="password" id="clave" name="clave" class="form-control">
					</div>
					<div class="form-group" style="display:none">
						<label class="">Repita su clave*</label>
						<input type="password" id="clave2" class="form-control" equalTo="#clave">
					</div>
					<div class="form-group">
						<label class="">Nombres*</label>
							<input type="text" id="nombre" name="nombres" class="form-control required">
					</div>
					<div class="form-group">
						<label class="">Apellidos*</label>
						<input type="text" id="apellido" name="apellidos" class="form-control required">
					</div>
					<div class="form-group">
						<label class="">Email</label>
						<input type="text" id="email" name="email" class="form-control email">
					</div>
					<div class="form-group">
						<label class="">Celular</label>
						<input type="text" id="celular" name="celular" class="form-control number">
					</div>
					<div class="form-group">
						<label class="">Estado*</label>
						<select class="form-control custom-select required" id="estado" name="estado">
							<option value="">Estado</option>
							<option value="1" selected>Activo</option>
							<option value="2">Inactivo</option>
						</select>
					</div>
					
					<button id="guardar_usuario_formulario" class="mb-2 btn btn-outline-success mr-2" type="button">Registrar</button>
				</form>
			</div>
		</div>
	</div>
  </body>
</html>