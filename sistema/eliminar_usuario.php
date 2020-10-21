<?php
session_start();
if ($_SESSION['idrol'] != 1) {
    header('location: ../');
}
include "../conexion.php";

if (!empty($_POST)) {
    $idusuario = $_POST['idusuario'];
    $query_delete = mysqli_query($conexion, "UPDATE usuario SET estatus= 0 
                                        WHERE idusuario=$idusuario ");

    if ($query_delete) {
        header("location: lista_usuarios.php");
        
    } else {
        $alert = '<p class="msg_error">Error al eliminar el registro</p>';
    }
}


if (empty($_REQUEST['id'])) {
    header("location: lista_usuarios.php");
} else {

    $idusuario = $_REQUEST['id'];

    $query = mysqli_query($conexion, "SELECT u.cedula, u.nombre, u.usuario, r.rol
                                            FROM usuario u
                                            INNER JOIN rol r
                                            ON u.rol = r.idrol
                                            WHERE u.idusuario= $idusuario");
    $result = mysqli_num_rows($query);

    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $identificacion = $data['cedula'];
            $nombre = $data['nombre'];
            $usuario = $data['usuario'];
            $rol = $data['rol'];
        }
    } else {
        header("location: lista_usuarios.php");
    }
}

?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <?php include "includes/scripts.php" ?>
    <title>ARCHIVAR REGISTRO</title>
    <link rel="shortcut icon" href="../img/cedimed-icono.png" type="image/x-icon">
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

        <a href="lista_usuarios.php" class="btn_new" style="position:fixed ; top:150px; left: 0;">Lista Usuarios</a>
        <a href="lista_entidades.php" class="btn_new" style="position:fixed ; top:250px; left: 0;">Lista Entidades</a>
        <a href="lista_estudios.php" class="btn_new" style="position:fixed ; top:350px; left: 0;">Lista Estudios</a>
        <a href="eliminar_citas.php" class="btn_new" style="position:fixed ; top:450px; left: 0;">Eliminar Citas</a>
        <a href="menu.php" class="btn_new" style="position:fixed ; top:550px; left: 0;">Editar Menú</a>

        <div class="data_delete">
            <h1>Nota: El usuario del empleado quedará inactivo</h1>
            <form action="" method="post">
                <h2>¿Está Seguro que desea archivar el siguiente registro?</h2>
                <hr>
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
                <p>Identificación: <span><?php echo $identificacion ?></span></p>
                <p>Nombre: <span><?php echo $nombre ?></span></p>
                <p>Usuario: <span><?php echo $usuario ?></span></p>
                <p>Área: <span><?php echo $rol ?></span></p>

                <input type="hidden" name="idusuario" id="idusuario" value="<?php echo $idusuario; ?>">
                <input type="submit" value="Confirmar" class="btn_ok glyphicon glyphicon-remove">

                <a href="lista_usuarios.php" class="btn_cancel"><span>Cancelar</span> </a>

            </form>
        </div>

    </section>

</body>

</html>