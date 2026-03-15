<?php
session_start();
include 'conexion.php';

// --- Seguridad ---
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Almacén')) {
  header("Location: login.php");
  exit;
}

// --- Totales ---
$total = $conn->query("SELECT COUNT(*) AS total FROM almacen")->fetch_assoc()['total'];
$disponibles = $conn->query("SELECT COUNT(*) AS total FROM almacen WHERE estado='Disponible'")->fetch_assoc()['total'];
$agotados = $conn->query("SELECT COUNT(*) AS total FROM almacen WHERE estado='Agotado'")->fetch_assoc()['total'];

// --- Productos ---
$productos = $conn->query("SELECT id, nombre, categoria, proveedor, stock, precio_compra, precio_venta, estado FROM almacen ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestión de Almacén | Librería JK</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body {
  background: linear-gradient(135deg, #e0eafc, #cfdef3);
  font-family: 'Poppins', sans-serif;
  color: #333;
  min-height: 100vh;
}
.container {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 15px 35px rgba(0,0,0,0.1);
  padding: 35px;
  margin-top: 50px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.container:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 45px rgba(0,0,0,0.15);
}
h3 {
  color: #0a3d62;
  font-weight: 700;
  letter-spacing: 0.5px;
  position: relative;
}
h3::after {
  content: '';
  position: absolute;
  bottom: -5px;
  left: 0;
  width: 60%;
  height: 3px;
  background: linear-gradient(90deg,#feca57,#f6b93b);
  border-radius: 10px;
}
.table thead {
  background: linear-gradient(135deg, #0a3d62, #1b4f72);
  color: #fff;
  font-weight: 600;
}
.table tbody tr:hover {
  background-color: #f8f9fa;
  transform: scale(1.01);
}
.badge {
  padding: 7px 12px;
  border-radius: 12px;
  font-weight: 600;
}
</style>
</head>
<body>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-box-seam"></i> Gestión de Almacén</h3>
    <div>
      <a href="reporte_pdf.php" class="btn btn-outline-danger me-2"><i class="bi bi-file-earmark-pdf-fill"></i> PDF</a>
      <a href="reporte_excel.php" class="btn btn-outline-success me-2"><i class="bi bi-file-earmark-excel-fill"></i> Excel</a>
      <a href="dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle"></i> Volver</a>
    </div>
  </div>

  <div class="row text-center mb-4">
    <div class="col-md-4 mb-3">
      <div class="card border-0 text-white" style="background:linear-gradient(135deg,#1e3a8a,#3b82f6);">
        <div class="card-body">
          <i class="bi bi-archive fs-1"></i>
          <h5>Total de Productos</h5>
          <h3 class="fw-bold"><?= $total ?></h3>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card border-0 text-white" style="background:linear-gradient(135deg,#22c55e,#16a34a);">
        <div class="card-body">
          <i class="bi bi-check-circle fs-1"></i>
          <h5>Disponibles</h5>
          <h3 class="fw-bold"><?= $disponibles ?></h3>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card border-0 text-white" style="background:linear-gradient(135deg,#ef4444,#b91c1c);">
        <div class="card-body">
          <i class="bi bi-exclamation-triangle fs-1"></i>
          <h5>Agotados</h5>
          <h3 class="fw-bold"><?= $agotados ?></h3>
        </div>
      </div>
    </div>
  </div>

  <h5 class="fw-bold text-secondary mb-3">Listado de Productos</h5>

  <div class="table-responsive">
    <table class="table table-hover table-bordered align-middle text-center">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Categoría</th>
          <th>Proveedor</th>
          <th>Stock</th>
          <th>Compra (S/)</th>
          <th>Venta (S/)</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php if($productos->num_rows>0): while($p=$productos->fetch_assoc()): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['nombre']) ?></td>
          <td><?= htmlspecialchars($p['categoria']) ?></td>
          <td><?= htmlspecialchars($p['proveedor']) ?></td>
          <td><?= $p['stock'] ?></td>
          <td><?= number_format($p['precio_compra'],2) ?></td>
          <td><?= number_format($p['precio_venta'],2) ?></td>
          <td>
            <?php
              $estado = strtolower($p['estado']);
              $class = ($estado == 'disponible') ? 'bg-success' : (($estado == 'agotado') ? 'bg-danger' : 'bg-secondary');
            ?>
            <span class="badge <?= $class ?>"><?= ucfirst($estado) ?></span>
          </td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="8" class="text-muted">No hay productos registrados.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
