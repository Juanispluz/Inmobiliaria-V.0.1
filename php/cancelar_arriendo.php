<?php
session_start();
require 'conexion.php'; // Archivo de conexión

if (!isset($_SESSION['usuario'])) {
    header("Location: ../html/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuarioId = $_SESSION['usuario'];  // ID del usuario en sesión

    // Preparar la consulta para verificar el arrendador actual
    $stmt = $conexion->prepare("SELECT usuario_arrendador_id FROM propiedades WHERE id = ?");
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($arrendadorId);
    $stmt->fetch();
    $stmt->close();

    // Verificar si el usuario actual es el arrendador de la propiedad
    if ($usuarioId == $arrendadorId) {
        // Preparar consulta para cancelar el arriendo
        $stmt = $conexion->prepare("UPDATE propiedades SET arrendada = FALSE, fecha_arrendada = NULL, usuario_arrendador_id = NULL WHERE id = ?");
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conexion->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conexion->close();

        header("Location: ../index.php");
        exit();
    } else {
        echo "No tienes permisos para cancelar el arriendo de esta propiedad.";
        $conexion->close();
    }
}
?>
