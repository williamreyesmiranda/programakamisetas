<?php
session_start();
if($_SESSION['idrol']!=1){
    header('location: ../');
}
include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['identificacion']) || empty($_POST['usuario'])
        || empty($_POST['rol'])){
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{
            
            $idusuario=$_POST['idusuario'];
            $nombre = $_POST['nombre'];
            $identificacion= $_POST['identificacion'];
            $usuario= $_POST['usuario'];
            $clave= md5($_POST['clave']);
            $rol=$_POST['rol'];

            $query =mysqli_query($conexion,"SELECT * FROM usuario
                                                     WHERE usuario='$usuario' AND idusuario !='$idusuario'");
            $result =mysqli_fetch_array($query);

            if ($result>0){
                $alert='<p class="msg_error">El usuario ya existe</p>';
            }else{
                if(empty($_POST['clave']))
                {
                    $sql_update = mysqli_query($conexion, "UPDATE usuario SET nombre= '$nombre', cedula='$identificacion',
                                            usuario='$usuario', rol='$rol'
                                            WHERE idusuario=$idusuario");
                }else{
                    $sql_update = mysqli_query($conexion, "UPDATE usuario SET nombre= '$nombre', cedula='$identificacion',
                                            usuario='$usuario', clave='$clave', rol='$rol'
                                            WHERE idusuario=$idusuario");
                }
                

               if($sql_update){
                    $alert='<p class="msg_save">Usuario Actualizado Correctamente</p>';
                }else{
                    $alert='<p class="msg_error">Error al Actualizar el Usuario</p>';
                }       
            }
        }
    }

//MOSTRAR DATOS

if (empty($_GET['id'])){
    header('location lista_administrador.php');
}
$iduser= $_GET['id'];

$sql= mysqli_query($conexion,"SELECT u.idusuario, u.nombre, u.usuario, u.cedula, u.clave, u.rol as 'idrol', r.rol  as 'rol'FROM usuario u 
INNER JOIN rol r  on u.rol = r.idrol  WHERE u.idusuario=$iduser");

$result_sql=mysqli_num_rows($sql);

if ($result_sql==0){
    header('location administrador.php');
}else{
    while($data= mysqli_fetch_array($sql)){
        $iduser= $data['idusuario'];
        $nombre= $data['nombre'];
        $identificacion=$data['cedula'];
        $clave=$data['clave'];
        $usuario=$data['usuario'];
        $idrol=$data['idrol'];
        $rol=$data['rol'];
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"?>
    <title>ACTUALIZACION USUARIO</title>
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

    <div class="form-register">
        
<a href="lista_usuarios.php" class="btn_new" style="position:fixed ; top:150px; left: 0;">Lista Usuarios</a>
<a href="lista_entidades.php" class="btn_new" style="position:fixed ; top:250px; left: 0;">Lista Entidades</a>
<a href="lista_estudios.php" class="btn_new" style="position:fixed ; top:350px; left: 0;">Lista Estudios</a>
<a href="eliminar_citas.php" class="btn_new" style="position:fixed ; top:450px; left: 0;">Eliminar Citas</a>
<a href="menu.php" class="btn_new" style="position:fixed ; top:550px; left: 0;">Editar Menú</a>
  

        <form action="" method="post">
       <h1>Actualización de Usuarios</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>
        <div>
            <input type="hidden" name="idusuario" id="idusuario" value="<?php echo $iduser;?>">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" placeholder="Ingrese Nombre y Apellido" autocomplete="off" value="<?php echo $nombre;?>">
        </div>
        <div>
            <label for="identificacion">Identificación</label>
            <input type="text" name="identificacion" id="identidicacion" placeholder="Ingrese N° Identificación" autocomplete="off" value="<?php echo $identificacion;?>">
        </div>
        <div>
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" id="nombre" placeholder="Ingrese el Usuario" autocomplete="off" value="<?php echo $usuario;?>">
        </div>
        <div>
            <label for="clave">Clave</label>
            <input type="password" name="clave" id="nombre" placeholder="Renovar Clave de Acceso (opcional)" autocomplete="off" value="">
        </div>
        <div>
            
            <?php
            $query_rol =mysqli_query($conexion,"SELECT * FROM rol ");
            $result_rol = mysqli_num_rows($query_rol);
            
            if($idrol==1){

            }else{
            ?>
            <label for="rol">Tipo de Usuario</label>
          <select name="rol" id="" class="itemunico">
              <?php echo "  <option value=\"".$idrol."\">".$rol."</option>";?>
          <option value="0" disabled >Selecciones una Opcion</option>  
            <?php 
           
            if ($result_rol>0){
                while($rol =mysqli_fetch_array($query_rol)){
                    
                  echo "  <option value=\"".$rol['idrol']."\">".$rol['rol']."</option>";
            }
            } ?>
          
           
          </select>
          <?php } ?>
        </div>
        <input type="submit" value="Editar Usuario" class="btn-save">

        </form>


    </div>


</section>
	
</body>
</html>