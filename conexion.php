<?php
$host= 'localhost';
$user= 'root';
$pwd= '';
$db= 'kamisetas';

$conexion= @mysqli_connect($host,$user,$pwd,$db);
$conexion->set_charset('utf8');
return $conexion;


?>