<?php
session_start();

include "../conexion.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	
    <?php include "includes/scripts.php"?>
    
	<title>PEDIDOS</title>
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

<a href="ingresopedidos.php" class="btn_new" style="position:fixed ; top:150px; left: 0px;">Ingresar Pedidos</a>


<center><div style="width:85%">

<h1>Lista de Pedidos Pendientes</h1>
        
       
        <table id="tabla" class="display" >
         <thead>   
            <tr class="titulo">
                <th style="border-right: 1px solid #9ecaca"colspan="12">Información Pedido</th>
                
                
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
                <th >Unds</th>
                <th>Usuario</th>
                <th>Editar</th>
               

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

            $query=mysqli_query($conexion, "SELECT pe.idpedido, pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
            pe.fecha_fin as 'finpedido', pe.dias_habiles, pro.siglas, pe.unds, us.nombre as 'usuario', est.estado FROM pedidos pe 
            INNER JOIN procesos pro 
            ON pe.procesos=pro.idproceso
            INNER JOIN usuario us 
            ON pe.usuario=us.idusuario
            INNER JOIN estado est 
            ON pe.estado=est.id_estado
            WHERE pe.estado<=2");
            
            $result=mysqli_num_rows($query);

            if ($result>0){
                while($data=mysqli_fetch_array($query)){

                    $idpedido=$data['idpedido'];
                    $unds=$data['unds'];
                    $hoy=date('Y-m-d');
                    $diapedido=$data['finpedido'];
                    $estadopedido=$data['estado'];
                    $diafaltapedido=  number_of_working_days($hoy, $diapedido)-1;
                    if($diafaltapedido<0){
                        $diafaltapedido=  -(number_of_working_days($diapedido, $hoy)-1);
                        
                    }
                    
                    echo "
                    <tr>
                    <td>".$data['num_pedido']."</td>
                    <td>".$data['cliente']."</td>
                    <td>".$data['asesor']."</td>
                    <td>".$data['iniciopedido']."</td>
                    <td>".$data['finpedido']."</td>
                    <td>".$data['dias_habiles']."</td>";
                    if($diafaltapedido>3){
                       echo "<td class=\"greentable\">".$diafaltapedido."</td>";
                    }elseif($diafaltapedido>=0){
                        echo "<td class=\"yellowtable\">".$diafaltapedido."</td>";  
                    }else{
                        echo "<td class=\"redtable\">".$diafaltapedido."</td>"; 
                    }
                    
                   echo " <td>".$data['siglas']."</td>
                    <td>".$estadopedido."</td>
                    <td >".$unds."</td>
                   
                    <td >".$data['usuario']."</td>
                    <td><div>";
                    if($data['usuario']==$_SESSION['nombre'] || $_SESSION['idrol']==1){
                        echo "<a title=\"Editar\"class=\"link_edit\"href=\"editar_pedido.php?id=".$idpedido."\"><span class=\"glyphicon glyphicon-edit\" aria-hidden=\"true\"></span></a> &nbsp&nbsp&nbsp
                        <a title=\"Modificar proceso\"class=\"link_edit\"href=\"editar_proceso.php?id=".$idpedido."\"><span class=\"glyphicon glyphicon-share\" aria-hidden=\"true\"></span></a>";
                    }
                    
                    
                    echo "
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
            
        "order": [[ 6, "asc" ]],
        "pageLength": 50,
        "language": {
              "url": "https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
   });
    </script>
   
     
    
</body>
</html>