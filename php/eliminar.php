<?php
session_start();
require 'conexion.php'; // Asegúrate de que la ruta está correcta

if (!isset($_SESSION['usuario'])) {
    header("Location: ../html/login.php"); // Asegúrate de que la ruta de redireccionamiento está correcta
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuarioId = $_SESSION['usuario'];

    // Verificar si el usuario actual es el publicador de la propiedad
    $stmt = $conexion->prepare("SELECT usuario_publicador_id FROM propiedades WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($usuario_publicador_id);
    $stmt->fetch();
    $stmt->close();

    if ($usuarioId != $usuario_publicador_id) {
        echo "No tienes permisos para eliminar esta propiedad.";
        exit;
    }

    // Eliminar la propiedad
    $stmt = $conexion->prepare("DELETE FROM propiedades WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Propiedad eliminada correctamente.";
    } else {
        echo "Error al eliminar la propiedad: " . $stmt->error;
    }
    $stmt->close();
    $conexion->close();

    header("Location: ../index.php"); // Asegúrate de que la ruta de redireccionamiento está correcta
    exit();
} else {
    echo "ID de propiedad no proporcionado.";
}
?>
<a href="../index.php">Volver al Listado</a>