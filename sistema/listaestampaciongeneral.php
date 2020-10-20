<?php
session_start();

include "../conexion.php";

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
    include "includes/header.php" ?>
    <section id="container">

        <a href="reporte_estampacion.php" class="btn_new" style="position:fixed ; top:150px; left: 0px;">Reporte</a>


        <center>
            <div style="width:100%">

                <h1>Lista General de Pedidos para ESTAMPACIÓN</h1>


                <table id="tabla" class="display">
                    <thead>
                        <tr class="titulo">
                            <th style="border-right: 1px solid #9ecaca" colspan="10">Información Pedido</th>

                            <th colspan="23"> Información estampación</th>
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
                            <th>Estado Pedido</th>
                            <th style="border-right: 1px solid #9ecaca">Unds</th>

                            <th>Fecha Inicio </th>
                            <th>Fecha Entrega</th>
                            <th>Días Hab</th>
                            <th>Días Falta</th>
                            <th>Prod</th>
                            <th>Técnica</th>
                            <th> N° Diseño</th>
                            <th>Posición</th>
                            <th>Seda</th>
                            <th>Grabación</th>
                            <th>N° Planchas</th>
                            <th>Frente</th>
                            <th>Espalda</th>
                            <th>Otros</th>
                            <th>Prepa (min)</th>
                            <th>Estam (min)</th>
                            <th>Subl (min)</th>
                            <th>Tiempo (horas)</th>
                            <th>Unds Parcial</th>
                            <th>Unds Falta</th>
                            <th>Observaciones</th>
                            <th>Estado</th>
                            <th>Acciones</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php
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

                        $query = mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
            pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
            estam.idestampacion, estam.iniciofecha as 'inicioestampacion', estam.finfecha as 'finestampacion', estam.dias as 'diasestampacion',
            estam.inicioprocesofecha, estam.finprocesofecha, estam.parcial, us.usuario, estam.obs_estampacion, pr.siglas, es.estado, est.estado as 'estadopedido',
            estam.tecnica, estam.nro_diseno, estam.posicion, estam.seda, estam.grabacion, estam.nro_plancha, estam.fren, estam.esp, estam.otro, 
            estam.prep, estam.est, estam.sub, estam.pedido
            FROM pedidos pe 
            INNER JOIN procesos pr ON pe.procesos=pr.idproceso
            INNER JOIN estampacion estam ON pe.idpedido=estam.pedido
            INNER JOIN usuario us on pe.usuario=us.idusuario
            INNER JOIN estado es ON estam.estado=es.id_estado
            INNER JOIN estado est ON pe.estado=est.id_estado
            WHERE estam.estado<=2");

                        $result = mysqli_num_rows($query);

                        if ($result > 0) {
                            while ($data = mysqli_fetch_array($query)) {

                                $nro_pedido = $data['num_pedido'];
                                $pedido = $data['pedido'];
                                $prod_confe = mysqli_query($conexion, "SELECT * FROM confeccion WHERE pedido=$pedido");
                                $consult_confec = mysqli_fetch_array($prod_confe);
                                $prod_confec = $consult_confec['entrega'];
                                $prod_bod = mysqli_query($conexion, "SELECT * FROM bodega WHERE pedido=$pedido");
                                $consult_bod =  mysqli_fetch_array($prod_bod);
                                $prod_bod = $consult_bod['entrega'];

                                $unds = $data['unds'];
                                $parcial = $data['parcial'];
                                $falta = $unds - $parcial;
                                $hoy = date('Y-m-d');
                                $diapedido = $data['finpedido'];
                                $diaestampacion = $data['finestampacion'];
                                $estadopedido = $data['estadopedido'];
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
                    <td>" . $nro_pedido . "</td>
                    <td>" . $data['cliente'] . "</td>
                    <td>" . $data['asesor'] . "</td>
                    <td>" . $data['iniciopedido'] . "</td>
                    <td>" . $data['finpedido'] . "</td>
                    <td>" . $data['diaspedido'] . "</td>";
                                if ($diafaltapedido > 3) {
                                    echo "<td class=\"greentable\">" . $diafaltapedido . "</td>";
                                } elseif ($diafaltapedido >= 0) {
                                    echo "<td class=\"yellowtable\">" . $diafaltapedido . "</td>";
                                } else {
                                    echo "<td class=\"redtable\">" . $diafaltapedido . "</td>";
                                }

                                echo " <td>" . $data['siglas'] . "</td>
                    <td>" . $estadopedido . "</td>
                    <td style=\"border-right: 1px solid #00a8a8\">" . $unds . "</td>
                   
                    <td>" . $data['inicioestampacion'] . "</td>
                    <td>" . $data['finestampacion'] . "</td>
                    <td>" . $data['diasestampacion'] . "</td>";
                                if ($diafaltaestampacion > 3) {
                                    echo "<td class=\"greentable\">" . $diafaltaestampacion . "</td>";
                                } elseif ($diafaltaestampacion >= 0) {
                                    echo "<td class=\"yellowtable\">" . $diafaltaestampacion . "</td>";
                                } else {
                                    echo "<td class=\"redtable\">" . $diafaltaestampacion . "</td>";
                                }
                                echo "
                    <td>" . $prod_confec . $prod_bod . "</td>
                    <td>" . $tecnica . "</td>
                    <td>" . $nro_diseno . "</td>
                    <td>" . $posicion . "</td>
                    <td>" . $seda . "</td>
                    <td>" . $grabacion . "</td>
                    <td>" . $nro_plancha . "</td>
                    <td>" . $fren . "</td>
                    <td>" . $esp . "</td>
                    <td>" . $otro . "</td>
                    <td>" . $prep . "</td>
                    <td>" . $est . "</td>
                    <td>" . $sub . "</td>
                    <td>" . round(($prep + $est + $sub) * $falta / $unds / 60, 2) . "</td></td>
                    <td>" . $parcial . "</td>
                    <td>" . $falta . "</td>
                    <td>" . $data['obs_estampacion'] . "</td>
                    <td>" . $data['estado'] . "</td>
                    <td><div>
                    <a title=\"Editar\"class=\"link_edit\"href=\"editar_estampacion.php?id=" . $data['idestampacion'] . "\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span></a>
                    <a title=\"Finalizar\"class=\"link_edit\"href=\"finalizar_estampacion.php?id=" . $data['idestampacion'] . "\"><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span></a>
                    <a title=\"Anular\"class=\"link_delete\"href=\"anular_estampacion.php?id=" . $data['idestampacion'] . "\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span></a>
                    
                    </div>
                    </td>                   
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
                    [13, "asc"]
                ],
                "pageLength": 50,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                },
            })
        </script>



</body>

</html>