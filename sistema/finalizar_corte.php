<?php
session_start();
if($_SESSION['idrol']!=1){
    header('location: ../');
}
include "../conexion.php";
date_default_timezone_set('America/Bogota');
if(!empty($_POST)){
$id = $_POST['idcorte'];
$obs = $_POST['obs'];
$parcial = $_POST['unds'];
$query_delete=mysqli_query($conexion, "UPDATE corte SET estado= 4, obs_corte='$obs', parcial='$parcial'
                                        WHERE idcorte=$id ");
$sql=mysqli_query($conexion, "SELECT * FROM corte WHERE idcorte=$idcorte");
$sqlselect=mysqli_fetch_array($sql);
$idpedido=$sqlselect['pedido'];
$sql_estado = mysqli_query($conexion, "UPDATE pedidos SET estado=1 WHERE idpedido='$idpedido'");
if($query_delete){
    $alert='<p class="msg_save">Pedido Finalizado Correctamente</p>';
}else{
    $alert='<p class="msg_error">Error al Finalizar el Pedido</p>';
}
}
 

if(empty($_REQUEST['id'])){
    header("location: listacortegeneral.php");
}else{
    
    $idcorte=$_REQUEST['id'];

    $query=mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
    pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
    co.idcorte, co.iniciofecha as 'iniciocorte', co.finfecha as 'fincorte', co.dias as 'diascorte',
    co.inicioprocesofecha, co.finprocesofecha, co.parcial, us.usuario, co.obs_corte, pr.siglas, es.estado, es.id_estado
    FROM pedidos pe 
    INNER JOIN procesos pr ON pe.procesos=pr.idproceso
    INNER JOIN corte co ON pe.idpedido=co.pedido
    INNER JOIN usuario us on pe.usuario=us.idusuario
    INNER JOIN estado es ON co.estado=es.id_estado WHERE co.idcorte=$idcorte");
    $result = mysqli_num_rows($query);

    if($result>0){
        $data = mysqli_fetch_array($query);
            $id= $data['idcorte'];
            $pedido= $data['num_pedido'];
            $iniciopedido=$data['iniciopedido'];
            $finpedido=$data['finpedido'];
            $siglas=$data['siglas'];
            $unds=$data['unds'];
            $iniciocorte=$data['iniciocorte'];
            $fincorte=$data['fincorte'];
            $parcial=$data['parcial'];
            $obs=$data['obs_corte'];
            $estado=$data['estado'];
            $idestado=$data['id_estado'];
        }else{
            header("location: lista_usuario.php");
        }
    }

?>



<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"?>
	<title>FINALIZAR PEDIDO</title>
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

    <a href="listacortegeneral.php" class="btn_new" style="position:fixed ; top:150px; left: 0px;">General</a>
    <a href="" class="btn_new" style="position:fixed ; top:150px; left: 120px;">Reporte</a>

        <div class="data_delete">
        
            <form style="width:450px" action="" method="post">
            <h2> <?php if ($_SESSION['sexo']=='hombre'){echo $_SESSION['nombre'].', ¿está seguro ';}else{echo $_SESSION['nombre'].', ¿está segura ';}?> que desea <b> FINALIZAR </b>el siguiente pedido?</h2>
            <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>
            <hr>
            <div style="border: 1px solid #00a8a8; border-radius: 10px; ">
            <input type="hidden" name="idcorte" id="idcorte" value="<?php echo $idcorte;?>">
            <input type="hidden" name="unds" id="unds" value="<?php echo $unds?>">
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
        <center> <h3 style="text-transform: uppercase; ">Info corte</h3>
        
        <div>
            Fecha Inicio: <?php echo $iniciocorte?>
        </div>
        <div>
            Fecha Entrega: <?php echo $fincorte?>
        </div>
        
        <div>
            <label for="obs">Observaciones (opcional)</label>
            <textarea  style ="width: 300px" name="obs" id="obs" rows="4" cols="45"></textarea> 
        </div>
        </center>
        </div>

            
            <input type="submit" value="Confirmar" class="btn_ok glyphicon glyphicon-remove">
            
            <a href="listacortegeneral.php" class="btn_cancel"><span >Cancelar</span> </a>
               
            </form>
        </div>
       
</section>

</body>
</html>