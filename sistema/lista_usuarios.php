<?php
session_start();
if($_SESSION['idrol']!=1){
    header('location: ../');
}
include "../conexion.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <?php include "includes/scripts.php"?>

    <title>ADMINISTRADOR</title>
    <link rel="shortcut icon" href="img/kamisetas-icono.png" type="image/x-icon">
    <style>

    </style>
</head>

<body>
    <?php 

if (empty($_SESSION['active'])){
  header('location: ../');
}
include "includes/header.php"?>
    <section id="container">

        <a href="lista_usuarios.php" class="btn_new" style="position:fixed ; top:200px; left: 0;">Lista Usuarios</a>
        <a href="menu.php" class="btn_new" style="position:fixed ; top:300px; left: 0;">Editar Menú</a>


        <center>
            <div style="width:950px">
                <h1>Lista de Usuarios</h1>

                <a href="registro_usuario.php" class="btn_new" style="position:absolute ; top:190px; left: 90%;">Crear
                    Usuario</a>
                <br><br>
                <table id="tablausuario" class="display">
                    <thead>
                        <tr>

                            <th>Identificación</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Cargo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
            $query=mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.usuario, u.cedula, u.rol as 'idrol', r.rol , u.estatus 
                                            FROM usuario u INNER JOIN rol r on u.rol = r.idrol 
                                            where estatus=1 ORDER BY u.nombre ASC");
            
            $result=mysqli_num_rows($query);

            if ($result>0){
                while($data=mysqli_fetch_array($query)){
                    echo "
                    <tr>
                    <td>".$data['cedula']."</td>
                    <td>".$data['nombre']."</td>
                    <td>".$data['usuario']."</td>
                    <td>".$data['rol']."</td>
                    <td><div>
                    <a class=\"link_edit\"href=\"editar_usuario.php?id=".$data['idusuario']."\"><span class=\"glyphicon glyphicon-edit\" aria-hidden=\"true\"></span></a>";
                    if($data['idrol']!=1){
             echo "<a class=\"link_delete\"href=\"eliminar_usuario.php?id=".$data['idusuario']."\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span></a>";
                    }
                    ?>
                        <?php
                    echo "
                    </div>
                    </td>
                    </tr>
                    ";
                }
            }
            ?>
                    </tbody>


                </table>
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