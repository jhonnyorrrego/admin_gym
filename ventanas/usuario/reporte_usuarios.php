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
echo(estilos_generales());
echo(estilos_iconos());
?>
<html>
  <head>
  </head>
  <body>
    <div class="container col-sm-10 cargando">
		<div class="row justify-content-md-center card card-small">
			<div class="card-header border-bottom">
				<h6 class="m-0"><b>Usuarios registrados</b></h6>
			</div>
			<div class="col-sm-12 card-body">
				<form class="text-center p-4 formulario_general" id="form_table" name="form_table">
					<table id="table" class="table-striped">
						<thead>
							<tr>
								<th data-field="identificacion" data-sortable="true" data-visible="true">Identificacion</th>
								<th data-field="nombres" data-sortable="true" data-visible="true">Nombres</th>
								<th data-field="apellidos" data-sortable="true" data-visible="true">Apellidos</th>
								<th data-field="email" data-sortable="true" data-visible="true">Email</th>
								<th data-field="celular" data-sortable="true" data-visible="true">Celular</th>
								<th data-field="tipo_usuario_funcion" data-sortable="false" data-visible="true">Tipo de usuario</th>
								<th data-field="estado_funcion" data-sortable="false" data-visible="true">Estado</th>
								<th data-field="acciones_usuario" data-sortable="false" data-visible="true">Acciones</th>
							</tr>
						</thead>
					</table>
					<input type="hidden" id="cantidad_total">
				</form>
			</div>
		</div>
	</div>
  </body>
  <script>
$body = $("body");

var cantidad_registros = 10;
$(document).ready(function(){//Se inicializa la tabla con estilos, el alto del documento y se ejecuta la accion para listar datos sobre la tabla
	var alto_documento = $(document).height();
	var alto_tabla = alto_documento-200;
	
	$('#table').bootstrapTable({
		method: 'get',
		cache: false,
		pagination: true,
		onlyInfoPagination: false,
		showColumns: false,
		showRefresh: false,
		minimumCountColumns: 2,
		clickToSelect: false,
		sidePagination: 'server',
		pageSize: cantidad_registros,
		search: true,
		cardView:false,
		pageList:'All',
		paginationVAlign: 'bottom',
		height: alto_tabla
	});
	
	procesamiento_listar();
});
$(document).ready(function(){//Se aplica el alto a la tabla para que se adapte al momento de cambiar el temaño de la ventana.
	$( window ).resize(function() {
		var alto_documento = $(document).height();
		var alto_tabla = alto_documento-200;
		
		$('#table').bootstrapTable( 'resetView' , {height: alto_tabla} );
	});
	
	$("#form_table").submit(function(){
		return false;
	});
});

$(document).on({
    //ajaxStart: function() { $body.addClass("loading");},
    //ajaxStop: function() { $body.removeClass("loading");}
});

function procesamiento_listar(){
	var data = $('#form_table').serializeObject();
	
	$('#table').bootstrapTable('getOptions').sidePagination = 'client';
	$('#table').bootstrapTable('selectPage', 1);
	$('#table').bootstrapTable('getOptions').sidePagination = 'server';
	
	$('#table').bootstrapTable('refreshOptions', {
		url: 'obtener_usuarios.php',
		queryParams: function (params) {
			console.log(params);
			var q = {
				"rows": cantidad_registros,
				"numfilas":cantidad_registros,
				"actual_row": params.offset,
				"pagina":(params.offset/cantidad_registros)+1,
				"search": params.search,
				"sort": params.sort,
				"order": params.order
			};
			$.extend( data, q);
			  
			var cantidad_total = $("#cantidad_total").val();
			if(cantidad_total){
				$.extend(data,{total:cantidad_total});
			}
			
			return data;
		},
        onLoadSuccess: function(data){
			$("#cantidad_total").val(data.total);
		}
	});
}
function jsonConcat(o1, o2) {
   for (var key in o2) {
    o1[key] = o2[key];
   }
   return o1;
  }
$.fn.serializeObject = function(){
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
  </script>
</html>
<?php
include_once($atras."ventanas/usuario/librerias_reporte_usuarios_js.php");
?>