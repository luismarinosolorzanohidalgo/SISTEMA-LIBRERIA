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
$productos = $conn->query("SELECT * FROM almacen ORDER BY id DESC");
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
/* === ESTILO PREMIUM === */
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

/* === Botones === */
.btn-primary {
  background: linear-gradient(135deg,#0a3d62,#1b4f72);
  border: none;
  font-weight: 600;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(10,61,98,0.3);
  transition: all 0.3s;
}
.btn-primary:hover {
  background: linear-gradient(135deg,#feca57,#f6b93b);
  color: #2d3436;
  transform: scale(1.05);
}

.btn-outline-danger, .btn-outline-success, .btn-outline-secondary {
  border-radius: 10px;
  font-weight: 500;
  transition: all .3s;
}
.btn-outline-danger:hover {
  background: #dc3545;
  color: #fff;
}
.btn-outline-success:hover {
  background: #198754;
  color: #fff;
}
.btn-outline-secondary:hover {
  background: #6c757d;
  color: #fff;
}

/* === Tarjetas estadísticas === */
.card {
  border-radius: 15px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* === Tabla === */
.table thead {
  background: linear-gradient(135deg, #0a3d62, #1b4f72);
  color: #fff;
  font-weight: 600;
}
.table tbody tr:hover {
  background-color: #f8f9fa;
  transform: scale(1.01);
}

/* === Badges === */
.badge {
  padding: 7px 12px;
  border-radius: 12px;
  font-weight: 600;
}

/* === Modales === */
.modal-content {
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.25);
  border: none;
}
.modal-header {
  background: linear-gradient(135deg, #0a3d62, #1b4f72);
  color: #fff;
}
.modal-header.bg-warning {
  background: linear-gradient(135deg, #feca57, #f6b93b) !important;
  color: #2d3436 !important;
}
.modal-body input {
  border-radius: 10px;
}
.modal-body input:focus {
  border-color: #feca57;
  box-shadow: 0 0 5px rgba(254,202,87,0.5);
}
.modal-footer {
  background: #f9f9f9;
  border-top: 1px solid #eee;
}

/* === Animación modal === */
@keyframes modalPop {
  0% {transform: scale(0.8) translateY(40px); opacity: 0;}
  100% {transform: scale(1) translateY(0); opacity: 1;}
}
.modal.fade .modal-dialog {
  transform: scale(0.8);
  opacity: 0;
  transition: all .3s ease-out;
}
.modal.show .modal-dialog {
  transform: scale(1);
  opacity: 1;
  animation: modalPop .35s ease forwards;
}
</style>
</head>
<body>

<div class="container">
  <!-- Encabezado -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-box-seam"></i> Gestión de Almacén</h3>
    <div>
      <a href="reporte_pdf.php" class="btn btn-outline-danger me-2"><i class="bi bi-file-earmark-pdf-fill"></i> PDF</a>
      <a href="reporte_excel.php" class="btn btn-outline-success me-2"><i class="bi bi-file-earmark-excel-fill"></i> Excel</a>
      <a href="dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle"></i> Volver</a>
    </div>
  </div>

  <!-- Tarjetas -->
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

  <!-- Tabla -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold text-secondary">Listado de Productos</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevo">
      <i class="bi bi-plus-circle"></i> Nuevo Producto
    </button>
  </div>

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
          <th>Acciones</th>
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
          <td>
            <button class="btn btn-outline-warning btn-sm" onclick='editarProducto(<?= json_encode($p) ?>)'>
              <i class="bi bi-pencil-square"></i>
            </button>
            <button class="btn btn-outline-danger btn-sm" onclick="eliminarProducto(<?= $p['id'] ?>)">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="9" class="text-muted">No hay productos registrados.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Nuevo -->
<div class="modal fade" id="modalNuevo" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="almacen_guardar.php" method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Agregar Nuevo Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label>Nombre:</label>
            <input type="text" name="nombre" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label>Categoría:</label>
            <input type="text" name="categoria" class="form-control">
          </div>
          <div class="col-md-6">
            <label>Proveedor:</label>
            <input type="text" name="proveedor" class="form-control">
          </div>
          <div class="col-md-4">
            <label>Stock:</label>
            <input type="number" name="stock" class="form-control" min="0" required>
          </div>
          <div class="col-md-4">
            <label>Precio Compra:</label>
            <input type="number" step="0.01" name="precio_compra" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label>Precio Venta:</label>
            <input type="number" step="0.01" name="precio_venta" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="almacen_guardar.php" method="POST">
        <div class="modal-header bg-warning">
          <h5 class="modal-title text-dark">Editar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          <input type="hidden" name="id" id="edit_id">
          <div class="col-md-6">
            <label>Nombre:</label>
            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label>Categoría:</label>
            <input type="text" name="categoria" id="edit_categoria" class="form-control">
          </div>
          <div class="col-md-6">
            <label>Proveedor:</label>
            <input type="text" name="proveedor" id="edit_proveedor" class="form-control">
          </div>
          <div class="col-md-4">
            <label>Stock:</label>
            <input type="number" name="stock" id="edit_stock" class="form-control" min="0">
          </div>
          <div class="col-md-4">
            <label>Precio Compra:</label>
            <input type="number" step="0.01" name="precio_compra" id="edit_compra" class="form-control">
          </div>
          <div class="col-md-4">
            <label>Precio Venta:</label>
            <input type="number" step="0.01" name="precio_venta" id="edit_venta" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar Cambios</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editarProducto(p) {
  document.getElementById('edit_id').value = p.id;
  document.getElementById('edit_nombre').value = p.nombre;
  document.getElementById('edit_categoria').value = p.categoria;
  document.getElementById('edit_proveedor').value = p.proveedor;
  document.getElementById('edit_stock').value = p.stock;
  document.getElementById('edit_compra').value = p.precio_compra;
  document.getElementById('edit_venta').value = p.precio_venta;
  new bootstrap.Modal(document.getElementById('modalEditar')).show();
}

function eliminarProducto(id) {
  Swal.fire({
    title: "¿Eliminar producto?",
    text: "Esta acción no se puede deshacer.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar"
  }).then(result => {
    if (result.isConfirmed) {
      window.location = "almacen_eliminar.php?id=" + id;
    }
  });
}

<?php if(isset($_GET['success'])): ?>
Swal.fire({
  icon: 'success',
  title: '✅ Operación exitosa',
  text: 'Los cambios se han guardado correctamente.',
  timer: 2000,
  showConfirmButton: false
});
<?php endif; ?>
</script>
</body>
</html>
