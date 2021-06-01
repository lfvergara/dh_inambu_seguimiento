<?php
# Ambiente del sistema
const AMBIENTE = "prod";
const SO_UNIX = true;

# Credenciales para la conexión con la base de datos MySQL
const DB_HOST = 'localhost';
const DB_USER = 'Yavin';
const DB_PASS = '73571n6d41n4m8u';
const DB_NAME = 'dh.inambu.prod';


# Algoritmos utilizados para la encriptación de credenciales
# para el registro y acceso de usuarios del sistema
const ALGORITMO_USER = 'crc32';
const ALGORITMO_PASS = 'sha512';
const ALGORITMO_FINAL = 'md5';


# Direcciones a recursos estáticos de interfaz gráfica
const TEMPLATE = "static/template.html";
if (SO_UNIX == true) {
	define('URL_APP', "/dh_inambu_seguimiento");
	define('URL_STATIC', "/static/template/");
	
	# Directorio private del sistema
	$url_private = "/srv/websites/dh_inambu_seguimiento/private/";
	define('URL_PRIVATE', $url_private);
	ini_set("include_path", URL_PRIVATE);
} else {
	define('URL_APP', "/PROYECTOS DHARMA/dh_inambu");
	define('URL_STATIC', "/PROYECTOS DHARMA/dh_inambu/static/template/");

	# Directorio private del sistema
	$url_private = "c:/dhInambuFiles/private/";
	define('URL_PRIVATE', $url_private);
	ini_set("include_path", URL_PRIVATE);
}

# Configuración estática del sistema
const APP_TITTLE = "dhInambú";
const APP_VERSION = "v3.0";
const APP_ABREV = "Dharma";
const LOGIN_URI = "/seguimiento/consultar";
const DEFAULT_MODULE = "seguimiento";
const DEFAULT_ACTION = "consultar";

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
?>
