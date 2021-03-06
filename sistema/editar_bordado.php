<?php
session_start();

include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    date_default_timezone_set('America/Bogota');


    $idbordado = $_POST['idbordado'];
    $logo = $_POST['logo'];
    $pte_diseno = $_POST['pte_diseno'];
    $num_bordado = $_POST['num_bordado'];
    $muestra = $_POST['muestra'];
    $punt_unidad = $_POST['punt_unidad'];
    $parcial = $_POST['parcial'];
    $obs = $_POST['obs'];
    $estado = $_POST['estado'];
    $usuario = $_SESSION['iduser'];
    $inicioproceso = date('Y-m-d');
    $sql = mysqli_query($conexion, "SELECT * FROM bordado WHERE estado<1 and idbordado=$idbordado");
    $sql_result = mysqli_num_rows($sql);


    if ($sql_result > 0) {
        $sql_update = mysqli_query($conexion, "UPDATE bordado SET parcial= '$parcial', obs_bordado='$obs',
                                            usuario='$usuario', estado='$estado', inicioprocesofecha='$inicioproceso', logo='$logo', pte_diseno='$pte_diseno', num_bordado='$num_bordado', muestra='$muestra', punt_unidad='$punt_unidad'
                                            WHERE idbordado=$idbordado ");
        $sql = mysqli_query($conexion, "SELECT * FROM bordado WHERE idbordado=$idbordado");
        $sqlselect = mysqli_fetch_array($sql);
        $idpedido = $sqlselect['pedido'];
        $sql_estado = mysqli_query($conexion, "UPDATE pedidos SET estado=1 WHERE idpedido='$idpedido'");
    } else {

        $sql_update = mysqli_query($conexion, "UPDATE bordado SET parcial= '$parcial', obs_bordado='$obs',
                                            usuario='$usuario', estado='$estado',  logo='$logo', pte_diseno='$pte_diseno', num_bordado='$num_bordado', muestra='$muestra', punt_unidad='$punt_unidad'
                                            WHERE idbordado=$idbordado");
        $sql = mysqli_query($conexion, "SELECT * FROM bordado WHERE idbordado=$idbordado");
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
    header('location listabordadogeneral.php');
}
$idbordado = $_GET['id'];

$sql = mysqli_query($conexion, "SELECT pe.num_pedido, pe.cliente, pe.asesor, pe.fecha_inicio as 'iniciopedido', 
pe.fecha_fin as 'finpedido', pe.dias_habiles as 'diaspedido', pe.unds, pe.fecha_ingreso, pe.usuario,
bor.idbordado, bor.iniciofecha as 'iniciobordado', bor.finfecha as 'finbordado', bor.dias as 'diasbordado',
bor.inicioprocesofecha, bor.finprocesofecha, bor.parcial, us.usuario, bor.obs_bordado, pr.siglas, es.estado, es.id_estado,
bor.logo, bor.pte_diseno, bor.num_bordado, bor.muestra, bor.punt_unidad
FROM pedidos pe 
INNER JOIN procesos pr ON pe.procesos=pr.idproceso
INNER JOIN bordado bor ON pe.idpedido=bor.pedido
INNER JOIN usuario us on pe.usuario=us.idusuario
INNER JOIN estado es ON bor.estado=es.id_estado WHERE bor.idbordado=$idbordado");

$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('location listabordadogeneral.php');
} else {
    $data = mysqli_fetch_array($sql);
    $logo=$data['logo'];
    $pte_diseno=$data['pte_diseno'];
    $num_bordado=$data['num_bordado'];
    $muestra=$data['muestra'];
    $punt_unidad=$data['punt_unidad'];
    $id = $data['idbordado'];
    $pedido = $data['num_pedido'];
    $cliente= $data['cliente'];
    $iniciopedido = $data['iniciopedido'];
    $finpedido = $data['finpedido'];
    $siglas = $data['siglas'];
    $unds = $data['unds'];
    $iniciobordado = $data['iniciobordado'];
    $finbordado = $data['finbordado'];
    $parcial = $data['parcial'];
    $obs = $data['obs_bordado'];
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

            <a href="listabordadogeneral.php" class="btn_new" style="position:fixed ; top:150px; left: 0px;">General</a>
            <a href="reporte_bordado.php" class="btn_new" style="position:fixed ; top:150px; left: 200px;">Reporte</a>


            <form action="" method="post">
                <h1>Actualización de Pedido</h1>
                <hr>
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

                <div>
                    <input type="hidden" name="idbordado" id="idbordado" value="<?php echo $idbordado; ?>">
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
                        <h3 style="text-transform: uppercase; ">Info Bordado</h3>

                        <div>
                            Fecha Inicio: <?php echo $iniciobordado ?>
                        </div>
                        <div>
                            Fecha Entrega: <?php echo $finbordado ?>
                        </div>
                        <div>                            
                            <label for="logo">Logo</label>
                            <input style="width: 300px" type="text" name="logo" id="logo" autocomplete="off" value="<?php echo $logo; ?>">
                        </div>
                        <div>                            
                            <label for="pte_diseno">Pte diseño</label>
                            <input style="width: 300px" type="text" name="pte_diseno" id="pte_diseno" autocomplete="off" value="<?php echo $pte_diseno; ?>">
                        </div>
                        <div>                            
                            <label for="num_bordado">Numero bordado</label>
                            <input style="width: 300px" type="number" name="num_bordado" id="num_bordado" autocomplete="off" value="<?php echo $num_bordado; ?>">
                        </div>
                        <div>                            
                            <label for="muestra">Muestra</label>
                            <input style="width: 300px" type="text" name="muestra" id="muestra" autocomplete="off" value="<?php echo $muestra; ?>">
                        </div>
                        <div>                            
                            <label for="punt_unidad">Puntada unidad</label>
                            <input style="width: 300px" type="number" name="punt_unidad" id="punt_unidad" autocomplete="off" value="<?php echo $punt_unidad; ?>">
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