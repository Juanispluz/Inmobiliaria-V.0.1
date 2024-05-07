<?php
session_start();

require 'conexion.php'; // Se asume que la conexión está en un archivo separado

// Asegurarse de que se recibieron todos los datos necesarios
if (isset($_POST['nombre'], $_POST['email'], $_POST['confirmar_email'], $_POST['contrasena'], $_POST['confirmar_contrasena'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $confirmar_email = $_POST['confirmar_email'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Validar que el correo electrónico y la contraseña coincidan
    if ($email !== $confirmar_email) {
        echo "Los correos electrónicos no coinciden.";
        exit();
    }

    if ($contrasena !== $confirmar_contrasena) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    // Hash de la contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Preparar la consulta SQL para evitar inyecciones SQL
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
    if ($stmt === false) {
        echo "Error al preparar la consulta: " . $conexion->error;
        exit();
    }

    $stmt->bind_param("sss", $nombre, $email, $contrasena_hash);
    if ($stmt->execute()) {
        echo "Usuario registrado correctamente.";
    } else {
        echo "Error al registrar el usuario: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Todos los campos son necesarios.";
}

// Cerrar la conexión
$conexion->close();
?>


<a href="../html/login.php">Iniciar Sesión</a>