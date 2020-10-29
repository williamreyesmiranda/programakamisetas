<?php 
date_default_timezone_set('America/Bogota');
header("Pragma: public");
header("Expires: 0");
$filename = "Reporte_Bordado ".date('Y-m-d H-i-s').".xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");



include "../../conexion.php";

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
	
    
    
	<title>CONFECCIÓN</title>
    <link rel="shortcut icon" href="img/kamisetas-icono.png" type="image/x-icon">
    <style>
        .redtable {
    background-color: rgb(245, 180, 173);
    padding: 0px;
}

.yellowtable {
    background-color: rgb(247, 255, 171);
    padding: 0px;
}

.greentable {
    background-color: rgb(173, 253, 181);
    padding: 0px;
}
.titulo {
    padding: 0px;
    text-align: center;
    background-color: #00a8a8;
    color: #9ecaca;
    font-size: 20px;
    border-top: 1px solid #9ecaca3d;
    text-transform: uppercase;
}

.titulo th {
    text-align: center;
}
    </style>
</head>
<body>

<center>
        <div style="width:99%">

            <h1 style="font-size:50px; font-weight:bold; color: #00a8a8">REPORTE DE BORDADO <?php echo date('d-m-Y'); ?>
            </h1>
            <table style="width:50% !important; ">
                <thead>
                    <tr>
                        <th class="titulo"></th>
                        <th class="titulo">Pedidos</th>
                        <th class="titulo">Unds Listas</th>
                        <th class="titulo">Unds Falta</th>
                        <th class="titulo">Unds Total</th>
                        <th class="titulo">Prod</th>
                        <th class="titulo">Total Bord</th>
                        <th class="titulo">Total Ptdas</th>
                        <th class="titulo">Total Hrs</th>
                        <th class="titulo">Total Turnos</th>
                    </tr>
                </thead>
                <tbody>
                    <th class="titulo">Fechas Vencidas</th>

                    <td><?php $query = mysqli_query($conexion, "SELECT * FROM bordado WHERE finfecha<'$hoy' AND estado<=2");
                        echo mysqli_num_rows($query) ?></td>
                    <td><?php $query = mysqli_query($conexion, "SELECT SUM(parcial) as 'suma' FROM bordado WHERE finfecha<'$hoy' AND estado<=2");
                        $result = mysqli_fetch_array($query);
                        echo $result['suma'] ?></td>
                    <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds-bor.parcial) as 'resta' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2 AND bor.finfecha<'$hoy'");
                        $result_falta = mysqli_fetch_array($query);
                        echo $falta = $result_falta['resta'] ?></td>
                    <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds) as 'suma' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2 AND bor.finfecha<'$hoy'");
                        $result = mysqli_fetch_array($query);
                        echo $unds = $result['suma'] ?></td>
                    <td><?php $query_prod = mysqli_query($conexion, "SELECT pedido FROM bordado WHERE estado<=2 AND finfecha<'$hoy'");
                        $condicion = mysqli_num_rows($query_prod);
                        $producto = 0;
                        if ($condicion > 0) {
                            while ($fetch_bod = mysqli_fetch_array($query_prod)) {
                                $pedido = $fetch_bod['pedido'];
                                $query_bode = mysqli_query($conexion, "SELECT pedido FROM bodega WHERE pedido=$pedido AND entrega<>''");
                                $resp_bode = mysqli_num_rows($query_bode);
                                if ($resp_bode > 0) {
                                    $producto++;
                                }
                                $query_confe = mysqli_query($conexion, "SELECT pedido FROM confeccion WHERE pedido=$pedido AND entrega<>''");
                                $resp_confe = mysqli_num_rows($query_confe);
                                if ($resp_confe > 0) {
                                    $producto++;
                                }
                            }
                        }

                        echo $producto;

                        ?></td>
                       <!--  consulta para hacer el while del num bordado y puntadas -->
                    <?php $query = mysqli_query($conexion, "SELECT bor.parcial, bor.num_bordado, bor.punt_unidad, pe.unds FROM bordado bor     INNER JOIN pedidos pe ON pe.idpedido=bor.pedido
                                     WHERE bor.finfecha<'$hoy' AND bor.estado<=2");
                                     $totalbordado=0;?>
                    <td><?php 
                        while($result = mysqli_fetch_array($query)){
                            $falta=$result['unds']-$result['parcial'];
                           $num_bordado=$result['num_bordado'];
                            $totalbordado=$totalbordado+($falta*$num_bordado);
                            
                        }
                        echo $totalbordado; ?></td>
                    <td><?php 
                    $query = mysqli_query($conexion, "SELECT bor.parcial, bor.num_bordado, bor.punt_unidad, pe.unds FROM bordado bor     INNER JOIN pedidos pe ON pe.idpedido=bor.pedido
                    WHERE bor.finfecha<'$hoy' AND bor.estado<=2");
                    $totalpuntadas=0;
                    while($result = mysqli_fetch_array($query)){
                        $falta=$result['unds']-$result['parcial'];
                        $punt_unidad=$result['punt_unidad'];
                        $totalpuntadas=$totalpuntadas+($falta*$punt_unidad);
                    }
                    echo $totalpuntadas;?></td>
                    <td><?php $totalhoras=round($totalpuntadas/66600,2);
                    echo $totalhoras;  ?></td>
                    <td><?php  echo round($totalhoras/8.25,2);?></td>

                    </tr>
                    <tr>

                        <th class="titulo">0 a 3 días</th>
                        <td><?php $query = mysqli_query($conexion, "SELECT * FROM bordado WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias' ");
                            echo mysqli_num_rows($query) ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(parcial) as 'suma' FROM bordado WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma'] ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds-bor.parcial) as 'resta' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2 AND bor.finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $result_falta = mysqli_fetch_array($query);
                            echo $falta = $result_falta['resta'] ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds) as 'suma' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2 AND bor.finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $result = mysqli_fetch_array($query);
                            echo $unds = $result['suma'] ?></td>
                        <td><?php $query_prod = mysqli_query($conexion, "SELECT pedido FROM bordado WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $condicion = mysqli_num_rows($query_prod);
                            $producto = 0;
                            if ($condicion > 0) {
                                while ($fetch_bod = mysqli_fetch_array($query_prod)) {
                                    @$pedido = $fetch_bod['pedido'];
                                    $query_bode = mysqli_query($conexion, "SELECT pedido FROM bodega WHERE pedido=$pedido AND entrega<>''");
                                    $resp_bode = mysqli_num_rows($query_bode);
                                    if ($resp_bode > 0) {
                                        $producto++;
                                    }
                                    $query_confe = mysqli_query($conexion, "SELECT pedido FROM confeccion WHERE pedido=$pedido AND entrega<>''");
                                    $resp_confe = mysqli_num_rows($query_confe);
                                    if ($resp_confe > 0) {
                                        $producto++;
                                    }
                                }
                            }

                            echo $producto;

                            ?></td>
                        <?php $query = mysqli_query($conexion, "SELECT bor.parcial, bor.num_bordado, bor.punt_unidad, pe.unds FROM bordado bor     INNER JOIN pedidos pe ON pe.idpedido=bor.pedido
                                     WHERE bor.estado<=2 AND bor.finfecha BETWEEN'$hoy' AND '$tresdias'");
                                     $totalbordado=0;      ?>
                    <td><?php 
                        while($result = mysqli_fetch_array($query)){
                            $falta=$result['unds']-$result['parcial'];
                            $num_bordado=$result['num_bordado'];
                            $totalbordado=$totalbordado+($falta*$num_bordado);
                            
                        }
                        echo $totalbordado; ?></td>
                    <td><?php 
                    $query = mysqli_query($conexion, "SELECT bor.parcial, bor.num_bordado, bor.punt_unidad, pe.unds FROM bordado bor     INNER JOIN pedidos pe ON pe.idpedido=bor.pedido
                    WHERE bor.estado<=2 AND bor.finfecha BETWEEN'$hoy' AND '$tresdias'");
                    $totalpuntadas=0;
                    while($result = mysqli_fetch_array($query)){
                        $falta=$result['unds']-$result['parcial'];
                        $punt_unidad=$result['punt_unidad'];
                        $totalpuntadas=$totalpuntadas+($falta*$punt_unidad);
                    }
                    echo $totalpuntadas;?></td>
                    <td><?php $totalhoras=round($totalpuntadas/66600,2);
                    echo $totalhoras;  ?></td>
                    <td><?php  echo round($totalhoras/8.25,2);?></td>


                    </tr>
                    <tr>
                        <th class="titulo">> 3 días </th>


                        <td><?php $query = mysqli_query($conexion, "SELECT * FROM bordado WHERE finfecha>='$cuatrodias' AND estado<=2");
                            echo mysqli_num_rows($query) ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(parcial) as 'suma' FROM bordado WHERE finfecha>='$cuatrodias' AND estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma'] ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds-bor.parcial) as 'resta' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2 AND bor.finfecha>='$cuatrodias'");
                            $result_falta = mysqli_fetch_array($query);
                            echo $result_falta['resta'] ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds) as 'suma' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2 AND bor.finfecha>='$cuatrodias'");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma']; ?></td>
                        <td><?php $query_prod = mysqli_query($conexion, "SELECT pedido FROM bordado WHERE estado<=2 AND finfecha>='$cuatrodias'");
                            $condicion = mysqli_num_rows($query_prod);
                            $producto = 0;
                            if ($condicion > 0) {
                                while ($fetch_bod = mysqli_fetch_array($query_prod)) {
                                    $pedido = $fetch_bod['pedido'];
                                    $query_bode = mysqli_query($conexion, "SELECT pedido FROM bodega WHERE pedido=$pedido AND entrega<>''");
                                    $resp_bode = mysqli_num_rows($query_bode);
                                    if ($resp_bode > 0) {
                                        $producto++;
                                    }
                                    $query_confe = mysqli_query($conexion, "SELECT pedido FROM confeccion WHERE pedido=$pedido AND entrega<>''");
                                    $resp_confe = mysqli_num_rows($query_confe);
                                    if ($resp_confe > 0) {
                                        $producto++;
                                    }
                                }
                            }

                            echo $producto;

                            ?></td>
                        <?php $query = mysqli_query($conexion, "SELECT bor.parcial, bor.num_bordado, bor.punt_unidad, pe.unds FROM bordado bor     INNER JOIN pedidos pe ON pe.idpedido=bor.pedido
                                     WHERE bor.estado<=2 AND bor.finfecha>='$cuatrodias'");
                                     $totalbordado=0;      ?>
                    <td><?php 
                        while($result = mysqli_fetch_array($query)){
                            $falta=$result['unds']-$result['parcial'];
                            $num_bordado=$result['num_bordado'];
                            $totalbordado=$totalbordado+($falta*$num_bordado);
                            
                        }
                        echo $totalbordado; ?></td>
                    <td><?php 
                    $query = mysqli_query($conexion, "SELECT bor.parcial, bor.num_bordado, bor.punt_unidad, pe.unds FROM bordado bor     INNER JOIN pedidos pe ON pe.idpedido=bor.pedido
                    WHERE bor.estado<=2 AND bor.finfecha>='$cuatrodias'");
                    $totalpuntadas=0;
                    while($result = mysqli_fetch_array($query)){
                        $falta=$result['unds']-$result['parcial'];
                        $punt_unidad=$result['punt_unidad'];
                        $totalpuntadas=$totalpuntadas+($falta*$punt_unidad);
                    }
                    echo $totalpuntadas;?></td>
                    <td><?php $totalhoras=round($totalpuntadas/66600,2);
                    echo $totalhoras;  ?></td>
                    <td><?php  echo round($totalhoras/8.25,2);?></td>
                    </tr>
                    <tr>
                        <th class="titulo">Total</th>
                        <td><?php $query = mysqli_query($conexion, "SELECT * FROM bordado WHERE  estado<=2");
                            echo mysqli_num_rows($query) ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(parcial) as 'suma' FROM bordado WHERE  estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma'] ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds-bor.parcial) as 'resta' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2");
                            $result_falta = mysqli_fetch_array($query);
                            echo $result_falta['resta'] ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds) as 'suma' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma'] ?></td>
                        <td><?php $query_prod = mysqli_query($conexion, "SELECT pedido FROM bordado WHERE estado<=2");
                            $condicion = mysqli_num_rows($query_prod);
                            $producto = 0;
                            if ($condicion > 0) {
                                while ($fetch_bod = mysqli_fetch_array($query_prod)) {
                                    $pedido = $fetch_bod['pedido'];
                                    $query_bode = mysqli_query($conexion, "SELECT pedido FROM bodega WHERE pedido=$pedido AND entrega<>''");
                                    $resp_bode = mysqli_num_rows($query_bode);
                                    if ($resp_bode > 0) {
                                        $producto++;
                                    }
                                    $query_confe = mysqli_query($conexion, "SELECT pedido FROM confeccion WHERE pedido=$pedido AND entrega<>''");
                                    $resp_confe = mysqli_num_rows($query_confe);
                                    if ($resp_confe > 0) {
                                        $producto++;
                                    }
                                }
                            }

                            echo $producto . "/" . $condicion;

                            ?></td>
                        <?php $query = mysqli_query($conexion, "SELECT bor.parcial, bor.num_bordado, bor.punt_unidad, pe.unds FROM bordado bor     INNER JOIN pedidos pe ON pe.idpedido=bor.pedido
                                     WHERE bor.estado<=2");
                                     $totalbordado=0;      ?>
                    <td><?php 
                        while($result = mysqli_fetch_array($query)){
                            $falta=$result['unds']-$result['parcial'];
                            $num_bordado=$result['num_bordado'];
                            $totalbordado=$totalbordado+($falta*$num_bordado);
                            
                        }
                        echo $totalbordado; ?></td>
                    <td><?php 
                    $query = mysqli_query($conexion, "SELECT bor.parcial, bor.num_bordado, bor.punt_unidad, pe.unds FROM bordado bor     INNER JOIN pedidos pe ON pe.idpedido=bor.pedido
                    WHERE bor.estado<=2");
                    $totalpuntadas=0;
                    while($result = mysqli_fetch_array($query)){
                        $falta=$result['unds']-$result['parcial'];
                        $punt_unidad=$result['punt_unidad'];
                        $totalpuntadas=$totalpuntadas+($falta*$punt_unidad);
                    }
                    echo $totalpuntadas;?></td>
                    <td><?php $totalhoras=round($totalpuntadas/66600,2);
                    echo $totalhoras;  ?></td>
                    <td><?php  echo round($totalhoras/8.25,2);?></td>

                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <div style="width:99%">
            <table id="tabla">
                <thead>

                    <tr class="titulo">
                        <th style="border-right: 1px solid #9ecaca" colspan="9">Información Pedido</th>
                        <th colspan="17"> Información bordado</th>
                    </tr>
                    <tr class="titulo">
                    <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Asesor</th>
                            <th>Fecha Inicio </th>
                            <th>Fecha Entrega</th>
                            <th>Días Hab</th>
                            <th>Días Falta</th>                            
                            <th>Proc</th>
                            <th style="border-right: 1px solid #9ecaca">Unds</th>

                            <th>Fecha Inicio </th>
                            <th>Fecha Entrega</th>
                            <th>Días Hab</th>
                            <th>Días Falta</th>
                            <th>Producto</th>
                            <th>Logo</th>
                            <th>Pendiente diseño</th>
                            <th>Numero bordado</th>
                            <th>Muestra</th>
                            <th>Total bordados</th>
                            <th>Puntadas x unidad</th>
                            <th>Total puntadas</th>
                            <th>Horas estimadas</th>
                            <th>Unds Parcial</th>
                            <th>Unds Falta</th>
                            <th>Observaciones</th>
                            <th>Estado</th>



                    </tr>
                    <tr>
                        <th class="titulo" colspan="26">FECHAS VENCIDAS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php


$query = mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
bor.idbordado, bor.iniciofecha as 'iniciobordado', bor.finfecha as 'finbordado', bor.dias as 'diasbordado',
bor.inicioprocesofecha, bor.finprocesofecha, bor.parcial, us.usuario, bor.obs_bordado, pr.siglas, es.estado, est.estado as 
'estadopedido', bor.logo, bor.pte_diseno, bor.num_bordado, bor.muestra, bor.punt_unidad, bor.pedido
FROM pedidos pe 
INNER JOIN procesos pr ON pe.procesos=pr.idproceso
INNER JOIN bordado bor ON pe.idpedido=bor.pedido
INNER JOIN usuario us on pe.usuario=us.idusuario
INNER JOIN estado es ON bor.estado=es.id_estado
INNER JOIN estado est ON pe.estado=est.id_estado
WHERE bor.finfecha<'$hoy' AND bor.estado<=2");

            $result = mysqli_num_rows($query);

            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    $nro_pedido = $data['num_pedido'];
                    $pedido = $data['pedido'];
                    $prod_confe = mysqli_query($conexion, "SELECT * FROM confeccion WHERE pedido=$pedido");
                    $consult_confec = mysqli_fetch_array($prod_confe);
                    $prod_confec = $consult_confec['entrega'];
                    
                    $prod_bod = mysqli_query($conexion, "SELECT * FROM bodega WHERE pedido=$pedido");
                    $consult_bod = mysqli_fetch_array($prod_bod);
                    $prod_bode = $consult_bod['entrega'];

                    $unds = $data['unds'];
                    $parcial = $data['parcial'];
                    $falta = $unds - $parcial;
                    $hoy = date('Y-m-d');
                    $diapedido = $data['finpedido'];
                    $diabordado = $data['finbordado'];
                    $estadopedido = $data['estadopedido'];
                    $logo=$data['logo'];
                    $pte_diseno=$data['pte_diseno'];
                    $num_bordado=$data['num_bordado'];
                    $muestra=$data['muestra'];
                    $punt_unidad=$data['punt_unidad'];
                    $total_bordados=$falta * $num_bordado;
                    $total_puntadas=$falta * $punt_unidad;
                    $horas_estimadas=round($total_puntadas/66600,2);


                    $diafaltapedido =  number_of_working_days($hoy, $diapedido) - 1;
                    if ($diafaltapedido < 0) {
                        $diafaltapedido =  - (number_of_working_days($diapedido, $hoy) - 1);
                    }

                    $diafaltabordado =  number_of_working_days($hoy, $diabordado) - 1;
                    if ($diafaltabordado < 0) {
                        $diafaltabordado =  - (number_of_working_days($diabordado, $hoy) - 1);
                    }
                    echo "
        <tr class=\"redtable\">
        <td>" . $data['num_pedido'] . "</td>
        <td>" . $data['cliente'] . "</td>
        <td>" . $data['asesor'] . "</td>
        <td>" . $data['iniciopedido'] . "</td>
        <td>" . $data['finpedido'] . "</td>
        <td>" . $data['diaspedido'] . "</td>";
                    if ($diafaltapedido > 3) {
                        echo "<td >" . $diafaltapedido . "</td>";
                    } elseif ($diafaltapedido >= 0) {
                        echo "<td >" . $diafaltapedido . "</td>";
                    } else {
                        echo "<td >" . $diafaltapedido . "</td>";
                    }

                    echo " <td>" . $data['siglas'] . "</td>
        <td style=\"border-right: 1px solid #00a8a8\">" . $unds . "</td>
       
        <td>" . $data['iniciobordado'] . "</td>
        <td>" . $data['finbordado'] . "</td>
        <td>" . $data['diasbordado'] . "</td>
        <td >" . $diafaltabordado . "</td>
                    <td>" . $prod_confec . $prod_bode . "</td>
                    <td>".$logo."</td>
                    <td>".$pte_diseno."</td>
                    <td>".$num_bordado."</td>
                    <td>".$muestra."</td>
                    <td>".$total_bordados."</td>
                    <td>".$punt_unidad."</td>                                
                    <td>".$total_puntadas."</td>
                    <td>".$horas_estimadas." </td>
        <td>" . $parcial . "</td>
        <td>" . $falta . "</td>
        <td>" . $data['obs_bordado'] . "</td>
        <td>" . $data['estado'] . "</td>
                    </tr>                    ";
                        }
                    }
                    ?>
                    <tr>
                        <th class="titulo" colspan="26">0 a 3 DÍAS</th>
                    </tr>

                    <?php


$query = mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
bor.idbordado, bor.iniciofecha as 'iniciobordado', bor.finfecha as 'finbordado', bor.dias as 'diasbordado',
bor.inicioprocesofecha, bor.finprocesofecha, bor.parcial, us.usuario, bor.obs_bordado, pr.siglas, es.estado, est.estado as 
'estadopedido', bor.logo, bor.pte_diseno, bor.num_bordado, bor.muestra, bor.punt_unidad, bor.pedido
FROM pedidos pe 
INNER JOIN procesos pr ON pe.procesos=pr.idproceso
INNER JOIN bordado bor ON pe.idpedido=bor.pedido
INNER JOIN usuario us on pe.usuario=us.idusuario
INNER JOIN estado es ON bor.estado=es.id_estado
INNER JOIN estado est ON pe.estado=est.id_estado
WHERE bor.estado<=2 AND bor.finfecha BETWEEN'$hoy' AND '$tresdias'");

            $result = mysqli_num_rows($query);

            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    $nro_pedido = $data['num_pedido'];
                    $pedido = $data['pedido'];
                    $prod_confe = mysqli_query($conexion, "SELECT * FROM confeccion WHERE pedido=$pedido");
                    $consult_confec = mysqli_fetch_array($prod_confe);
                    $prod_confec = $consult_confec['entrega'];
                    
                    $prod_bod = mysqli_query($conexion, "SELECT * FROM bodega WHERE pedido=$pedido");
                    $consult_bod = mysqli_fetch_array($prod_bod);
                    $prod_bode = $consult_bod['entrega'];

                    $unds = $data['unds'];
                    $parcial = $data['parcial'];
                    $falta = $unds - $parcial;
                    $hoy = date('Y-m-d');
                    $diapedido = $data['finpedido'];
                    $diabordado = $data['finbordado'];
                    $estadopedido = $data['estadopedido'];
                    $logo=$data['logo'];
                    $pte_diseno=$data['pte_diseno'];
                    $num_bordado=$data['num_bordado'];
                    $muestra=$data['muestra'];
                    $punt_unidad=$data['punt_unidad'];
                    $total_bordados=$falta * $num_bordado;
                    $total_puntadas=$falta * $punt_unidad;
                    $horas_estimadas=round($total_puntadas/66600,2);


                    $diafaltapedido =  number_of_working_days($hoy, $diapedido) - 1;
                    if ($diafaltapedido < 0) {
                        $diafaltapedido =  - (number_of_working_days($diapedido, $hoy) - 1);
                    }

                    $diafaltabordado =  number_of_working_days($hoy, $diabordado) - 1;
                    if ($diafaltabordado < 0) {
                        $diafaltabordado =  - (number_of_working_days($diabordado, $hoy) - 1);
                    }
                    echo "
        <tr class=\"yellowtable\">
        <td>" . $data['num_pedido'] . "</td>
        <td>" . $data['cliente'] . "</td>
        <td>" . $data['asesor'] . "</td>
        <td>" . $data['iniciopedido'] . "</td>
        <td>" . $data['finpedido'] . "</td>
        <td>" . $data['diaspedido'] . "</td>";
                    if ($diafaltapedido > 3) {
                        echo "<td >" . $diafaltapedido . "</td>";
                    } elseif ($diafaltapedido >= 0) {
                        echo "<td >" . $diafaltapedido . "</td>";
                    } else {
                        echo "<td >" . $diafaltapedido . "</td>";
                    }

                    echo " <td>" . $data['siglas'] . "</td>
        <td style=\"border-right: 1px solid #00a8a8\">" . $unds . "</td>
       
        <td>" . $data['iniciobordado'] . "</td>
        <td>" . $data['finbordado'] . "</td>
        <td>" . $data['diasbordado'] . "</td>
        <td >" . $diafaltabordado . "</td>
                    <td>" . $prod_confec . $prod_bode . "</td>
                    <td>".$logo."</td>
                    <td>".$pte_diseno."</td>
                    <td>".$num_bordado."</td>
                    <td>".$muestra."</td>
                    <td>".$total_bordados."</td>
                    <td>".$punt_unidad."</td>                                
                    <td>".$total_puntadas."</td>
                    <td>".$horas_estimadas." </td>
        <td>" . $parcial . "</td>
        <td>" . $falta . "</td>
        <td>" . $data['obs_bordado'] . "</td>
        <td>" . $data['estado'] . "</td>
                    </tr>                    ";
                        }
                    }
                    ?>
                    <tr>
                        <th class="titulo" colspan="26">4 DÍAS EN ADELANTE</th>
                    </tr>

                    <?php


$query = mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
bor.idbordado, bor.iniciofecha as 'iniciobordado', bor.finfecha as 'finbordado', bor.dias as 'diasbordado',
bor.inicioprocesofecha, bor.finprocesofecha, bor.parcial, us.usuario, bor.obs_bordado, pr.siglas, es.estado, est.estado as 
'estadopedido', bor.logo, bor.pte_diseno, bor.num_bordado, bor.muestra, bor.punt_unidad, bor.pedido
FROM pedidos pe 
INNER JOIN procesos pr ON pe.procesos=pr.idproceso
INNER JOIN bordado bor ON pe.idpedido=bor.pedido
INNER JOIN usuario us on pe.usuario=us.idusuario
INNER JOIN estado es ON bor.estado=es.id_estado
INNER JOIN estado est ON pe.estado=est.id_estado
WHERE bor.estado<=2 AND bor.finfecha>='$cuatrodias'");

            $result = mysqli_num_rows($query);

            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    $nro_pedido = $data['num_pedido'];
                    $pedido = $data['pedido'];
                    $prod_confe = mysqli_query($conexion, "SELECT * FROM confeccion WHERE pedido=$pedido");
                    $consult_confec = mysqli_fetch_array($prod_confe);
                    $prod_confec = $consult_confec['entrega'];
                    
                    $prod_bod = mysqli_query($conexion, "SELECT * FROM bodega WHERE pedido=$pedido");
                    $consult_bod = mysqli_fetch_array($prod_bod);
                    $prod_bode = $consult_bod['entrega'];

                    $unds = $data['unds'];
                    $parcial = $data['parcial'];
                    $falta = $unds - $parcial;
                    $hoy = date('Y-m-d');
                    $diapedido = $data['finpedido'];
                    $diabordado = $data['finbordado'];
                    $estadopedido = $data['estadopedido'];
                    $logo=$data['logo'];
                    $pte_diseno=$data['pte_diseno'];
                    $num_bordado=$data['num_bordado'];
                    $muestra=$data['muestra'];
                    $punt_unidad=$data['punt_unidad'];
                    $total_bordados=$falta * $num_bordado;
                    $total_puntadas=$falta * $punt_unidad;
                    $horas_estimadas=round($total_puntadas/66600,2);


                    $diafaltapedido =  number_of_working_days($hoy, $diapedido) - 1;
                    if ($diafaltapedido < 0) {
                        $diafaltapedido =  - (number_of_working_days($diapedido, $hoy) - 1);
                    }

                    $diafaltabordado =  number_of_working_days($hoy, $diabordado) - 1;
                    if ($diafaltabordado < 0) {
                        $diafaltabordado =  - (number_of_working_days($diabordado, $hoy) - 1);
                    }
                    echo "
        <tr class=\"greentable\">
        <td>" . $data['num_pedido'] . "</td>
        <td>" . $data['cliente'] . "</td>
        <td>" . $data['asesor'] . "</td>
        <td>" . $data['iniciopedido'] . "</td>
        <td>" . $data['finpedido'] . "</td>
        <td>" . $data['diaspedido'] . "</td>";
                    if ($diafaltapedido > 3) {
                        echo "<td >" . $diafaltapedido . "</td>";
                    } elseif ($diafaltapedido >= 0) {
                        echo "<td >" . $diafaltapedido . "</td>";
                    } else {
                        echo "<td >" . $diafaltapedido . "</td>";
                    }

                    echo " <td>" . $data['siglas'] . "</td>
        <td style=\"border-right: 1px solid #00a8a8\">" . $unds . "</td>
       
        <td>" . $data['iniciobordado'] . "</td>
        <td>" . $data['finbordado'] . "</td>
        <td>" . $data['diasbordado'] . "</td>
        <td >" . $diafaltabordado . "</td>
                    <td>" . $prod_confec . $prod_bode . "</td>
                    <td>".$logo."</td>
                    <td>".$pte_diseno."</td>
                    <td>".$num_bordado."</td>
                    <td>".$muestra."</td>
                    <td>".$total_bordados."</td>
                    <td>".$punt_unidad."</td>                                
                    <td>".$total_puntadas."</td>
                    <td>".$horas_estimadas." </td>
        <td>" . $parcial . "</td>
        <td>" . $falta . "</td>
        <td>" . $data['obs_bordado'] . "</td>
        <td>" . $data['estado'] . "</td>
                    </tr>                    ";
                        }
                    }
                    ?>
                </tbody>


            </table>

        </div>
    </center>




       

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