<?php
date_default_timezone_set("America/Bogota");

if (!defined("SERVIDOR")){
	define("SERVIDOR", "127.0.0.1");
}
if (!defined("USUARIO")){
	define("USUARIO", "admin");
}
if (!defined("CLAVE")){
	define("CLAVE", "Samteamo123");
}
if (!defined("DB")){
	define("DB", "admin_gym");
}
if (!defined("MOTOR")){
	define("MOTOR", "mysql");
}

ini_set("display_errors",true);
ini_set("safe_mode",false);
?>