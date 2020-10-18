
<?php 
include "../../conexion.php";
 
 $cliente=$_POST['nombrecliente'] ; 
 
$query=mysqli_query($conexion, "SELECT * FROM clientes WHERE nombre='$cliente'");
$valor=mysqli_num_rows($query);
if($valor>0){
    $assoc=mysqli_fetch_assoc($query);
    echo "<label for=\"asesor\">Asesor (Automático)</label>
    <input type=\"text\" name=\"asesor\" id=\"asesor\" disabled value=\"".$assoc['asesor']."\">";
}else{
    echo "<label for=\"asesor\">Asesor (Automático)</label>
    <input type=\"text\" name=\"asesor\" id=\"asesor\" disabled value=\"K-misetas\">";
}


?>