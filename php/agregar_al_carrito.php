<?php
session_start();

require_once 'conexion.php'; // Aquí incluye el archivo de conexión a la base de datos

if (isset($_POST['producto_id']) && !empty($_POST['producto_id'])) {
    $producto_id = $_POST['producto_id'];
    
    // Obtener el ID del usuario de la sesión (debes tener un sistema de inicio de sesión previo)
    $id_usuario = $_SESSION['id_usuario']; // Suponiendo que guardas el ID del usuario en la sesión

    // Verificar si el producto ya está en el carrito del usuario
    $query = "SELECT id FROM carrito WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':id_producto', $producto_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Si el producto ya está en el carrito, actualizar la cantidad
        // Aquí podrías implementar la lógica para incrementar la cantidad en lugar de simplemente sobrescribirla
        $query = "UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':id_producto', $producto_id);
        $stmt->execute();
    } else {
        // Si el producto no está en el carrito, agregarlo con una cantidad inicial de 1
        $query = "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (:id_usuario, :id_producto, 1)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':id_producto', $producto_id);
        $stmt->execute();
    }

    // Redireccionar a la página de donde se hizo la solicitud
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    // Si no se recibió un ID de producto válido, redirigir a la página de inicio
    header('Location: index.php');
    exit();
}
?>
