function arrendarPropiedad(id) {
    if (confirm("¿Estás seguro de que quieres arrendar esta propiedad?")) {
        window.location.href = '../php/arrendar.php?id=' + id;
    }
};

function cancelarArriendo(id) {
    if (confirm("¿Estás seguro de que deseas cancelar el arriendo de esta propiedad?")) {
        window.location.href = '../php/cancelar_arriendo.php?id=' + id;
    }
};

function arrendarPropiedad(id) {
console.log(id);  // Esto mostrará el ID en la consola del navegador
    if (confirm("¿Estás seguro de que quieres arrendar esta propiedad?")) {
        window.location.href = '../php/arrendar.php?id=' + id;
    }
};