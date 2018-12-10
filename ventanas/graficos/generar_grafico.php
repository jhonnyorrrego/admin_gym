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

$datasets_contenido = "
				backgroundColor: [
	                'rgba(255, 99, 132, 0.2)',
	                'rgba(54, 162, 235, 0.2)',
	                'rgba(255, 206, 86, 0.2)',
	                'rgba(75, 192, 192, 0.2)',
	                'rgba(153, 102, 255, 0.2)',
	                'rgba(255, 159, 64, 0.2)'
	            ],
	            borderColor: [
	                'rgba(255,99,132,1)',
	                'rgba(54, 162, 235, 1)',
	                'rgba(255, 206, 86, 1)',
	                'rgba(75, 192, 192, 1)',
	                'rgba(153, 102, 255, 1)',
	                'rgba(255, 159, 64, 1)'
	            ],
	            borderWidth: 1,";
$options = "
		options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                }
	            }]
	        }
	    }";
?>
<?php echo(encabezado());?>

<script>
$(document).ready(function(){
	generar_graficos();

	$("#filtrar_grafico").click(function(){
		generar_graficos();
	});
});

function generar_graficos(){
	var x_fechai = $("#fechai").val();
	var x_fechaf = $("#fechaf").val();

	if(x_fechai > x_fechaf){
		notificacion('La fecha inicial debe ser menor a la fecha final','warning',4000);
		return false;
	}

	var formulario = $("#filtros_grafico");
	var data = $(formulario).serializeArray(); // convert form to array
	data.push({name: "fk_idusu", value: '<?php echo($idusuario); ?>'});
	data.push({name: "ejecutar", value: 'obtener_unidad_medida'});

	$(".cargando").show();

	$.ajax({
		url: 'ejecutar_acciones.php',
		type: 'POST',
		dataType: 'json',
		async: false,
		data: $.param(data),
		success : function(respuesta){
			$(".cargando").hide();
			
			$("#capa_graficos").html("");//vaciar la capa cada vez que filtren
			$.each(respuesta, function(indice, item){
				$("#capa_graficos").append('<div id="capa_informacion_usuario" class="col-lg-6"><div class="card card-small mb-4"><div class="card-header border-bottom"><h6 class="m-0"><b>' + item.nombre + '</b></h6></div><div class="card-body p-0"><canvas id="grafico_' + item.id + '"></canvas></div></div></div>');

				definir_graficos(indice, item);
			});
		}
	});
}

function definir_graficos(indice, item){
	var formulario = $("#filtros_grafico");
	var data = $(formulario).serializeArray(); // convert form to array
	data.push({name: "fk_idusu", value: '<?php echo($idusuario); ?>'});
	data.push({name: "medida_corporal", value: item.id});
	data.push({name: "ejecutar", value: 'obtener_json_datos'});
	$(".cargando").show();

	$.ajax({
		url: 'ejecutar_acciones.php',
		type: 'POST',
		dataType: 'json',
		async: false,
		data: $.param(data),
		success : function(respuesta){
			var etiquetas = [];
			var valores = [];

			$.each(respuesta, function(indice2, item2){
				etiquetas.push(item2.etiquetas);
				valores.push(item2.valores);
			});

			var ctx = document.getElementById("grafico_" + item.id);

			var myChart = new Chart(ctx, {
			    type: 'bar',
			    data: {
			        labels: etiquetas,
			        datasets: [{	
			        	type: 'bar',            
			            <?php echo($datasets_contenido); ?>

			            label: item.nombre,
			            data: valores
			        },{
						type: 'line',
						label: 'Línea',
						
						borderColor: 'green',
						fill: false,
						data: valores
					}]
			    },
			    <?php echo($options); ?>
			});

			$(".cargando").hide();

			//$('html, body').animate({ scrollTop: $('#capa_graficos').offset().top -80 }, 'slow');
		}
	});
}
</script>
<style type="text/css">
.cargando {
    position: fixed;
    z-index: 1000;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background: rgba( 255, 255, 255, .8 ) url('<?php echo($atras); ?>img/ajax-loader.gif') 50% 50% no-repeat;
}
</style>

<div class="cargando" style="display:none"></div>

<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <h3 class="page-title">Gr&aacuteficos</h3>
  </div>
</div>

<div class="row">
	<?php
	$fechai = date('Y') . "-01-01";
	$fechaf = $conexion -> sumar_fecha($conexion -> sumar_fecha($fechai,1,'year','Y-m-d'),-1,'day','Y-m-d');
	?>
	<script>
$(document).ready(function(){
	$('#fechai').datepicker({
    	language : 'es',
   		format: 'yyyy-mm-dd',
   		autoclose: true,
   		setEndDate: new Date(<?php echo($fechaf); ?>)
	});

	$('#fechaf').datepicker({
    	language : 'es',
   		format: 'yyyy-mm-dd',
   		autoclose: true,
   		setStartDate : new Date(<?php echo($fechai); ?>)
	});

	$("#ejecutar_fechai").click(function(){
    	$("#fechai").datepicker('show');
    });
    $("#ejecutar_fechaf").click(function(){
    	$("#fechaf").datepicker('show');
    });
});
	</script>
	<div id="capa_informacion_usuario" class="col-lg-6">
		<div class="card card-small mb-4">
			<div class="card-header border-bottom">
				<h6 class="m-0"><b>Filtros</b></h6>
			</div>

			<div class="card-body border-bottom">
				<div class="row">
					<div class="col-md-12">
						<i class="fas fa-user mr-1"></i>
						<strong class="mr-1"> Nombres:</strong>
						<a href="<?php echo($atras); ?>ventanas/usuario/ver_usuario.php?idusuario=<?php echo($idusuario); ?>"><?php echo($datos_usuario[0]["nombres"] . " " . $datos_usuario[0]["apellidos"]); ?></a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<form id="filtros_grafico" name="filtros_grafico" method="post" enctype="multipart/form-data">
					<div class="form-row">
				        <div class="form-group col-md-5">
				        	<label class="">Fecha inicial</label>
				        	<div class="input-group" id="capa_fechai">
					    		<input type="text" class="form-control date" id="fechai" name="fechai" readonly="" value="<?php echo($fechai); ?>">
					    		<div class="input-group-append" id="ejecutar_fechai">
									<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
								</div>
					    	</div>
					    </div>

					    <div class="form-group col-md-5">
				        	<label class="">Fecha final</label>
				        	<div class="input-group" id="capa_fechaf">
					    		<input type="text" class="form-control date" id="fechaf" name="fechaf" readonly="" value="<?php echo($fechaf); ?>">
					    		<div class="input-group-append" id="ejecutar_fechaf">
									<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
								</div>
					    	</div>
					    </div>
					    <div class="form-group col-2">
					    	<label class="">&nbsp;</label>
					    	<div class="input-group text-right">
					    		<button id="filtrar_grafico" class="btn btn-outline-success" type="button">Filtrar</button>
					    	</div>
					    </div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row" id="capa_graficos">
	
</div>
<?php echo(pie()); ?>