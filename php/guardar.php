<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../html/login.php");
    exit();
}

require 'conexion.php'; // Archivo de conexión

if (!isset($_POST['titulo'], $_POST['descripcion'], $_POST['precio'], $_FILES['imagen'])) {
    echo "Todos los campos son requeridos.";
    exit();
}

$titulo = $conexion->real_escape_string($_POST['titulo']);
$descripcion = $conexion->real_escape_string($_POST['descripcion']);
$precio = $conexion->real_escape_string($_POST['precio']);
$usuario_publicador_id = $_SESSION['usuario']; // Asegúrate de que esta línea esté presente y correcta.

if ($_FILES['imagen']['error'] != 0) {
    echo "Error en el archivo de imagen.";
    exit();
}

$extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
$extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);

if (!in_array(strtolower($extension), $extensiones_permitidas)) {
    echo "Formato de imagen no permitido. Use JPG, JPEG, PNG, o GIF.";
    exit();
}

if (!file_exists('../imagenes')) {
    mkdir('../imagenes', 0755, true);
}

$contadorArchivo = '../utils/contador.txt';
$contador = 1;
if (file_exists($contadorArchivo)) {
    $contador = (int)file_get_contents($contadorArchivo) + 1;
}

$imagen_nombre_seguro = $contador . '.' . $extension;
$ruta_imagen = '../imagenes/' . $imagen_nombre_seguro;

if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen)) {
    file_put_contents($contadorArchivo, $contador);
    $query = "INSERT INTO propiedades (titulo, descripcion, precio, imagen, usuario_publicador_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    if ($stmt) {
        $stmt->bind_param('ssisi', $titulo, $descripcion, $precio, $ruta_imagen, $usuario_publicador_id);
        if ($stmt->execute()) {
            echo "La propiedad se ha publicado correctamente.";
        } else {
            echo "Error al publicar la propiedad: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }
} else {
    echo "Error al cargar la imagen.";
}

$conexion->close();
?>

<a href="../index.php">Volver al Listado</a>