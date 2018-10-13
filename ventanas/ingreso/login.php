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
echo(login_css());
?>
<html>
	<head>
	</head>
	<body>
		<div class="container">
			<div class="card card-small">
				<div class="card-header border-bottom">
					<h6 class="m-0"><b>Ingreso</b></h6>
				</div>
				<div class="row card-body">
					<div class="col-md-4 login-sec">
						<form class="login-form">
						  <div class="form-group">
							<label for="exampleInputEmail1" class="">Usuario</label>
							<input type="text" class="form-control" placeholder="">
							
						  </div>
						  <div class="form-group">
							<label for="exampleInputPassword1" class="">Contrase&ntilde;a</label>
							<input type="password" class="form-control" placeholder="">
						  </div>
						  
						  
							<div class="form-check">
								<button type="submit" class="mb-2 btn btn-outline-success mr-2 float-right">Submit</button>
							  </div>
						  
						</form>
					</div>
					<div class="col-md-8 banner-sec">
						<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
							<ol class="carousel-indicators">
								<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
								<!--li data-target="#carouselExampleIndicators" data-slide-to="1"></li-->
							</ol>
							<div class="carousel-inner" role="listbox">
								<div class="carousel-item active">
									<img class="d-block img-fluid" src="https://static.pexels.com/photos/33972/pexels-photo.jpg" alt="First slide">
									<div class="carousel-caption d-none d-md-block">
										<div class="banner-text">
											<h2>Esto es Gym Admin</h2>
											<p>Permite la gesti&oacute;n y administraci&oacute;n de tu gymnasio.</p>
										</div>	
									</div>
								</div>
								<!--div class="carousel-item">
									<img class="d-block img-fluid" src="https://images.pexels.com/photos/7097/people-coffee-tea-meeting.jpg" alt="First slide">
								</div-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>