<?php
session_start();

include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    date_default_timezone_set('America/Bogota');


    $idestampacion = $_POST['idestampacion'];
    $parcial = $_POST['parcial'];
    $obs = $_POST['obs'];
    $estado = $_POST['estado'];
    $usuario = $_SESSION['iduser'];
    $inicioproceso = date('Y-m-d');
    $tecnica = $_POST['tecnica'];
    $nro_diseno = $_POST['nro_diseno'];
    $posicion = $_POST['posicion'];
    $seda = $_POST['seda'];
    $grabacion = $_POST['grabacion'];
    $nro_plancha = $_POST['plancha'];
    $fren = $_POST['fren'];
    $esp = $_POST['esp'];
    $otro = $_POST['otro'];
    $prep = $_POST['prep'];
    $est = $_POST['est'];
    $sub = $_POST['sub'];
    $sql = mysqli_query($conexion, "SELECT * FROM estampacion WHERE estado<1 and idestampacion=$idestampacion");
    $sql_result = mysqli_num_rows($sql);


    if ($sql_result > 0) {
        $sql_update = mysqli_query($conexion, "UPDATE estampacion SET parcial= '$parcial', obs_estampacion='$obs',
                                            usuario='$usuario', estado='$estado', inicioprocesofecha='$inicioproceso',
                                            tecnica='$tecnica', nro_diseno=$nro_diseno, posicion=$posicion, seda=$seda,
                                            grabacion='$grabacion', nro_plancha=$nro_plancha, fren='$fren', esp='$esp',
                                            otro='$otro',prep=$prep, est=$est, sub=$sub
                                            WHERE idestampacion=$idestampacion ");
        $sql = mysqli_query($conexion, "SELECT * FROM estampacion WHERE idestampacion=$idestampacion");
        $sqlselect = mysqli_fetch_array($sql);
        $idpedido = $sqlselect['pedido'];
        $sql_estado = mysqli_query($conexion, "UPDATE pedidos SET estado=1 WHERE idpedido='$idpedido'");
    } else {

        $sql_update = mysqli_query($conexion, "UPDATE estampacion SET parcial= '$parcial', obs_estampacion='$obs',
                                            usuario='$usuario', estado='$estado',tecnica='$tecnica', nro_diseno=$nro_diseno, posicion=$posicion, seda=$seda,
                                            grabacion='$grabacion', nro_plancha=$nro_plancha, fren='$fren', esp='$esp',
                                            otro='$otro',prep=$prep, est=$est, sub=$sub
                                            WHERE idestampacion=$idestampacion");
        $sql = mysqli_query($conexion, "SELECT * FROM estampacion WHERE idestampacion=$idestampacion");
        $sqlselect = mysqli_fetch_array($sql);
        $idpedido = $sqlselect['pedido'];
        $sql_estado = mysqli_query($conexion, "UPDATE pedidos SET estado=1 WHERE idpedido='$idpedido'");
    }
    if ($sql_update) {
        $alert = '<p class="msg_save">Pedido Actualizado Correctamente</p>';
    } else {
        $alert = '<p class="msg_error">Error al Actualizar el Pedido</p>';
    }
}

//MOSTRAR DATOS

if (empty($_GET['id'])) {
    header('location listaestampaciongeneral.php');
}
$idestampacion = $_GET['id'];

$sql = mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
estam.idestampacion, estam.iniciofecha as 'inicioestampacion', estam.finfecha as 'finestampacion', estam.dias as 'diasestampacion',
estam.inicioprocesofecha, estam.finprocesofecha, estam.parcial, us.usuario, estam.obs_estampacion, pr.siglas, es.estado, es.id_estado,
estam.tecnica, estam.nro_diseno, estam.posicion, estam.seda, estam.grabacion, estam.nro_plancha, estam.fren, estam.esp, estam.otro, 
estam.prep, estam.est, estam.sub
FROM pedidos pe 
INNER JOIN procesos pr ON pe.procesos=pr.idproceso
INNER JOIN estampacion estam ON pe.idpedido=estam.pedido
INNER JOIN usuario us on pe.usuario=us.idusuario
INNER JOIN estado es ON estam.estado=es.id_estado WHERE estam.idestampacion=$idestampacion");

$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('location listaestampaciongeneral.php');
} else {
    $data = mysqli_fetch_array($sql);
    $id = $data['idestampacion'];
    $pedido = $data['num_pedido'];
    $cliente = $data['cliente'];
    $iniciopedido = $data['iniciopedido'];
    $finpedido = $data['finpedido'];
    $siglas = $data['siglas'];
    $unds = $data['unds'];
    $inicioestampacion = $data['inicioestampacion'];
    $finestampacion = $data['finestampacion'];
    $parcial = $data['parcial'];
    $obs = $data['obs_estampacion'];
    $estado = $data['estado'];
    $idestado = $data['id_estado'];
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
}

?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <?php include "includes/scripts.php" ?>
    <title>ACTUALIZACIÓN</title>
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

        <div class="form-register">

            <a href="listaestampaciongeneral.php" class="btn_new" style="position:fixed ; top:150px; left: 0px;">General</a>
            <a href="reporte_estampacion.php" class="btn_new" style="position:fixed ; top:150px; left: 200px;">Reporte</a>


            <form action="" method="post">
                <h1>Actualización de Pedido</h1>
                <hr>
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

                <div>
                    <input type="hidden" name="idestampacion" id="idestampacion" value="<?php echo $idestampacion; ?>">
                </div>
                <div style="border: 1px solid #00a8a8; border-radius: 10px; ">
                    <center>
                        <h3 style="text-transform: uppercase; ">pedido: <?php echo $pedido ?></h3>
                        <div>
                            Cliente: <?php echo $cliente ?>
                        </div>
                        <div>
                            Fecha Inicio: <?php echo $iniciopedido ?>
                        </div>
                        <div>
                            Fecha Entrega: <?php echo $finpedido ?>
                        </div>
                        <div>
                            Procesos: <?php echo $siglas ?>
                        </div>
                        <div>
                            Unidades: <?php echo $unds ?>
                        </div>
                    </center>
                </div>
                <div style="border: 1px solid #00a8a8; border-radius: 10px; ">
                    <center>
                        <h3 style="text-transform: uppercase; ">Info estampación</h3>

                        <div>
                            Fecha Inicio: <?php echo $inicioestampacion ?>
                        </div>
                        <div>
                            Fecha Entrega: <?php echo $finestampacion ?>
                        </div>
                        <div>
                            <label for="tecnica">Técnica</label>
                            <select style="width: 250px" name="tecnica" id="tecnica" class="itemunico">
                                <?php echo "  <option value=\"" . $tecnica . "\">" . $tecnica . "</option>"; ?>
                                <option value="0" disabled>Selecciones una Opcion</option>
                                <option value="TEXTIL">TEXTIL</option>
                                <option value="PLASTISOL">PLASTISOL</option>
                                <option value="VINILO">VINILO</option>
                                <option value="CORROSION">CORROSION</option>
                                <option value="TEXT/CORR">TEXT/CORR</option>
                                <option value="PLAST/CORR">PLAST/CORR</option>
                                <option value="PLAST/TEXT">PLAST/TEXT</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="columns3">
                                <label for="nro_diseno">N° Diseños</label>
                                <select style="width: 100px" name="nro_diseno" id="nro_diseno" class="itemunico">
                                    <?php echo "  <option value=\"" . $nro_diseno . "\">" . $nro_diseno . "</option>"; ?>
                                    <option value="" disabled></option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8"></option>
                                </select>
                            </div>
                            <div class="columns3">
                                <label for="posicion">Posición</label>
                                <select style="width: 100px" name="posicion" id="posicion" class="itemunico">
                                    <?php echo "  <option value=\"" . $posicion . "\">" . $posicion . "</option>"; ?>
                                    <option value="" disabled></option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8"></option>
                                </select>
                            </div>
                            <div class="columns3">
                                <label for="seda">Seda</label>
                                <select style="width: 100px" name="seda" id="seda" class="itemunico">
                                    <?php echo "  <option value=\"" . $seda . "\">" . $seda . "</option>"; ?>
                                    <option value="0" disabled></option>
                                    <option value="55">55</option>
                                    <option value="64">64</option>
                                    <option value="77">77</option>
                                    <option value="100">100</option>
                                    <option value="120">120</option>
                                </select>
                            </div>
                            <div class="columns2">
                                <label for="plancha">N° Planchas</label>
                                <select style="width: 150px" name="plancha" id="plancha" class="itemunico">
                                    <?php echo "  <option value=\"" . $nro_plancha . "\">" . $nro_plancha . "</option>"; ?>
                                    <option value="" disabled></option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8"></option>
                                </select>
                            </div>
                            <div class="columns2">
                                <label for="grabacion">Grabación</label>
                                <select style="width: 150px" name="grabacion" id="grabacion" class="itemunico">
                                    <?php echo "  <option value=\"" . $grabacion . "\">" . $grabacion . "</option>"; ?>
                                    <option value="" disabled></option>
                                    <option value="si">si</option>
                                    <option value="no">no</option>
                                </select>
                            </div>
                           
                        </div>
                        <div>
                            <label for="fren">Frente</label>
                            <input style="width: 300px" type="text" name="fren" id="fren" autocomplete="on" value="<?php echo $fren; ?>">
                        </div>
                        <div>
                            <label for="esp">Espalda</label>
                            <input style="width: 300px" type="text" name="esp" id="esp" autocomplete="on" value="<?php echo $esp; ?>">
                        </div>
                        <div>
                            <label for="otro">Otros</label>
                            <input style="width: 300px" type="text" name="otro" id="otro" autocomplete="on" value="<?php echo $otro; ?>">
                        </div>
                        <div class="row">
                            <div class="columns3">
                                <label for="prep">Preparación</label>
                                <input style="width: 100px" type="number" name="prep" id="prep" autocomplete="on" value="<?php echo $prep; ?>">
                            </div>
                            <div class="columns3">
                                <label for="est">Estampación</label>
                                <input style="width: 100px" type="number" name="est" id="est" autocomplete="on" value="<?php echo $est; ?>">
                            </div>
                            <div class="columns3">
                                <label for="sub">Sublimación</label>
                                <input style="width: 100px" type="number" name="sub" id="sub" autocomplete="on" value="<?php echo $sub; ?>">
                            </div>
                        </div>
                        <div>
                            <label for="parcial">Unds Parcial</label>
                            <input style="width: 50px" type="text" name="parcial" id="parcial" autocomplete="off" value="<?php echo $parcial; ?>">
                        </div>
                        <div>
                            <label for="obs">Observaciones</label>
                            <textarea style="width: 300px" name="obs" id="obs" rows="4" cols="45"><?php echo $obs; ?></textarea>
                        </div>
                        <div>
                            <label for="estado">Estado</label>
                            <?php
                            $query_estado = mysqli_query($conexion, "SELECT * FROM estado WHERE id_estado=1 OR id_estado=2");
                            $result_estado = mysqli_num_rows($query_estado);
                            ?>
                            <select style="width: 300px" name="estado" id="estado" class="itemunico">
                                <?php echo "  <option value=\"" . $idestado . "\">" . $estado . "</option>"; ?>
                                <option value="0" disabled>Selecciones una Opcion</option>
                                <?php
                                if ($result_estado > 0) {
                                    while ($rol = mysqli_fetch_array($query_estado)) {
                                        echo "  <option value=\"" . $rol['id_estado'] . "\">" . $rol['estado'] . "</option>";
                                    }
                                } ?>
                            </select>
                        </div>
                    </center>
                </div>
                <input type="submit" value="Editar Pedido" class="btn-save">

            </form>


        </div>


    </section>

</body>

</html>