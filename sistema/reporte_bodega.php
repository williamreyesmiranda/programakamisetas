<?php
session_start();
if($_SESSION['idrol']!=1){
    header('location: ../');
}
include "../conexion.php";
date_default_timezone_set('America/Bogota');
//fechas a dias
function number_of_working_days($from, $to) {
    $workingDays = [1, 2, 3, 4, 5]; # formato = N (1 = lunes, ...)
    $holidayDays = ['', '*', '']; # fechas festivas

    $from = new DateTime($from);
    $to = new DateTime($to);
    $to->modify('+1 day');
    $interval = new DateInterval('P1D');
    $periods = new DatePeriod($from, $interval, $to);

    $days = 0;
    foreach ($periods as $period) {
        if (!in_array($period->format('N'), $workingDays)) continue;
        if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
        if (in_array($period->format('*-m-d'), $holidayDays)) continue;
        $days++;
    }
    return $days;
}
//dias a fechas
function sumasdiasemana($fecha,$dias)
        {
            $datestart= strtotime($fecha);
            $datesuma = 15 * 86400;
            $diasemana = date('N',$datestart);
            $totaldias = $diasemana+$dias;
            $findesemana = intval( $totaldias/5) *2 ; 
            $diasabado = $totaldias % 5 ; 
            if ($diasabado==6) $findesemana++;
            if ($diasabado==0) $findesemana=$findesemana-2;
         
            $total = (($dias+$findesemana) * 86400)+$datestart ; 
            return $fechafinal = date('Y-m-d', $total);
        }
        $hoy=date('Y-m-d');
        $tresdias=sumasdiasemana($hoy, 3);
        $cuatrodias=sumasdiasemana($hoy, 4);

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	
    <?php include "includes/scripts.php"?>
    
	<title>BODEGA</title>
	<link rel="shortcut icon" href="img/kamisetas-icono.png" type="image/x-icon">
	<style>
  
</style>
</head>
<body>
<?php 

if (empty($_SESSION['active'])){
  header('location: ../');
}
?>


<a href="listabodegageneral.php" class="btn_new" style="position:fixed ; top:0px; left: 0px;"><input style="display:block; width:150px; position:fixed ; top:0px; left: 0%;;" class="btn_new" type='button' href="listabodegageneral.php" value='MENÚ' /></a>

<input style="display:block; width:150px; position:fixed ; top:0px; left: 85%;;" class="btn_new" type='button' onclick='window.print();' value='Imprimir' />


<center><div style="width:99%">

<h1 style="font-size:50px; font-weight:bold; color: #00a8a8">REPORTE DE BODEGA <?php echo date('d-m-Y');?></h1>
        <table style="width:50% !important; ">
            <thead>
                <tr >
                    <th ></th>
                    <th>Fechas Vencidas</th>
                    <th >0 a 3 días</th>
                    <th >> 3 días </th>
                    <th >Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th >Pedidos</th>
                    <td background="red"><?php $query=mysqli_query($conexion,"SELECT * FROM bodega WHERE finfecha<'$hoy' AND estado<=2"); echo mysqli_num_rows($query)?></td>
                    <td><?php $query=mysqli_query($conexion,"SELECT * FROM bodega WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias' "); echo mysqli_num_rows($query)?></td>
                    <td><?php $query=mysqli_query($conexion,"SELECT * FROM bodega WHERE finfecha>='$cuatrodias' AND estado<=2"); echo mysqli_num_rows($query)?></td>
                    <td><?php $query=mysqli_query($conexion,"SELECT * FROM bodega WHERE  estado<=2"); echo mysqli_num_rows($query)?></td>
                </tr>
                <tr>
                    <th >Unds Listas</th>
                    <td><?php $query=mysqli_query($conexion,"SELECT SUM(parcial) as 'suma' FROM bodega WHERE finfecha<'$hoy' AND estado<=2"); $result=mysqli_fetch_array($query); echo $result['suma'];?></td>
                    <td><?php $query=mysqli_query($conexion,"SELECT SUM(parcial) as 'suma' FROM bodega WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias'"); $result=mysqli_fetch_array($query); echo $result['suma'];?></td>
                    <td><?php $query=mysqli_query($conexion,"SELECT SUM(parcial) as 'suma' FROM bodega WHERE finfecha>='$cuatrodias' AND estado<=2"); $result=mysqli_fetch_array($query); echo $result['suma'];?></td>
                    <td><?php $query=mysqli_query($conexion,"SELECT SUM(parcial) as 'suma' FROM bodega WHERE  estado<=2"); $result=mysqli_fetch_array($query); echo $result['suma'];?></td>
                </tr>
                <tr>
                    <th >Unds Faltantes</th>
                    <td><?php $query=mysqli_query($conexion,"SELECT SUM(pe.unds-bo.parcial) as 'resta' FROM bodega bo INNER JOIN pedidos pe ON bo.pedido=pe.idpedido
                                                            WHERE bo.estado<=2 AND bo.finfecha<'$hoy'"); $result=mysqli_fetch_array($query); echo $result['resta'];?></td>
                    <td><?php $query=mysqli_query($conexion,"SELECT SUM(pe.unds-bo.parcial) as 'resta' FROM bodega bo INNER JOIN pedidos pe ON bo.pedido=pe.idpedido
                                                            WHERE bo.estado<=2 AND bo.finfecha BETWEEN'$hoy' AND '$tresdias'"); $result=mysqli_fetch_array($query); echo $result['resta'];?></td>
                    <td><?php $query=mysqli_query($conexion,"SELECT SUM(pe.unds-bo.parcial) as 'resta' FROM bodega bo INNER JOIN pedidos pe ON bo.pedido=pe.idpedido
                                                            WHERE bo.estado<=2 AND bo.finfecha>='$cuatrodias'"); $result=mysqli_fetch_array($query); echo $result['resta'];?></td>
                    <td><?php $query=mysqli_query($conexion,"SELECT SUM(pe.unds-bo.parcial) as 'resta' FROM bodega bo INNER JOIN pedidos pe ON bo.pedido=pe.idpedido
                                                            WHERE bo.estado<=2"); $result=mysqli_fetch_array($query); echo $result['resta'];?></td>
                </tr>
                <tr>
                    <th >Unds Totales</th>
                    <td><?php $query=mysqli_query($conexion,"SELECT SUM(pe.unds) as 'suma' FROM bodega bo INNER JOIN pedidos pe ON bo.pedido=pe.idpedido
                                                            WHERE bo.estado<=2 AND bo.finfecha<'$hoy'"); $result=mysqli_fetch_array($query); echo $result['suma'];?></td>
                    <td><?php $query=mysqli_query($conexion,"SELECT SUM(pe.unds) as 'suma' FROM bodega bo INNER JOIN pedidos pe ON bo.pedido=pe.idpedido
                                                            WHERE bo.estado<=2 AND bo.finfecha BETWEEN'$hoy' AND '$tresdias'"); $result=mysqli_fetch_array($query); echo $result['suma'];?></td>
                    <td><?php $query=mysqli_query($conexion,"SELECT SUM(pe.unds) as 'suma' FROM bodega bo INNER JOIN pedidos pe ON bo.pedido=pe.idpedido
                                                            WHERE bo.estado<=2 AND bo.finfecha>='$cuatrodias'"); $result=mysqli_fetch_array($query); echo $result['suma'];?></td>
                    <td><?php $query=mysqli_query($conexion,"SELECT SUM(pe.unds) as 'suma' FROM bodega bo INNER JOIN pedidos pe ON bo.pedido=pe.idpedido
                                                            WHERE bo.estado<=2"); $result=mysqli_fetch_array($query); echo $result['suma'];?></td>
                </tr>
            </tbody>
        </table>
       <br><br><br>
        <table id="tabla" class="display" >
         <thead>  
              
            <tr>
                <th style="border-right: 1px solid #9ecaca"colspan="9">Información Pedido</th>
                <th colspan="8"> Información Bodega</th>
            </tr>   
             <tr>
                <th>Pedido</th>
                <th>Cliente</th>
                <th>Asesor</th>
                <th>Fecha Inicio  </th>
                <th>Fecha Entrega</th>
                <th>Días Hab</th>
                <th>Días Falta</th>
                <th>Proc</th>
                <th style="border-right: 1px solid #9ecaca">Unds</th>
                
                <th>Fecha Inicio  </th>
                <th>Fecha Entrega</th>
                <th>Días Hab</th>
                <th>Días Falta</th>
                <th>Unds Parcial</th>
                <th>Unds Falta</th>
                <th>Observaciones</th>
                <th>Estado</th>
               
               

            </tr>
            <tr>
             <th colspan="17">FECHAS VENCIDAS</th>
             </tr>
        </thead>
        <tbody>
            <?php
            
            
            $query=mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
            pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
            bo.idbodega, bo.iniciofecha as 'iniciobodega', bo.finfecha as 'finbodega', bo.dias as 'diasbodega',
            bo.inicioprocesofecha, bo.finprocesofecha, bo.parcial, us.usuario, bo.obs_bodega, pr.siglas, es.estado
            FROM pedidos pe 
            INNER JOIN procesos pr ON pe.procesos=pr.idproceso
            INNER JOIN bodega bo ON pe.idpedido=bo.pedido
            INNER JOIN usuario us on pe.usuario=us.idusuario
            INNER JOIN estado es ON bo.estado=es.id_estado
            WHERE bo.estado<=2 and finfecha<'$hoy'");
            
            $result=mysqli_num_rows($query);

            if ($result>0){
                while($data=mysqli_fetch_array($query)){

                    
                    $unds=$data['unds'];
                    $parcial=$data['parcial'];
                    $falta=$unds-$parcial;
                    
                    $diapedido=$data['finpedido'];
                    $diabodega=$data['finbodega'];
                    $diafaltapedido=  number_of_working_days($hoy, $diapedido)-1;
                    if($diafaltapedido<0){
                        $diafaltapedido=  -(number_of_working_days($diapedido, $hoy)-1);
                    }
                    $diafaltabodega=  number_of_working_days($hoy, $diabodega)-1;   
                    if($diafaltabodega<0){
                        $diafaltabodega=  -(number_of_working_days($diabodega, $hoy)-1);
                    }
                    echo "
                    <tr>
                    <td style=\"background-color: #ff000088;\">".$data['num_pedido']."</td>
                    <td style=\"background-color: #ff000088;\">".$data['cliente']."</td>
                    <td style=\"background-color: #ff000088;\">".$data['asesor']."</td>
                    <td style=\"background-color: #ff000088;\">".$data['iniciopedido']."</td>
                    <td style=\"background-color: #ff000088;\">".$data['finpedido']."</td>
                    <td style=\"background-color: #ff000088;\">".$data['diaspedido']."</td>";
                    if($diafaltapedido>3){
                       echo "<td style=\"background-color: #00ff1588;\">".$diafaltapedido."</td>";
                    }elseif($diafaltapedido>=0){
                        echo "<td style=\"background-color: #fbff0088;\">".$diafaltapedido."</td>";  
                    }else{
                        echo "<td style=\"background-color: #ff000088;\">".$diafaltapedido."</td>"; 
                    }
                    
                   echo " <td style=\"background-color: #ff000088;\">".$data['siglas']."</td>
                    <td style=\"border-right: 1px solid #00a8a8;background-color: #ff000088\">".$unds."</td>
                   
                    <td style=\"background-color: #ff000088;\">".$data['iniciobodega']."</td>
                    <td style=\"background-color: #ff000088;\">".$data['finbodega']."</td>
                    <td style=\"background-color: #ff000088;\">".$data['diasbodega']."</td>";
                    if($diafaltabodega>3){
                        echo "<td style=\"background-color: #00ff1588;\">".$diafaltabodega."</td>";
                     }elseif($diafaltabodega>=0){
                         echo "<td style=\"background-color: #fbff0088;\">".$diafaltabodega."</td>";  
                     }else{
                         echo "<td style=\"background-color: #ff000088;\">".$diafaltabodega."</td>"; 
                     }
                    echo "<td style=\"background-color: #ff000088;\">".$parcial."</td>
                    <td style=\"background-color: #ff000088;\">".$falta."</td>
                    <td style=\"background-color: #ff000088;\">".$data['obs_bodega']."</td>
                    <td style=\"background-color: #ff000088;\">".$data['estado']."</td>
                    </tr>                    ";
                }
            }
            ?>
            <tr>
                <th colspan="17">0 a 3 DÍAS</th>
            </tr>
         
            <?php
            
            
            $query=mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
            pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
            bo.idbodega, bo.iniciofecha as 'iniciobodega', bo.finfecha as 'finbodega', bo.dias as 'diasbodega',
            bo.inicioprocesofecha, bo.finprocesofecha, bo.parcial, us.usuario, bo.obs_bodega, pr.siglas, es.estado
            FROM pedidos pe 
            INNER JOIN procesos pr ON pe.procesos=pr.idproceso
            INNER JOIN bodega bo ON pe.idpedido=bo.pedido
            INNER JOIN usuario us on pe.usuario=us.idusuario
            INNER JOIN estado es ON bo.estado=es.id_estado
            WHERE bo.estado<=2 and finfecha BETWEEN'$hoy' AND '$tresdias'");
            
            $result=mysqli_num_rows($query);

            if ($result>0){
                while($data=mysqli_fetch_array($query)){

                    
                    $unds=$data['unds'];
                    $parcial=$data['parcial'];
                    $falta=$unds-$parcial;
                    
                    $diapedido=$data['finpedido'];
                    $diabodega=$data['finbodega'];
                    $diafaltapedido=  number_of_working_days($hoy, $diapedido)-1;
                    if($diafaltapedido<0){
                        $diafaltapedido=  -(number_of_working_days($diapedido, $hoy)-1);
                    }
                    $diafaltabodega=  number_of_working_days($hoy, $diabodega)-1;   
                    if($diafaltabodega<0){
                        $diafaltabodega=  -(number_of_working_days($diabodega, $hoy)-1);
                    }
                    echo "
                    <tr>
                    <td style=\"background-color: #fbff0088;\">".$data['num_pedido']."</td>
                    <td style=\"background-color: #fbff0088;\">".$data['cliente']."</td>
                    <td style=\"background-color: #fbff0088;\">".$data['asesor']."</td>
                    <td style=\"background-color: #fbff0088;\">".$data['iniciopedido']."</td>
                    <td style=\"background-color: #fbff0088;\">".$data['finpedido']."</td>
                    <td style=\"background-color: #fbff0088;\">".$data['diaspedido']."</td>";
                    if($diafaltapedido>3){
                       echo "<td style=\"background-color: #00ff1588;\">".$diafaltapedido."</td>";
                    }elseif($diafaltapedido>=0){
                        echo "<td style=\"background-color: #fbff0088;\">".$diafaltapedido."</td>";  
                    }else{
                        echo "<td style=\"background-color: #ff000088;\">".$diafaltapedido."</td>"; 
                    }
                    
                   echo " <td style=\"background-color: #fbff0088;\">".$data['siglas']."</td>
                    <td style=\"border-right: 1px solid #00a8a8; background-color: #fbff0088\">".$unds."</td>
                   
                    <td style=\"background-color: #fbff0088;\">".$data['iniciobodega']."</td>
                    <td style=\"background-color: #fbff0088;\">".$data['finbodega']."</td>
                    <td style=\"background-color: #fbff0088;\">".$data['diasbodega']."</td>";
                    if($diafaltabodega>3){
                        echo "<td style=\"background-color: #00ff1588;\">".$diafaltabodega."</td>";
                     }elseif($diafaltabodega>=0){
                         echo "<td style=\"background-color: #fbff0088;\">".$diafaltabodega."</td>";  
                     }else{
                         echo "<td style=\"background-color: #ff000088;\">".$diafaltabodega."</td>"; 
                     }
                    echo "<td style=\"background-color: #fbff0088;\">".$parcial."</td>
                    <td style=\"background-color: #fbff0088;\">".$falta."</td>
                    <td style=\"background-color: #fbff0088;\">".$data['obs_bodega']."</td>
                    <td style=\"background-color: #fbff0088;\">".$data['estado']."</td>
                    </tr>                    ";
                }
            }
            ?>
            <tr>
                <th colspan="17">4 DÍAS EN ADELANTE</th>
            </tr>
         
            <?php
            
           
            $query=mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
            pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
            bo.idbodega, bo.iniciofecha as 'iniciobodega', bo.finfecha as 'finbodega', bo.dias as 'diasbodega',
            bo.inicioprocesofecha, bo.finprocesofecha, bo.parcial, us.usuario, bo.obs_bodega, pr.siglas, es.estado
            FROM pedidos pe 
            INNER JOIN procesos pr ON pe.procesos=pr.idproceso
            INNER JOIN bodega bo ON pe.idpedido=bo.pedido
            INNER JOIN usuario us on pe.usuario=us.idusuario
            INNER JOIN estado es ON bo.estado=es.id_estado
            WHERE bo.estado<=2 and finfecha>='$cuatrodias'");
            
            $result=mysqli_num_rows($query);

            if ($result>0){
                while($data=mysqli_fetch_array($query)){

                    
                    $unds=$data['unds'];
                    $parcial=$data['parcial'];
                    $falta=$unds-$parcial;
                    
                    $diapedido=$data['finpedido'];
                    $diabodega=$data['finbodega'];
                    $diafaltapedido=  number_of_working_days($hoy, $diapedido)-1;
                    if($diafaltapedido<0){
                        $diafaltapedido=  -(number_of_working_days($diapedido, $hoy)-1);
                    }
                    $diafaltabodega=  number_of_working_days($hoy, $diabodega)-1;   
                    if($diafaltabodega<0){
                        $diafaltabodega=  -(number_of_working_days($diabodega, $hoy)-1);
                    }
                    echo "
                    <tr>
                    <td style=\"background-color: #00ff1588;\">".$data['num_pedido']."</td>
                    <td style=\"background-color: #00ff1588;\">".$data['cliente']."</td>
                    <td style=\"background-color: #00ff1588;\">".$data['asesor']."</td>
                    <td style=\"background-color: #00ff1588;\">".$data['iniciopedido']."</td>
                    <td style=\"background-color: #00ff1588;\">".$data['finpedido']."</td>
                    <td style=\"background-color: #00ff1588;\">".$data['diaspedido']."</td>";
                    if($diafaltapedido>3){
                       echo "<td style=\"background-color: #00ff1588;\">".$diafaltapedido."</td>";
                    }elseif($diafaltapedido>=0){
                        echo "<td style=\"background-color: #fbff0088;\">".$diafaltapedido."</td>";  
                    }else{
                        echo "<td style=\"background-color: #ff000088;\">".$diafaltapedido."</td>"; 
                    }
                    
                   echo " <td style=\"background-color: #00ff1588;\">".$data['siglas']."</td>
                    <td style=\"border-right: 1px solid #00a8a8; background-color: #00ff1588;\">".$unds."</td>
                   
                    <td style=\"background-color: #00ff1588;\">".$data['iniciobodega']."</td>
                    <td style=\"background-color: #00ff1588;\">".$data['finbodega']."</td>
                    <td style=\"background-color: #00ff1588;\">".$data['diasbodega']."</td>";
                    if($diafaltabodega>3){
                        echo "<td style=\"background-color: #00ff1588;\">".$diafaltabodega."</td>";
                     }elseif($diafaltabodega>=0){
                         echo "<td style=\"background-color: #fbff0088;\">".$diafaltabodega."</td>";  
                     }else{
                         echo "<td style=\"background-color: #ff000088;\">".$diafaltabodega."</td>"; 
                     }
                    echo "<td style=\"background-color: #00ff1588;\">".$parcial."</td>
                    <td style=\"background-color: #00ff1588;\">".$falta."</td>
                    <td style=\"background-color: #00ff1588;\"> ".$data['obs_bodega']."</td>
                    <td style=\"background-color: #00ff1588;\">".$data['estado']."</td>
                    </tr>                    ";
                }
            }
            ?>
         </tbody>   
          

        </table>
      
</div></center>




       

<!-- ************************************* -->

    <script>
         $('#tabla').DataTable({
            
        "order": [[ 12, "asc" ]],
        "pageLength": 50,
        "language": {
              "url": "https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
   })
    </script>
   
     
    
</body>
</html>