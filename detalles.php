<?php
session_start();
require 'php/conexion.php'; // Asegúrate de que la ruta es correcta

// Obtener el ID de la propiedad desde la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    echo "ID de propiedad inválido.";
    exit;
}

// Preparar la consulta SQL para obtener los detalles de la propiedad, incluyendo el nombre del publicador
$query = "SELECT p.*, u.nombre as publicador_nombre FROM propiedades p
          LEFT JOIN usuarios u ON p.usuario_publicador_id = u.id
          WHERE p.id = ?";
$stmt = $conexion->prepare($query);
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    echo "No se encontró la propiedad.";
    $stmt->close();
    $conexion->close();
    exit;
}

$propiedad = $resultado->fetch_assoc();
$stmt->close();
$conexion->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalles de la Propiedad</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($propiedad['titulo']); ?></h1>
    <p>Publicado por: <?php echo htmlspecialchars($propiedad['publicador_nombre']); ?></p>
    <img src="<?php echo 'imagenes/' . htmlspecialchars(basename($propiedad['imagen'])); ?>" alt="Imagen de la propiedad" width="400">
    <p><?php echo htmlspecialchars($propiedad['descripcion']); ?></p>
    <p>Precio: $<?php echo htmlspecialchars($propiedad['precio']); ?></p>
    
    <!-- Mostrar botón de arrendar si la propiedad no está arrendada y el usuario actual no es el publicador -->
    <?php if (!$propiedad['arrendada'] && $_SESSION['usuario'] != $propiedad['usuario_publicador_id']): ?>
        <button onclick="arrendarPropiedad(<?php echo $propiedad['id']; ?>)">Arrendar</button>
    <?php endif; ?>

    <!-- Mostrar botón de cancelar arriendo si la propiedad está arrendada y el usuario actual es el arrendador -->
    <?php if ($propiedad['arrendada']): ?>
        <p>Arrendada desde <?php echo date('d-m-Y', strtotime($propiedad['fecha_arrendada'])); ?></p>
        <?php if ($_SESSION['usuario'] == $propiedad['usuario_arrendador_id']): ?>
            <button onclick="cancelarArriendo(<?php echo $propiedad['id']; ?>)">Cancelar Arriendo</button>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Mostrar botón de eliminar solo si el usuario actual es el publicador de la propiedad -->
    <?php if ($_SESSION['usuario'] == $propiedad['usuario_publicador_id']): ?>
        <button onclick="eliminarPropiedad(<?php echo $propiedad['id']; ?>)">Eliminar</button>
    <?php endif; ?>

    <a href="index.php">Volver al Listado</a>

    <script src="js/parametros.js"></script>
</body>
</html>

