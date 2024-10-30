<?php
session_start();

// Verifica si la clave "Usuario" está definida
if (!isset($_SESSION["Usuario"]) || $_SESSION["Usuario"] == "") {
    // Redirige a login.php si la sesión no está activa
    header("Location: https://the-fuentes-corp-ws1-460518334160.us-central1.run.app/login.php");
    exit(); // Usa exit() para asegurarte de que el script se detenga después de la redirección
}

// El resto de tu código aquí
?>