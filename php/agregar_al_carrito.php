<?php
session_start();

require_once 'conexion.php'; // Aquí incluye el archivo de conexión a la base de datos

// Verificar si se ha enviado un ID de producto válido por POST
if (isset($_POST['producto_id']) && is_numeric($_POST['producto_id'])) {
    $producto_id = $_POST['producto_id'];
    
    // Verificar si el ID de usuario en la sesión es válido
    if (isset($_SESSION['id_usuario']) && is_numeric($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];

        // Verificar si el producto ya está en el carrito del usuario
        $query = "SELECT id FROM carrito WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $producto_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Si el producto ya está en el carrito, actualizar la cantidad
            $query = "UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
        } else {
            // Si el producto no está en el carrito, agregarlo con una cantidad inicial de 1
            $query = "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (:id_usuario, :id_producto, 1)";
        }

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $producto_id, PDO::PARAM_INT);
        $stmt->execute();

        // Redireccionar a la página de donde se hizo la solicitud
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

// Si no se recibió un ID de producto válido o un ID de usuario válido en la sesión, redirigir a la página de inicio
header('Location: index.php');
exit();
?>
