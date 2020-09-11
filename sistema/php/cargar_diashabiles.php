
<?php 
include "../../conexion.php";
 
 $fechafin=$_POST['fechafin'] ; 
 $fechainicio=$_POST['fechainicio'] ; 

    echo number_of_working_days($fechainicio, $fechafin);
    $diashabiles= number_of_working_days($fechainicio, $fechafin);

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


?>