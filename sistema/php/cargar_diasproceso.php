<?php
include "../../conexion.php";


$fechafin = $_POST['fechafin'];
$fechainicio = $_POST['fechainicio'];


$diashabiles = number_of_working_days($fechainicio, $fechafin);

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


$idproceso = $_POST['procesos'];
$query = mysqli_query($conexion, "SELECT * FROM procesos WHERE idproceso =$idproceso");
$result = mysqli_fetch_array($query);

$diaspedido = $diashabiles;
$diasproceso = $result['dias_habiles'];
$diferencia = $diasproceso - $diaspedido;
echo "El proceso selecionado tiene por defecto " . $diasproceso . " días hábiles. <br /><br />";
if ($diaspedido >= $diasproceso) {
    echo "Este pedido cumple con los días minimos para el proceso de las áreas.";
} else {
    echo "Este pedido no cumple con los días minimos para el proceso de las áreas, con una diferencia de " . $diferencia . " dias menos. Por Favor comunicarse con el comercial para rectificar fecha de entrega. ";
}
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
    return $fechafinal = date('d-m-Y', $total);
}

#comparacion celda1
$dato1 = strtolower($result['1']);
$tiempo1 = $result['tiempo1'];
$dias1 = round($diaspedido * $tiempo1);
$inicio1 = sumasdiasemana($fechainicio, 0);
$final1 = sumasdiasemana($inicio1, $dias1 - 1);
if ($dato1 != '') {

?><div style="border: 1px solid #00a8a8; border-radius: 10px; ">
        <center>
            <h3 style="text-transform: uppercase; "><?php echo $dato1 ?></h3>
            <div>
                Días Hábiles: <?php echo $dias1 ?>
            </div>
            <div>
                Fecha Inicio: <?php echo $inicio1 ?>
            </div>
            <div>
                Fecha Entrega: <?php echo $final1 ?>
            </div>
        </center>
    </div>
<?php
}

#comparacion celda2
$dato2 = strtolower($result['2']);
$tiempo2 = $result['tiempo2'];
$dias2 = round($diaspedido * $tiempo2);
$inicio2 = sumasdiasemana($final1, 1);
$final2 = sumasdiasemana($inicio2, $dias2 - 1);
if ($dato2 != '') {

?>
    <div style="border: 1px solid #00a8a8; border-radius: 10px; ">
        <center>
            <h3 style="text-transform: uppercase; "><?php echo $dato2 ?></h3>
            <div>
                Días Hábiles: <?php echo $dias2 ?>
            </div>
            <div>
                Fecha Inicio: <?php echo $inicio2 ?>
            </div>
            <div>
                Fecha Entrega: <?php echo $final2 ?>
            </div>
        </center>
    </div>
<?php
}
#comparacion celda3
$dato3 = strtolower($result['3']);
$tiempo3 = $result['tiempo3'];
$dias3 = round($diaspedido * $tiempo3);


$inicio3 = sumasdiasemana($final2, 1);
$final3 = sumasdiasemana($inicio3, $dias3 - 1);
if ($dato3 != '') {

?>
    <div style="border: 1px solid #00a8a8; border-radius: 10px; ">
        <center>
            <h3 style="text-transform: uppercase; "><?php echo $dato3 ?></h3>
            <div>
                Días Hábiles: <?php echo $dias3 ?>
            </div>
            <div>
                Fecha Inicio: <?php echo $inicio3 ?>
            </div>
            <div>
                Fecha Entrega: <?php echo $final3 ?>
            </div>
        </center>
    </div>
<?php
}

#comparacion celda4
$dato4 = strtolower($result['4']);
$tiempo4 = $result['tiempo4'];
$dias4 = round($diaspedido * $tiempo4);

$inicio4 = sumasdiasemana($final3, 1);
$final4 = sumasdiasemana($inicio4, $dias4 - 1);
if ($dato4 != '') {

?>
    <div style="border: 1px solid #00a8a8; border-radius: 10px; ">
        <center>
            <h3 style="text-transform: uppercase; "><?php echo $dato4 ?></h3>
            <div>
                Días Hábiles: <?php echo $dias4 ?>
            </div>
            <div>
                Fecha Inicio: <?php echo $inicio4 ?>
            </div>
            <div>
                Fecha Entrega: <?php echo $final4 ?>
            </div>
        </center>
    </div>
<?php
}

#comparacion celda5
$dato5 = strtolower($result['5']);
$tiempo5 = $result['tiempo5'];
$dias5 = round($diaspedido * $tiempo5);
$inicio5 = sumasdiasemana($final4, 1);
$final5 = sumasdiasemana($inicio5, $dias5 - 1);
if ($dato5 != '') {

?>
    <div style="border: 1px solid #00a8a8; border-radius: 10px; ">
        <center>
            <h3 style="text-transform: uppercase; "><?php echo $dato5 ?></h3>
            <div>
                Días Hábiles: <?php echo $dias5 ?>
            </div>
            <div>
                Fecha Inicio: <?php echo $inicio5 ?>
            </div>
            <div>
                Fecha Entrega: <?php echo $final5 ?>
            </div>
        </center>
    </div>
<?php
}
#comparacion celda6
$dato6 = strtolower($result['6']);
$tiempo6 = $result['tiempo6'];
$dias6 = round($diaspedido * $tiempo6);
$inicio6 = sumasdiasemana($final5, 1);
$final6 = sumasdiasemana($inicio6, $dias6 - 1);
if ($dato6 != '') {

?>
    <div style="border: 1px solid #00a8a8; border-radius: 10px; ">
        <center>
            <h3 style="text-transform: uppercase; "><?php echo $dato6 ?></h3>
            <div>
                Días Hábiles: <?php echo $dias6 ?>
            </div>
            <div>
                Fecha Inicio: <?php echo $inicio6 ?>
            </div>
            <div>
                Fecha Entrega: <?php echo $final6 ?>
            </div>
        </center>
    </div>
<?php
}

?>