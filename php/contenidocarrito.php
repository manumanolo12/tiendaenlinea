<?php
session_start();

require_once 'conexion.php'; // Incluir el archivo de conexión a la base de datos

// Verificar si el usuario ha iniciado sesión y obtener su ID de usuario desde la sesión
if (isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    // Consultar el contenido del carrito del usuario
    $query = "SELECT productos.nombre, productos.precio, carrito.cantidad
              FROM carrito
              INNER JOIN productos ON carrito.id_producto = productos.id
              WHERE carrito.id_usuario = :id_usuario";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

    // Verificar si hay productos en el carrito
    if ($stmt->rowCount() > 0) {
        echo "<h2>Contenido del carrito</h2>";
        echo "<table>";
        echo "<tr><th>Producto</th><th>Precio</th><th>Cantidad</th></tr>";
        $total_pagar = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nombre = $row['nombre'];
            $precio = $row['precio'];
            $cantidad = $row['cantidad'];
            $subtotal = $precio * $cantidad;
            $total_pagar += $subtotal;
            echo "<tr><td>$nombre</td><td>$precio</td><td>$cantidad</td></tr>";
        }
        echo "</table>";
        echo "<h3>Total a pagar: $total_pagar</h3>";
        
        // Botón para realizar la compra
        echo '<form action="realizar_compra.php" method="post">';
        echo '<input type="hidden" name="total_pagar" value="' . $total_pagar . '">';
        echo '<input type="submit" value="Realizar Compra">';
        echo '</form>';
    } else {
        echo "El carrito está vacío.";
    }
} else {
    // Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
    header('Location: login.php');
    exit();
}
?>
