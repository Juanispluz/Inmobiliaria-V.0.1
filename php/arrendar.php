<?php
session_start();
require 'conexion.php'; // Archivo de conexión

if (!isset($_SESSION['usuario'])) {
    header("Location: ../html/login.php");
    exit();
}

$id = $_GET['id'];
$usuarioId = $_SESSION['usuario'];

// Verificar primero si el usuario es el publicador de la propiedad
$stmt = $conexion->prepare("SELECT usuario_publicador_id FROM propiedades WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($usuario_publicador_id);
$stmt->fetch();
$stmt->close();

if ($usuarioId == $usuario_publicador_id) {
    echo "No puedes arrendar tu propia publicación.";
    exit;
}

// Preparar la consulta para actualizar el estado de la propiedad
$query = "UPDATE propiedades SET arrendada = TRUE, usuario_arrendador_id = ?, fecha_arrendada = NOW() WHERE id = ?";
$stmt = $conexion->prepare($query);
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("ii", $usuarioId, $id);
if (!$stmt->execute()) {
    die("Error al ejecutar la consulta: " . $stmt->error);
}

$stmt->close();
$conexion->close();

// Redirigir de vuelta a la página de detalles
header("Location: ../index.php?id=" . $id);
exit();
?>
