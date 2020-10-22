<?php
session_start();
if ($_SESSION['idrol'] != 1) {
    header('location: ../');
}
include "../conexion.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <?php include "includes/scripts.php" ?>

    <title>ADMINISTRADOR</title>
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

        <a href="lista_usuarios.php" class="btn_new" style="position:fixed ; top:200px; left: 0;">Lista Usuarios</a>
        <a href="menu.php" class="btn_new" style="position:fixed ; top:300px; left: 0;">Editar Menú</a>


        <center>
            <div style="width:900px">

                <?php
                if (!empty($_POST)) {
                    $titulo1 = $_POST['titulo1'];
                    $descripcion1 = $_POST['descripcion1'];
                    $update1 = mysqli_query($conexion, "UPDATE menu SET titulo='$titulo1', descripcion='$descripcion1' WHERE id=1");

                    $titulo2 = $_POST['titulo2'];
                    $descripcion2 = $_POST['descripcion2'];
                    $update2 = mysqli_query($conexion, "UPDATE menu SET titulo='$titulo2', descripcion='$descripcion2' WHERE id=2");

                    $titulo3 = $_POST['titulo3'];
                    $descripcion3 = $_POST['descripcion3'];
                    $update3 = mysqli_query($conexion, "UPDATE menu SET titulo='$titulo3', descripcion='$descripcion3' WHERE id=3");

                    $alert = "<p class=\"msg_save\">Se ha actualizado correctamente la información del menú</p>";
                }
                //mostrar datos
                $query1 = mysqli_query($conexion, "SELECT * FROM menu WHERE id=1");
                $data1 = mysqli_fetch_array($query1);
                $query2 = mysqli_query($conexion, "SELECT * FROM menu WHERE id=2");
                $data2 = mysqli_fetch_array($query2);
                $query3 = mysqli_query($conexion, "SELECT * FROM menu WHERE id=3");
                $data3 = mysqli_fetch_array($query3);

                ?>
                <!-- <a href="registro_usuario.php" class="btn_new">Crear Entidad</a> -->
                <br><br>
                <form action="" method="post">
                    <h1>Modificación de Información en el Menú</h1>
                    <hr>
                    <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
                    <div>
                        <h3>
                            <div for="">Información 1</div>
                            <input type="text" name="titulo1" id="titulo1" class="titulo1"
                                value="<?php echo $data1['titulo'] ?>">
                        </h3>
                        <textarea style="text-align: justify;text-transform: none !important;" name="descripcion1"
                            id="descripcion1" cols="30" rows="10"><?php echo $data1['descripcion'] ?></textarea>
                    </div>
                    <div>
                        <h3>
                            <div for="">Información 2</div>
                            <input type="text" name="titulo2" id="titulo2" class="titulo2"
                                value="<?php echo $data2['titulo'] ?>">
                        </h3>
                        <textarea style="text-align: justify;text-transform: none !important;" name="descripcion2"
                            id="descripcion2" cols="30" rows="10"><?php echo $data2['descripcion'] ?> </textarea>
                    </div>
                    <div>
                        <h3>
                            <div for="">Información 3</div>
                            <input type="text" name="titulo3" id="titulo3" class="titulo3"
                                value="<?php echo $data3['titulo'] ?>">
                        </h3>
                        <textarea style="text-align: justify;text-transform: none !important;" name="descripcion3"
                            id="descripcion3" cols="30" rows="10"><?php echo $data3['descripcion'] ?></textarea>
                    </div>


                    <input type="submit" value="Modificar" class="btn-save">



                </form>
            </div>
        </center>

    </section>
    <script type="text/javascript">
    $(document).ready(function() {
        $('.fecha').val();



        $('.fecha').change(function() {
            cargarrangofecha();


        });

    })
    </script>


    <script type="text/javascript">
    function cargarrangofecha() {
        $.ajax({
            type: "POST",
            url: "php/cargar_rangofechas.php",
            data: "fecha=" + $('.fecha').val(),
            success: function(r) {
                $('#citassaeliminar').html(r);

            }
        });

    }
    </script>
    <!-- ************************************* -->
    <script>
    function openCorte(atributo, menutab) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(menutab).style.display = "block";
        atributo.currentTarget.className += " active";
    }
    document.getElementById("defaultOpen").click();
    </script>

    <script>
    $('#tablausuario,#tablaentidades,#tablaestudios').DataTable({
        "order": [
            [1, "asc"]
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
    })
    </script>



</body>

</html>