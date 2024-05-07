<?php
session_start();
require 'php/conexion.php'; // Incluir el archivo de conexión

// Consulta SQL para obtener las propiedades en orden descendente
$query = "SELECT * FROM propiedades ORDER BY id DESC";
$resultado = $conexion->query($query);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

// Opcional: Procesar los resultados aquí o en otra parte del script
// while ($fila = $resultado->fetch_assoc()) {
//     echo "Propiedad ID: " . $fila['id'] . " - Nombre: " . $fila['nombre'] . "<br>";
// }

// Cerrar la conexión
$conexion->close();
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
            <?php if (!$propiedad['arrendada']): ?>
                <button onclick="arrendarPropiedad(<?php echo $propiedad['id']; ?>)">Arrendar</button>
            <?php else: ?>
                <p>Esta propiedad está arrendada desde <?php echo date('d-m-Y', strtotime($propiedad['fecha_arrendada'])); ?></p>
                <?php if (isset($_SESSION['usuario']) && $propiedad['usuario_arrendador_id'] == $_SESSION['usuario']): ?>
                    <button onclick="cancelarArriendo(<?php echo $propiedad['id']; ?>)">Cancelar Arriendo</button>
                <?php endif; ?>
            <?php endif; ?>
            <a href="html/detalles.php?id=<?php echo $propiedad['id']; ?>">Ver Detalles</a>
        </div>
    <?php endwhile; ?>

    <script src="js/index.js"></script>
</body>
</html>
