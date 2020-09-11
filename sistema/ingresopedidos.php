<?php
session_start();

include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        
        if(empty($_POST['nropedido']) || empty($_POST['nombrecliente']) || empty($_POST['asesor'])
        || empty($_POST['fechainicio'])  || empty($_POST['fechafin']) || empty($_POST['procesos']) || empty($_POST['unds'])){
            $alert ='<p class="msg_error">Todos los campos son obligatorios</p>';
            
        }else{
            


            $nropedido= $_POST['nropedido'];
            $nombre = $_POST['nombrecliente'];
            $asesor= $_POST['asesor'];
            $fechainicio=$_POST['fechainicio'];
            $fechafin= $_POST['fechafin'];
            $procesos= $_POST['procesos'];
            $usuario_id=$_SESSION['iduser'];
            $unds=$_POST['unds'];
                
            
            

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
           

            $diashabiles=  number_of_working_days($fechainicio, $fechafin);
            
         
                $query_insert = mysqli_query($conexion,"INSERT INTO pedidos(num_pedido,cliente, asesor, fecha_inicio, fecha_fin, dias_habiles, procesos, unds, usuario )
                                                            values('$nropedido','$nombre','$asesor','$fechainicio','$fechafin',$diashabiles,$procesos,$unds,$usuario_id)");
                $query_max = mysqli_query($conexion,"SELECT max(idpedido) as 'maxpedido' FROM pedidos");
                $data=mysqli_fetch_array($query_max);
    
                
                $maxpedido=$data['maxpedido'];
                if($query_insert){
                    $alert ='<label class="msg_save alert">Pedido Ingresado Correctamente</label>';
                }else{
                    $alert ='<label class="msg_error alert">Error al ingresar el Pedido</label>';
                }       
            
        }
        $idproceso=$_POST['procesos'] ;
        $query=mysqli_query($conexion,"SELECT * FROM procesos WHERE idproceso =$idproceso");
        $result=mysqli_fetch_array($query);
        $diaspedido=$diashabiles;
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
        
        #comparacion celda1
        $dato1=strtolower($result['1']);
        $tiempo1=$result['tiempo1'] ;
        $dias1= round($diaspedido*$tiempo1);
        $inicio1=sumasdiasemana($fechainicio,0);
        $final1= sumasdiasemana($inicio1,$dias1-1);
        if($dato1!=''){
            $insert_1=mysqli_query($conexion,"INSERT INTO $dato1(pedido, iniciofecha, finfecha, dias) VALUES ('$maxpedido','$inicio1','$final1', $dias1)");
            }
        
        #comparacion celda2
        $dato2=strtolower($result['2']);
        $tiempo2=$result['tiempo2'] ;
        $dias2= round($diaspedido*$tiempo2);
        $inicio2=sumasdiasemana($final1,1);
        $final2= sumasdiasemana($inicio2,$dias2-1);
        if($dato2!=''){
            $insert_2=mysqli_query($conexion,"INSERT INTO $dato2(pedido, iniciofecha, finfecha, dias) VALUES ('$maxpedido','$inicio2','$final2', $dias2)");
           }
       
        #comparacion celda3
        $dato3=strtolower($result['3']);
        $tiempo3=$result['tiempo3'] ;
        $dias3= round($diaspedido*$tiempo3);
        $inicio3=sumasdiasemana($final2,1);
        $final3= sumasdiasemana($inicio3,$dias3-1);
        if($dato3!=''){
            $insert_3=mysqli_query($conexion,"INSERT INTO $dato3(pedido, iniciofecha, finfecha, dias) VALUES ('$maxpedido','$inicio3','$final3', $dias3)");
           }
            
        #comparacion celda4
        $dato4=strtolower($result['4']);
        $tiempo4=$result['tiempo4'] ;
        $dias4= round($diaspedido*$tiempo4);
        $inicio4=sumasdiasemana($final3,1);
        $final4= sumasdiasemana($inicio4,$dias4-1);
        if($dato4!=''){
            $insert_4=mysqli_query($conexion,"INSERT INTO $dato4(pedido, iniciofecha, finfecha, dias) VALUES ('$maxpedido','$inicio4','$final4', $dias4)");
           }

        #comparacion celda5
        $dato5=strtolower($result['5']);
        $tiempo5=$result['tiempo5'] ;
        $dias5= round($diaspedido*$tiempo5);
        $inicio5=sumasdiasemana($final4,1);
        $final5= sumasdiasemana($inicio5,$dias5-1);
        if($dato5!=''){
            $insert_5=mysqli_query($conexion,"INSERT INTO $dato5(pedido, iniciofecha, finfecha, dias) VALUES ('$maxpedido','$inicio5','$final5', $dias5)");
           }

        #comparacion celda6
        $dato6=strtolower($result['6']);
        $tiempo6=$result['tiempo6'] ;
        $dias6= round($diaspedido*$tiempo6);
        $inicio6=sumasdiasemana($final5,1);
        $final6= sumasdiasemana($inicio6,$dias6-1);
        if($dato6!=''){
            $insert_6=mysqli_query($conexion,"INSERT INTO $dato6(pedido, iniciofecha, finfecha, dias) VALUES ('$maxpedido','$inicio6','$final6', $dias6)");
           }
   
        
    
    }#FIN DEL POST
?>



<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"?>
    <title>REGISTRO PEDIDOS</title>
    <link rel="shortcut icon" href="img/kamisetas-icono.png" type="image/x-icon">
	<style>
		
	</style>
</head>
<body>

<?php 

if (empty($_SESSION['active'])){
  header('location: ../');
}
 include "includes/header.php" ;
?>



<section id="container">
<div class="form-register">
        
        

        <form action="" method="post" id="formulario_pedido">
       <h1>Registro de Pedidos</h1>
        <center>(*) campos requeridos</center> 
        <?php echo isset($alert) ? $alert : '';?>
         <hr>
        
       
        <div>
            <label for="nropedido">Nro Pedido (*)</label>
            <input value="" type="text" name="nropedido" id="nropedido" placeholder="Ingrese N° Pedido" autocomplete="off" autofocus required>
        </div>
        <div>
            <label for="nombrecliente">Nombre Cliente(*)</label>
            <input value=""type="text" name="nombrecliente" id="nombrecliente" placeholder="Ingrese el Nombre del Cliente" autocomplete="on" required>
        </div>
        <div>
            <label for="asesor">Asesor(*)</label>
            <input value=""type="text" name="asesor" id="asesor" placeholder="Ingrese el Nombre del Comercial" autocomplete="on" required>
        </div>
        
        <div>
            <label for="fechainicio">Fecha Inicio(*)</label>
            <input type="date" name="fechainicio" id="fechainicio" required>
        </div>
        <div>
            <label for="fechafin">Fecha Entrega(*)</label>
            <input type="date" name="fechafin" id="fechafin" required>
        </div>
        <div>
            <label for="diashabiles">Días hábiles(Automático)</label>
            
            <div id="diashabiles" style="font-size:30px" ></div>
        </div>
        <div>
            <label for="unds">Unidades(*)</label>
            <input type="text" name="unds" id="unds" autocomplete="off" required>
        </div>
        <div>
            <label for="procesos">Procesos Implicados(*)</label>
            <?php
            $query_procesos =mysqli_query($conexion,"SELECT * FROM procesos");
            $result_procesos= mysqli_num_rows($query_procesos);
            
            ?>
          <select name="procesos" id="procesos" class="select2" required>
          <option value="0" disabled selected>Seleccione una Opción</option> 
            <?php 
            if ($result_procesos>0){
                while($procesos =mysqli_fetch_array($query_procesos)){
                    
                  echo "<option value=\"".$procesos['idproceso']."\">".$procesos['siglas']."</option>";
            }
            } ?>
          
          
          </select>
          
        </div>
        <div id="diasprocesos"></div>

        
        <input type="submit" value="Ingresar Pedido" class="btn-save">

        </form>


    </div>


</section>

<script type="text/javascript">
	$(document).ready(function(){
		$('#procesos').val();
		
		

		$('#procesos').change(function(){
			diasproceso();
			
			
		});
	
	})
	function diasproceso(){
		$.ajax({
			type:"POST",
			url:"php/cargar_diasproceso.php",
            data: $("#formulario_pedido").serialize(),
            
			success:function(r){
				$('#diasprocesos').html(r);
				
			}
		});
		
	}
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#fechafin').val();
		
		

		$('#fechafin').change(function(){
			fechafin();
			
			
		});
	
	})
	function fechafin(){
		$.ajax({
			type:"POST",
			url:"php/cargar_diashabiles.php",
            data: $("#formulario_pedido").serialize(),
            
			success:function(r){
                $('#diashabiles').html(r);
                
				
			}
		});
		
	}
</script>
    
<script>
$('.select2').select2({
    containerCssClass: "wrap"
});
</script>
</body>
</html>