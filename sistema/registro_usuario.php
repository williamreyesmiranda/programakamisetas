<?php
session_start();
if ($_SESSION['idrol'] != 1) {
    header('location: ../');
}
include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    if (
        empty($_POST['nombre']) || empty($_POST['identificacion']) || empty($_POST['usuario'])
        || empty($_POST['clave'])  || empty($_POST['rol']) || empty($_POST['sexo'])
    ) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
    } else {


        $nombre = $_POST['nombre'];
        $identificacion = $_POST['identificacion'];
        $usuario = $_POST['usuario'];
        $clave = $_POST['clave'];
        $rol = $_POST['rol'];
        $sexo = $_POST['sexo'];


        $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario='$usuario'");
        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert = '<p class="msg_error">El usuario ya existe</p>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO usuario(nombre,cedula,usuario,clave,rol, sexo, estatus)
                                                            value('$nombre','$identificacion','$usuario','$clave','$rol','$sexo',1)");
            if ($query_insert) {
                $alert = '<p class="msg_save">Usuario Creado Correctamente</p>';
            } else {
                $alert = '<p class="msg_error">Error al crear el usuario</p>';
            }
        }
    }
}



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <?php include "includes/scripts.php" ?>
    <title>REGISTRO EMPLEADOS</title>
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
            <a href="lista_usuarios.php" class="btn_new" style="position:fixed ; top:200px; left: 0;">Lista Usuarios</a>
            <a href="menu.php" class="btn_new" style="position:fixed ; top:300px; left: 0;">Editar Menú</a>



            <form action="" method="post">
                <h1>Registro de Empleados</h1>
                <hr>
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
                <div>
                    <label for="nombre">Nombre Empleado</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Ingrese Nombre y Apellido"
                        autocomplete="off" value="<?php if(!empty($_POST)){ echo $_POST['nombre'];}?>">
                </div>
                <div>
                    <label for="identificacion">Identificación</label>
                    <input type="text" name="identificacion" id="identidicacion"
                        value="<?php if(!empty($_POST)){ echo $_POST['identificacion'];}?>"
                        placeholder="Ingrese N° Identificación" autocomplete="off">
                </div>
                <div>
                    <label for="sexo">Sexo</label>
                    <select name="sexo" id="sexo">
                        <option disabled selected value="">Seleccione una Opción</option>
                        <option value="hombre">Hombre</option>
                        <option value="mujer">Mujer</option>
                    </select>
                </div>
                <div>
                    <label for="usuario">Usuario</label>
                    <input type="text" name="usuario" id="usuario"
                        value="<?php if(!empty($_POST)){ echo $_POST['usuario'];}?>" placeholder="Ingrese el Usuario"
                        autocomplete="off">
                </div>
                <div>
                    <label for="clave">Clave</label>
                    <input type="password" name="clave" id="clave"
                        value="<?php if(!empty($_POST)){ echo $_POST['clave'];}?>" placeholder="Clave de Acceso"
                        autocomplete="off">
                </div>
                <div>
                    <label for="rol">Cargo</label>
                    <?php
                    $query_rol = mysqli_query($conexion, "SELECT * FROM rol ");
                    $result_rol = mysqli_num_rows($query_rol);

                    ?>
                    <select name="rol" id="" class="select2">
                        <option value="0" disabled selected>Seleccione una Opcion</option>
                        <?php
                        if ($result_rol > 0) {
                            while ($rol = mysqli_fetch_array($query_rol)) {

                                echo "  <option value=\"" . $rol['idrol'] . "\">" . $rol['rol'] . "</option>";
                            }
                        } ?>


                    </select>
                </div>
                <input type="submit" value="Crear Usuario" class="btn-save">

            </form>


        </div>


    </section>

</body>

</html>