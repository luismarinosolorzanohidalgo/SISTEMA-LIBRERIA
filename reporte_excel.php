<?php
include "conexion.php";

// Obtener todos los productos de la tabla almacen
$productos = $conn->query("SELECT id, nombre, categoria, proveedor, stock, precio_venta FROM almacen ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Exportar Excel | Librería JK</title>
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg,#e0f2e9,#c8e6c9);
      font-family: 'Poppins', sans-serif;
      color:#2d3436;
    }
    .container {
      background: #fff;
      border-radius: 20px;
      padding: 40px;
      margin-top: 60px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    h3 {
      color: #145a32;
      font-weight: 700;
    }
    .btn-success {
      background: linear-gradient(135deg, #27ae60, #2ecc71);
      border: none;
      font-weight: 600;
      transition: 0.3s;
    }
    .btn-success:hover {
      background: linear-gradient(135deg, #feca57, #f6b93b);
      color: #2d3436;
      transform: scale(1.05);
    }
    .table {
      border-radius: 12px;
      overflow: hidden;
    }
    .table thead {
      background: linear-gradient(135deg, #1e8449, #27ae60);
      color: #fff;
      font-size: 0.95rem;
    }
    .table tbody tr {
      transition: all 0.2s;
    }
    .table tbody tr:hover {
      background-color: #e9f7ef;
      transform: scale(1.01);
    }
    .badge-stock {
      background-color: #2ecc71;
      color: #fff;
      padding: 6px 10px;
      border-radius: 10px;
    }
    .badge-low {
      background-color: #e74c3c;
    }
  </style>
</head>
<body>

<div class="container text-center">
  <img src="img/logo_jk.png" alt="Logo Librería JK" width="120" class="mb-3">
  <h3 class="fw-bold">Reporte General del Almacén</h3>
  <p class="text-muted mb-4">Visualiza o exporta todos los productos de Librería JK</p>

  <button onclick="exportarExcel()" class="btn btn-success px-4">
    <i class="bi bi-file-earmark-excel-fill"></i> Exportar a Excel
  </button>

  <a href="almacen.php" class="btn btn-secondary ms-2">
    <i class="bi bi-arrow-left-circle"></i> Volver
  </a>

  <div class="table-responsive mt-4">
    <table class="table table-hover table-bordered align-middle text-center" id="tabla-productos">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Categoría</th>
          <th>Proveedor</th>
          <th>Stock</th>
          <th>Precio Venta (S/)</th>
        </tr>
      </thead>
      <tbody>
        <?php while($p = $productos->fetch_assoc()): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['nombre']) ?></td>
          <td><?= htmlspecialchars($p['categoria']) ?></td>
          <td><?= htmlspecialchars($p['proveedor']) ?></td>
          <td>
            <span class="badge <?= $p['stock'] < 5 ? 'badge-low' : 'badge-stock' ?>">
              <?= $p['stock'] ?>
            </span>
          </td>
          <td><?= number_format($p['precio_venta'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function exportarExcel() {
  const XLSX = window.XLSX;
  const tabla = document.getElementById("tabla-productos");
  const fechaHora_actual = new Date().toLocaleString();

  const wb = XLSX.utils.book_new();
  const ws_data = [];

  // Encabezado con títulos
  ws_data.push([]);
  ws_data.push(["LIBRERÍA JK"]);
  ws_data.push(["REPORTE GENERAL DE ALMACÉN"]);
  ws_data.push([`Fecha y hora de exportación: ${fechaHora_actual}`]);
  ws_data.push([]);

  // Encabezados de tabla
  const headers = [];
  tabla.querySelectorAll("thead tr th").forEach(th => headers.push(th.innerText.trim()));
  ws_data.push(headers);

  // Datos
  tabla.querySelectorAll("tbody tr").forEach(tr => {
    const row = [];
    tr.querySelectorAll("td").forEach(td => row.push(td.innerText.trim()));
    ws_data.push(row);
  });

  // Crear hoja
  const ws = XLSX.utils.aoa_to_sheet(ws_data);

  // Fusionar celdas para títulos
  ws['!merges'] = [
    { s: { r: 1, c: 0 }, e: { r: 1, c: 5 } },
    { s: { r: 2, c: 0 }, e: { r: 2, c: 5 } },
    { s: { r: 3, c: 0 }, e: { r: 3, c: 5 } },
  ];

  // Estilos de título
  const titleStyle = {
    font: { name: "Calibri", sz: 22, bold: true, color: { rgb: "145A32" } },
    alignment: { horizontal: "center" }
  };
  const subtitleStyle = {
    font: { name: "Calibri", sz: 16, bold: true, color: { rgb: "196F3D" } },
    alignment: { horizontal: "center" }
  };
  const fechaStyle = {
    font: { name: "Calibri", sz: 11, italic: true, color: { rgb: "566573" } },
    alignment: { horizontal: "center" }
  };

  ws["A2"].s = titleStyle;
  ws["A3"].s = subtitleStyle;
  ws["A4"].s = fechaStyle;

  // Estilo para encabezado de tabla
  const headerStyle = {
    font: { bold: true, color: { rgb: "FFFFFF" } },
    fill: { fgColor: { rgb: "27AE60" } },
    alignment: { horizontal: "center", vertical: "center" },
    border: {
      top: { style: "thin", color: { rgb: "000000" } },
      bottom: { style: "thin", color: { rgb: "000000" } },
      left: { style: "thin", color: { rgb: "000000" } },
      right: { style: "thin", color: { rgb: "000000" } }
    }
  };

  const cellStyle = {
    border: {
      top: { style: "thin", color: { rgb: "000000" } },
      bottom: { style: "thin", color: { rgb: "000000" } },
      left: { style: "thin", color: { rgb: "000000" } },
      right: { style: "thin", color: { rgb: "000000" } }
    },
    alignment: { horizontal: "center" }
  };

  // Aplicar estilo encabezado
  for (let col = 0; col < headers.length; col++) {
    const cell = XLSX.utils.encode_cell({ r: 5, c: col });
    if (ws[cell]) ws[cell].s = headerStyle;
  }

  // Aplicar bordes a filas
  const totalRows = ws_data.length;
  for (let row = 6; row < totalRows; row++) {
    for (let col = 0; col < headers.length; col++) {
      const cell = XLSX.utils.encode_cell({ r: row, c: col });
      if (!ws[cell]) continue;
      ws[cell].s = cellStyle;
    }
  }

  // Ajuste de columnas
  ws['!cols'] = [
    { wch: 6 },
    { wch: 30 },
    { wch: 20 },
    { wch: 25 },
    { wch: 12 },
    { wch: 15 }
  ];

  // Guardar archivo Excel
  XLSX.utils.book_append_sheet(wb, ws, "Almacén Librería JK");
  XLSX.writeFile(wb, `Reporte_Almacen_JK_${fechaHora_actual.replace(/[/:]/g, "-")}.xlsx`);
}
</script>
</body>
</html>
