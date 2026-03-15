<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería JK | Portal de Acceso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            margin: 0;
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3a8a, #2563eb, #60a5fa);
            background-size: 300% 300%;
            animation: gradientMove 10s ease infinite;
            position: relative;
        }

        /* 🔹 Fondo con partículas suaves */
        .bg-bubbles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .bg-bubbles li {
            position: absolute;
            list-style: none;
            display: block;
            width: 20px;
            height: 20px;
            background: rgba(255,255,255,0.2);
            animation: rise 25s infinite;
            bottom: -150px;
            border-radius: 50%;
        }

        .bg-bubbles li:nth-child(1) { left: 10%; width: 25px; height: 25px; animation-delay: 0s; }
        .bg-bubbles li:nth-child(2) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 17s; }
        .bg-bubbles li:nth-child(3) { left: 25%; width: 30px; height: 30px; animation-delay: 4s; }
        .bg-bubbles li:nth-child(4) { left: 40%; width: 10px; height: 10px; animation-delay: 0s; animation-duration: 22s; }
        .bg-bubbles li:nth-child(5) { left: 55%; width: 35px; height: 35px; animation-delay: 3s; }
        .bg-bubbles li:nth-child(6) { left: 65%; width: 20px; height: 20px; animation-delay: 5s; }
        .bg-bubbles li:nth-child(7) { left: 75%; width: 25px; height: 25px; animation-delay: 7s; }
        .bg-bubbles li:nth-child(8) { left: 85%; width: 12px; height: 12px; animation-delay: 1s; animation-duration: 20s; }

        @keyframes rise {
            0% { transform: translateY(0) rotate(0deg); opacity: 0.4; }
            50% { opacity: 1; }
            100% { transform: translateY(-1000px) rotate(360deg); opacity: 0; }
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container {
            position: relative;
            z-index: 2;
            background: rgba(255,255,255,0.96);
            padding: 50px 40px;
            border-radius: 25px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
            max-width: 460px;
            width: 100%;
            text-align: center;
            animation: fadeIn 1.2s ease;
        }

        .login-container img {
            width: 140px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        .login-container h1 {
            font-weight: 700;
            color: #1e3a8a;
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .login-container p {
            color: #6b7280;
            font-size: 1rem;
            margin-bottom: 30px;
        }

        .btn-acceder {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            border: none;
            padding: 14px 35px;
            font-weight: 600;
            color: white;
            border-radius: 12px;
            width: 100%;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(37,99,235,0.4);
        }

        .btn-acceder:hover {
            background: linear-gradient(90deg, #1e40af, #1e3a8a);
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(30,58,138,0.45);
        }

        .btn-acceder i {
            margin-right: 8px;
        }

        footer {
            position: absolute;
            bottom: 15px;
            width: 100%;
            text-align: center;
            color: #fff;
            font-size: 14px;
            opacity: 0.9;
            z-index: 2;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(15px);}
            to {opacity: 1; transform: translateY(0);}
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body>

    <!-- 🌌 Fondo dinámico -->
    <ul class="bg-bubbles">
        <li></li><li></li><li></li><li></li>
        <li></li><li></li><li></li><li></li>
    </ul>

    <!-- 🏷️ Contenedor principal -->
    <div class="login-container">
        <img src="img/logo_jk.png" alt="Logo Librería JK">
        <h1>Librería JK</h1>
        <p>Accede al sistema interno de gestión y ventas</p>
        <a href="login.php" class="btn-acceder">
            <i class="fas fa-door-open"></i> Ingresar al Sistema
        </a>
    </div>

    <footer>
        © <?php echo date("Y"); ?> Librería JK — Todos los derechos reservados
    </footer>

</body>
</html>
