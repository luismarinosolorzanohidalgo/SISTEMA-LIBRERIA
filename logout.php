<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = [];
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cerrando sesión...</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: linear-gradient(135deg, #e0eafc, #cfdef3);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body>
<script>
Swal.fire({
  icon: 'success',
  title: 'Sesión cerrada correctamente',
  text: '¡Vuelve pronto a Librería JK!',
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: true
}).then(() => {
  window.location = "login.php";
});
</script>
</body>
</html>
