<?php
session_start();

include "../conexion.php";
date_default_timezone_set('America/Bogota');
//fechas a dias
function number_of_working_days($from, $to)
{
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
function sumasdiasemana($fecha, $dias)
{
    $datestart = strtotime($fecha);
    $datesuma = 15 * 86400;
    $diasemana = date('N', $datestart);
    $totaldias = $diasemana + $dias;
    $findesemana = intval($totaldias / 5) * 2;
    $diasabado = $totaldias % 5;
    if ($diasabado == 6) $findesemana++;
    if ($diasabado == 0) $findesemana = $findesemana - 2;

    $total = (($dias + $findesemana) * 86400) + $datestart;
    return $fechafinal = date('Y-m-d', $total);
}
$hoy = date('Y-m-d');
$tresdias = sumasdiasemana($hoy, 3);
$cuatrodias = sumasdiasemana($hoy, 4);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <?php include "includes/scripts.php" ?>

    <title>ESTAMPACIÓN</title>
    <link rel="shortcut icon" href="img/kamisetas-icono.png" type="image/x-icon">
    <style>

    </style>
</head>

<body>
    <?php

    if (empty($_SESSION['active'])) {
        header('location: ../');
    }
    ?>
    <!-- <img id="logo" src="../img/kamisetas.png" alt="" style="display:block; width:200px; position:fixed ; top:90px; left:0px;"> -->

    <a href="listaestampaciongeneral.php" class="btn_new" style="position:fixed ; top:0px; left: 0px;"><input style="display:block; width:150px; position:fixed ; top:0px; left: 0%;;" class="btn_new" type='button' href="listaestampaciongeneral.php" value='MENÚ' /></a>

    <input style="display:block; width:150px; position:fixed ; top:0px; left: 85%;;" class="btn_new" type='button' onclick='window.print();' value='Imprimir' />


    <center>
        <div style="width:99%">

            <h1 style="font-size:50px; font-weight:bold; color: #00a8a8">REPORTE DE ESTAMPACIÓN <?php echo date('d-m-Y'); ?></h1>
            <table style="width:40% !important; ">
                <thead>
                    <tr>
                        <th class="titulo"></th>
                        <th class="titulo">Pedidos</th>
                        <th class="titulo">Unds Listas</th>
                        <th class="titulo">Unds Falta</th>
                        <th class="titulo">Unds Total</th>
                        <th class="titulo">Prod</th>
                        <th class="titulo">Prep (hrs)</th>
                        <th class="titulo">Est (hrs)</th>
                        <th class="titulo">Sub (hrs)</th>
                        <th class="titulo">Total Prod (hrs)</th>
                    </tr>
                </thead>
                <tbody>
                    <th class="titulo">Fechas Vencidas</th>

                    <td><?php $query = mysqli_query($conexion, "SELECT * FROM estampacion WHERE finfecha<'$hoy' AND estado<=2");
                        echo mysqli_num_rows($query) ?></td>
                    <td><?php $query = mysqli_query($conexion, "SELECT SUM(parcial) as 'suma' FROM estampacion WHERE finfecha<'$hoy' AND estado<=2");
                        $result = mysqli_fetch_array($query);
                        echo $result['suma'] ?></td>
                    <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds-estam.parcial) as 'resta' FROM estampacion estam INNER JOIN pedidos pe ON estam.pedido=pe.idpedido
                                                            WHERE estam.estado<=2 AND estam.finfecha<'$hoy'");
                        $result_falta = mysqli_fetch_array($query);
                        echo $falta=$result_falta['resta'] ?></td>
                    <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds) as 'suma' FROM estampacion estam INNER JOIN pedidos pe ON estam.pedido=pe.idpedido
                                                            WHERE estam.estado<=2 AND estam.finfecha<'$hoy'");
                        $result = mysqli_fetch_array($query);
                        echo $unds=$result['suma'] ?></td>
                    <td><?php $query_prod = mysqli_query($conexion, "SELECT pedido FROM estampacion WHERE estado<=2 AND finfecha<'$hoy'");
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
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(prep) as 'prep' FROM estampacion WHERE finfecha<'$hoy' AND estado<=2");
                        $result = mysqli_fetch_array($query);
                        echo round($result['prep']/60,2) ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(est) as 'est' FROM estampacion WHERE finfecha<'$hoy' AND estado<=2");
                        $result = mysqli_fetch_array($query);
                        echo round($result['est']/60,2) ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(sub) as 'sub' FROM estampacion WHERE finfecha<'$hoy' AND estado<=2");
                        $result = mysqli_fetch_array($query);
                        echo round($result['sub']/60,2) ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(sub+prep+est) as 'total' FROM estampacion WHERE finfecha<'$hoy' AND estado<=2");
                        $result = mysqli_fetch_array($query);
                        echo round($result['total']/60,2) ?></td>
                    
                    </tr>
                    <tr>

                        <th class="titulo">0 a 3 días</th>
                        <td><?php $query = mysqli_query($conexion, "SELECT * FROM estampacion WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias' ");
                            echo mysqli_num_rows($query) ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(parcial) as 'suma' FROM estampacion WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma'] ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds-estam.parcial) as 'resta' FROM estampacion estam INNER JOIN pedidos pe ON estam.pedido=pe.idpedido
                                                            WHERE estam.estado<=2 AND estam.finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $result_falta = mysqli_fetch_array($query);
                            echo $falta=$result_falta['resta'] ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds) as 'suma' FROM estampacion estam INNER JOIN pedidos pe ON estam.pedido=pe.idpedido
                                                            WHERE estam.estado<=2 AND estam.finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $result = mysqli_fetch_array($query);
                            echo $unds=$result['suma'] ?></td>
                            <td><?php $query_prod = mysqli_query($conexion, "SELECT pedido FROM estampacion WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias'");
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
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(prep) as 'prep' FROM estampacion WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $result = mysqli_fetch_array($query);
                            echo round($result['prep']/60,2) ?></td>
                            <td><?php $query = mysqli_query($conexion, "SELECT SUM(est) as 'est' FROM estampacion WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $result = mysqli_fetch_array($query);
                            echo round($result['est']/60,2) ?></td>
                            <td><?php $query = mysqli_query($conexion, "SELECT SUM(sub) as 'sub' FROM estampacion WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $result = mysqli_fetch_array($query);
                            echo round($result['sub']/60,2) ?></td>
                            <td><?php $query = mysqli_query($conexion, "SELECT SUM(sub+prep+est) as 'total' FROM estampacion WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $result = mysqli_fetch_array($query);
                            echo round($result['total']/60,2) ?></td>


                    </tr>
                    <tr>
                        <th class="titulo">> 3 días </th>


                        <td><?php $query = mysqli_query($conexion, "SELECT * FROM estampacion WHERE finfecha>='$cuatrodias' AND estado<=2");
                            echo mysqli_num_rows($query) ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(parcial) as 'suma' FROM estampacion WHERE finfecha>='$cuatrodias' AND estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma'] ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds-estam.parcial) as 'resta' FROM estampacion estam INNER JOIN pedidos pe ON estam.pedido=pe.idpedido
                                                            WHERE estam.estado<=2 AND estam.finfecha>='$cuatrodias'");
                            $result_falta = mysqli_fetch_array($query);
                            echo $result_falta['resta'] ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds) as 'suma' FROM estampacion estam INNER JOIN pedidos pe ON estam.pedido=pe.idpedido
                                                            WHERE estam.estado<=2 AND estam.finfecha>='$cuatrodias'");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma']; ?></td>
                            <td><?php $query_prod = mysqli_query($conexion, "SELECT pedido FROM estampacion WHERE estado<=2 AND finfecha>='$cuatrodias'");
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
                    <td><?php $query = mysqli_query($conexion, "SELECT SUM(prep) as 'prep' FROM estampacion WHERE finfecha>='$cuatrodias' AND estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo round($result['prep']/60,2) ?></td>
                            <td><?php $query = mysqli_query($conexion, "SELECT SUM(est) as 'est' FROM estampacion WHERE finfecha>='$cuatrodias' AND estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo round($result['est']/60,2) ?></td>
                            <td><?php $query = mysqli_query($conexion, "SELECT SUM(sub) as 'sub' FROM estampacion WHERE finfecha>='$cuatrodias' AND estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo round($result['sub']/60,2) ?></td>
                            <td><?php $query = mysqli_query($conexion, "SELECT SUM(sub+prep+est) as 'total' FROM estampacion WHERE finfecha>='$cuatrodias' AND estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo round($result['total']/60,2) ?></td>
                    </tr>
                    <tr>
                        <th class="titulo">Total</th>
                        <td><?php $query = mysqli_query($conexion, "SELECT * FROM estampacion WHERE  estado<=2");
                            echo mysqli_num_rows($query) ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(parcial) as 'suma' FROM estampacion WHERE  estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma'] ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds-estam.parcial) as 'resta' FROM estampacion estam INNER JOIN pedidos pe ON estam.pedido=pe.idpedido
                                                            WHERE estam.estado<=2");
                            $result_falta = mysqli_fetch_array($query);
                            echo $result_falta['resta'] ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds) as 'suma' FROM estampacion estam INNER JOIN pedidos pe ON estam.pedido=pe.idpedido
                                                            WHERE estam.estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma'] ?></td>
                            <td><?php $query_prod = mysqli_query($conexion, "SELECT pedido FROM estampacion WHERE estado<=2");
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

                        echo $producto."/".$condicion;

                        ?></td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(prep) as 'prep' FROM estampacion WHERE estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo round($result['prep']/60,2) ?></td>
                            <td><?php $query = mysqli_query($conexion, "SELECT SUM(est) as 'est' FROM estampacion WHERE estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo round($result['est']/60,2) ?></td>
                            <td><?php $query = mysqli_query($conexion, "SELECT SUM(sub) as 'sub' FROM estampacion WHERE estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo round($result['sub']/60,2) ?></td>
                            <td><?php $query = mysqli_query($conexion, "SELECT SUM(sub+prep+est) as 'total' FROM estampacion WHERE estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo round($result['total']/60,2) ?></td>
                            
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
                        <th colspan="22"> Información estampacion</th>
                    </tr>
                    <tr class="titulo">
                        <th>Ped</th>
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
                        <th>Prod</th>
                        <th>Técn</th>
                        <th> N° Dis</th>
                        <th>Pos</th>
                        <th>Seda</th>
                        <th>Grab</th>
                        <th>N° Plan</th>
                        <th>Fte</th>
                        <th>Esp</th>
                        <th>Otr</th>
                        <th>Prepa (min)</th>
                        <th>Estam (min)</th>
                        <th>Subl (min)</th>
                        <th>Tiempo (horas)</th>
                        <th>Unds Parcial</th>
                        <th>Unds Falta</th>
                        <th>Obs</th>
                        <th>Est</th>



                    </tr>
                    <tr>
                        <th class="titulo" colspan="31">FECHAS VENCIDAS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php


                    $query = mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
                    pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
                    estam.idestampacion, estam.iniciofecha as 'inicioestampacion', estam.finfecha as 'finestampacion', estam.dias as 'diasestampacion',
                    estam.inicioprocesofecha, estam.finprocesofecha, estam.parcial, us.usuario, estam.obs_estampacion, pr.siglas, es.estado, 
                    estam.tecnica, estam.nro_diseno, estam.posicion, estam.seda, estam.grabacion, estam.nro_plancha, estam.fren, estam.esp, estam.otro, 
                    estam.prep, estam.est, estam.sub, estam.pedido
            FROM pedidos pe 
            INNER JOIN procesos pr ON pe.procesos=pr.idproceso
            INNER JOIN estampacion estam ON pe.idpedido=estam.pedido
            INNER JOIN usuario us on pe.usuario=us.idusuario
            INNER JOIN estado es ON estam.estado=es.id_estado
            
            WHERE estam.estado<=2 and finfecha<'$hoy'");

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
                            $prod_bod = $consult_bod['entrega'];
                            $unds = $data['unds'];
                            $parcial = $data['parcial'];
                            $falta = $unds - $parcial;
                            $diapedido = $data['finpedido'];
                            $diaestampacion = $data['finestampacion'];
                            $tecnica = $data['tecnica'];
                            $nro_diseno = $data['nro_diseno'];
                            $posicion = $data['posicion'];
                            $seda = $data['seda'];
                            $grabacion = $data['grabacion'];
                            $nro_plancha = $data['nro_plancha'];
                            $fren = $data['fren'];
                            $esp = $data['esp'];
                            $otro = $data['otro'];
                            $prep = $data['prep'];
                            $est = $data['est'];
                            $sub = $data['sub'];
                            $diafaltapedido =  number_of_working_days($hoy, $diapedido) - 1;
                            if ($diafaltapedido < 0) {
                                $diafaltapedido =  - (number_of_working_days($diapedido, $hoy) - 1);
                            }
                            $diafaltaestampacion =  number_of_working_days($hoy, $diaestampacion) - 1;
                            if ($diafaltaestampacion < 0) {
                                $diafaltaestampacion =  - (number_of_working_days($diaestampacion, $hoy) - 1);
                            }
                            echo "
                    <tr>
                    <td class=\"redtable\">" . $data['num_pedido'] . "</td>
                    <td class=\"redtable\">" . $data['cliente'] . "</td>
                    <td class=\"redtable\">" . $data['asesor'] . "</td>
                    <td class=\"redtable\">" . $data['iniciopedido'] . "</td>
                    <td class=\"redtable\">" . $data['finpedido'] . "</td>
                    <td class=\"redtable\">" . $data['diaspedido'] . "</td>";
                            if ($diafaltapedido > 3) {
                                echo "<td class=\"greentable\">" . $diafaltapedido . "</td>";
                            } elseif ($diafaltapedido >= 0) {
                                echo "<td class=\"yellowtable\">" . $diafaltapedido . "</td>";
                            } else {
                                echo "<td class=\"redtable\">" . $diafaltapedido . "</td>";
                            }

                            echo " <td class=\"redtable\">" . $data['siglas'] . "</td>
                    <td class=\"redtable\" style=\"border-right: 1px solid #00a8a8\">" . $unds . "</td>
                   
                    <td class=\"redtable\">" . $data['inicioestampacion'] . "</td>
                    <td class=\"redtable\">" . $data['finestampacion'] . "</td>
                    <td class=\"redtable\">" . $data['diasestampacion'] . "</td>";
                            if ($diafaltaestampacion > 3) {
                                echo "<td class=\"greentable\">" . $diafaltaestampacion . "</td>";
                            } elseif ($diafaltaestampacion >= 0) {
                                echo "<td class=\"yellowtable\">" . $diafaltaestampacion . "</td>";
                            } else {
                                echo "<td class=\"redtable\">" . $diafaltaestampacion . "</td>";
                            }
                            echo "
                            <td class=\"redtable\">" . $prod_confec . $prod_bod . "</td>
                    <td class=\"redtable\">" . $tecnica . "</td>
                    <td class=\"redtable\">" . $nro_diseno . "</td>
                    <td class=\"redtable\">" . $posicion . "</td>
                    <td class=\"redtable\">" . $seda . "</td>
                    <td class=\"redtable\">" . $grabacion . "</td>
                    <td class=\"redtable\">" . $nro_plancha . "</td>
                    <td class=\"redtable\">" . $fren . "</td>
                    <td class=\"redtable\">" . $esp . "</td>
                    <td class=\"redtable\">" . $otro . "</td>
                    <td class=\"redtable\">" . $prep . "</td>
                    <td class=\"redtable\">" . $est . "</td>
                    <td class=\"redtable\">" . $sub . "</td>
                    <td class=\"redtable\">" . round(($prep + $est + $sub) * $falta / $unds / 60, 2) . "</td></td>
                    <td class=\"redtable\">" . $parcial . "</td>
                    <td class=\"redtable\">" . $falta . "</td>
                    <td class=\"redtable\">" . $data['obs_estampacion'] . "</td>
                    <td class=\"redtable\">" . $data['estado'] . "</td>
                    </tr>                    ";
                        }
                    }
                    ?>
                    <tr>
                        <th class="titulo" colspan="31">0 a 3 DÍAS</th>
                    </tr>

                    <?php


                    $query = mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
            pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
            estam.idestampacion, estam.iniciofecha as 'inicioestampacion', estam.finfecha as 'finestampacion', estam.dias as 'diasestampacion',
            estam.inicioprocesofecha, estam.finprocesofecha, estam.parcial, us.usuario, estam.obs_estampacion, pr.siglas, es.estado, 
            estam.tecnica, estam.nro_diseno, estam.posicion, estam.seda, estam.grabacion, estam.nro_plancha, estam.fren, estam.esp, estam.otro, 
            estam.prep, estam.est, estam.sub, estam.pedido
            FROM pedidos pe 
            INNER JOIN procesos pr ON pe.procesos=pr.idproceso
            INNER JOIN estampacion estam ON pe.idpedido=estam.pedido
            INNER JOIN usuario us on pe.usuario=us.idusuario
            INNER JOIN estado es ON estam.estado=es.id_estado
            WHERE estam.estado<=2 and finfecha BETWEEN'$hoy' AND '$tresdias'");

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
                            $prod_bod = $consult_bod['entrega'];
                            $unds = $data['unds'];
                            $parcial = $data['parcial'];
                            $falta = $unds - $parcial;
                            $diapedido = $data['finpedido'];
                            $diaestampacion = $data['finestampacion'];
                            $tecnica = $data['tecnica'];
                            $nro_diseno = $data['nro_diseno'];
                            $posicion = $data['posicion'];
                            $seda = $data['seda'];
                            $grabacion = $data['grabacion'];
                            $nro_plancha = $data['nro_plancha'];
                            $fren = $data['fren'];
                            $esp = $data['esp'];
                            $otro = $data['otro'];
                            $prep = $data['prep'];
                            $est = $data['est'];
                            $sub = $data['sub'];
                            $diafaltapedido =  number_of_working_days($hoy, $diapedido) - 1;
                            if ($diafaltapedido < 0) {
                                $diafaltapedido =  - (number_of_working_days($diapedido, $hoy) - 1);
                            }
                            $diafaltaestampacion =  number_of_working_days($hoy, $diaestampacion) - 1;
                            if ($diafaltaestampacion < 0) {
                                $diafaltaestampacion =  - (number_of_working_days($diaestampacion, $hoy) - 1);
                            }
                            echo "
                    <tr>
                    <td class=\"yellowtable\">" . $data['num_pedido'] . "</td>
                    <td class=\"yellowtable\">" . $data['cliente'] . "</td>
                    <td class=\"yellowtable\">" . $data['asesor'] . "</td>
                    <td class=\"yellowtable\">" . $data['iniciopedido'] . "</td>
                    <td class=\"yellowtable\">" . $data['finpedido'] . "</td>
                    <td class=\"yellowtable\">" . $data['diaspedido'] . "</td>";
                            if ($diafaltapedido > 3) {
                                echo "<td class=\"greentable\">" . $diafaltapedido . "</td>";
                            } elseif ($diafaltapedido >= 0) {
                                echo "<td class=\"yellowtable\">" . $diafaltapedido . "</td>";
                            } else {
                                echo "<td class=\"redtable\">" . $diafaltapedido . "</td>";
                            }

                            echo " <td class=\"yellowtable\">" . $data['siglas'] . "</td>
                    <td class=\"yellowtable\" style=\"border-right: 1px solid #00a8a8\">" . $unds . "</td>
                   
                    <td class=\"yellowtable\">" . $data['inicioestampacion'] . "</td>
                    <td class=\"yellowtable\">" . $data['finestampacion'] . "</td>
                    <td class=\"yellowtable\">" . $data['diasestampacion'] . "</td>";
                            if ($diafaltaestampacion > 3) {
                                echo "<td class=\"greentable\">" . $diafaltaestampacion . "</td>";
                            } elseif ($diafaltaestampacion >= 0) {
                                echo "<td class=\"yellowtable\">" . $diafaltaestampacion . "</td>";
                            } else {
                                echo "<td class=\"redtable\">" . $diafaltaestampacion . "</td>";
                            }
                            echo "
                            <td class=\"yellowtable\">" . $prod_confec . $prod_bod . "</td>
                    <td class=\"yellowtable\">" . $tecnica . "</td>
                    <td class=\"yellowtable\">" . $nro_diseno . "</td>
                    <td class=\"yellowtable\">" . $posicion . "</td>
                    <td class=\"yellowtable\">" . $seda . "</td>
                    <td class=\"yellowtable\">" . $grabacion . "</td>
                    <td class=\"yellowtable\">" . $nro_plancha . "</td>
                    <td class=\"yellowtable\">" . $fren . "</td>
                    <td class=\"yellowtable\">" . $esp . "</td>
                    <td class=\"yellowtable\">" . $otro . "</td>
                    <td class=\"yellowtable\">" . $prep . "</td>
                    <td class=\"yellowtable\">" . $est . "</td>
                    <td class=\"yellowtable\">" . $sub . "</td>
                    <td class=\"yellowtable\">" . round(($prep + $est + $sub) * $falta / $unds / 60, 2) . "</td></td>
                    <td class=\"yellowtable\">" . $parcial . "</td>
                    <td class=\"yellowtable\">" . $falta . "</td>
                    <td class=\"yellowtable\">" . $data['obs_estampacion'] . "</td>
                    <td class=\"yellowtable\">" . $data['estado'] . "</td>
                    </tr>                    ";
                        }
                    }
                    ?>
                    <tr>
                        <th class="titulo" colspan="31">4 DÍAS EN ADELANTE</th>
                    </tr>

                    <?php


                    $query = mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
            pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
            estam.idestampacion, estam.iniciofecha as 'inicioestampacion', estam.finfecha as 'finestampacion', estam.dias as 'diasestampacion',
            estam.inicioprocesofecha, estam.finprocesofecha, estam.parcial, us.usuario, estam.obs_estampacion, pr.siglas, es.estado,
            estam.tecnica, estam.nro_diseno, estam.posicion, estam.seda, estam.grabacion, estam.nro_plancha, estam.fren, estam.esp, estam.otro, 
            estam.prep, estam.est, estam.sub, estam.pedido
            FROM pedidos pe 
            INNER JOIN procesos pr ON pe.procesos=pr.idproceso
            INNER JOIN estampacion estam ON pe.idpedido=estam.pedido
            INNER JOIN usuario us on pe.usuario=us.idusuario
            INNER JOIN estado es ON estam.estado=es.id_estado
            WHERE estam.estado<=2 and finfecha>='$cuatrodias'");

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
                            $prod_bod = $consult_bod['entrega'];
                            $unds = $data['unds'];
                            $parcial = $data['parcial'];
                            $falta = $unds - $parcial;
                            $diapedido = $data['finpedido'];
                            $diaestampacion = $data['finestampacion'];
                            $tecnica = $data['tecnica'];
                            $nro_diseno = $data['nro_diseno'];
                            $posicion = $data['posicion'];
                            $seda = $data['seda'];
                            $grabacion = $data['grabacion'];
                            $nro_plancha = $data['nro_plancha'];
                            $fren = $data['fren'];
                            $esp = $data['esp'];
                            $otro = $data['otro'];
                            $prep = $data['prep'];
                            $est = $data['est'];
                            $sub = $data['sub'];
                            $diafaltapedido =  number_of_working_days($hoy, $diapedido) - 1;
                            if ($diafaltapedido < 0) {
                                $diafaltapedido =  - (number_of_working_days($diapedido, $hoy) - 1);
                            }
                            $diafaltaestampacion =  number_of_working_days($hoy, $diaestampacion) - 1;
                            if ($diafaltaestampacion < 0) {
                                $diafaltaestampacion =  - (number_of_working_days($diaestampacion, $hoy) - 1);
                            }
                            echo "
                    <tr>
                    <td class=\"greentable\">" . $data['num_pedido'] . "</td>
                    <td class=\"greentable\">" . $data['cliente'] . "</td>
                    <td class=\"greentable\">" . $data['asesor'] . "</td>
                    <td class=\"greentable\">" . $data['iniciopedido'] . "</td>
                    <td class=\"greentable\">" . $data['finpedido'] . "</td>
                    <td class=\"greentable\">" . $data['diaspedido'] . "</td>";
                            if ($diafaltapedido > 3) {
                                echo "<td class=\"greentable\">" . $diafaltapedido . "</td>";
                            } elseif ($diafaltapedido >= 0) {
                                echo "<td class=\"yellowtable\">" . $diafaltapedido . "</td>";
                            } else {
                                echo "<td class=\"redtable\">" . $diafaltapedido . "</td>";
                            }

                            echo " <td class=\"greentable\">" . $data['siglas'] . "</td>
                    <td class=\"greentable\" style=\"border-right: 1px solid #00a8a8\">" . $unds . "</td>
                   
                    <td class=\"greentable\">" . $data['inicioestampacion'] . "</td>
                    <td class=\"greentable\">" . $data['finestampacion'] . "</td>
                    <td class=\"greentable\">" . $data['diasestampacion'] . "</td>";
                            if ($diafaltaestampacion > 3) {
                                echo "<td class=\"greentable\">" . $diafaltaestampacion . "</td>";
                            } elseif ($diafaltaestampacion >= 0) {
                                echo "<td class=\"yellowtable\">" . $diafaltaestampacion . "</td>";
                            } else {
                                echo "<td class=\"redtable\">" . $diafaltaestampacion . "</td>";
                            }
                            echo "
                            <td class=\"greentable\">" . $prod_confec . $prod_bod . "</td>
                    <td class=\"greentable\">" . $tecnica . "</td>
                    <td class=\"greentable\">" . $nro_diseno . "</td>
                    <td class=\"greentable\">" . $posicion . "</td>
                    <td class=\"greentable\">" . $seda . "</td>
                    <td class=\"greentable\">" . $grabacion . "</td>
                    <td class=\"greentable\">" . $nro_plancha . "</td>
                    <td class=\"greentable\">" . $fren . "</td>
                    <td class=\"greentable\">" . $esp . "</td>
                    <td class=\"greentable\">" . $otro . "</td>
                    <td class=\"greentable\">" . $prep . "</td>
                    <td class=\"greentable\">" . $est . "</td>
                    <td class=\"greentable\">" . $sub . "</td>
                    <td class=\"greentable\">" . round(($prep + $est + $sub) * $falta / $unds / 60, 2) . "</td></td>
                    <td class=\"greentable\">" . $parcial . "</td>
                    <td class=\"greentable\">" . $falta . "</td>
                    <td class=\"greentable\">" . $data['obs_estampacion'] . "</td>
                    <td class=\"greentable\">" . $data['estado'] . "</td>
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

            "order": [
                [12, "asc"]
            ],
            "pageLength": 50,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            },
        })
    </script>



</body>

</html>