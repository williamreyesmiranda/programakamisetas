<?php
session_start();

include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    date_default_timezone_set('America/Bogota');


    $idterminacion = $_POST['idterminacion'];
    $parcial = $_POST['parcial'];
    $obs = $_POST['obs'];
    $estado = $_POST['estado'];
    $usuario = $_SESSION['iduser'];
    $inicioproceso = date('Y-m-d');
    $sql = mysqli_query($conexion, "SELECT * FROM terminacion WHERE estado<1 and idterminacion=$idterminacion");
    $sql_result = mysqli_num_rows($sql);


    if ($sql_result > 0) {
        $sql_update = mysqli_query($conexion, "UPDATE terminacion SET parcial= '$parcial', obs_terminacion='$obs',
                                            usuario='$usuario', estado='$estado', inicioprocesofecha='$inicioproceso'
                                            WHERE idterminacion=$idterminacion ");
        $sql = mysqli_query($conexion, "SELECT * FROM terminacion WHERE idterminacion=$idterminacion");
        $sqlselect = mysqli_fetch_array($sql);
        $idpedido = $sqlselect['pedido'];
        $sql_estado = mysqli_query($conexion, "UPDATE pedidos SET estado=1 WHERE idpedido='$idpedido'");
    } else {

        $sql_update = mysqli_query($conexion, "UPDATE terminacion SET parcial= '$parcial', obs_terminacion='$obs',
                                            usuario='$usuario', estado='$estado'
                                            WHERE idterminacion=$idterminacion");
        $sql = mysqli_query($conexion, "SELECT * FROM terminacion WHERE idterminacion=$idterminacion");
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
    header('location listaterminaciongeneral.php');
}
$idterminacion = $_GET['id'];

$sql = mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
ter.idterminacion, ter.iniciofecha as 'inicioterminacion', ter.finfecha as 'finterminacion', ter.dias as 'diasterminacion',
ter.inicioprocesofecha, ter.finprocesofecha, ter.parcial, us.usuario, ter.obs_terminacion, pr.siglas, es.estado, es.id_estado
FROM pedidos pe 
INNER JOIN procesos pr ON pe.procesos=pr.idproceso
INNER JOIN terminacion ter ON pe.idpedido=ter.pedido
INNER JOIN usuario us on pe.usuario=us.idusuario
INNER JOIN estado es ON ter.estado=es.id_estado WHERE ter.idterminacion=$idterminacion");

$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('location listaterminaciongeneral.php');
} else {
    $data = mysqli_fetch_array($sql);
    $id = $data['idterminacion'];
    $pedido = $data['num_pedido'];
    $cliente= $data['cliente'];
    $iniciopedido = $data['iniciopedido'];
    $finpedido = $data['finpedido'];
    $siglas = $data['siglas'];
    $unds = $data['unds'];
    $inicioterminacion = $data['inicioterminacion'];
    $finterminacion = $data['finterminacion'];
    $parcial = $data['parcial'];
    $obs = $data['obs_terminacion'];
    $estado = $data['estado'];
    $idestado = $data['id_estado'];
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

            <a href="listaterminaciongeneral.php" class="btn_new" style="position:fixed ; top:150px; left: 0px;">General</a>
            <a href="reporte_terminacion.php" class="btn_new" style="position:fixed ; top:150px; left: 200px;">Reporte</a>


            <form action="" method="post">
                <h1>Actualización de Pedido</h1>
                <hr>
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

                <div>
                    <input type="hidden" name="idterminacion" id="idterminacion" value="<?php echo $idterminacion; ?>">
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
                        <h3 style="text-transform: uppercase; ">Info terminación</h3>

                        <div>
                            Fecha Inicio: <?php echo $inicioterminacion ?>
                        </div>
                        <div>
                            Fecha Entrega: <?php echo $finterminacion ?>
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