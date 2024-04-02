// Función para filtrar los productos por categoría
function filtrarPorCategoria(categoria) {
    $.ajax({
        url: 'filtrar_productos.php',
        type: 'GET',
        data: { categoria: categoria },
        success: function(response) {
            $('#lista-productos').html(response);
        }
    });
}

// Función para ordenar los productos
function ordenarProductos(orden) {
    $.ajax({
        url: 'ordenar_productos.php',
        type: 'GET',
        data: { orden: orden },
        success: function(response) {
            $('#lista-productos').html(response);
        }
    });
}
