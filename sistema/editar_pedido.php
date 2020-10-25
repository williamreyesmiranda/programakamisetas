<?php
session_start();

include "../conexion.php";
if (empty($_GET['id'])) {
    header('location lista_pedidos.php');
}
$idpedido = $_GET['id'];

$sql = mysqli_query($conexion, "SELECT pe.idpedido, pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
pe.fecha_fin as 'finpedido', pe.dias_habiles, pro.siglas, pro.idproceso,pe.unds, us.nombre as 'usuario', est.estado FROM pedidos pe 
INNER JOIN procesos pro 
ON pe.procesos=pro.idproceso
INNER JOIN usuario us 
ON pe.usuario=us.idusuario
INNER JOIN estado est 
ON pe.estado=est.id_estado WHERE pe.idpedido=$idpedido");

$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('location lista:pedidos.php');
} else {
    $data = mysqli_fetch_array($sql);
    $id = $data['idpedido'];
    $asesor = $data['asesor'];
    $pedido = $data['num_pedido'];
    $cliente = $data['cliente'];
    $iniciopedido = $data['iniciopedido'];
    $finpedido = $data['finpedido'];
    $idproceso=$data['idproceso'];
    $siglas = $data['siglas'];
    $unds = $data['unds'];
    $dias = $data['dias_habiles'];
    
}
    if(!empty($_POST)){
        $alert='';
        
        if(empty($_POST['nropedido']) || empty($_POST['nombrecliente']) 
        || empty($_POST['asesor'])|| empty($_POST['fechainicio'])  || empty($_POST['fechafin']) || empty($_POST['procesos']) || empty($_POST['unds'])){
            $alert ='<p class="msg_error">Todos los campos son obligatorios</p>';
            
        }else{
            

            $idpedido=$_POST['idpedido'];
            $nropedido= $_POST['nropedido'];
            $nombre = $_POST['nombrecliente'];
            $fechainicio=$_POST['fechainicio'];
            $fechafin= $_POST['fechafin'];
            $procesos= $_POST['procesos'];
            $asesor=$_POST['asesor'];
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
            $diashabiles=  number_of_working_days($fechainicio, $fechafin);
            
                
                $query_insert = mysqli_query($conexion,"UPDATE pedidos SET num_pedido='$nropedido',cliente='$nombre', 
                fecha_inicio='$fechainicio', fecha_fin='$fechafin', dias_habiles='$diashabiles',  unds=$unds, 
                asesor='$asesor', usuario='$usuario_id', actualizacion=current_timestamp() WHERE idpedido='$idpedido'");

                if($query_insert){
                    $alert .='<label class="msg_save alert">Pedido Actualizado Correctamente</label>';
                }else{
                    $alert .='<label class="msg_error alert">Error al actualizar el Pedido</label>';
                }  
                $query=mysqli_query($conexion,"SELECT * FROM procesos WHERE idproceso =$procesos");
                $result=mysqli_fetch_array($query);
                $diaspedido=number_of_working_days($fechainicio, $fechafin);
        
        #comparacion celda1
        $dato1=($result['1']);
        $tiempo1=$result['tiempo1'] ;
        $dias1= round($diaspedido*$tiempo1);
        $inicio1=sumasdiasemana($fechainicio,0);
        $final1= sumasdiasemana($inicio1,$dias1-1);
        $insert_1=mysqli_query($conexion,"UPDATE $dato1 SET iniciofecha='$inicio1', finfecha='$final1', dias=$dias1 WHERE pedido=$idpedido");
        if($insert_1){
            $alert .="<label class=\"msg_save alert\">".$dato1." actualizado</label>";
            
        }else{
            $alert .="<label class=\"msg_error alert\">Error al actualizar en ".$dato1."</label>";
        } 
        
        #comparacion celda2
        $dato2=($result['2']);
        $tiempo2=$result['tiempo2'] ;
        $dias2= round($diaspedido*$tiempo2);
        $inicio2=sumasdiasemana($final1,1);
        $final2= sumasdiasemana($inicio2,$dias2-1);
        if($dato2!=''){
            $insert_2=mysqli_query($conexion,"UPDATE $dato2 SET iniciofecha='$inicio2', finfecha='$final2', dias=$dias2 WHERE pedido=$idpedido");
        if($insert_2){
            $alert .="<label class=\"msg_save alert\">".$dato2." actualizado</label>";
            
        }else{
            $alert .="<label class=\"msg_error alert\">Error al actualizar en ".$dato2."</label>";
        } 
           }
       
        #comparacion celda3
        $dato3=($result['3']);
        $tiempo3=$result['tiempo3'] ;
        $dias3= round($diaspedido*$tiempo3);
        $inicio3=sumasdiasemana($final2,1);
        $final3= sumasdiasemana($inicio3,$dias3-1);
        if($dato3!=''){
            $insert_3=mysqli_query($conexion,"UPDATE $dato3 SET iniciofecha='$inicio3', finfecha='$final3', dias=$dias3 WHERE pedido=$idpedido");
        if($insert_3){
            $alert .="<label class=\"msg_save alert\">".$dato3." actualizado</label>";
            
        }else{
            $alert .="<label class=\"msg_error alert\">Error al actualizar en ".$dato3."</label>";
        } 
        }
            
        #comparacion celda4
        $dato4=($result['4']);
        $tiempo4=$result['tiempo4'] ;
        $dias4= round($diaspedido*$tiempo4);
        $inicio4=sumasdiasemana($final3,1);
        $final4= sumasdiasemana($inicio4,$dias4-1);
        if($dato4!=''){
            $insert_4=mysqli_query($conexion,"UPDATE $dato4 SET iniciofecha='$inicio4', finfecha='$final4', dias=$dias4 WHERE pedido=$idpedido");
        if($insert_4){
            $alert .="<label class=\"msg_save alert\">".$dato4." actualizado</label>";
            
        }else{
            $alert .="<label class=\"msg_error alert\">Error al actualizar en ".$dato4."</label>";
        } 
        }

        #comparacion celda5
        $dato5=($result['5']);
        $tiempo5=$result['tiempo5'] ;
        $dias5= round($diaspedido*$tiempo5);
        $inicio5=sumasdiasemana($final4,1);
        $final5= sumasdiasemana($inicio5,$dias5-1);
        if($dato5!=''){
            $insert_5=mysqli_query($conexion,"UPDATE $dato5 SET iniciofecha='$inicio5', finfecha='$final5', dias=$dias5 WHERE pedido=$idpedido");
        if($insert_5){
            $alert .="<label class=\"msg_save alert\">".$dato5." actualizado</label>";
            
        }else{
            $alert .="<label class=\"msg_error alert\">Error al actualizar en ".$dato5."</label>";
        } 
        }

        #comparacion celda6
        $dato6=($result['6']);
        $tiempo6=$result['tiempo6'] ;
        $dias6= round($diaspedido*$tiempo6);
        $inicio6=sumasdiasemana($final5,1);
        $final6= sumasdiasemana($inicio6,$dias6-1);
        if($dato6!=''){
            $insert_6=mysqli_query($conexion,"UPDATE $dato6 SET iniciofecha='$inicio6', finfecha='$final6', dias=$dias6 WHERE pedido=$idpedido");
            if($insert_6){
                $alert .="<label class=\"msg_save alert\">".$dato6." actualizado</label>";
                
            }else{
                $alert .="<label class=\"msg_error alert\">Error al actualizar en ".$dato6."</label>";
            } 
        }

        }
        
        
    
    }#FIN DEL POST

    
        ?>




<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"?>
    <title>ACTUALIACIÓN</title>
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
<a href="lista_pedidos.php" class="btn_new" style="position:fixed ; top:150px; left: 0px;">Lista Pedidos</a>
<div class="form-register">
        
        

        <form action="" method="post" id="formulario_pedido">
       <h1>Actualización de Pedido</h1>
        <center>(*) campos requeridos</center> 
        <?php echo isset($alert) ? $alert : '';?>
         <hr>
        
       
        <div>
            <label for="nropedido">Nro Pedido (*)</label>
            <input type="text" name="nropedido" id="nropedido" value="<?php echo $pedido?>">
        </div>
        <div class="regcliente">
            <input type="hidden" name="idpedido" id="idpedido" value="<?php echo $idpedido?>">
           
           <?php
           $query_idcliente =mysqli_query($conexion,"SELECT * FROM clientes order by nombre asc");
           $result_idcliente = mysqli_num_rows($query_idcliente);
           
           ?>
           <label>Nombre Cliente (*)   
         <input list="nombrecliente" name="nombrecliente" class="nombrecliente" value="<?php echo $cliente?>"></label>
         <datalist name="nombrecliente"id="nombrecliente">
             
           <?php 
           if ($result_idcliente>0){
               while($idcliente =mysqli_fetch_array($query_idcliente)){
                   
                 echo "  <option value=\"".$idcliente['nombre']."\">";
           }
           } ?>
         
         </datalist>
         
       </div>
        <div >
            <label for="asesor">Asesor (*)</label>
            <input type="text" name="asesor" id="asesor" value="<?php echo $asesor?>">
        </div>
        
        <div>
            <label for="fechainicio">Fecha Inicio(*)</label>
            <input type="date" name="fechainicio" id="fechainicio" value="<?php echo $iniciopedido?>" required>
        </div>
        <div>
            <label for="fechafin">Fecha Entrega(*)</label>
            <input type="date" name="fechafin" id="fechafin" value="<?php echo $finpedido?>"required>
        </div>
        <div>
            <label for="diashabiles">Días hábiles(Automático)</label>
            
            <div id="diashabiles" style="font-size:30px" ><?php echo $dias?></div>
        </div>
        <div>
            <label for="unds">Unidades(*)</label>
            <input type="text" name="unds" id="unds" autocomplete="off" value="<?php echo $unds?>"required>
        </div>
        <div>
            <label for="procesos">Procesos Implicados( Desactivado)</label>
            <?php
            $query_procesos =mysqli_query($conexion,"SELECT * FROM procesos");
            $result_procesos= mysqli_num_rows($query_procesos);
            
            ?>
          <select name="procesos" id="procesos" class="select2" required>
          <?php echo "  <option value=\"" . $idproceso . "\">" . $siglas . "</option>"; ?>
          
          
          
          </select>
          
        </div>
        <div id="diasprocesos"></div>

        
        <input type="submit" value="Ingresar Pedido" class="btn-save">

        </form>


    </div>


</section>


<script type="text/javascript">
	$(document).ready(function(){
		$('#fechafin').val();
		
		

		$('#fechafin').change(function(){
            fechafin();
            diasproceso();
			document.getElementById("fecha").value = "";
            
            
            
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
		$('#fechainicio').val();
		
		

		$('#fechainicio').change(function(){
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