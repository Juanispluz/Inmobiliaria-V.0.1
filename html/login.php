<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <form action="../php/procesar_login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>
        <input type="checkbox" onclick="mostrarContrasena()"> Mostrar contraseña

        <br>

        <input type="submit" value="Iniciar Sesión">
    </form>
    <a href="registro.php">Registrarse</a>

    <script src="../js/login.js"></script>
</body>
</html>