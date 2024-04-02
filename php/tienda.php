<?php
// Incluir el archivo de conexión a la base de datos
require_once 'conexion.php';

// Consulta SQL base para seleccionar todos los productos
$query = "SELECT * FROM productos";

// Verificar si se ha enviado una categoría por GET
if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
    $categoria_seleccionada = $_GET['categoria'];
    // Agregar condición de categoría a la consulta SQL
    $query .= " WHERE categoria = :categoria";
}

// Verificar si se ha enviado una opción de ordenamiento por GET
if (isset($_GET['orden']) && !empty($_GET['orden'])) {
    $orden_seleccionado = $_GET['orden'];
    // Agregar ordenamiento a la consulta SQL
    $query .= " ORDER BY precio $orden_seleccionado";
}

// Ejecutar la consulta SQL
$stmt = $conn->prepare($query);

if (isset($categoria_seleccionada)) {
    // Vincular parámetro de categoría si está definido
    $stmt->bindParam(':categoria', $categoria_seleccionada);
}

$stmt->execute();

// Obtener todas las categorías disponibles
$categorias_query = "SELECT DISTINCT categoria FROM productos";
$categorias_stmt = $conn->query($categorias_query);
$categorias = $categorias_stmt->fetchAll(PDO::FETCH_COLUMN);

// Mostrar el formulario de filtro por categoría y ordenamiento por precio
echo '<form action="tienda.php" method="GET">';
echo '<label for="categoria">Filtrar por categoría:</label>';
echo '<select id="categoria" name="categoria">';
echo '<option value="">Todos</option>';
foreach ($categorias as $categoria) {
    // Marcar como seleccionada la categoría actual
    $selected = isset($_GET['categoria']) && $_GET['categoria'] == $categoria ? 'selected' : '';
    echo '<option value="' . $categoria . '" ' . $selected . '>' . $categoria . '</option>';
}
echo '</select>';

echo '<label for="orden">Ordenar por precio:</label>';
echo '<select id="orden" name="orden">';
echo '<option value="ASC">Menor a mayor</option>';
echo '<option value="DESC">Mayor a menor</option>';
echo '</select>';

echo '<input type="submit" value="Aplicar">';
echo '</form>';

// Verificar si se encontraron productos
if ($stmt->rowCount() > 0) {
    // Mostrar los productos en la página
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="producto">';
        echo '<img src="../imagenes/' . $row['imagen'] . '" alt="' . $row['nombre'] . '">';
        echo '<h3>' . $row['nombre'] . '</h3>';
        echo '<p>Categoría: ' . $row['categoria'] . '</p>'; // Mostrar la categoría
        echo '<p>Precio: $' . $row['precio'] . '</p>';
        echo '<p>Disponibles: ' . $row['disponibles'] . '</p>';
        echo '<p>' . $row['descripcion'] . '</p>';
        echo '<form action="agregar_al_carrito.php" method="POST">';
        echo '<input type="hidden" name="producto_id" value="' . $row['id'] . '">';
        echo '<input type="submit" value="Agregar al carrito">';
        echo '</form>';
        echo '</div>';
    }
} else {
    // Mostrar un mensaje si no se encontraron productos
    echo 'No se encontraron productos.';
}
?>
