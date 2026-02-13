<?php
// Configuración de conexión a la base de datos RDS
// Estas variables se obtienen de las variables de entorno configuradas por CloudFormation

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'admin');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'workshopdb');
?>
