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
echo(bootstrap_datepicker());
echo(estilos_generales());
echo(estilos_iconos());
echo(date_format_jquery());

$idusuario = @$_REQUEST["idusuario"];

$datos_usuario = $conexion -> obtener_datos_usuario($idusuario);

$defecto = $atras . "img/sin_foto.png";
$imagen_usuario = $conexion -> obtener_imagen_usuario($idusuario);
if(@$imagen_usuario){
	$defecto = $atras . ALMACENAMIENTO . $imagen_usuario;
}

$tipo = array();
$tipo[1] = "Cliente";
$tipo[2] = "Administrador";
$tipo_html = '<option value="">Tipo de usuario</option>';
$tipo_html .= '<option value="1">Cliente</option>';
$tipo_html .= '<option value="2">Administrador</option>';

$estado = array();
$estado[1] = "Activo";
$estado[2] = "Inactivo";
$estado_html = '<option value="">Estado</option>';
$estado_html .= '<option value="1">Activo</option>';
$estado_html .= '<option value="2">Inactivo</option>';
?>
<html>
  <head>
  	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  	<style type="text/css">
  	.campo_editar{
  		cursor: pointer;
  	}
  	.error{
		color:red;
	}
  	</style>
	<script type='text/javascript'>
	$().ready(function() {
		$('html, body').animate({scrollTop:0}, 'slow');

		$('#usuario_view').validate();

		$('#anexar_imagen').click(function(){
			$("#imagen_usuario").click();
		});
		
		$("#imagen_usuario").change(function(){
			var formData = new FormData(document.getElementById("usuario_view"));
			formData.append('idusu', '<?php echo($idusuario); ?>');
			formData.append('ejecutar', 'guardar_imagen');
			
			$.ajax({
				url : 'ejecutar_acciones.php',
				type : 'POST',
				dataType: 'json',
				async: false,
				cache: false,
				contentType: false,
				processData: false,
				data : formData,
				success : function(respuesta){
					if(respuesta.exito){
						notificacion(respuesta.mensaje,'success',4000);
						$("#anexar_imagen").attr("src",respuesta.imagen);
					} else {
						notificacion(respuesta.mensaje,'warning',4000);
					}
				}
			});
		});

		$(document).on('click' , '.campo_editar' , function() {
			var nombre = $(this).attr('nombre');
			var tipo = $(this).attr('tipo');
			var validacion = $(this).attr('validacion');

			if(tipo == 'texto'){
				var html_dato = $(this).html();
				var html_nuevo = '<input class="form-control campo_guardar ' + validacion + '" type="text" id="' + nombre + '" value="' + html_dato + '" tipo="' + tipo + '">';

				$(this).html(html_nuevo);
				$(this).removeClass('campo_editar');

				var fieldInput = $('#' + nombre);
				var fldLength= fieldInput.val().length;
				fieldInput.focus();
				fieldInput[0].setSelectionRange(fldLength, fldLength);
			} else if(tipo == 'lista_desplegable'){
				var html_dato = $(this).attr("valor");
				if(nombre == 'tipo'){
					var html_nuevo = '<select class="form-control custom-select campo_guardar ' + validacion + '" type="text" id="' + nombre + '" tipo="' + tipo + '"><?php echo($tipo_html); ?></select>';

				} else if(nombre == 'estado'){
					var html_nuevo = '<select class="form-control custom-select campo_guardar ' + validacion + '" type="text" id="' + nombre + '"  tipo="' + tipo + '"><?php echo($estado_html); ?></select>';
				}

				$(this).html(html_nuevo);
				$(this).removeClass('campo_editar');


				$("#" + nombre + " option[value="+ html_dato +"]").attr("selected",true);
				$("#" + nombre).focus();
			}

		});

		$(document).on('blur' , '.campo_guardar' , function() {
			var resultado_formulario = $('#usuario_view').valid();
			if(!resultado_formulario){
				return false;
			}

			var elemento_td = $(this).parent();
			var tipo = $(this).attr('tipo');

			if(tipo == 'texto'){
				var x_nombre = $(this).attr('id');
				var x_valor = $(this).val();

				$.ajax({
					url: 'ejecutar_acciones.php',
					type: 'POST',
					dataType: 'json',
					async: false,
					data: {ejecutar: 'modificar_unico_usuario', tipo : tipo, nombre: x_nombre, valor: x_valor, id: '<?php echo($idusuario); ?>'},
					success : function(respuesta){
						if(respuesta.exito){
							notificacion(respuesta.mensaje,'success',4000);
						} else {
							notificacion(respuesta.mensaje,'warning',4000);
						}
					}
				});

				elemento_td.html(x_valor);
				elemento_td.addClass('campo_editar');
			} else if(tipo == 'lista_desplegable'){
				var x_nombre = $(this).attr('id');
				var x_valor = $(this).val();
				//var x_etiqueta = $(this + ' option:selected').text();
				var x_etiqueta = $("#" + x_nombre + " option:selected").text();

				$.ajax({
					url: 'ejecutar_acciones.php',
					type: 'POST',
					dataType: 'json',
					async: false,
					data: {ejecutar: 'modificar_unico_usuario', tipo : tipo, nombre: x_nombre, valor: x_valor, id: '<?php echo($idusuario); ?>'},
					success : function(respuesta){
						if(respuesta.exito){
							notificacion(respuesta.mensaje,'success',4000);
						} else {
							notificacion(respuesta.mensaje,'warning',4000);
						}
					}
				});

				elemento_td.html(x_etiqueta);
				elemento_td.attr("valor", x_valor);
				elemento_td.addClass('campo_editar');
			}
		});

		$("#guardar_mensualidad_formulario").click(function(){
			var x_fechai = $("#fechai").val();
			var x_fechaf = $("#fechaf").val();

			if(x_fechai > x_fechaf){
				notificacion('La fecha inicial debe ser menor a la fecha final','warning',4000);
				return false;
			}

			$.ajax({
				url: 'ejecutar_acciones.php',
				type: 'POST',
				dataType: 'json',
				async: false,
				data: {ejecutar: 'agregar_mensualidad', fechai : x_fechai, fechaf: x_fechaf, id: '<?php echo($idusuario); ?>'},
				success : function(respuesta){
					if(respuesta.exito){
						notificacion(respuesta.mensaje,'success',4000);
						$("#info_mensualidad").html(respuesta.html);
					} else {
						notificacion(respuesta.mensaje,'warning',4000);
					}
				}
			});
			
		});
	});
	</script>
  </head>
  <body>
	<div class="container" style="">
		<div class="row">
			<div class="col-md-auto">
				<div class="card card-small">
					<div class="card-header border-bottom">
						<h6 class="m-0"><b>Informaci&oacute;n de usuario</b></h6>
					</div>
					<div class="card-body">
						<form id="usuario_view" name="usuario_view" method="post" enctype="multipart/form-data">
							<table class="table table-bordered" style="font-size:13px;width:100%">
								<tr>
									<td style="width:20%"><b>Nombres</b></td>
									<td style="width:40%;" class="campo_editar" nombre="nombres" tipo="texto" validacion="required"><?php echo($datos_usuario[0]["nombres"]); ?></td>
									<td style="width:40%; vertical-align: middle" rowspan="7">
										<img src="<?php echo($defecto); ?>" class="img-fluid rounded" id="anexar_imagen" style="cursor:pointer;width:250px">
										<input type="file" name="imagen_usuario" id="imagen_usuario" style="display:none">
									</td>
								</tr>
								<tr>
									<td><b>Apellidos</b></td>
									<td class="campo_editar" nombre="apellidos" tipo="texto" validacion="required"><?php echo($datos_usuario[0]["apellidos"]); ?></td>
								</tr>
								<tr>
									<td><b>Identificaci&oacuten</b></td>
									<td><?php echo($datos_usuario[0]["identificacion"]); ?></td>
								</tr>
								<tr>
									<td><b>Email</b></td>
									<td class="campo_editar" nombre="email" tipo="texto" validacion="email"><?php echo($datos_usuario[0]["email"]); ?></td>
								</tr>
								<tr>
									<td><b>Celular</b></td>
									<td class="campo_editar" nombre="celular" tipo="texto" validacion="number"><?php echo($datos_usuario[0]["celular"]); ?></td>
								</tr>
								<tr>
									<td><b>Tipo</b></td>
									<td class="campo_editar" nombre="tipo" tipo="lista_desplegable" valor="<?php echo($datos_usuario[0]["tipo"]); ?>" validacion="required"><?php echo($tipo[$datos_usuario[0]["tipo"]]); ?></td>
								</tr>
								<tr>
									<td><b>Estado</b></td>
									<td class="campo_editar" nombre="estado" tipo="lista_desplegable" valor="<?php echo($datos_usuario[0]["estado"]); ?>" validacion="required"><?php echo($estado[$datos_usuario[0]["estado"]]); ?></td>
								</tr>
							</table>
						</form>
						<div id="info_mensualidad">
						<?php
						echo($conexion -> obtener_texto_mensualidad($idusuario));
						?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-md-auto">
				<?php
				$fechai = date('Y-m-d');
				$fechaf = $conexion -> sumar_fecha($fechai,1,'month','Y-m-d');
				?>
				<div class="card card-small">
					<div class="card-header border-bottom">
						<h6 class="m-0"><b>Mensualidad</b></h6>
					</div>
					<div class="card-body">
				        <div class="form-group">
				        	<label class="">Fecha inicial</label>
				        	<div class="input-group" id="capa_fechai">
					    		<input type="text" class="form-control date" id="fechai" readonly="" value="<?php echo($fechai); ?>">
					    		<div class="input-group-append" id="ejecutar_fechai">
									<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
								</div>
					    	</div>
					    </div>

					    <div class="form-group">
				        	<label class="">Fecha final</label>
				        	<div class="input-group" id="capa_fechaf">
					    		<input type="text" class="form-control date" id="fechaf" readonly="" value="<?php echo($fechaf); ?>">
					    		<div class="input-group-append" id="ejecutar_fechaf">
									<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
								</div>
					    	</div>
					    </div>

					    <button id="guardar_mensualidad_formulario" class="mb-2 btn btn-outline-success mr-2" type="button">Registrar</button>
						     
					    <script type="text/javascript">
				            $('#fechai').datepicker({
				            	language : 'es',
				           		format: 'yyyy-mm-dd',
				           		autoclose: true,
				           		setEndDate: new Date(<?php echo($fechaf); ?>)
							}).on('changeDate',function(event){
								var dia_mas = new Date(event.date);
								var fechaf = new Date(event.date);

								var nueva_fecha2 = dia_mas.setDate(dia_mas.getDate() + 1);
								var fecha_formateada2 = $.format.date(nueva_fecha2, "yyyy-MM-dd");
    							$('#fechaf').datepicker('setStartDate', new Date(fecha_formateada2));

								var nueva_fecha = fechaf.setMonth(fechaf.getMonth() + 1);
								nueva_fecha = fechaf.setDate(fechaf.getDate());
								var fecha_formateada = $.format.date(nueva_fecha, "yyyy-MM-dd");
								$("#fechaf").val(fecha_formateada);
							});
							$('#fechaf').datepicker({
				            	language : 'es',
				           		format: 'yyyy-mm-dd',
				           		autoclose: true,
				           		setStartDate : new Date(<?php echo($fechai); ?>)
							}).on('changeDate', function(event){
								var endDate = new Date(event.date.valueOf());
    							$('#fechai').datepicker('setEndDate', endDate);
							});

				            $("#ejecutar_fechai").click(function(){
				            	$("#fechai").datepicker('show');
				            });
				            $("#ejecutar_fechaf").click(function(){
				            	$("#fechaf").datepicker('show');
				            });
					    </script>
					</div>
				</div>
			</div>

			<!--div class="col-md-auto">
				<div class="card card-small">
					<div class="card-header border-bottom">
						<h6 class="m-0">Registro de ingresos</h6>
					</div>
					<div class="card-body">
						
					</div>
				</div>
			</div>

			<div class="col-md-auto">
				<div class="card card-small">
					<div class="card-header border-bottom">
						<h6 class="m-0">Rutina</h6>
					</div>
					<div class="card-body">
						
					</div>
				</div>
			</div-->
		</div>
	</div>
  </body>
</html>