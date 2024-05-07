<?php
session_start();
require 'conexion.php'; // Utilizar un archivo separado para la conexión

// Asegurarse de que los datos necesarios están presentes
if (!isset($_POST['email'], $_POST['contrasena'])) {
    echo "Todos los campos son necesarios.";
    exit;
}

$email = $_POST['email'];
$contrasena = $_POST['contrasena'];

// Preparar la consulta SQL para evitar inyecciones SQL
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();

    // Verificar la contraseña
    if (password_verify($contrasena, $usuario['contrasena'])) {
        // Establecer la variable de sesión para el usuario
        $_SESSION['usuario'] = $usuario['id']; // Asume que 'id' es la columna que contiene el ID del usuario

        // Redireccionar al usuario a la página principal
        header("Location: ../index.php");
        exit;
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}

// Cerrar la conexión
$conexion->close();
?>
