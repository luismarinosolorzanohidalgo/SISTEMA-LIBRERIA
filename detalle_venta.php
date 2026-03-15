<?php
session_start();
include 'conexion.php';

// Seguridad básica
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Cajero')) {
    header("Location: login.php");
    exit;
}

// Validar id de venta
$id_venta = $_GET['id'] ?? 0;
$id_venta = intval($id_venta);

if ($id_venta <= 0) die("ID de venta inválido.");

// Obtener datos de la venta
$stmt = $conn->prepare("SELECT * FROM ventas WHERE id = ?");
$stmt->bind_param("i", $id_venta);
$stmt->execute();
$venta = $stmt->get_result()->fetch_assoc();
if (!$venta) die("Venta no encontrada.");

// Obtener productos de la venta
$stmt2 = $conn->prepare("SELECT * FROM venta_detalle WHERE id_venta = ?");
$stmt2->bind_param("i", $id_venta);
$stmt2->execute();
$productos = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

// Calcular total
$total = array_sum(array_column($productos, 'subtotal'));
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle Venta #<?= $venta['id'] ?> | Librería JK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --azul: #0a3d62;
            --dorado: #feca57;
            --fondo-body: linear-gradient(135deg, #1e3799, #2980b9, #6dd5fa);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--fondo-body);
            padding: 40px 20px;
            color: #2d3436;
            min-height: 100vh;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        h1 {
            color: var(--azul);
            text-align: center;
            font-weight: 700;
            animation: fadeInDown 1s ease;
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.95);
            margin-bottom: 20px;
            padding: 25px;
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .logo {
            display: block;
            margin: 0 auto 15px auto;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: rotateLogo 1.5s ease;
        }

        @keyframes rotateLogo {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .table th {
            background-color: var(--azul);
            color: white;
            text-align: center;
        }

        .table td,
        .table th {
            vertical-align: middle;
            text-align: center;
            transition: 0.3s;
        }

        .table tbody tr:hover {
            background: rgba(254, 202, 87, 0.2);
            transform: scale(1.02);
        }

        .total {
            font-weight: 700;
            font-size: 1.3em;
            text-align: right;
            margin-top: 15px;
            color: #c0392b;
            animation: fadeInUp 1s ease;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-custom {
            background-color: var(--azul);
            color: white;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 25px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: var(--dorado);
            color: #2d3436;
            transform: scale(1.05);
        }

        .metodo-box {
            background-color: #f9f9f9;
            border-left: 5px solid var(--dorado);
            padding: 10px 20px;
            border-radius: 10px;
            margin-top: 10px;
            animation: fadeInUp 0.8s ease;
        }
    </style>
</head>

<body>
    <div class="container">

        <img src="logo_jk.png" alt="Logo Librería JK" class="logo">

        <h1>Detalle de la Venta #<?= $venta['id'] ?></h1>

        <div class="card">
            <p><strong>Cliente:</strong> <?= htmlspecialchars($venta['cliente']) ?></p>
            <p><strong>DNI:</strong> <?= htmlspecialchars($venta['dni']) ?></p>
            <p><strong>Usuario:</strong> <?= htmlspecialchars($venta['usuario']) ?></p>
            <p><strong>Fecha:</strong> <?= $venta['fecha'] ?></p>

            <!-- 🟡 Método de pago -->
            <div class="metodo-box">
                <p><strong>Método de Pago:</strong>
                    <?= htmlspecialchars(ucfirst($venta['metodo_pago'])) ?>
                </p>

                <?php if (!empty($venta['numero_operacion'])): ?>
                    <p><strong>N° de Operación:</strong> <?= htmlspecialchars($venta['numero_operacion']) ?></p>
                <?php else: ?>
                    <?php if (in_array(strtolower($venta['metodo_pago']), ['yape', 'tarjeta', 'plin', 'transferencia'])): ?>
                        <p style="color:#e67e22"><em>(Sin número de operación registrado)</em></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario (S/)</th>
                        <th>Subtotal (S/)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nombre']) ?></td>
                            <td><?= $p['cantidad'] ?></td>
                            <td><?= number_format($p['precio'], 2) ?></td>
                            <td><?= number_format($p['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total">Total a pagar: S/<?= number_format($total, 2) ?></div>
        </div>

        <div class="text-center mt-3">
            <a href="ventas.php" class="btn btn-custom"><i class="bi bi-arrow-left"></i> Volver a Ventas</a>
        </div>

    </div>
</body>

</html>