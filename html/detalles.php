<?php
session_start();
require '../php/conexion.php'; // Incluir el archivo de conexión

// Obtener el ID de la propiedad desde la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Usa intval para asegurar que el ID es un entero

if ($id == 0) {
    echo "ID de propiedad inválido.";
    exit; // Finaliza la ejecución si el ID es inválido
}

// Preparar la consulta SQL para obtener los detalles de la propiedad
$query = "SELECT *, usuarios.nombre as usuario_nombre FROM propiedades 
          LEFT JOIN usuarios ON propiedades.usuario_arrendador_id = usuarios.id
          WHERE propiedades.id = ?";
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
    exit; // Finaliza la ejecución si no se encuentra la propiedad
}

$propiedad = $resultado->fetch_assoc();
$stmt->close();
$conexion->close();

// Aquí podrías continuar con el procesamiento de $propiedad, por ejemplo, mostrándola en una página web.
?>


<!DOCTYPE html>
<html>
<head>
    <title>Detalles de la Propiedad</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($propiedad['titulo']); ?></h1>
    <img src="<?php echo '../imagenes/' . htmlspecialchars(basename($propiedad['imagen'])); ?>" alt="Imagen de la propiedad" width="400">
    <p><?php echo htmlspecialchars($propiedad['descripcion']); ?></p>
    <p>Precio: $<?php echo htmlspecialchars($propiedad['precio']); ?></p>
    <?php if (!$propiedad['arrendada']): ?>
        <button onclick="arrendarPropiedad(<?php echo $propiedad['id']; ?>)">Arrendar</button>
    <?php else: ?>
        <p>Arrendada desde <?php echo date('d-m-Y', strtotime($propiedad['fecha_arrendada'])); ?></p>
        <?php if (isset($_SESSION['usuario']) && $propiedad['usuario_arrendador_id'] == $_SESSION['usuario']): ?>
            <button onclick="cancelarArriendo(<?php echo $propiedad['id']; ?>)">Cancelar Arriendo</button>
        <?php endif; ?>
    <?php endif; ?>
    <a href="../index.php">Volver al Listado</a>

    <script src="../js/detalles.js"></script>
</body>
</html>
