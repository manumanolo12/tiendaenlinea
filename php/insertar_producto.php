<?php
// Incluir el archivo de conexión a la base de datos
require_once 'conexion.php';

// Verificar si se han enviado datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $disponibles = $_POST["disponibles"];
    $descripcion = $_POST["descripcion"];
    $categoria = $_POST["categoria"]; // Obtener la categoría del formulario

    // Procesar la imagen
    $imagen_nombre = $_FILES['imagen']['name'];
    $imagen_temp = $_FILES['imagen']['tmp_name'];
    $imagen_tipo = $_FILES['imagen']['type'];

    // Mover la imagen al directorio deseado
    $ruta_imagen = '../imagenes/' . $imagen_nombre;
    move_uploaded_file($imagen_temp, $ruta_imagen);

    // Insertar el nuevo producto en la base de datos
    $query = "INSERT INTO productos (nombre, precio, disponibles, descripcion, imagen, categoria) 
              VALUES (:nombre, :precio, :disponibles, :descripcion, :imagen, :categoria)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':disponibles', $disponibles);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':imagen', $imagen_nombre);
    $stmt->bindParam(':categoria', $categoria); // Vincular la categoría a la consulta
    $stmt->execute();

    // Redirigir al usuario a una página de éxito o mostrar un mensaje de éxito
    header("Location: ../agregar_producto.html");
    exit();
} else {
    // Si no se recibieron datos por POST, redirigir a la página de agregar producto
    header("Location: ../agregar_producto.html");
    exit();
}
?>
