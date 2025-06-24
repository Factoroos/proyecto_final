<?php
$host = "localhost";
$user = "jorge_candia";
$pass = "jorge_candia2025";
$db   = "jorge_candia_db1";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar errores
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer codificación a UTF-8
$conn->set_charset("utf8");
?>

