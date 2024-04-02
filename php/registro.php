<?php
// Incluir el archivo de conexión a la base de datos
require_once 'conexion.php';

// Verificar si se han enviado datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Insertar el nuevo usuario en la base de datos
    $query = "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Redirigir al usuario a una página de éxito o mostrar un mensaje de éxito
    header("Location: ../index.html");
    exit();
} else {
    // Si no se recibieron datos por POST, redirigir a la página de registro
    header("Location: registro.html");
    exit();
}
?>
