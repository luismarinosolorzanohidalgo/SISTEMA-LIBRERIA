<?php
session_start();
include 'conexion.php'; // Asegúrate de tener tu conexión mysqli $conn aquí

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $row['rol'];

            echo "<script>
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Bienvenido, $usuario',
                        text: 'Redirigiendo al sistema...',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = 'splash.html';
                    });
                }, 200);
            </script>";
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería JK | Iniciar Sesión</title>
    <link rel="icon" href="img/logo_jk.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(270deg, #1e3a8a, #2563eb, #60a5fa);
            background-size: 600% 600%;
            animation: gradientBG 10s ease infinite;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        .login-box {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 50px 40px;
            width: 400px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            animation: fadeIn 0.8s ease;
        }

        .login-box img {
            width: 120px;
            animation: float 3s ease-in-out infinite;
            margin-bottom: 10px;
        }

        .login-box h2 {
            color: #1e3a8a;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-box p {
            color: #6b7280;
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            font-size: 15px;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.25);
        }

        .btn-login {
            background: linear-gradient(90deg, #2563eb, #1e40af);
            border: none;
            color: white;
            font-weight: 600;
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            margin-top: 15px;
            transition: 0.3s;
        }

        .btn-login:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 18px rgba(37,99,235,0.4);
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .password-container {
            position: relative;
        }

        .password-container i {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
        }

        footer {
            position: absolute;
            bottom: 15px;
            text-align: center;
            color: white;
            font-size: 14px;
            width: 100%;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(15px);}
            to {opacity: 1; transform: translateY(0);}
        }

        @keyframes float {
            0%, 100% {transform: translateY(0);}
            50% {transform: translateY(-8px);}
        }

        @media (max-width: 480px) {
            .login-box {width: 90%; padding: 35px 25px;}
            .login-box img {width: 90px;}
        }
    </style>
</head>
<body>

    <div class="login-box">
        <img src="img/logo_jk.png" alt="Logo Librería JK">
        <h2>Librería JK</h2>
        <p>Acceso al sistema de gestión</p>

        <?php if (!empty($error)): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?= $error ?>',
                    confirmButtonColor: '#2563eb'
                });
            </script>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="usuario" class="form-control mb-3" placeholder="Usuario" required>

            <div class="password-container mb-3">
                <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
                <i class="bi bi-eye-slash" id="togglePassword"></i>
            </div>

            <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>

        <a href="principal.php" class="d-block mt-3" style="color:#2563eb; text-decoration:none;">← Volver a la página principal</a>
    </div>

    <footer>
        © <?= date("Y"); ?> Librería JK — Todos los derechos reservados
    </footer>

    <script>
    // Mostrar / ocultar contraseña
    document.getElementById("togglePassword").addEventListener("click", function () {
        const pass = document.getElementById("password");
        const icon = this;
        if (pass.type === "password") {
            pass.type = "text";
            icon.classList.replace("bi-eye-slash", "bi-eye");
        } else {
            pass.type = "password";
            icon.classList.replace("bi-eye", "bi-eye-slash");
        }
    });
    </script>

</body>
</html>
