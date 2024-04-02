<?php


// Verificar si se han enviado datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el correo electrónico enviado desde el formulario
    $email = $_POST["email"];

    // Aquí deberías realizar la validación del correo electrónico y buscar el usuario en la base de datos
    // Por ejemplo, puedes usar PDO para conectarte a una base de datos MySQL

    // Incluir el archivo de conexión
    require_once 'conexion.php';

    try {
        // Consulta para buscar el usuario por su correo electrónico
        $query = "SELECT * FROM usuarios WHERE correo = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Verificar si se encontró algún usuario con el correo electrónico proporcionado
        if ($stmt->rowCount() == 1) {
            // Aquí puedes enviar un correo electrónico al usuario con un enlace para restablecer su contraseña
            // Por simplicidad, aquí solo mostraremos un mensaje
           echo('CONTRASENA RECUPERADA ');
        } else {
            // Si no se encontró ningún usuario con el correo electrónico proporcionado, mostrar un mensaje de error
            echo "No se encontró ninguna cuenta asociada a ese correo electrónico.";
        }
    } catch(PDOException $e) {
        // En caso de error, mostrar un mensaje de error
        echo "Error: " . $e->getMessage();
    }
} else {
    // Si no se recibieron datos por POST, redirigir a la página de recuperación de contraseña
    header("Location: recuperar_contrasena.html");
    exit();
}
?>
