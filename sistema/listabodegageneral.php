<?php
session_start();

include "../conexion.php";

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
include "includes/header.php"?>
<section id="container">

<a href="reporte_bodega.php" class="btn_new" style="position:fixed ; top:150px; left: 0px;">Reporte</a>


<center><div style="width:100%">

<h1>Lista General de Pedidos para BODEGA</h1>
        
       
        <table id="tabla" class="display" >
         <thead>   
            <tr class="titulo">
                <th style="border-right: 1px solid #9ecaca"colspan="10">Información Pedido</th>
                
                <th colspan="9"> Información Bodega</th>
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
            bo.idbodega, bo.iniciofecha as 'iniciobodega', bo.finfecha as 'finbodega', bo.dias as 'diasbodega',
            bo.inicioprocesofecha, bo.finprocesofecha, bo.parcial, us.usuario, bo.obs_bodega, pr.siglas, es.estado, est.estado as 'estadopedido'
            FROM pedidos pe 
            INNER JOIN procesos pr ON pe.procesos=pr.idproceso
            INNER JOIN bodega bo ON pe.idpedido=bo.pedido
            INNER JOIN usuario us on pe.usuario=us.idusuario
            INNER JOIN estado es ON bo.estado=es.id_estado
            INNER JOIN estado est ON pe.estado=est.id_estado
            WHERE bo.estado<=2");
            
            $result=mysqli_num_rows($query);

            if ($result>0){
                while($data=mysqli_fetch_array($query)){

                    
                    $unds=$data['unds'];
                    $parcial=$data['parcial'];
                    $falta=$unds-$parcial;
                    $hoy=date('Y-m-d');
                    $diapedido=$data['finpedido'];
                    $diabodega=$data['finbodega'];
                    $estadopedido=$data['estadopedido'];
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
                   
                    <td>".$data['iniciobodega']."</td>
                    <td>".$data['finbodega']."</td>
                    <td>".$data['diasbodega']."</td>";
                    if($diafaltabodega>3){
                        echo "<td class=\"greentable\">".$diafaltabodega."</td>";
                     }elseif($diafaltabodega>=0){
                         echo "<td class=\"yellowtable\">".$diafaltabodega."</td>";  
                     }else{
                         echo "<td class=\"redtable\">".$diafaltabodega."</td>"; 
                     }
                    echo "<td>".$parcial."</td>
                    <td>".$falta."</td>
                    <td>".$data['obs_bodega']."</td>
                    <td>".$data['estado']."</td>
                    <td><div>
                    <a title=\"Editar\"class=\"link_edit\"href=\"editar_bodega.php?id=".$data['idbodega']."\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span></a>
                    <a title=\"Finalizar\"class=\"link_edit\"href=\"finalizar_bodega.php?id=".$data['idbodega']."\"><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span></a>
                    <a title=\"Anular\"class=\"link_delete\"href=\"anular_bodega.php?id=".$data['idbodega']."\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span></a>
                    
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