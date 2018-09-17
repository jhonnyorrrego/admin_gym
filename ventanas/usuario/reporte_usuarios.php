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
			<div class="col-sm-0">
				<form class="text-center border border-light p-4" id="form_table" name="form_table">
					<p class="h4 mb-4">Usuarios registrados</p>
					<table id="table">
						<thead>
							<tr>
								<th data-field="id" data-sortable="false" data-visible="true">Id</th>
								<th data-field="name" data-sortable="false" data-visible="true">Name</th>
								<th data-field="price" data-sortable="false" data-visible="true">Price</th>
							</tr>
						</thead>
					</table>
				</form>
			</div>
		</div>
	</div>
  </body>
  <script>
$body = $("body");
$(document).ready(function(){
	$('#table').bootstrapTable({
		method: 'get',
		cache: false,
		pagination: true,
		showColumns: false,
		showRefresh: false,
		minimumCountColumns: 2,
		clickToSelect: false,
		sidePagination: 'server',
		pageSize: 20,
		search: false,
		cardView:false,
		pageList:'All',
		paginationVAlign:'both',
		height: 400
	});
	
	procesamiento_listar();
});
$(document).on({
    ajaxStart: function() { $body.addClass("loading");},
    ajaxStop: function() { $body.removeClass("loading");}
});

function procesamiento_listar(externo){
	var data = $('#form_table').serializeObject();
	
	$('#table').bootstrapTable('getOptions').sidePagination = 'client';
	$('#table').bootstrapTable('selectPage', 1);
	$('#table').bootstrapTable('getOptions').sidePagination = 'server';
	
	$('#table').bootstrapTable('refreshOptions', {
		url: '<?php echo($raiz); ?>js/json_bootstrap-table.json',
		queryParams: function (params) {
			var q = {
			"rows": 20,
			"numfilas":20,
			"actual_row": params.offset,
			"pagina":(params.offset/20)+1,
			//"search": params.search,
			//"sort": params.sort,
			"order": params.order
		};
		$.extend( data, q);
		if(externo){
		  $.extend(data,{externo:1});
		}
		return data;
		},
        onLoadSuccess: function(data){
			
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