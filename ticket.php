<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Cajero')) {
    header("Location: login.php");
    exit;
}

$id_venta = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_venta <= 0) {
    die("Error: ID de venta no válido.");
}

$stmt = $conn->prepare("SELECT * FROM ventas WHERE id = ?");
$stmt->bind_param("i", $id_venta);
$stmt->execute();
$venta = $stmt->get_result()->fetch_assoc();

$stmt2 = $conn->prepare("SELECT * FROM venta_detalle WHERE id_venta = ?");
$stmt2->bind_param("i", $id_venta);
$stmt2->execute();
$detalles = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ticket térmico | Librería JK</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 10px;
    background: white;
}

.ticket {
    width: 80mm;
    margin: auto;
    padding: 10px;
    text-align: center;
    font-size: 13px;
}

h2 {
    margin: 5px 0;
    font-size: 16px;
    font-weight: 700;
}

.logo {
    width: 60px;
    opacity: 0.4;
    margin-bottom: 5px;
}

hr {
    border: 1px dashed #999;
    margin: 5px 0;
}

.table {
    width: 100%;
    text-align: left;
    font-size: 12px;
    margin-top: 8px;
}

.table th, .table td {
    padding: 2px 0;
}

.total {
    text-align: right;
    font-weight: bold;
    margin-top: 10px;
    font-size: 14px;
}

.footer {
    text-align: center;
    font-size: 11px;
    margin-top: 8px;
}

@media print {
    @page {
        size: 80mm auto;
        margin: 0;
    }
    body { background: white; }
}
</style>
</head>
<body onload="window.print()">
<div class="ticket">
    <img src="logo_jk.png" alt="Logo" class="logo"><br>
    <h2>Librería JK</h2>
    <p>RUC: 1234567890<br>Calle Falsa 123<br>Tel: 987654321</p>
    <hr>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($venta['cliente'] ?? 'No especificado') ?><br>
    <strong>DNI:</strong> <?= htmlspecialchars($venta['dni'] ?? '-') ?><br>
    <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?><br>
    <strong>Pago:</strong> <?= htmlspecialchars($venta['metodo_pago'] ?? '-') ?></p>
    <hr>
    <table class="table">
        <thead>
            <tr><th>Producto</th><th>Cant.</th><th>Imp.</th></tr>
        </thead>
        <tbody>
        <?php foreach ($detalles as $d): ?>
        <tr>
            <td><?= htmlspecialchars($d['nombre']) ?></td>
            <td><?= $d['cantidad'] ?></td>
            <td>S/ <?= number_format($d['subtotal'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <hr>
    <div class="total">TOTAL: S/ <?= number_format($venta['total'], 2) ?></div>
    <hr>
    <div class="footer">
        ¡Gracias por su compra!<br>
        Librería JK — Calidad y confianza<br>
        <?= date('d/m/Y H:i') ?>
    </div>
</div>
</body>
</html>
