
<?php
date_default_timezone_set('America/Bogota'); 
if (empty($_SESSION['active'])){
  header('location: ../');
}
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	
<style>


</style>
<header>
		<div class="header">
			<img id="logo" src="../img/kamisetasclaro.png" alt="" style="width: 200px">
			<h1>Sistema de Producción</h1>
			<div class="optionsBar">
				<p>Medellin, <?php echo fechaC() ;?></p>
				<span>|</span>
				<span class="user"><?php echo $_SESSION['nombre'].' ('.$_SESSION['rol'].')'; ?></span>
				<a style="color: #9ecaca; font-weight:bold; font-size: 20px; text-decoration: none;"title="Cerrar Sesión"href="../salir.php"><img  id="" src="./img/salir.png" alt="" style="width: 40px; color: #9ecaca;"></a>
			</div>
		</div>
		<?php include "nav.php"?>

		
	</header>

	