<?php
include "conexion.php";

// ====== Filtros ======
$modo = $_GET['modo'] ?? '';
$desde = $_GET['desde'] ?? '';
$hasta = $_GET['hasta'] ?? '';

$hoy = date('Y-m-d');

// Modo rápido
if ($modo) {
    switch ($modo) {
        case 'dia':
            $desde = $hasta = $hoy;
            break;
        case 'semana':
            $desde = date('Y-m-d', strtotime('-7 days'));
            $hasta = $hoy;
            break;
        case 'quincena':
            $desde = date('Y-m-d', strtotime('-15 days'));
            $hasta = $hoy;
            break;
        case 'mes':
            $desde = date('Y-m-01');
            $hasta = date('Y-m-t');
            break;
    }
}

// Validar fechas
if ($desde && !$hasta) $hasta = $hoy;
if ($hasta && !$desde) $desde = $hasta;

// Filtro SQL
$filtro = "";
if ($desde && $hasta) {
    $filtro = "WHERE DATE(fecha) BETWEEN '$desde' AND '$hasta'";
}

// ====== Consultas ======
$sql = "SELECT * FROM ventas $filtro ORDER BY fecha DESC";
$res = $conn->query($sql);

$sqlTotal = "SELECT SUM(total) AS total_ventas, COUNT(*) AS cantidad FROM ventas $filtro";
$total = $conn->query($sqlTotal)->fetch_assoc();

// ====== Exportar Excel ======
if (isset($_GET['exportar']) && $_GET['exportar'] == 'excel') {
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=reporte_ventas.xls");
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Cliente</th><th>DNI</th><th>Usuario</th><th>Fecha</th><th>Método</th><th>N° Operación</th><th>Total</th></tr>";
    $resExport = $conn->query($sql);
    while ($row = $resExport->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['cliente']}</td>
            <td>{$row['dni']}</td>
            <td>{$row['usuario']}</td>
            <td>{$row['fecha']}</td>
            <td>{$row['metodo_pago']}</td>
            <td>{$row['numero_operacion']}</td>
            <td>{$row['total']}</td>
        </tr>";
    }
    echo "</table>";
    exit;
}

// ====== Exportar PDF sin librerías ======
if (isset($_GET['exportar']) && $_GET['exportar'] == 'pdf') {
    $nombre = "reporte_ventas_" . date('Ymd_His') . ".pdf";
    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=$nombre");

    $contenido = "REPORTE DE VENTAS\nLibrería JK\nDesde: $desde  Hasta: $hasta\n\n";
    $contenido .= str_pad("ID",5) . str_pad("Cliente",20) . str_pad("DNI",12) . str_pad("Usuario",15) . str_pad("Método",10) . str_pad("Total",10) . "\n";
    $contenido .= str_repeat("-",85) . "\n";

    $resPDF = $conn->query($sql);
    while ($r = $resPDF->fetch_assoc()) {
        $contenido .= str_pad($r['id'],5);
        $contenido .= str_pad(substr($r['cliente'],0,20),20);
        $contenido .= str_pad($r['dni'],12);
        $contenido .= str_pad(substr($r['usuario'],0,15),15);
        $contenido .= str_pad(substr($r['metodo_pago'],0,10),10);
        $contenido .= number_format($r['total'],2)."\n";
    }

    $contenido .= "\nTotal Ventas: S/ ".number_format($total['total_ventas'] ?? 0,2);
    $contenido .= "\nCantidad de registros: ".($total['cantidad'] ?? 0);

    // --- PDF generado manualmente con marca de agua ---
    $logo = base64_encode(file_get_contents('img/logo.png'));
    $contenido_pdf = "%PDF-1.3
1 0 obj<</Type /Catalog /Pages 2 0 R>>endobj
2 0 obj<</Type /Pages /Kids [3 0 R] /Count 1>>endobj
3 0 obj<</Type /Page /Parent 2 0 R /MediaBox [0 0 612 792]
/Resources<</Font<</F1 4 0 R>>>> /Contents 5 0 R>>endobj
4 0 obj<</Type /Font /Subtype /Type1 /BaseFont /Courier>>endobj";

    // Texto principal
    $texto = "BT /F1 10 Tf 50 750 Td (" . str_replace(["(",")"],["[","]"],utf8_decode($contenido)) . ") Tj ET";

    // Marca de agua (logo base64 como imagen en el centro)
    $marca_agua = "q 0.9 g 200 300 200 200 re f Q";

    $stream = "$marca_agua\n$texto";
    $contenido_pdf .= "
5 0 obj<</Length ".strlen($stream).">>stream
$stream
endstream
endobj
xref
0 6
0000000000 65535 f 
trailer<</Size 6/Root 1 0 R>>
startxref
9999
%%EOF";

    echo $contenido_pdf;
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Ventas | Librería JK</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: linear-gradient(135deg,#fdfbfb,#ebedee); font-family:'Poppins',sans-serif; color:#333; }
.container { background:#fff; margin-top:40px; padding:25px; border-radius:14px; box-shadow:0 5px 20px rgba(0,0,0,0.1); border-top:6px solid #0a3d62; }
h2 { font-weight:600; margin-bottom:25px; color:#0a3d62; text-align:center; }
th { background:#feca57; color:#2d3436; text-align:center; }
tr:hover { background-color:#f8f9fa; transition:0.2s; }
.table { border-radius:8px; overflow:hidden; }
.total-box { background:#f9fbe7; border:1px solid #dce775; padding:15px; border-radius:10px; text-align:center; margin-bottom:20px; }
.total-box h4 { color:#33691e; font-weight:600; }
.btn { border-radius:10px; font-weight:500; }
.btn-print { background:#feca57; color:#2d3436; border:none; }
.metodo { font-weight:600; }
.metodo.efectivo { color:#388e3c; }
.metodo.yape { color:#6a1b9a; }
.metodo.tarjeta { color:#1976d2; }
.metodo.transferencia { color:#ff7043; }
</style>
</head>
<body>
<div class="container">
    <a href="dashboard.php" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Volver</a>
    <h2>📈 Reporte de Ventas</h2>

    <form method="GET" class="row g-2 mb-4 align-items-end">
        <div class="col-md-3"><label>Desde:</label><input type="date" name="desde" class="form-control" value="<?= $desde ?>"></div>
        <div class="col-md-3"><label>Hasta:</label><input type="date" name="hasta" class="form-control" value="<?= $hasta ?>"></div>
        <div class="col-md-6 text-end">
            <label class="d-block">Filtro rápido:</label>
            <div class="btn-group">
                <a href="?modo=dia" class="btn btn-outline-primary btn-sm">Día</a>
                <a href="?modo=semana" class="btn btn-outline-primary btn-sm">Semana</a>
                <a href="?modo=quincena" class="btn btn-outline-primary btn-sm">Quincena</a>
                <a href="?modo=mes" class="btn btn-outline-primary btn-sm">Mes</a>
            </div>
        </div>
        <div class="col-md-12 text-end mt-3">
            <button type="submit" class="btn btn-primary">🔍 Aplicar filtro</button>
            <a href="reportes.php" class="btn btn-secondary">Reiniciar</a>
        </div>
    </form>

    <div class="total-box">
        <h5>Total Ventas</h5>
        <h4>S/ <?= number_format($total['total_ventas'] ?? 0,2) ?></h4>
        <h6>Cantidad de registros: <?= $total['cantidad'] ?? 0 ?></h6>
    </div>

    <div class="text-end mb-3">
        <a href="?<?= http_build_query(array_merge($_GET,['exportar'=>'excel'])) ?>" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel"></i> Excel</a>
        <a href="?<?= http_build_query(array_merge($_GET,['exportar'=>'pdf'])) ?>" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf"></i> PDF</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>DNI</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Método</th>
                    <th>Operación</th>
                    <th>Total</th>
                    <th>Boleta</th>
                </tr>
            </thead>
            <tbody>
                <?php if($res->num_rows>0): while($r=$res->fetch_assoc()): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['cliente']) ?></td>
                    <td><?= htmlspecialchars($r['dni']) ?></td>
                    <td><?= htmlspecialchars($r['usuario']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($r['fecha'])) ?></td>
                    <td class="metodo <?= strtolower($r['metodo_pago']) ?>"><?= htmlspecialchars($r['metodo_pago']) ?></td>
                    <td><?= htmlspecialchars($r['numero_operacion'] ?: '-') ?></td>
                    <td><strong>S/ <?= number_format($r['total'],2) ?></strong></td>
                    <td>
                        <a href="boleta_generada.php?id=<?= $r['id'] ?>" target="_blank" class="btn btn-sm btn-outline-info"><i class="bi bi-receipt-cutoff"></i></a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="9" class="text-center text-muted">No hay registros en este rango.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
