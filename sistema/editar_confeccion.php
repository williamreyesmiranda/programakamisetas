<?php
session_start();

include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        date_default_timezone_set('America/Bogota');
        
            
            $idconfeccion=$_POST['idconfeccion'];
            $parcial = $_POST['parcial'];
            $obs= $_POST['obs'];
            $estado= $_POST['estado'];
            $usuario=$_SESSION['iduser'];
            $entrega=$_POST['entrega'];
            $inicioproceso=date('Y-m-d');
            $sql=mysqli_query($conexion, "SELECT * FROM confeccion WHERE estado<1 and idconfeccion=$idconfeccion");
            $sql_result=mysqli_num_rows($sql); 
            
           
            if($sql_result>0){
                $sql_update = mysqli_query($conexion, "UPDATE confeccion SET parcial= '$parcial', obs_confeccion='$obs',
                                            usuario='$usuario', estado='$estado', inicioprocesofecha='$inicioproceso', entrega='$entrega'
                                            WHERE idconfeccion=$idconfeccion ");
                $sql=mysqli_query($conexion, "SELECT * FROM confeccion WHERE idconfeccion=$idconfeccion");
                $sqlselect=mysqli_fetch_array($sql);
                $idpedido=$sqlselect['pedido'];
                $sql_estado = mysqli_query($conexion, "UPDATE pedidos SET estado=1 WHERE idpedido='$idpedido'");
            }else{

            $sql_update = mysqli_query($conexion, "UPDATE confeccion SET parcial= '$parcial', obs_confeccion='$obs',
                                            usuario='$usuario', estado='$estado', entrega='$entrega'
                                            WHERE idconfeccion=$idconfeccion");
            $sql=mysqli_query($conexion, "SELECT * FROM confeccion WHERE idconfeccion=$idconfeccion");
            $sqlselect=mysqli_fetch_array($sql);
            $idpedido=$sqlselect['pedido'];
            $sql_estado = mysqli_query($conexion, "UPDATE pedidos SET estado=1 WHERE idpedido='$idpedido'");
            }
               if($sql_update){
                    $alert='<p class="msg_save">Pedido Actualizado Correctamente</p>';   
                }else{
                    $alert='<p class="msg_error">Error al Actualizar el Pedido</p>';
                }       
            
        
    }

//MOSTRAR DATOS

if (empty($_GET['id'])){
    header('location listaconfecciongeneral.php');
}
$idconfeccion= $_GET['id'];

$sql= mysqli_query($conexion,"SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario, con.entrega,
con.idconfeccion, con.iniciofecha as 'inicioconfeccion', con.finfecha as 'finconfeccion', con.dias as 'diasconfeccion',
con.inicioprocesofecha, con.finprocesofecha, con.parcial, us.usuario, con.obs_confeccion, pr.siglas, es.estado, es.id_estado
FROM pedidos pe 
INNER JOIN procesos pr ON pe.procesos=pr.idproceso
INNER JOIN confeccion con ON pe.idpedido=con.pedido
INNER JOIN usuario us on pe.usuario=us.idusuario
INNER JOIN estado es ON con.estado=es.id_estado WHERE con.idconfeccion=$idconfeccion");

$result_sql=mysqli_num_rows($sql);

if ($result_sql==0){
    header('location listaconfecciongeneral.php');
}else{
    $data=mysqli_fetch_array($sql);
        $id= $data['idconfeccion'];
        $pedido= $data['num_pedido'];
        $iniciopedido=$data['iniciopedido'];
        $finpedido=$data['finpedido'];
        $siglas=$data['siglas'];
        $unds=$data['unds'];
        $inicioconfeccion=$data['inicioconfeccion'];
        $finconfeccion=$data['finconfeccion'];
        $parcial=$data['parcial'];
        $obs=$data['obs_confeccion'];
        $estado=$data['estado'];
        $idestado=$data['id_estado'];
    
    }

?>



<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"?>
    <title>ACTUALIZACIÓN</title>
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

    <div class="form-register">
        
    <a href="listaconfecciongeneral.php" class="btn_new" style="position:fixed ; top:150px; left: 0px;">General</a>
    <a href="reporte_confeccion.php" class="btn_new" style="position:fixed ; top:150px; left: 200px;">Reporte</a>
  

        <form action="" method="post">
       <h1>Actualización de Pedido</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

        <div>
            <input type="hidden" name="idconfeccion" id="idconfeccion" value="<?php echo $idconfeccion;?>">
        </div>
        <div style="border: 1px solid #00a8a8; border-radius: 10px; ">
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
        <center> <h3 style="text-transform: uppercase; ">Info confección</h3>
        
        <div>
            Fecha Inicio: <?php echo $inicioconfeccion?>
        </div>
        <div>
            Fecha Entrega: <?php echo $finconfeccion?>
        </div>
        <div>
            <label for="parcial">Unds Parcial</label>
            <input style ="width: 50px"type="text" name="parcial" id="parcial" autocomplete="off" value="<?php echo $parcial;?>">
        </div>
        <div>
            <label for="entrega">Entrega Producto</label>
            
          <select style ="width: 300px" name="entrega" id="entrega" class="itemunico">
              <?php echo "  <option value=\"".$data['entrega']."\">".$data['entrega']."</option>";?>
          <option value="0" disabled >Selecciones una Opción</option>  
          <option value="confección-parcial" >confe-parcial</option>
          <option value="corte-completo" >corte-completo</option>
          <option value="corte-parcial" >corte-parcial</option>    
           </select>
        </div>
        <div>
            <label for="obs">Observaciones</label>
            <textarea  style ="width: 300px" name="obs" id="obs" rows="4" cols="45"><?php echo $obs;?></textarea> 
        </div>
        <div>
            <label for="estado">Estado</label>
            <?php
            $query_estado =mysqli_query($conexion,"SELECT * FROM estado WHERE id_estado=1 OR id_estado=2");
            $result_estado = mysqli_num_rows($query_estado);
            
            ?>
          <select style ="width: 300px" name="estado" id="estado" class="itemunico">
              <?php echo "  <option value=\"".$idestado."\">".$estado."</option>";?>
          <option value="0" disabled >Selecciones una Opcion</option>  
            <?php 
           
            if ($result_estado>0){
                while($rol =mysqli_fetch_array($query_estado)){
                    
                  echo "  <option value=\"".$rol['id_estado']."\">".$rol['estado']."</option>";
            }
            } ?>
           </select>
        </div>
        </center>
        </div>
        
        <input type="submit" value="Editar Pedido" class="btn-save">

        </form>


    </div>


</section>
	
</body>
</html>