<?php
// Datos de conexión a la base de datos
$host = "localhost";
$dbname = "ieu";
$username = "root";
$password = "root";

// Intentar establecer la conexión
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configurar PDO para que muestre excepciones en caso de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Establecer el juego de caracteres a UTF-8
    $conn->exec("SET NAMES utf8");
} catch(PDOException $e) {
    // Si no se puede establecer la conexión, mostrar un mensaje de error
    die("Error de conexión: " . $e->getMessage());
}
?>
