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

    <title>bordado</title>
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


    <a href="listabordadogeneral.php" class="btn_new" style="position:fixed ; top:0px; left: 0px;"><input style="display:block; width:150px; position:fixed ; top:0px; left: 0%;;" class="btn_new" type='button' href="listabordadogeneral.php" value='MENÚ' /></a>

    <input style="display:block; width:150px; position:fixed ; top:0px; left: 85%;;" class="btn_new" type='button' onclick='window.print();' value='Imprimir' />


    <center>
        <div style="width:99%">

            <h1 style="font-size:50px; font-weight:bold; color: #00a8a8">REPORTE DE bordado <?php echo date('d-m-Y'); ?>
            </h1>
            <table style="width:50% !important; ">
                <thead>
                    <tr>
                        <th class="titulo"></th>
                        <th class="titulo">Fechas Vencidas</th>
                        <th class="titulo">0 a 3 días</th>
                        <th class="titulo">> 3 días </th>
                        <th class="titulo">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="titulo">Pedidos</th>
                        <td background="red">
                            <?php $query = mysqli_query($conexion, "SELECT * FROM bordado WHERE finfecha<'$hoy' AND estado<=2");
                            echo mysqli_num_rows($query) ?>
                        </td>
                        <td><?php $query = mysqli_query($conexion, "SELECT * FROM bordado WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias' ");
                            echo mysqli_num_rows($query) ?>
                        </td>
                        <td><?php $query = mysqli_query($conexion, "SELECT * FROM bordado WHERE finfecha>='$cuatrodias' AND estado<=2");
                            echo mysqli_num_rows($query) ?>
                        </td>
                        <td><?php $query = mysqli_query($conexion, "SELECT * FROM bordado WHERE  estado<=2");
                            echo mysqli_num_rows($query) ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="titulo">Unds Listas</th>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(parcial) as 'suma' FROM bordado WHERE finfecha<'$hoy' AND estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma']; ?>
                        </td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(parcial) as 'suma' FROM bordado WHERE estado<=2 AND finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma']; ?>
                        </td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(parcial) as 'suma' FROM bordado WHERE finfecha>='$cuatrodias' AND estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma']; ?>
                        </td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(parcial) as 'suma' FROM bordado WHERE  estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="titulo">Unds Faltantes</th>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds-bor.parcial) as 'resta' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2 AND bor.finfecha<'$hoy'");
                            $result = mysqli_fetch_array($query);
                            echo $result['resta']; ?>
                        </td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds-bor.parcial) as 'resta' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2 AND bor.finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $result = mysqli_fetch_array($query);
                            echo $result['resta']; ?>
                        </td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds-bor.parcial) as 'resta' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2 AND bor.finfecha>='$cuatrodias'");
                            $result = mysqli_fetch_array($query);
                            echo $result['resta']; ?>
                        </td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds-bor.parcial) as 'resta' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo $result['resta']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="titulo">Unds Totales</th>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds) as 'suma' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2 AND bor.finfecha<'$hoy'");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma']; ?>
                        </td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds) as 'suma' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2 AND bor.finfecha BETWEEN'$hoy' AND '$tresdias'");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma']; ?>
                        </td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds) as 'suma' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2 AND bor.finfecha>='$cuatrodias'");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma']; ?>
                        </td>
                        <td><?php $query = mysqli_query($conexion, "SELECT SUM(pe.unds) as 'suma' FROM bordado bor INNER JOIN pedidos pe ON bor.pedido=pe.idpedido
                                                            WHERE bor.estado<=2");
                            $result = mysqli_fetch_array($query);
                            echo $result['suma']; ?>
                        </td>
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
                        <th colspan="8"> Información bordado</th>
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
                        <th>Unds Parcial</th>
                        <th>Unds Falta</th>
                        <th>Observaciones</th>
                        <th>Estado</th>



                    </tr>
                    <tr>
                        <th class="titulo" colspan="17">FECHAS VENCIDAS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php


                    $query = mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
            pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
            bor.idbordado, bor.iniciofecha as 'iniciobordado', bor.finfecha as 'finbordado', bor.dias as 'diasbordado',
            bor.inicioprocesofecha, bor.finprocesofecha, bor.parcial, us.usuario, bor.obs_bordado, pr.siglas, es.estado
            FROM pedidos pe 
            INNER JOIN procesos pr ON pe.procesos=pr.idproceso
            INNER JOIN bordado bor ON pe.idpedido=bor.pedido
            INNER JOIN usuario us on pe.usuario=us.idusuario
            INNER JOIN estado es ON bor.estado=es.id_estado
            WHERE bor.estado<=2 and finfecha<'$hoy'");

                    $result = mysqli_num_rows($query);

                    if ($result > 0) {
                        while ($data = mysqli_fetch_array($query)) {


                            $unds = $data['unds'];
                            $parcial = $data['parcial'];
                            $falta = $unds - $parcial;

                            $diapedido = $data['finpedido'];
                            $diabordado = $data['finbordado'];
                            $diafaltapedido =  number_of_working_days($hoy, $diapedido) - 1;
                            if ($diafaltapedido < 0) {
                                $diafaltapedido =  - (number_of_working_days($diapedido, $hoy) - 1);
                            }
                            $diafaltabordado =  number_of_working_days($hoy, $diabordado) - 1;
                            if ($diafaltabordado < 0) {
                                $diafaltabordado =  - (number_of_working_days($diabordado, $hoy) - 1);
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
                   
                    <td class=\"redtable\">" . $data['iniciobordado'] . "</td>
                    <td class=\"redtable\">" . $data['finbordado'] . "</td>
                    <td class=\"redtable\">" . $data['diasbordado'] . "</td>";
                            if ($diafaltabordado > 3) {
                                echo "<td class=\"greentable\">" . $diafaltabordado . "</td>";
                            } elseif ($diafaltabordado >= 0) {
                                echo "<td class=\"yellowtable\">" . $diafaltabordado . "</td>";
                            } else {
                                echo "<td class=\"redtable\">" . $diafaltabordado . "</td>";
                            }
                            echo "<td class=\"redtable\">" . $parcial . "</td>
                    <td class=\"redtable\">" . $falta . "</td>
                    <td class=\"redtable\">" . $data['obs_bordado'] . "</td>
                    <td class=\"redtable\">" . $data['estado'] . "</td>
                    </tr>                    ";
                        }
                    }
                    ?>
                    <tr>
                        <th class="titulo" colspan="17">0 a 3 DÍAS</th>
                    </tr>

                    <?php


                    $query = mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
            pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
            bor.idbordado, bor.iniciofecha as 'iniciobordado', bor.finfecha as 'finbordado', bor.dias as 'diasbordado',
            bor.inicioprocesofecha, bor.finprocesofecha, bor.parcial, us.usuario, bor.obs_bordado, pr.siglas, es.estado
            FROM pedidos pe 
            INNER JOIN procesos pr ON pe.procesos=pr.idproceso
            INNER JOIN bordado bor ON pe.idpedido=bor.pedido
            INNER JOIN usuario us on pe.usuario=us.idusuario
            INNER JOIN estado es ON bor.estado=es.id_estado
            WHERE bor.estado<=2 and finfecha BETWEEN'$hoy' AND '$tresdias'");

                    $result = mysqli_num_rows($query);

                    if ($result > 0) {
                        while ($data = mysqli_fetch_array($query)) {


                            $unds = $data['unds'];
                            $parcial = $data['parcial'];
                            $falta = $unds - $parcial;

                            $diapedido = $data['finpedido'];
                            $diabordado = $data['finbordado'];
                            $diafaltapedido =  number_of_working_days($hoy, $diapedido) - 1;
                            if ($diafaltapedido < 0) {
                                $diafaltapedido =  - (number_of_working_days($diapedido, $hoy) - 1);
                            }
                            $diafaltabordado =  number_of_working_days($hoy, $diabordado) - 1;
                            if ($diafaltabordado < 0) {
                                $diafaltabordado =  - (number_of_working_days($diabordado, $hoy) - 1);
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
                   
                    <td class=\"yellowtable\">" . $data['iniciobordado'] . "</td>
                    <td class=\"yellowtable\">" . $data['finbordado'] . "</td>
                    <td class=\"yellowtable\">" . $data['diasbordado'] . "</td>";
                            if ($diafaltabordado > 3) {
                                echo "<td class=\"greentable\">" . $diafaltabordado . "</td>";
                            } elseif ($diafaltabordado >= 0) {
                                echo "<td class=\"yellowtable\">" . $diafaltabordado . "</td>";
                            } else {
                                echo "<td class=\"redtable\">" . $diafaltabordado . "</td>";
                            }
                            echo "<td class=\"yellowtable\">" . $parcial . "</td>
                    <td class=\"yellowtable\">" . $falta . "</td>
                    <td class=\"yellowtable\">" . $data['obs_bordado'] . "</td>
                    <td class=\"yellowtable\">" . $data['estado'] . "</td>
                    </tr>                    ";
                        }
                    }
                    ?>
                    <tr>
                        <th class="titulo" colspan="17">4 DÍAS EN ADELANTE</th>
                    </tr>

                    <?php


                    $query = mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
            pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
            bor.idbordado, bor.iniciofecha as 'iniciobordado', bor.finfecha as 'finbordado', bor.dias as 'diasbordado',
            bor.inicioprocesofecha, bor.finprocesofecha, bor.parcial, us.usuario, bor.obs_bordado, pr.siglas, es.estado
            FROM pedidos pe 
            INNER JOIN procesos pr ON pe.procesos=pr.idproceso
            INNER JOIN bordado bor ON pe.idpedido=bor.pedido
            INNER JOIN usuario us on pe.usuario=us.idusuario
            INNER JOIN estado es ON bor.estado=es.id_estado
            WHERE bor.estado<=2 and finfecha>='$cuatrodias'");

                    $result = mysqli_num_rows($query);

                    if ($result > 0) {
                        while ($data = mysqli_fetch_array($query)) {


                            $unds = $data['unds'];
                            $parcial = $data['parcial'];
                            $falta = $unds - $parcial;

                            $diapedido = $data['finpedido'];
                            $diabordado = $data['finbordado'];
                            $diafaltapedido =  number_of_working_days($hoy, $diapedido) - 1;
                            if ($diafaltapedido < 0) {
                                $diafaltapedido =  - (number_of_working_days($diapedido, $hoy) - 1);
                            }
                            $diafaltabordado =  number_of_working_days($hoy, $diabordado) - 1;
                            if ($diafaltabordado < 0) {
                                $diafaltabordado =  - (number_of_working_days($diabordado, $hoy) - 1);
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
                   
                    <td class=\"greentable\">" . $data['iniciobordado'] . "</td>
                    <td class=\"greentable\">" . $data['finbordado'] . "</td>
                    <td class=\"greentable\">" . $data['diasbordado'] . "</td>";
                            if ($diafaltabordado > 3) {
                                echo "<td class=\"greentable\">" . $diafaltabordado . "</td>";
                            } elseif ($diafaltabordado >= 0) {
                                echo "<td class=\"yellowtable\">" . $diafaltabordado . "</td>";
                            } else {
                                echo "<td class=\"redtable\">" . $diafaltabordado . "</td>";
                            }
                            echo "<td class=\"greentable\">" . $parcial . "</td>
                    <td class=\"greentable\">" . $falta . "</td>
                    <td class=\"greentable\"> " . $data['obs_bordado'] . "</td>
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