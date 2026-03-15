<?php
session_start();
include 'conexion.php';

// Seguridad
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Cajero')) {
    header("Location: login.php");
    exit;
}

$id_venta = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Obtener venta
$stmt = $conn->prepare("SELECT * FROM ventas WHERE id=?");
$stmt->bind_param("i", $id_venta);
$stmt->execute();
$venta = $stmt->get_result()->fetch_assoc();

// Obtener detalle
$stmt2 = $conn->prepare("SELECT * FROM venta_detalle WHERE id_venta=?");
$stmt2->bind_param("i", $id_venta);
$stmt2->execute();
$detalles = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

// Usuario que atiende
$usuario = htmlspecialchars($_SESSION['usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Boleta Web | Librería JK</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --azul: #0a3d62;
    --dorado: #feca57;
    --gris: #f0f0f0;
}
body {
    font-family: 'Poppins', sans-serif;
    background: var(--gris);
    padding: 20px;
}

.boleta-web {
    max-width: 900px;
    margin: 0 auto;
    background: white;
    padding: 40px 50px;
    border-radius: 20px;
    box-shadow: 0 12px 35px rgba(0,0,0,0.25);
    position: relative;
    overflow: hidden;
}

/* Marca de agua del logo */
.boleta-web::before {
    content: "";
    position: absolute;
    top: 40px; /* Pegado más arriba */
    left: 50%;
    width: 300px;
    height: 300px;
    background: url('logo_jk.png') no-repeat center/contain;
    opacity: 0.08;
    transform: translateX(-50%);
    pointer-events: none;
}

h1, h2 {
    text-align: center;
    margin: 8px 0;
    font-weight: 700;
}

h1 {
    font-size: 2.2rem;
    color: var(--azul);
}

h2 {
    font-size: 1rem;
    color: #555;
}

.info-cliente, .info-venta {
    margin: 20px 0;
    font-size: 1.1rem;
    font-weight: 500;
    line-height: 1.6;
}

.table-productos th, .table-productos td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    font-size: 1rem;
}

.table-productos th {
    background-color: var(--dorado);
    color: #2d3436;
    font-weight: 600;
}

.table-productos td {
    vertical-align: middle;
}

.total {
    text-align: right;
    font-weight: 700;
    font-size: 1.6rem;
    margin-top: 20px;
    color: var(--azul);
}

.usuario {
    font-style: italic;
    margin-top: 12px;
    text-align: right;
    color: #555;
}

/* Botón imprimir */
.btn-print {
    display: block;
    width: 200px;
    margin: 20px auto;
    font-size: 1.1rem;
    background: var(--dorado);
    color: #2d3436;
    border: none;
    border-radius: 12px;
    padding: 10px;
    cursor: pointer;
    transition: 0.3s;
}
.btn-print:hover {
    background: #e6b94a;
}

@media print {
    body {
        background: none;
        padding: 0;
    }
    .boleta-web {
        max-width: 100%;
        margin: 0;
        border-radius: 0;
        padding: 20px 15px;
        box-shadow: none;
        page-break-inside: avoid;
        font-size: 12pt;
        line-height: 1.4;
    }
    /* Logo de marca de agua */
    .boleta-web::before {
        content: "";
        position: absolute;
        top: 5px; /* Pegado arriba */
        left: 50%;
        width: 200px;
        height: 200px;
        background: url('logo_jk.png') no-repeat center/contain;
        opacity: 0.05;
        transform: translateX(-50%);
        pointer-events: none;
    }
    h1, h2 {
        text-align: center;
        color: #000;
    }
    h1 { font-size: 18pt; }
    h2 { font-size: 10pt; }
    .info-cliente, .info-venta {
        font-size: 11pt;
        margin: 10px 0;
    }
    .table-productos th, .table-productos td {
        padding: 5px 8px;
        font-size: 10pt;
        border: 1px solid #aaa;
    }
    .table-productos th {
        background-color: #feca57;
        color: #2d3436;
        font-weight: bold;
    }
    .total {
        text-align: right;
        font-weight: 700;
        font-size: 12pt;
        margin-top: 10px;
    }
    .usuario {
        text-align: right;
        font-style: italic;
        font-size: 10pt;
    }
    /* Ocultar el botón en impresión */
    .btn-print {
        display: none;
    }
}


</style>
</head>
<body>
<div class="boleta-web">
    <h1>Librería JK</h1>
    <h2>RUC: 1234567890 | Dirección: Calle Falsa 123 | Tel: 987654321</h2>

    <div class="info-cliente">
        <strong>Cliente:</strong> <?= htmlspecialchars($venta['cliente']) ?> | 
        <strong>DNI:</strong> <?= htmlspecialchars($venta['dni']) ?>
    </div>

    <div class="info-venta">
        <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?><br>
        <strong>Método de pago:</strong> <?= htmlspecialchars($venta['metodo_pago']) ?> 
        <?= $venta['numero_operacion'] ? "(N° {$venta['numero_operacion']})" : "" ?><br>
        <strong>Atendido por:</strong> <?= $usuario ?>
    </div>

    <table class="table table-productos">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($detalles as $d): ?>
            <tr>
                <td><?= htmlspecialchars($d['nombre']) ?></td>
                <td><?= $d['cantidad'] ?></td>
                <td>S/ <?= number_format($d['precio'],2) ?></td>
                <td>S/ <?= number_format($d['subtotal'],2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">TOTAL: S/ <?= number_format($venta['total'],2) ?></div>
    <div class="usuario">Atendido por: <?= $usuario ?></div>
</div>

<button class="btn-print" onclick="window.print()">
    <i class="bi bi-printer-fill"></i> Imprimir Boleta
</button>

</body>
</html>
