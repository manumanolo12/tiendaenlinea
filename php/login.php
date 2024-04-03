<?php
require_once 'conexion.php';
// Verificar si se han enviado datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Aquí deberías realizar la validación de los datos y la autenticación en una base de datos
    // Por ejemplo, puedes usar PDO para conectarte a una base de datos MySQL

    // Ejemplo de conexión PDO a una base de datos MySQL
 
    
    try {
        
        
        // Consulta para verificar las credenciales del usuario
        $query = "SELECT * FROM usuarios WHERE email = :email AND password = :password";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        
        // Comprobar si se encontró algún usuario con las credenciales proporcionadas
        if ($stmt->rowCount() == 1) {
            // Iniciar sesión
            session_start();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION["id_usuario"] = $usuario["id"];
            $_SESSION["email"] = $email;
            // Redirigir al usuario a la página principal de la tienda u otra página de su elección
            header("Location: tienda.php");
            exit();
        } else {
            // Si las credenciales son incorrectas, redirigir de nuevo a la página de inicio de sesión con un mensaje de error
            header("Location: index.php?error=credenciales_invalidas");
            exit();
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Si no se recibieron datos por POST, redirigir a la página de inicio de sesión
    header("Location: index.php");
    exit();
}
?>
