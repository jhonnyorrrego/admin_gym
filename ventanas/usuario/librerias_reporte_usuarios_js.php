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
	$("#pantallas_usuarios").attr("src", src);
});
</script>