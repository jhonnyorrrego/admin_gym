<?php
$atras="../../";
require_once $atras . 'vendor/autoload.php';
include_once($atras."lib_gym.php");
global $conexion, $raiz;
$raiz = $atras;

include_once ($atras . 'librerias.php');
echo(tema_dashboard_lite());
echo(notificacion());
echo(jquery_validate());
?>
<?php echo(encabezado());?>
<style>
.error{
	color:red;
}
</style>
<script>
$(document).ready(function(){
  $("#enlace_usuario_add").addClass("active");
  $("#navbarDropdown").click();
});
</script>
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
						
						setTimeout(function(){window.open("ver_usuario.php?idusuario=" + resultado.idusu, '_self');},1500); 
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
			$("#clave").parent().show(200);
			
			$("#clave2").addClass("required");
			$("#clave2").parent().show(200);
		} else {
			$("#clave").removeClass("required");
			$("#clave").val("");
			$("#clave").parent().hide(200);
			
			$("#clave2").removeClass("required");
			$("#clave2").val("");
			$("#clave2").parent().hide(200);
			
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

<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <h3 class="page-title">Registro de usuario</h3>
  </div>
</div>

<div class="row">
  <div class="col-lg-4">
    <div class="card card-small mb-4">
      <div class="card-header border-bottom">
        <h6 class="m-0">Datos de usuario</h6>
      </div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item p-3">
          <div class="row">
            <div class="col">
              <form class="formulario_general" name="usuario_add" id="usuario_add">
	            <div class="form-group">
	                <label>Tipo de usuario</label>
	                <select class="form-control" id="tipo" name="tipo">
	                	<option value="">Tipo de usuario</option>
						<option value="1" selected>Cliente</option>
						<option value="2">Administrador</option>
	                </select>
	            </div>

	            <div class="form-group">
					<label class="">Identificaci&oacute;n*</label>
					<input type="number" id="identificacion" name="identificacion" class="form-control required number" pattern="[0-9]*">
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
					<input type="text" id="celular" name="celular" class="form-control">
				</div>
				<div class="form-group">
					<label class="">Estado*</label>
					<select class="form-control custom-select required" id="estado" name="estado">
						<option value="">Estado</option>
						<option value="1" selected>Activo</option>
						<option value="2">Inactivo</option>
					</select>
				</div>
                <button type="button" id="guardar_usuario_formulario" class="btn btn-outline-success">Registrar</button>
              </form>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>

<?php echo(pie()); ?>