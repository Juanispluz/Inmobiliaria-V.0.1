function mostrarContrasena() {
    var contrasenaInput = document.getElementById("contrasena");
    if (contrasenaInput.type === "password") {
        contrasenaInput.type = "text";
    } else {
        contrasenaInput.type = "password";
    }
};

function mostrarConfirmarContrasena() {
    var confirmarContrasenaInput = document.getElementById("confirmar_contrasena");
    if (confirmarContrasenaInput.type === "password") {
        confirmarContrasenaInput.type = "text";
    } else {
        confirmarContrasenaInput.type = "password";
    }
};
