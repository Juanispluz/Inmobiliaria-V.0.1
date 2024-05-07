<?php
// Parámetros de conexión
$servidor = "localhost"; // Servidor de la base de datos
$usuario = "root"; // Usuario de la base de datos
$contrasena = ""; // Contraseña del usuario de la base de datos
$base_datos = "inmobiliaria"; // Nombre de la base de datos

// Crear la conexión
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
echo "Conexión exitosa";
?>
