<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "libreria_jk";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// ✅ Fijar zona horaria de Perú
date_default_timezone_set('America/Lima');
?>
