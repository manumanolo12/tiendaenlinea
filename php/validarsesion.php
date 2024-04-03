<?php
session_start();

// Verificar si hay alguna variable de sesión definida
if (!empty($_SESSION)) {
    echo "Valores de las variables de sesión:<br>";
    // Iterar sobre todas las variables de sesión y mostrar sus nombres y valores
    foreach ($_SESSION as $key => $value) {
        echo "$key: $value<br>";
    }
} else {
    echo "No hay variables de sesión definidas actualmente.";
}
?>