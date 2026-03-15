<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería JK | Útiles Escolares y Oficina</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* 🌎 Estilo global */
        body {
            font-family: 'Poppins', sans-serif;
            color: #1e293b;
            background-color: #f9fafc;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        /* 🔹 Navbar */
        .navbar {
            background: rgba(13, 71, 161, 0.85);
            backdrop-filter: blur(12px);
            transition: all 0.4s ease-in-out;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .navbar.scrolled {
            background: linear-gradient(90deg, #0d47a1, #1565c0);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.25);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.7rem;
            color: #fff !important;
        }

        .nav-link {
            color: #fff !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: #ffca28 !important;
        }

        .btn-main {
            background: linear-gradient(135deg, #ffca28, #ffc107);
            color: #000;
            border-radius: 30px;
            font-weight: 600;
            padding: 10px 22px;
            transition: 0.3s;
        }

        .btn-main:hover {
            transform: scale(1.08);
            background: linear-gradient(135deg, #ffd54f, #ffb300);
        }

        /* 🌠 Hero */
        .hero {
            height: 100vh;
            background: url('loguito.png') center/cover no-repeat fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(3px);
        }

        .hero-content {
            text-align: center;
            color: white;
            position: relative;
            z-index: 2;
            animation: fadeUp 1.5s ease;
        }

        .ofertas {
            background: linear-gradient(90deg, #fff8e1, #fffde7);
            position: relative;
            overflow: hidden;
        }

        .oferta-card {
            border-radius: 18px;
            transition: all 0.4s ease;
            background: #ffffff;
        }

        .oferta-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(255, 193, 7, 0.25);
        }

        .oferta-card img {
            height: 230px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .oferta-card:hover img {
            transform: scale(1.08);
        }

        .badge-oferta {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(135deg, #e53935, #b71c1c);
            color: white;
            font-weight: bold;
            padding: 8px 14px;
            border-radius: 30px;
            font-size: 0.9rem;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        .hero h1 {
            font-size: 3.6rem;
            font-weight: 800;
            color: #ffca28;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
        }

        .hero p {
            font-size: 1.2rem;
            margin-top: 1rem;
            color: #f8fafc;
        }

        .hero .btn-main {
            background: linear-gradient(135deg, #ffd54f, #fbc02d);
            border: none;
            color: #222;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        }

        .hero .btn-main:hover {
            transform: scale(1.08);
            background: #ffb300;
            color: #fff;
        }

        /* 🔹 Secciones */
        section {
            padding: 90px 0;
        }

        .section-title {
            text-align: center;
            font-weight: 700;
            color: #0d47a1;
            margin-bottom: 50px;
            position: relative;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: #ffca28;
            margin: 10px auto;
            border-radius: 10px;
        }

        /* 🧱 Productos */
        .product-card {
            border-radius: 20px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            transform: translateY(0);
        }

        .product-card:hover {
            transform: translateY(-12px) scale(1.03);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .product-card img {
            height: 240px;
            width: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover img {
            transform: scale(1.1);
        }

        .product-info {
            padding: 22px;
            text-align: center;
        }

        .product-info h5 {
            font-weight: 600;
            color: #1e293b;
        }

        .price {
            font-size: 1.1rem;
            color: #0d47a1;
            font-weight: 700;
        }

        .btn-outline-primary {
            border-color: #0d47a1;
            color: #0d47a1;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: #0d47a1;
            color: white;
        }

        /* 💛 Ofertas */
        .ofertas {
            background: linear-gradient(90deg, #ffca28, #ffe082);
            color: #0d47a1;
            text-align: center;
            padding: 70px 0;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.1);
        }

        /* 📬 Contacto */
        .contact-card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .contact-card input,
        .contact-card textarea {
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .contact-card button {
            background: linear-gradient(135deg, #0d47a1, #1976d2);
            color: #fff;
            border: none;
            border-radius: 30px;
            transition: 0.3s;
        }

        .contact-card button:hover {
            background: linear-gradient(135deg, #1e88e5, #1565c0);
            transform: scale(1.05);
        }

        /* 🪩 Footer */
        footer {
            background: linear-gradient(135deg, #0d47a1, #1e3a8a);
            color: #f1f5f9;
            text-align: center;
            padding: 50px 0 40px;
        }

        footer a {
            color: #ffca28;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        footer .social-icons i {
            font-size: 1.5rem;
            margin: 0 10px;
            color: #ffca28;
            transition: 0.3s;
        }

        footer .social-icons i:hover {
            color: #fff;
            transform: scale(1.2);
        }

        /* ✨ Animaciones */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <!-- 🔹 NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-pencil-fill me-2"></i>Librería JK</a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="#inicio" class="nav-link">Inicio</a></li>
                    <li class="nav-item"><a href="#productos" class="nav-link">Productos</a></li>
                    <li class="nav-item"><a href="#ofertas" class="nav-link">Ofertas</a></li>
                    <li class="nav-item"><a href="#contacto" class="nav-link">Contacto</a></li>
                    <li class="nav-item"><a href="principal.php" class="btn btn-main ms-3">Ingresar</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 🔹 HERO -->
    <section id="inicio" class="hero">
        <div class="hero-content">
            <h1>¡Bienvenido a Librería JK!</h1>
            <p>Encuentra los mejores útiles escolares, artículos de oficina y materiales de arte en un solo lugar.</p>
            <a href="#productos" class="btn btn-main mt-3">Explorar Catálogo</a>
        </div>
    </section>

    <!-- 🔹 PRODUCTOS -->
    <section id="productos">
        <div class="container">
            <h2 class="section-title">🛍️ Productos Destacados</h2>
            <div class="row g-4">
                <?php
                $productos = [
                    ["nombre" => "Cuaderno Universitario", "precio" => "S/ 9.90", "imagen" => "img/cuaderno.jpg"],
                    ["nombre" => "Lapicero Azul BIC", "precio" => "S/ 1.50", "imagen" => "img/lapicero.jpg"],
                    ["nombre" => "Mochila Escolar", "precio" => "S/ 75.00", "imagen" => "img/mochila.jpg"],
                    ["nombre" => "Caja de Colores", "precio" => "S/ 17.90", "imagen" => "img/colores.jpg"],
                    ["nombre" => "Resaltadores 6u", "precio" => "S/ 14.50", "imagen" => "img/resaltadores.jpg"],
                    ["nombre" => "Tijeras Escolares", "precio" => "S/ 4.80", "imagen" => "img/tijeras.jpg"],
                    ["nombre" => "Plumones Artísticos", "precio" => "S/ 22.00", "imagen" => "img/plumones.jpg"],
                    ["nombre" => "Cartuchera Doble", "precio" => "S/ 18.00", "imagen" => "img/cartuchera.jpg"]
                ];
                foreach ($productos as $p) {
                    echo '
        <div class="col-md-3 col-sm-6">
          <div class="product-card">
            <img src="' . $p['imagen'] . '" alt="' . $p['nombre'] . '">
            <div class="product-info">
              <h5>' . $p['nombre'] . '</h5>
              <p class="price">' . $p['precio'] . '</p>
              <a href="#" class="btn btn-outline-primary btn-sm mt-2">Ver Detalles</a>
            </div>
          </div>
        </div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- 🔹 OFERTAS -->
    <section id="ofertas" class="ofertas py-5">
        <div class="container">
            <h2 class="section-title text-dark">🎁 Ofertas Especiales</h2>
            <p class="text-center mb-5 fs-5 text-dark opacity-75">
                ¡Aprovecha los descuentos de temporada y equípate con lo mejor para el regreso a clases!
            </p>

            <div class="row g-4 justify-content-center">
                <?php
                $ofertas = [
                    [
                        "nombre" => "Mochila Escolar Premium",
                        "precio_normal" => "S/ 95.00",
                        "precio_descuento" => "S/ 75.00",
                        "imagen" => "img/mochila.jpg",
                        "descuento" => "−20%"
                    ],
                    [
                        "nombre" => "Caja de Colores Faber-Castell 24u",
                        "precio_normal" => "S/ 28.90",
                        "precio_descuento" => "S/ 22.00",
                        "imagen" => "img/colores.jpg",
                        "descuento" => "−24%"
                    ],
                    [
                        "nombre" => "Set de Plumones Artísticos",
                        "precio_normal" => "S/ 30.00",
                        "precio_descuento" => "S/ 22.00",
                        "imagen" => "img/plumones.jpg",
                        "descuento" => "−27%"
                    ]
                ];

                foreach ($ofertas as $o) {
                    echo '
        <div class="col-md-4 col-sm-6">
          <div class="card oferta-card border-0 shadow-sm">
            <div class="position-relative overflow-hidden">
              <img src="' . $o['imagen'] . '" class="card-img-top" alt="' . $o['nombre'] . '">
              <span class="badge-oferta">' . $o['descuento'] . '</span>
            </div>
            <div class="card-body text-center">
              <h5 class="fw-bold text-primary">' . $o['nombre'] . '</h5>
              <p class="mb-1 text-muted text-decoration-line-through">' . $o['precio_normal'] . '</p>
              <p class="fs-5 fw-bold text-danger">' . $o['precio_descuento'] . '</p>
              <a href="#" class="btn btn-outline-danger btn-sm mt-2">
                <i class="bi bi-cart-plus"></i> Agregar al carrito
              </a>
            </div>
          </div>
        </div>';
                }
                ?>
            </div>
        </div>
    </section>


    <!-- 🔹 CONTACTO -->
    <section id="contacto">
        <div class="container">
            <h2 class="section-title">📬 Contáctanos</h2>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="contact-card">
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" placeholder="Tu nombre">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Correo</label>
                                <input type="email" class="form-control" placeholder="correo@ejemplo.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mensaje</label>
                                <textarea class="form-control" rows="4" placeholder="¿En qué podemos ayudarte?"></textarea>
                            </div>
                            <button type="submit" class="btn w-100">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 🔹 FOOTER -->
    <footer>
        <div class="container">
            <div class="social-icons mb-3">
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-whatsapp"></i></a>
            </div>
            <p>© <?php echo date("Y"); ?> Librería JK — Todos los derechos reservados.</p>
            <p><a href="#inicio">Volver arriba ↑</a></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener("scroll", () => {
            const nav = document.querySelector(".navbar");
            if (window.scrollY > 50) nav.classList.add("scrolled");
            else nav.classList.remove("scrolled");
        });
    </script>
</body>

</html>