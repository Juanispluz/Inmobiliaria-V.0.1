<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Publicar Nueva Propiedad</title>
</head>
<body>
    <h1>Publicar Nueva Propiedad</h1>
    <form action="../php/guardar.php" method="POST" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" min="0" step="0.01" required>

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" required>

        <input type="submit" value="Publicar">
    </form>
</body>
</html>