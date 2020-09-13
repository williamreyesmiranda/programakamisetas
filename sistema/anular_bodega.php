<?php
session_start();

include "../conexion.php";
date_default_timezone_set('America/Bogota');
if(!empty($_POST)){
$id = $_POST['idbodega'];
$obs = $_POST['obs'];
$query_delete=mysqli_query($conexion, "UPDATE bodega SET estado= 3, obs_bodega='$obs' 
                                        WHERE idbodega=$id ");

if($query_delete){
    $alert='<p class="msg_save">Pedido Anulado Correctamente</p>';
}else{
    $alert='<p class="msg_error">Error al Anular el Pedido</p>';
}
}
 

if(empty($_REQUEST['id'])){
    header("location: listabodegageneral.php");
}else{
    
    $idbodega=$_REQUEST['id'];

    $query=mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
    pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
    bo.idbodega, bo.iniciofecha as 'iniciobodega', bo.finfecha as 'finbodega', bo.dias as 'diasbodega',
    bo.inicioprocesofecha, bo.finprocesofecha, bo.parcial, us.usuario, bo.obs_bodega, pr.siglas, es.estado, es.id_estado
    FROM pedidos pe 
    INNER JOIN procesos pr ON pe.procesos=pr.idproceso
    INNER JOIN bodega bo ON pe.idpedido=bo.pedido
    INNER JOIN usuario us on pe.usuario=us.idusuario
    INNER JOIN estado es ON bo.estado=es.id_estado WHERE bo.idbodega=$idbodega");
    $result = mysqli_num_rows($query);

    if($result>0){
        $data = mysqli_fetch_array($query);
            $id= $data['idbodega'];
            $pedido= $data['num_pedido'];
            $iniciopedido=$data['iniciopedido'];
            $finpedido=$data['finpedido'];
            $siglas=$data['siglas'];
            $unds=$data['unds'];
            $iniciobodega=$data['iniciobodega'];
            $finbodega=$data['finbodega'];
            $parcial=$data['parcial'];
            $obs=$data['obs_bodega'];
            $estado=$data['estado'];
            $idestado=$data['id_estado'];
        }else{
            header("location: listabodegageneral.php");
        }
    }

?>



<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"?>
	<title>ANULAR PEDIDO</title>
	<link rel="shortcut icon" href="img/kamisetas-icono.png" type="image/x-icon">
	<style>
		
	</style>
</head>
<body>

<?php 

if (empty($_SESSION['active'])){
  header('location: ../');
}
include "includes/header.php"?>
	<section id="container">

    <a href="listabodegageneral.php" class="btn_new" style="position:fixed ; top:150px; left: 0px;">General</a>
    <a href="reporte_bodega.php" class="btn_new" style="position:fixed ; top:150px; left: 200px;">Reporte</a>

        <div class="data_delete">
        
            <form style="width:450px" action="" method="post">
            <h2> <?php if ($_SESSION['sexo']=='hombre'){echo $_SESSION['nombre'].', ¿está seguro ';}else{echo $_SESSION['nombre'].', ¿está segura ';}?> que desea <b> ANULAR </b>el siguiente pedido?</h2>
            <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>
            <hr>
            <div style="border: 1px solid #00a8a8; border-radius: 10px; ">
            <input type="hidden" name="idbodega" id="idbodega" value="<?php echo $idbodega;?>">
        <center> <h3 style="text-transform: uppercase; ">pedido: <?php echo $pedido?></h3>
       
        <div>
            Fecha Inicio: <?php echo $iniciopedido?>
        </div>
        <div>
            Fecha Entrega: <?php echo $finpedido?>
        </div>
        <div>
            Procesos: <?php echo $siglas?>
        </div>
        <div>
            Unidades: <?php echo $unds?>
        </div>
        </center>
        </div>
        <div style="border: 1px solid #00a8a8; border-radius: 10px; ">
        <center> <h3 style="text-transform: uppercase; ">Info Bodega</h3>
        
        <div>
            Fecha Inicio: <?php echo $iniciobodega?>
        </div>
        <div>
            Fecha Entrega: <?php echo $finbodega?>
        </div>
        <div>
            <label for="obs">Observaciones (opcional)</label>
            <textarea  style ="width: 300px" name="obs" id="obs" rows="4" cols="45"></textarea> 
        </div>
        </center>
        </div>

            
            <input type="submit" value="Confirmar" class="btn_ok glyphicon glyphicon-remove">
            
            <a href="listabodegageneral.php" class="btn_cancel"><span >Cancelar</span> </a>
               
            </form>
        </div>
       
</section>

</body>
</html>