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
    <title>Registrarse</title>
</head>
<body>
    <h1>Registrarse</h1>
    <form action="../php/procesar_registro.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="confirmar_email">Confirmar Email:</label>
        <input type="email" id="confirmar_email" name="confirmar_email" required>

        <br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>
        <input type="checkbox" onclick="mostrarContrasena()"> Mostrar contraseña

        <br>

        <label for="confirmar_contrasena">Confirmar Contraseña:</label>
        <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
        <input type="checkbox" onclick="mostrarConfirmarContrasena()"> Mostrar contraseña

        <br>

        <input type="submit" value="Registrarse">
    </form>
    <a href="login.php">Iniciar Sesión</a>

    <script src="../js/registo.js"></script>
</body>
</html>