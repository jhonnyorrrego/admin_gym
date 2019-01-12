<?php
global $atras;
?>
<script>
$(document).ready(function(){
  var alto_documento=$(document).height();
  $("#pantallas_usuarios").height(alto_documento-150);
  
  $( window ).resize(function() {
	  var alto_documento=$(document).height();
	  $("#pantallas_usuarios").height(alto_documento-150);
  });
});
</script>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <iframe class="modal-content" id="pantallas_usuarios" name="pantallas_usuarios" border="0px" frameborder="0"></iframe>
  </div>
</div>
<script>
$(document).on('click','.ver_usuario',function(){
	var idusuario = $(this).attr("idusuario");
	var src = "<?php echo($atras); ?>ventanas/usuario/ver_usuario.php?idusuario=" + idusuario;
	window.open(src,"_self");
});
$(document).on('click','.ver_grafico',function(){
  var idusuario = $(this).attr("idusuario");
  var src = "<?php echo($atras); ?>ventanas/graficos/generar_grafico.php?idusuario=" + idusuario;
  window.open(src,"_self");
});
$(document).on('click','.ingresar_usuario', function(){
  if(!confirm('Está seguro de ingresar este usuario?')){
    return false;
  }

  var x_idusu = $(this).attr("idusuario");
  $.ajax({
    url: '<?php echo($atras); ?>ventanas/ingreso/ejecutar_acciones.php',
    type: 'POST',
    dataType: 'json',
    async: false,
    data: {ejecutar: 'confirmar_ingreso_usuario', idusu : x_idusu},
    success : function(respuesta){
      if(respuesta.exito){
        notificacion(respuesta.mensaje,'success',4000);

        refrescarUltimoAcceso(x_idusu);
      } else {
        notificacion(respuesta.mensaje,'warning',4000);
      }
    }
  });
});

function refrescarUltimoAcceso(x_idusu){
    $.ajax({
        url: '<?php echo($atras); ?>ventanas/usuario/ejecutar_acciones.php',
        type: 'POST',
        dataType: 'json',
        async: false,
        data: {ejecutar: 'obtener_ultimo_acceso', idusu : x_idusu},
        success : function(respuesta){
            if(respuesta.exito){
                $("#capaUltimoAcceso_" + x_idusu).html(respuesta.ultimo_acceso);
            }
        }
    });
}
</script>