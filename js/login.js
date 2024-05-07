function mostrarContrasena() {
    var contrasenaInput = document.getElementById("contrasena");
    if (contrasenaInput.type === "password") {
        contrasenaInput.type = "text";
    } else {
        contrasenaInput.type = "password";
    }
};
