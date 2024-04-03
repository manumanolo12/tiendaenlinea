<?php
session_start();

require_once 'conexion.php'; // Incluir el archivo de conexión a la base de datos

// Verificar si el usuario ha iniciado sesión y obtener su ID de usuario desde la sesión
if (isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    // Verificar si se recibió el total a pagar desde el formulario
    if (isset($_POST['total_pagar'])) {
        $total_pagar = $_POST['total_pagar'];

        // Guardar la compra en la tabla de compras
        $query = "INSERT INTO compras (id_usuario, total_pagar) VALUES (:id_usuario, :total_pagar)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':total_pagar', $total_pagar);
        $stmt->execute();

        // Obtener el ID de la compra recién creada
        $id_compra = $conn->lastInsertId();

        // Obtener el contenido del carrito del usuario
        $query_carrito = "SELECT * FROM carrito WHERE id_usuario = :id_usuario";
        $stmt_carrito = $conn->prepare($query_carrito);
        $stmt_carrito->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt_carrito->execute();

        // Insertar los detalles de la compra en la tabla de detalle_compra
        while ($row = $stmt_carrito->fetch(PDO::FETCH_ASSOC)) {
            $id_producto = $row['id_producto'];
            $cantidad = $row['cantidad'];

            // Obtener el precio unitario del producto
            $query_precio = "SELECT precio FROM productos WHERE id = :id_producto";
            $stmt_precio = $conn->prepare($query_precio);
            $stmt_precio->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt_precio->execute();
            $precio_unitario = $stmt_precio->fetchColumn();

            // Calcular el subtotal
            $subtotal = $precio_unitario * $cantidad;

            // Insertar el detalle de la compra en la tabla detalle_compra
            $query_insert_detalle = "INSERT INTO detalle_compra (id_compra, id_producto, cantidad, precio_unitario, subtotal) 
                                     VALUES (:id_compra, :id_producto, :cantidad, :precio_unitario, :subtotal)";
            $stmt_insert_detalle = $conn->prepare($query_insert_detalle);
            $stmt_insert_detalle->bindParam(':id_compra', $id_compra, PDO::PARAM_INT);
            $stmt_insert_detalle->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt_insert_detalle->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt_insert_detalle->bindParam(':precio_unitario', $precio_unitario);
            $stmt_insert_detalle->bindParam(':subtotal', $subtotal);
            $stmt_insert_detalle->execute();
        }

        // Vaciar el carrito del usuario después de realizar la compra
        $query_vaciar_carrito = "DELETE FROM carrito WHERE id_usuario = :id_usuario";
        $stmt_vaciar_carrito = $conn->prepare($query_vaciar_carrito);
        $stmt_vaciar_carrito->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt_vaciar_carrito->execute();

        // Redirigir a la página de donde se hizo la solicitud
        header('Location: confirmacion_compra.php');
        exit();
    } else {
        // Si no se recibió el total a pagar, redirigir a la página de inicio
        header('Location: index.php');
        exit();
    }
} else {
    // Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
    header('Location: login.php');
    exit();
}
?>
