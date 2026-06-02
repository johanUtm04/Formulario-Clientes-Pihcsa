<?php 
require_once ('conexion.php');

$username = "nuevo_usuario";
$nombre = "Nuevo admin";
$paswoordPlano = "123456";

$hash = password_hash($paswoordPlano, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios_admin (username, password, nombre)
        VALUES ('$username', '$hash', '$nombre')";

if (mysqli_query($conexion, $sql)) {
    echo "Usuario creado correctamente";
} else {
    echo "Error: " . mysqli_error($conexion);
}