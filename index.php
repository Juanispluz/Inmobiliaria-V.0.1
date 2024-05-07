<?php
session_start();
require 'php/conexion.php'; // Archivo de conexión

$usuarioId = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

// Inicia la consulta con la cláusula SELECT
$query = "SELECT propiedades.*, usuarios.nombre AS arrendador_nombre FROM propiedades
          LEFT JOIN usuarios ON propiedades.usuario_arrendador_id = usuarios.id";

// Añade condiciones específicas para el usuario o por defecto para usuarios no logueados
if ($usuarioId) {
    $query .= " WHERE propiedades.usuario_publicador_id = ? OR propiedades.usuario_arrendador_id = ? OR propiedades.arrendada = 0";
} else {
    $query .= " WHERE propiedades.arrendada = 0";
}

// Importante: Añadir la cláusula ORDER BY al final de todas las condiciones
$query .= " ORDER BY propiedades.id DESC";

// Preparar la consulta según sea el caso con o sin usuario logueado
if ($usuarioId) {
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $usuarioId, $usuarioId); // Doble bind_param si el usuario está logueado
} else {
    $stmt = $conexion->prepare($query);
}

// Ejecutar la consulta
$stmt->execute();
$resultado = $stmt->get_result();

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listado de Propiedades</title>
</head>
<body>
    <h1>Propiedades Disponibles</h1>

    <?php if (isset($_SESSION['usuario'])): ?>
        <a href="html/publicar.php">Publicar Nueva Propiedad</a>
        <a href="php/logout.php">Cerrar Sesión</a>
    <?php else: ?>
        <a href="html/login.php">Iniciar Sesión</a>
    <?php endif; ?>

    <?php while ($propiedad = $resultado->fetch_assoc()): ?>
        <div>
            <h2><?php echo htmlspecialchars($propiedad['titulo']); ?></h2>
            <img src="<?php echo 'imagenes/' . htmlspecialchars(basename($propiedad['imagen'])); ?>" alt="Imagen de la propiedad" width="200">
            <p><?php echo htmlspecialchars($propiedad['descripcion']); ?></p>
            <p>Precio: $<?php echo htmlspecialchars($propiedad['precio']); ?></p>
            
            <?php if (!$propiedad['arrendada'] && (!isset($_SESSION['usuario']) || $_SESSION['usuario'] != $propiedad['usuario_publicador_id'])): ?>
                <button onclick="arrendarPropiedad(<?php echo $propiedad['id']; ?>)">Arrendar</button>
            <?php elseif ($propiedad['arrendada']): ?>
                <p>Esta propiedad está arrendada desde <?php echo date('d-m-Y', strtotime($propiedad['fecha_arrendada'])); ?></p>
                <?php if (isset($_SESSION['usuario']) && $propiedad['usuario_arrendador_id'] == $_SESSION['usuario']): ?>
                    <button onclick="cancelarArriendo(<?php echo $propiedad['id']; ?>)">Cancelar Arriendo</button>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['usuario']) && $propiedad['usuario_publicador_id'] == $_SESSION['usuario']): ?>
                <button onclick="eliminarPropiedad(<?php echo $propiedad['id']; ?>)">Eliminar</button>
            <?php endif; ?>
            
            <a href="detalles.php?id=<?php echo $propiedad['id']; ?>">Ver Detalles</a>
        </div>
    <?php endwhile; ?>

    <script src="js/parametros.js"></script>
</body>
</html>
