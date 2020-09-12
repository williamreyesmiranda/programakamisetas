<?php session_start();
include "../conexion.php";?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"?>
	<title>INICIO PRODUCCION</title>
	<link rel="shortcut icon" href="img/kamisetas-icono.png" type="image/x-icon">
	<style>
		
	</style>
</head>
<body>

<?php 

if (empty($_SESSION['active'])){
  header('location: ../');
}
include "includes/header.php";
	/* $query1=mysqli_query($conexion, "SELECT * FROM menu WHERE id=1");
	$data1=mysqli_fetch_array($query1);
	$query2=mysqli_query($conexion, "SELECT * FROM menu WHERE id=2");
	$data2=mysqli_fetch_array($query2);
	$query3=mysqli_query($conexion, "SELECT * FROM menu WHERE id=3");
	$data3=mysqli_fetch_array($query3); */
?>
	<section id="container">
	 	<center><h1 style="text-transform: uppercase;"> <?php if ($_SESSION['sexo']=='hombre'){echo 'Bienvenido '.$_SESSION['nombre'].' al sistema de producción';}else{echo 'Bienvenida '.$_SESSION['nombre'].' al sistema de producción';};?></h1>
		<br><br>
		<!--<table style=" width: 100%;">
			<tr style=" font-size: 50px; width: 90%;">
				<th style=" font-size: 50px; width: 30%; text-transform: uppercase;"><?php echo $data1['titulo']; ?></th>
				<th style=" font-size: 50px; width: 30%; text-transform: uppercase;"><?php echo $data2['titulo']; ?></th>
				<th style=" font-size: 50px; width: 30%; text-transform: uppercase;"><?php echo $data3['titulo']; ?></th>
			</tr>
			<tr style="font-size: 17px; width: 90%;">
				<td valign="top" style=" font-size: 20px; width: 30%; color:#196783; text-align: justify;text-transform: none;"><?php echo $data1['descripcion']; ?></td>
				<td valign="top" style=" font-size: 20px; width: 30%; color:#196783; text-align: justify;text-transform: none;"><?php echo $data2['descripcion']; ?></td>
				<td valign="top" style=" font-size: 20px; width: 30%; color:#196783; text-align: justify; text-transform: none;"><?php echo $data3['descripcion']; ?></td>
			</tr>

		</table></center> -->
	
	</section>
</body>
</html>