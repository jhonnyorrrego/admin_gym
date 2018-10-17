<?php
$atras="";
require_once 'vendor/autoload.php';
include_once($atras."lib_gym.php");
global $conexion;

include_once ('librerias.php');

echo(bootstrap_css());
echo(jquery_js());
echo(bootstrap_js());
echo(notificacion());
echo(estilos_iconos());

$movil = $conexion -> detectar_movil(1);
?>
<html>
  <head>
    
  </head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light border">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
		<?php
		if(@$_SESSION["usuario" . LLAVE_SESION]){
		?>
        <li class="nav-item active border-right">
          <a class="nav-link enlaces" href="#" id="inicio"> <i class="fas fa-home"></i> Inicio </a>
        </li>
		<?php
		}
		?>
		<!--li class="nav-item border-right">
          <a class="nav-link enlaces" href="#" id="usuario_nuevo"> <i class="fas fa-user-plus"></i> Usuario nuevo <span class="sr-only">(current)</span></a>
        </li-->
		<!--li class="nav-item border-right">
          <a class="nav-link enlaces" href="#" id="reporte_usuario"> <i class="fas fa-list-ul"></i> Usuarios <span class="sr-only">(current)</span></a>
        </li-->
		
		<?php
		if(@$_SESSION["usuario" . LLAVE_SESION]){
		?>
        <li class="nav-item dropdown border-right">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-list-ul"></i> Usuarios <span class="sr-only">(current)</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item enlaces" href="#" id="reporte_usuario">
				<i class="fas fa-list-ul"></i> Lista de usuarios
			</a>
            <a class="dropdown-item enlaces" href="#" id="usuario_nuevo">
				<i class="fas fa-user-plus"></i> Usuario nuevo
			</a>
            
			<!--div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a-->
          </div>
        </li>
		    <li class="nav-item active">
          <a class="nav-link enlaces" href="#" id="logout"> <i class="fas fa-power-off"></i> Cerrar sesi&oacute;n </a>
        </li>
		<?php
		}
		?>
      </ul>
	  
	  <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
		<li class="nav-item">
			<img src="img/logo.png" alt="logo" style="width:150px;height:35px" class="img-fluid rounded">
		</li>
	  </ul>
    </div>
  </nav>
<?php
$pagina_defecto = 'ventanas/ingreso/login.php';
if(@$_SESSION["usuario" . LLAVE_SESION]){
  $pagina_defecto = 'ventanas/ingreso/ingreso.php';
}
?>
  <div id="capa_iframe_cuerpo" style="" class="">
    <iframe id="iframe_cuerpo" name="iframe_cuerpo" style="width:100%;height:100%;" border="0px" frameborder="0" src="<?php echo($pagina_defecto); ?>"></iframe>
  </div>
</body>
</html>
<script>
$(document).ready(function(){
  var alto_documento=$(document).height();
  $("#iframe_cuerpo").height(alto_documento-150);
  
  $( window ).resize(function() {
	  var alto_documento=$(document).height();
	  $("#iframe_cuerpo").height(alto_documento-150);
  });
});
$(".enlaces").click(function(){
  $(".enlaces").removeClass("active");
  var boton=$(this).attr("id");
  
  if(boton == 'inicio'){          
    $("#iframe_cuerpo").attr("src","ventanas/ingreso/ingreso.php");
  }
  if(boton == 'usuario_nuevo'){          
    $("#iframe_cuerpo").attr("src","ventanas/usuario/usuario_add.php");
  }
  if(boton == 'reporte_usuario'){          
    $("#iframe_cuerpo").attr("src","ventanas/usuario/reporte_usuarios.php");
  }
  if(boton == 'logout'){          
    $("#iframe_cuerpo").attr("src","ventanas/ingreso/salir.php");
  }
});
</script>