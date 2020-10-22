<?php
$host= 'localhost';
$user= 'intranet_william';
$pwd= 'wrm1124023751';
$db= 'intranet_kamisetas';

$conexion= mysqli_connect($host,$user,$pwd,$db);
$conexion->set_charset('utf8');
return $conexion;


?>