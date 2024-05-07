<?php
session_start();
require 'conexion.php'; // Incluir el archivo de conexión

if (!isset($_SESSION['usuario'])) {
    header("Location: ../html/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuarioId = $_SESSION['usuario'];

    // Preparar la consulta para actualizar el estado de la propiedad
    $query = "UPDATE propiedades SET arrendada = TRUE, usuario_arrendador_id = ?, fecha_arrendada = NOW() WHERE id = ?";
    $stmt = $conexion->prepare($query);
    
    // Verificar si la preparación fue exitosa
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    // Asociar los parámetros y ejecutar la consulta
    $stmt->bind_param("ii", $usuarioId, $id);
    if (!$stmt->execute()) {
        die("Error al ejecutar la consulta: " . $stmt->error);
    }

    // Cerrar el statement y la conexión
    $stmt->close();
    $conexion->close();

    // Redirigir de vuelta a la página de detalles
    header("Location: ../html/detalles.php?id=" . $id);
    exit();
}
?>

