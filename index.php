
        <?php
        session_start();
      if (!empty($_SESSION['active'])){
        header('location: sistema/');
      }else{
    
    if (!empty($_POST)){
        $alert = '';
        if (empty($_POST['usuario']) || empty($_POST['clave'])){
           $alert = 'Ingrese su usuario y su clave';
        }else{
            require_once "conexion.php";
    
            $user =mysqli_real_escape_string($conexion,$_POST['usuario']);
            $pass =md5(mysqli_real_escape_string($conexion,$_POST['clave']));
    
            
            
            $query = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.usuario, u.cedula, u.sexo , u.rol as 'idrol' ,r.rol FROM usuario u INNER JOIN rol r on u.rol = r.idrol 
                                         WHERE u.usuario= '$user' AND u.clave= '$pass' AND u.estatus>=1");
            $result= mysqli_num_rows($query);
    
            if ($result >0){
                $data= mysqli_fetch_array($query);
                if($user!='william'){
                    $insert_sesion=mysqli_query($conexion,"INSERT INTO inicio_sesion(usuario, hora) VALUES ('$user', current_timestamp())");
                }
                
               
                $_SESSION['active'] = true;
                $_SESSION['iduser'] = $data['idusuario'];
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['user'] = $data['usuario'];
                $_SESSION['idrol'] = $data['idrol']; 
                $_SESSION['rol'] = $data['rol']; 
                $_SESSION['cedula'] = $data['cedula']; 
                $_SESSION['sexo'] = $data['sexo']; 
                
                header('location: sistema/');
            } else{
                $alert="EL usuario o la clave son incorrectos";
                session_destroy();
            }
        }
      }
    }
     ?>
     
     <!DOCTYPE html>
     <html lang="es">
     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>INICIO DE SESIÓN</title>
         <link rel="shortcut icon" href="img/kamisetas-icono.png" type="image/x-icon">
         
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
         <link rel="stylesheet" href="css/stylee.css">
         
         <style>
         
         </style>
     </head>
     <body>
        <SEction id="container">
    
            <form id="inicio"action="" method="post">
                <h3>Inicio de Sesión del Área Producción</h3>
                <img src="img/kamisetas.png" alt="Login_kamisetas">
    
            <div >
                <span ></span>
                <input type="text" name="usuario" id="usuario" placeholder="Ingrese Usuario" autocomplete="off">
            </div>
            <div>
                 <span></span>
                 <input type="password" name="clave" id="clave" placeholder="Ingrese Contraseña" >
            </div>  
            <center><p class="alert"> <?php echo isset($alert)? $alert:'';?></p></center>
            <div>
               
                <input type="submit" value="INICIAR SESIÓN" id="enviar">
            </div>
       
            </form>
    
        </SEction>
    
    
     </body>
     </html>
        