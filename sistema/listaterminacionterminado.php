<?php
session_start();

include "../conexion.php";


?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	
    <?php include "includes/scripts.php"?>
    
	<title>TERMINACIÓN</title>
	<link rel="shortcut icon" href="img/kamisetas-icono.png" type="image/x-icon">
	<style>
		
	</style>
</head>
<body>
<?php 
if (empty($_GET['id'])) {
    header('location listaterminaciongeneral.php');
}else{
    $idterminacion = $_GET['id'];
    $update=mysqli_query($conexion, "UPDATE terminacion SET estado=1 WHERE idterminacion=$idterminacion");

    if($update){
        $alert = '<p class="msg_save">Pedido Restaurado Correctamente</p>';
    }else{
        $alert = '<p class="msg_error">No se pudo resturar el pedido Correctamente</p>';
    }
}

if (empty($_SESSION['active'])){
  header('location: ../');
}
include "includes/header.php"?>
<section id="container">

<a href="listaterminaciongeneral.php" class="btn_new" style="position:fixed ; top:200px; left: 0px;">Lista Terminación</a>


<center><div style="width:100%">

<h1>Lista de pedidos Terminados y Anulados para TERMINACIÓN</h1>
<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
        
       
        <table id="tabla" class="display" >
         <thead>   
            <tr class="titulo">
                <th style="border-right: 1px solid #9ecaca"colspan="10">Información Pedido</th>
                
                <th colspan="11"> Información terminación</th>
            </tr>   
             <tr class="titulo">
                <th>Pedido</th>
                <th>Cliente</th>
                <th>Asesor</th>
                <th>Fecha Inicio  </th>
                <th>Fecha Entrega</th>
                <th>Días Hab</th>
                <th>Días Falta</th>
                <th>Proc</th>
                <th>Estado Pedido</th>
                <th style="border-right: 1px solid #9ecaca">Unds</th>
                
                <th>Fecha Inicio  </th>
                <th>Fecha Entrega</th>
                <th>Días Hab</th>
                <th>Días Falta</th>
                <th>Unds Parcial</th>
                <th>Unds Falta</th>
                <th>Tiempo Total (min)</th>
                <th>Tiempo Total (hrs)</th>
                <th>Observaciones</th>
                <th>Estado</th>
                <th>Acciones</th>
               

            </tr>
        </thead>
        <tbody>
            <?php
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

            $query=mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
            pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
            ter.idterminacion, ter.iniciofecha as 'inicioterminacion', ter.finfecha as 'finterminacion', ter.dias as 'diasterminacion',
            ter.inicioprocesofecha, ter.finprocesofecha, ter.parcial, us.usuario, ter.obs_terminacion, pr.siglas, es.estado, est.estado as 'estadopedido', ter.tiempo
            FROM pedidos pe 
            INNER JOIN procesos pr ON pe.procesos=pr.idproceso
            INNER JOIN terminacion ter ON pe.idpedido=ter.pedido
            INNER JOIN usuario us on pe.usuario=us.idusuario
            INNER JOIN estado es ON ter.estado=es.id_estado
            INNER JOIN estado est ON pe.estado=est.id_estado
            WHERE ter.estado>2");
            
            $result=mysqli_num_rows($query);

            if ($result>0){
                while($data=mysqli_fetch_array($query)){

                    
                    $unds=$data['unds'];
                    $parcial=$data['parcial'];
                    $falta=$unds-$parcial;
                    $hoy=date('Y-m-d');
                    $diapedido=$data['finpedido'];
                    $diaterminacion=$data['finterminacion'];
                    $estadopedido=$data['estadopedido'];
                    $tiempo=$data['tiempo'];
                    $total_tiempo=round($tiempo*$falta,2);
                    $diafaltapedido=  number_of_working_days($hoy, $diapedido)-1;
                    if($diafaltapedido<0){
                        $diafaltapedido=  -(number_of_working_days($diapedido, $hoy)-1);
                        
                    }
                    
                    $diafaltaterminacion=  number_of_working_days($hoy, $diaterminacion)-1;   
                    if($diafaltaterminacion<0){
                        $diafaltaterminacion=  -(number_of_working_days($diaterminacion, $hoy)-1);
                    }
                    echo "
                    <tr>
                    <td>".$data['num_pedido']."</td>
                    <td>".$data['cliente']."</td>
                    <td>".$data['asesor']."</td>
                    <td>".$data['iniciopedido']."</td>
                    <td>".$data['finpedido']."</td>
                    <td>".$data['diaspedido']."</td>";
                    if($diafaltapedido>3){
                       echo "<td class=\"greentable\">".$diafaltapedido."</td>";
                    }elseif($diafaltapedido>=0){
                        echo "<td class=\"yellowtable\">".$diafaltapedido."</td>";  
                    }else{
                        echo "<td class=\"redtable\">".$diafaltapedido."</td>"; 
                    }
                    
                   echo " <td>".$data['siglas']."</td>
                    <td>".$estadopedido."</td>
                    <td style=\"border-right: 1px solid #00a8a8\">".$unds."</td>
                   
                    <td>".$data['inicioterminacion']."</td>
                    <td>".$data['finterminacion']."</td>
                    <td>".$data['diasterminacion']."</td>";
                    if($diafaltaterminacion>3){
                        echo "<td class=\"greentable\">".$diafaltaterminacion."</td>";
                     }elseif($diafaltaterminacion>=0){
                         echo "<td class=\"yellowtable\">".$diafaltaterminacion."</td>";  
                     }else{
                         echo "<td class=\"redtable\">".$diafaltaterminacion."</td>"; 
                     }
                    echo "<td>".$parcial."</td>
                    <td>".$falta."</td>
                    <td>".$total_tiempo."</td>
                    <td>".round($total_tiempo/60,2)."</td>
                    <td>".$data['obs_terminacion']."</td>
                    <td>".$data['estado']."</td>
                    <td><div>
                    <a title=\"Restaurar Pedido\"class=\"link_edit\"href=\"listaterminacionterminado.php?id=".$data['idterminacion']."\"><span class=\"glyphicon glyphicon-share\" aria-hidden=\"true\"></span></a>
                    
                    
                    </div>
                    </td>                   
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
            
        "order": [[ 13, "asc" ]],
        "pageLength": 50,
        "language": {
              "url": "https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
   })
    </script>
   
     
    
</body>
</html>