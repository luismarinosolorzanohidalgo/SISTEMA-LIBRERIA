<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Principal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --bg-color: #f5f6fa;
        --text-color: #1e272e;
        --card-bg: rgba(255,255,255,0.8);
        --accent: #007bff;
        --hover-accent: #0056d2;
    }

    body.dark {
        --bg-color: #0f172a;
        --text-color: #e2e8f0;
        --card-bg: rgba(30,41,59,0.8);
        --accent: #ffd369;
        --hover-accent: #ffaf40;
    }

    body {
        font-family: "Poppins", sans-serif;
        background: var(--bg-color);
        color: var(--text-color);
        transition: background 0.5s ease, color 0.5s ease;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* 🌟 Navbar */
    .navbar {
        background: rgba(255,255,255,0.3);
        backdrop-filter: blur(15px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 50;
        transition: background 0.5s ease;
    }

    body.dark .navbar {
        background: rgba(15,23,42,0.6);
        box-shadow: 0 4px 20px rgba(0,0,0,0.5);
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--text-color) !important;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .emoji-book {
        font-size: 1.8rem;
        animation: floatEmoji 3s ease-in-out infinite;
    }

    @keyframes floatEmoji {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }

    .btn-toggle {
        background: none;
        border: none;
        color: var(--text-color);
        font-size: 1.5rem;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-toggle:hover {
        transform: rotate(20deg);
    }

    /* 🧩 Contenido principal */
    .dashboard-container {
        padding: 80px 25px;
        animation: fadeIn 1s ease;
    }

    h3 {
        text-align: center;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 50px;
        text-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* 🪄 Tarjetas */
    .card {
        border: none;
        border-radius: 20px;
        padding: 35px 25px;
        background: var(--card-bg);
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        color: var(--text-color);
        text-align: center;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }

    .card i {
        font-size: 60px;
        color: var(--accent);
        margin-bottom: 15px;
        transition: color 0.3s;
    }

    .card:hover i {
        color: var(--hover-accent);
    }

    .card-title {
        font-weight: 700;
        font-size: 1.4rem;
        color: var(--accent);
    }

    .card p {
        color: rgba(0,0,0,0.6);
    }

    body.dark .card p {
        color: rgba(255,255,255,0.6);
    }

    /* Botones */
    .btn-custom {
        background: var(--accent);
        border: none;
        color: #fff;
        border-radius: 10px;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .btn-custom:hover {
        background: var(--hover-accent);
        transform: scale(1.05);
    }

    /* Footer */
    footer {
        text-align: center;
        padding: 25px;
        color: var(--text-color);
        font-weight: 500;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        box-shadow: 0 -2px 15px rgba(0,0,0,0.1);
        transition: background 0.5s;
    }

    body.dark footer {
        background: rgba(15,23,42,0.6);
    }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg px-4">
    <a class="navbar-brand" href="#">
        <span class="emoji-book">⚙️📚</span>
        <span>Panel del Sistema</span>
    </a>
    <div class="ms-auto d-flex align-items-center gap-3">
        <button class="btn-toggle" id="toggleMode" title="Cambiar modo"><i class="bi bi-moon-fill"></i></button>
        <span>👋 Hola, <?= htmlspecialchars($_SESSION['usuario']); ?></span>
        <a href="logout.php" class="btn btn-sm btn-outline-danger fw-bold">Cerrar sesión</a>
    </div>
</nav>

<div class="dashboard-container container">
    <h3>Bienvenido al Panel de Control</h3>
    <div class="row g-4 justify-content-center">

        <div class="col-md-4 col-lg-3">
            <div class="card">
                <i class="bi bi-box-seam"></i>
                <h5 class="card-title">Almacén</h5>
                <p>Gestiona productos y controla el inventario.</p>
                <a href="almacen.php" class="btn-custom mt-2">Ir al Almacén</a>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card">
                <i class="bi bi-cash-coin"></i>
                <h5 class="card-title">Ventas</h5>
                <p>Registra tus ventas de manera rápida.</p>
                <a href="ventas.php" class="btn-custom mt-2">Ver Ventas</a>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card">
                <i class="bi bi-graph-up-arrow"></i>
                <h5 class="card-title">Reportes</h5>
                <p>Obtén estadísticas detalladas del sistema.</p>
                <a href="reportes.php" class="btn-custom mt-2">Ver Reportes</a>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card">
                <i class="bi bi-people-fill"></i>
                <h5 class="card-title">Usuarios</h5>
                <p>Administra los usuarios del sistema.</p>
                <a href="Usuarios.php" class="btn-custom mt-2">Administrar</a>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card">
                <i class="bi bi-person-check-fill"></i>
                <h5 class="card-title">Clientes</h5>
                <p>Visualiza y gestiona tus clientes.</p>
                <a href="clientes.php" class="btn-custom mt-2">Ver Clientes</a>
            </div>
        </div>
    </div>
</div>

<footer>
    © <?= date("Y"); ?> — Panel Administrativo
</footer>

<script>
const toggleBtn = document.getElementById("toggleMode");
const body = document.body;
toggleBtn.addEventListener("click", () => {
    body.classList.toggle("dark");
    const icon = toggleBtn.querySelector("i");
    icon.classList.toggle("bi-moon-fill");
    icon.classList.toggle("bi-sun-fill");
});
</script>

</body>
</html>
