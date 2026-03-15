<?php
session_start();
include 'conexion.php';

// Seguridad básica
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Almacén')) {
  header("Location: login.php");
  exit;
}

// Obtener productos
$productos = $conn->query("SELECT id, nombre, categoria, proveedor, stock, precio_venta FROM almacen ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Almacén | Librería JK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
</head>
<body class="bg-light">
<div class="container mt-4 text-center">
  <img src="img/logo_jk.png" alt="Logo Librería JK" width="120" class="mb-2">
  <h3 class="text-primary fw-bold">ALMACÉN – LIBRERÍA JK</h3>
  <p class="text-muted">Reporte general de productos registrados</p>
  <button onclick="generarPDF()" class="btn btn-success px-4"><i class="bi bi-file-earmark-pdf-fill"></i> Exportar PDF</button>
  <a href="almacen.php" class="btn btn-secondary ms-2"><i class="bi bi-arrow-left-circle"></i> Volver</a>

  <div class="table-responsive mt-4">
    <table class="table table-bordered table-hover align-middle text-center" id="tabla-almacen">
      <thead class="table-success">
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
          <td><?= $p['stock'] ?></td>
          <td><?= number_format($p['precio_venta'],2) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
async function generarPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF('p', 'mm', 'a4');
  const fechaHora = new Date().toLocaleString();

  const img = new Image();
  img.src = "img/logo_jk.png";

  img.onload = function() {
    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();

    // Marca de agua GRANDE y TRANSPARENTE
    const wmWidth = 120;
    const wmHeight = 120;
    const wmX = (pageWidth - wmWidth) / 2;
    const wmY = (pageHeight - wmHeight) / 2;
    doc.addImage(img, 'PNG', wmX, wmY, wmWidth, wmHeight, '', 'FAST');
    doc.setGState(new doc.GState({ opacity: 0.08 }));

    // Logo principal arriba
    doc.setGState(new doc.GState({ opacity: 1 }));
    const logoWidth = 30;
    const logoHeight = 30;
    const logoX = (pageWidth - logoWidth) / 2;
    doc.addImage(img, 'PNG', logoX, 10, logoWidth, logoHeight);

    // Encabezado
    doc.setFontSize(16);
    doc.setTextColor(15, 76, 129);
    doc.text("LIBRERÍA JK", pageWidth / 2, 50, { align: "center" });
    doc.setFontSize(13);
    doc.text("REPORTE DE PRODUCTOS – ALMACÉN", pageWidth / 2, 57, { align: "center" });
    doc.setFontSize(9);
    doc.setTextColor(100);
    doc.text(`Generado el ${fechaHora}`, pageWidth / 2, 63, { align: "center" });

    // Tabla
    const headers = [];
    document.querySelectorAll("#tabla-almacen thead tr th").forEach(th => headers.push(th.innerText));
    const rows = [];
    document.querySelectorAll("#tabla-almacen tbody tr").forEach(tr => {
      const row = [];
      tr.querySelectorAll("td").forEach(td => row.push(td.innerText.trim()));
      rows.push(row);
    });

    doc.autoTable({
      startY: 70,
      head: [headers],
      body: rows,
      theme: "grid",
      headStyles: {
        fillColor: [46, 204, 113],
        textColor: 255,
        fontStyle: "bold",
        halign: "center"
      },
      styles: {
        fontSize: 9,
        valign: "middle",
        halign: "center"
      },
      alternateRowStyles: { fillColor: [248, 255, 248] },
      margin: { top: 10 },
      didDrawPage: (data) => {
        doc.setFontSize(9);
        doc.setTextColor(150);
        doc.text(`Página ${data.pageNumber}`, pageWidth - 25, pageHeight - 10);
      }
    });

    // Footer
    doc.setFontSize(10);
    doc.setTextColor(90);
    doc.text("Librería JK - Reporte generado automáticamente", pageWidth / 2, pageHeight - 10, { align: "center" });

    doc.save(`Reporte_Almacen_${fechaHora.replace(/[/:]/g, "-")}.pdf`);
  };
}
</script>
</body>
</html>
