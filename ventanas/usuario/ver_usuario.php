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

echo(bootstrap_datepicker());
echo(date_format_jquery());

$idusuario = @$_REQUEST["idusuario"];

$datos_usuario = $conexion -> obtener_datos_usuario($idusuario);

$defecto = $atras . "img/sin_foto.png";
$imagen_usuario = $conexion -> obtener_imagen_usuario($idusuario);
if(@$imagen_usuario && file_exists($atras . $imagen_usuario)){
	$defecto = $atras . $imagen_usuario;
}
?>
<?php echo(encabezado());?>

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
		$('#mensualidad').validate();

		$('#anexar_imagen').click(function(){
			$("#imagen_usuario").click();
		});
		
		$("#imagen_usuario").change(function(){
			var formData = new FormData(document.getElementById("usuario_view_image"));
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

		$(document).on('click' , '#actualizar_usuario_formulario' , function() {
			var formulario = $("#usuario_view");
			var resultado = formulario.valid();

			if(resultado){
				var data = $(formulario).serializeArray(); // convert form to array
				data.push({name: "ejecutar", value: 'actualizar_usuario_formulario'});
				data.push({name: "idusu", value: '<?php echo($idusuario); ?>'});

				$.ajax({
					url: 'ejecutar_acciones.php',
					type: 'POST',
					dataType: 'json',
					async: false,
					data: $.param(data),
					success : function(respuesta){
						if(respuesta.exito){
							notificacion(respuesta.mensaje,'success',4000);
							$("#info_estado").html(respuesta.info_estado);
						} else {
							notificacion(respuesta.mensaje,'warning',4000);
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
					data: {ejecutar: 'validar_cedula', identificacion : x_identificacion, idusu : "<?php echo($idusuario); ?>"},
					success : function(resultado){
						if(!resultado.exito){
							notificacion(resultado.mensaje,'warning',5000);
						}
					}
				});
			}
		});

		$("#guardar_mensualidad_formulario").click(function(){
			var validar = $("#mensualidad").valid();
			if(!validar){
				return false;
			}

			var x_fechai = $("#fechai").val();
			var x_fechaf = $("#fechaf").val();
			var valor_ =new String($("#valor").val());
            var x_valor = valor_.replace(/\./g,"");

			if(x_fechai > x_fechaf){
				notificacion('La fecha inicial debe ser menor a la fecha final','warning',4000);
				return false;
			}

			$.ajax({
				url: 'ejecutar_acciones.php',
				type: 'POST',
				dataType: 'json',
				async: false,
				data: {ejecutar: 'agregar_mensualidad', fechai : x_fechai, fechaf: x_fechaf, valor: x_valor, id: '<?php echo($idusuario); ?>'},
				success : function(respuesta){
					if(respuesta.exito){
						notificacion(respuesta.mensaje,'success',4000);
						$("#info_mensualidad").html(respuesta.info_mensualidad);
						$("#info_estado").html(respuesta.info_estado);
						$("#info_valor").html(respuesta.info_valor);
						$("#info_dias_faltantes").html(respuesta.info_dias_faltantes);
						
						$('html, body').animate({ scrollTop: $('#capa_informacion_usuario').offset().top -80 }, 'slow');
					} else {
						notificacion(respuesta.mensaje,'warning',4000);
					}
				}
			});
			
		});

		$("#tipo").change(function(){
			var tipo = $(this).val();
			if(tipo == 2){//
				$("#clave").addClass("required");
				$("#clave").parent().parent().show(1000);
				
				$("#clave2").addClass("required");
				$("#clave2").parent().parent().show(1000);
			} else {
				$("#clave").removeClass("required");
				$("#clave").val("");
				$("#clave").parent().parent().hide(1000);
				
				$("#clave2").removeClass("required");
				$("#clave2").val("");
				$("#clave2").parent().parent().hide(1000);
				
			}
		});

		$("#confirmar_ingreso").click(function(){
			if(!confirm('Está seguro de ingresar este usuario?')){
				return false;
			}

	 		var x_idusu = '<?php echo($idusuario); ?>';
	 		$.ajax({
	 			url: '<?php echo($atras); ?>ventanas/ingreso/ejecutar_acciones.php',
	 			type: 'POST',
				dataType: 'json',
				async: false,
				data: {ejecutar: 'confirmar_ingreso_usuario', idusu : x_idusu},
				success : function(respuesta){
					if(respuesta.exito){
						notificacion(respuesta.mensaje,'success',4000);
					} else {
						notificacion(respuesta.mensaje,'warning',4000);
					}
				}
	 		});
	 	});
	});
	</script>

<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <h3 class="page-title">Usuario</h3>
  </div>
</div>

<div class="row">
  <div id="capa_informacion_usuario" class="col-lg-4">
		<div class="card card-small mb-4">
			<div class="card-header border-bottom">
				<h6 class="m-0"><b>Estado de usuario</b></h6>
			</div>

			<div class="card-body p-0">
				<ul class="list-group list-group-flush">
                    <li class="list-group-item p-3 text-center">
						<form id="usuario_view_image" name="usuario_view_image" method="post" enctype="multipart/form-data">
							<img src="<?php echo($defecto); ?>" class="img-fluid rounded-circle" id="anexar_imagen" style="cursor:pointer;width:250px">
							<input type="file" name="imagen_usuario" id="imagen_usuario" style="display:none">
						</form>

						<button type="button" class="mb-2 btn btn-sm btn-pill btn-outline-success mr-2" id="confirmar_ingreso">
                      		<i class="fas fa-check-circle mr-1"></i>Ingresar
                  		</button>
					</li>
					<li class="list-group-item p-3">
						<span class="d-flex mb-2">
                          <i class="fas fa-flag mr-1"></i>
                          <strong class="mr-1"> Estado:</strong>
                          <div id="info_estado">
							<?php
								echo($conexion -> obtener_texto_estado_usuario($idusuario));
							?>
							</div>
                        </span>
                        <span class="d-flex mb-2">
                        	<i class="far fa-calendar-alt mr-1"></i>
                          	<strong class="mr-1"> Mensualidad:</strong>
                          	<div id="info_mensualidad">
							<?php
								echo($conexion -> obtener_texto_mensualidad($idusuario));
							?>
							</div>
                        </span>
                        <span class="d-flex mb-2">
                        	<i class="fas fa-dollar-sign mr-1"></i>
                          	<strong class="mr-1"> Valor:</strong>
                          	<div id="info_valor">
							<?php
								echo($conexion -> obtener_texto_valor($idusuario));
							?>
							</div>
                        </span>
                        <span class="d-flex mb-2">
                        	<i class="far fa-clock mr-1"></i>
                        	<strong class="mr-1"> D&iacute;as faltantes:</strong>
                        	<div id="info_dias_faltantes">
                        		<?php
								echo($conexion -> obtener_dias_faltantes($idusuario));
								?>
                        	</div>
                        </span>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<div class="card card-small mb-4">
			<div class="card-header border-bottom">
		    	<h6 class="m-0">Informaci&oacute;n de usuario</h6>
		    </div>
			<div class="card-body">
				<form id="usuario_view" name="usuario_view" method="post" enctype="multipart/form-data">
					<div class="form-row">
						<div class="form-group col-md-6">
			                <label>Tipo de usuario</label>
			                <select class="form-control" id="tipo" name="tipo">
			                	<option value="">Tipo de usuario</option>
								<option value="1" <?php if($datos_usuario[0]["tipo"] == 1)echo("selected"); ?>>Cliente</option>
								<option value="2" <?php if($datos_usuario[0]["tipo"] == 2)echo("selected"); ?>>Administrador</option>
			                </select>
			            </div>
			            <div class="form-group col-md-6">
							<label class="">Identificaci&oacute;n*</label>
							<input type="text" id="identificacion" name="identificacion" class="form-control required number" value="<?php echo($datos_usuario[0]["identificacion"]); ?>">
						</div>
					</div>
					<div class="form-row" style="display: none;">
						<div class="form-group col-md-6">
							<label class="">Clave*</label>
							<input type="password" id="clave" name="clave" class="form-control" value="">
						</div>
						<div class="form-group col-md-6">
							<label class="">Repita su clave*</label>
							<input type="password" id="clave2" class="form-control" equalTo="#clave">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label class="">Nombres*</label>
							<input type="text" id="nombre" name="nombres" class="form-control required" value="<?php echo($datos_usuario[0]["nombres"]); ?>">
						</div>
						<div class="form-group col-md-6">
							<label class="">Apellidos*</label>
							<input type="text" id="apellido" name="apellidos" class="form-control required" value="<?php echo($datos_usuario[0]["apellidos"]); ?>">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label class="">Email</label>
							<input type="text" id="email" name="email" class="form-control email" value="<?php echo($datos_usuario[0]["email"]); ?>">
						</div>
						<div class="form-group col-md-6">
							<label class="">Celular</label>
							<input type="text" id="celular" name="celular" class="form-control" value="<?php echo($datos_usuario[0]["celular"]); ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="">Estado*</label>
						<select class="form-control custom-select required" id="estado" name="estado">
							<option value="">Estado</option>
							<option value="1" <?php if($datos_usuario[0]["estado"] == 1)echo("selected"); ?>>Activo</option>
							<option value="2" <?php if($datos_usuario[0]["estado"] == 2)echo("selected"); ?>>Inactivo</option>
						</select>
					</div>
					<button type="button" id="actualizar_usuario_formulario" class="btn btn-outline-success">Actualizar</button>
				</form>
			</div>
		</div>
	</div>
	
	<div class="col-lg-3">
		<?php
		$fechai = date('Y-m-d');
		$fechaf = $conexion -> sumar_fecha($fechai,1,'month','Y-m-d');
		?>
		<div class="card card-small mb-4">
			<div class="card-header border-bottom">
				<h6 class="m-0"><b>Mensualidad</b></h6>
			</div>
			<div class="card-body">
				<form id="mensualidad" name="mensualidad" method="post" enctype="multipart/form-data">
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

				    <div class="form-group">
						<label class="">Valor*</label>
						<input type="text" id="valor" name="valor" class="form-control required" value="">
					</div>

				    <button id="guardar_mensualidad_formulario" class="btn btn-outline-success" type="button">Registrar</button>
				</form>
				     
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

		            $("#valor").keyup(function(){
			            var valor=$(this).val().replace(/[^0-9]/g, '');
			            $(this).val(Moneda_r(valor));
			        });

		            function Moneda_r(valor){
			            var num = valor.replace(/\./g,'');
			            if(!isNaN(num)){
			                 num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
			                num = num.split('').reverse().join('').replace(/^[\.]/,'');
			                return(num);
			            }
			        }
			    </script>
			</div>
		</div>
	</div>

	<div id="capa_informacion_usuario" class="col-lg-4">
		<div class="card card-small mb-4">
			<div class="card-header border-bottom">
				<h6 class="m-0"><b>Anexos</b></h6>
			</div>

			<div class="card-body">
				<form id="anexos_usuario" name="anexos_usuario" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<input type="file" name="anexos" class="form-control-file btn">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php echo(pie()); ?>