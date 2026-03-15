<?php
include 'conexion.php';
session_start();

$alert = ''; // variable para disparar SweetAlert desde PHP

// --- AGREGAR ---
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol'];
    $estado = $_POST['estado'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password, nombre_completo, email, telefono, rol, estado, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssssss", $usuario, $password, $nombre, $email, $telefono, $rol, $estado);
    $stmt->execute();
    $alert = 'added';
}

// --- EDITAR ---
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol'];
    $estado = $_POST['estado'];

    $stmt = $conn->prepare("UPDATE usuarios SET usuario=?, nombre_completo=?, email=?, telefono=?, rol=?, estado=? WHERE id=?");
    $stmt->bind_param("ssssssi", $usuario, $nombre, $email, $telefono, $rol, $estado, $id);
    $stmt->execute();
    $alert = 'updated';
}

// --- ELIMINAR ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $alert = 'deleted';
}

// --- CAMBIAR CONTRASEÑA ---
if (isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $id = $_POST['id'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE usuarios SET password=? WHERE id=?");
    $stmt->bind_param("si", $new_password, $id);
    $stmt->execute();
    $alert = 'password_changed';
}

$result = $conn->query("SELECT * FROM usuarios ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Usuarios | Librería JK</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body {
    background: linear-gradient(135deg, #0d6efd, #6610f2);
    min-height: 100vh;
    font-family: "Comic Sans MS", cursive, sans-serif;
    display: flex;
    flex-direction: column;
}
.container {
    max-width: 1100px;
    margin-top: 50px;
    flex: 1;
}
.logo {
    display: block;
    margin: 0 auto 10px;
    width: 130px;
    height: 130px;
    object-fit: contain;
    background: white;
    border-radius: 50%;
    box-shadow: 0 4px 20px rgba(255,255,255,0.4);
    padding: 8px;
}
h1 {
    text-align: center;
    background: rgba(255,255,255,0.9);
    color: #0d6efd;
    padding: 15px 0;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    margin-bottom: 25px;
    font-weight: 700;
}
.card {
    border: none;
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    padding: 15px;
}
table th {
    background: #0d6efd;
    color: white;
    padding: 12px;
    text-align: center;
}
table td {
    text-align: center;
    padding: 12px;
    border-bottom: 1px solid #eee;
}
.btn {
    border-radius: 10px;
    font-weight: bold;
}
.btn-success { background: linear-gradient(90deg, #00b09b, #96c93d); border: none; }
.btn-warning { background: linear-gradient(90deg, #ffb347, #ffcc33); border: none; color: #222; }
.btn-danger { background: linear-gradient(90deg, #ff416c, #ff4b2b); border: none; }
.btn-info { background: linear-gradient(90deg, #36d1dc, #5b86e5); border: none; color: white; }
.modal-content {
    border-radius: 20px;
    background: linear-gradient(180deg, #ffffff, #f2f5ff);
    border: none;
    font-family: "Comic Sans MS";
}
footer {
    background: rgba(255,255,255,0.9);
    text-align: center;
    color: #333;
    font-size: 14px;
    padding: 15px 0;
    box-shadow: 0 -3px 10px rgba(0,0,0,0.1);
    margin-top: auto;
}
</style>
</head>

<body>
<div class="container">
<img src="img/logo_jk.png" alt="Logo" class="logo">
<h1>Gestión de Usuarios</h1>

<div class="d-flex justify-content-between mb-3">
    <a href="dashboard.php" class="btn btn-light shadow-sm">⬅ Volver</a>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">+ Agregar Usuario</button>
</div>

<div class="card">
<table class="table table-hover align-middle">
<thead>
<tr>
<th>ID</th>
<th>Usuario</th>
<th>Nombre</th>
<th>Email</th>
<th>Teléfono</th>
<th>Rol</th>
<th>Estado</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['usuario']) ?></td>
<td><?= htmlspecialchars($row['nombre_completo']) ?></td>
<td><?= htmlspecialchars($row['email']) ?></td>
<td><?= htmlspecialchars($row['telefono']) ?></td>
<td><span class="badge bg-primary"><?= $row['rol'] ?></span></td>
<td><span class="badge bg-<?= $row['estado']=='Activo'?'success':'secondary' ?>"><?= $row['estado'] ?></span></td>
<td>
<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id'] ?>">✏️</button>
<a href="usuarios.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirmar(event)">🗑️</a>
<button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#pass<?= $row['id'] ?>">🔑</button>
</td>
</tr>

<!-- Modal Editar -->
<div class="modal fade" id="edit<?= $row['id'] ?>" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content p-3">
<form method="POST">
<input type="hidden" name="action" value="edit">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<div class="modal-header border-0">
<h5 class="modal-title">Editar Usuario</h5>
</div>
<div class="modal-body">
<div class="mb-2"><label>Usuario</label><input type="text" name="usuario" class="form-control" value="<?= $row['usuario'] ?>" required></div>
<div class="mb-2"><label>Nombre Completo</label><input type="text" name="nombre_completo" class="form-control" value="<?= $row['nombre_completo'] ?>" required></div>
<div class="mb-2"><label>Email</label><input type="email" name="email" class="form-control" value="<?= $row['email'] ?>" required></div>
<div class="mb-2"><label>Teléfono</label><input type="text" name="telefono" class="form-control" value="<?= $row['telefono'] ?>"></div>
<div class="mb-2"><label>Rol</label>
<select name="rol" class="form-select">
<option <?= $row['rol']=='Administrador'?'selected':'' ?>>Administrador</option>
<option <?= $row['rol']=='Almacén'?'selected':'' ?>>Almacén</option>
<option <?= $row['rol']=='Ventas'?'selected':'' ?>>Ventas</option>
<option <?= $row['rol']=='Cliente'?'selected':'' ?>>Cliente</option>
</select>
</div>
<div class="mb-2"><label>Estado</label>
<select name="estado" class="form-select">
<option <?= $row['estado']=='Activo'?'selected':'' ?>>Activo</option>
<option <?= $row['estado']=='Inactivo'?'selected':'' ?>>Inactivo</option>
</select>
</div>
</div>
<div class="modal-footer border-0">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Guardar</button>
</div>
</form>
</div>
</div>
</div>

<!-- Modal Cambiar Contraseña -->
<div class="modal fade" id="pass<?= $row['id'] ?>" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content p-3">
<form method="POST">
<input type="hidden" name="action" value="change_password">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<div class="modal-header border-0">
<h5 class="modal-title">Cambiar Contraseña</h5>
</div>
<div class="modal-body">
<div class="mb-2">
<label>Nueva Contraseña</label>
<input type="password" name="new_password" class="form-control" placeholder="Nueva contraseña" required>
</div>
<div class="mb-2">
<label>Confirmar Contraseña</label>
<input type="password" name="confirm_password" class="form-control" placeholder="Confirmar contraseña" required>
</div>
</div>
<div class="modal-footer border-0">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-info">Guardar</button>
</div>
</form>
</div>
</div>
</div>

<?php endwhile; ?>
</tbody>
</table>
</div>
</div>

<!-- Modal Agregar -->
<div class="modal fade" id="addModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content p-3">
<form method="POST">
<input type="hidden" name="action" value="add">
<div class="modal-header border-0">
<h5 class="modal-title">Agregar Usuario</h5>
</div>
<div class="modal-body">
<div class="mb-2"><label>Usuario</label><input type="text" name="usuario" class="form-control" required></div>
<div class="mb-2"><label>Nombre Completo</label><input type="text" name="nombre_completo" class="form-control" required></div>
<div class="mb-2"><label>Email</label><input type="email" name="email" class="form-control" required></div>
<div class="mb-2"><label>Teléfono</label><input type="text" name="telefono" class="form-control"></div>
<div class="mb-2"><label>Rol</label>
<select name="rol" class="form-select">
<option>Administrador</option>
<option>Almacén</option>
<option>Ventas</option>
<option>Cliente</option>
</select>
</div>
<div class="mb-2"><label>Estado</label>
<select name="estado" class="form-select">
<option>Activo</option>
<option>Inactivo</option>
</select>
</div>
<div class="mb-2"><label>Contraseña</label><input type="password" name="password" class="form-control" required></div>
</div>
<div class="modal-footer border-0">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-success">Guardar</button>
</div>
</form>
</div>
</div>
</div>

<footer>© <?= date('Y') ?> Librería JK — Todos los derechos reservados.</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmar(e){
    e.preventDefault();
    const url = e.currentTarget.getAttribute('href');
    Swal.fire({
        title: '¿Eliminar usuario?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar'
    }).then((r)=>{
        if(r.isConfirmed){
            window.location.href = url;
        }
    });
}

// Validación de contraseñas en modal
document.querySelectorAll('form').forEach(f => {
    f.addEventListener('submit', function(e){
        if(this.action.value === 'change_password'){
            const pass = this.new_password.value;
            const confirm = this.confirm_password.value;
            if(pass !== confirm){
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: 'Las contraseñas no coinciden.',
                    confirmButtonColor:'#0d6efd'
                });
            }
        }
    });
});

<?php if($alert == 'added'): ?>
Swal.fire({icon:'success', title:'¡Usuario agregado!', text:'El usuario fue registrado correctamente', timer:2000, showConfirmButton:false}).then(()=>{window.location.href='usuarios.php';});
<?php elseif($alert == 'updated'): ?>
Swal.fire({icon:'success', title:'¡Datos actualizados!', text:'El usuario fue modificado correctamente', timer:2000, showConfirmButton:false}).then(()=>{window.location.href='usuarios.php';});
<?php elseif($alert == 'deleted'): ?>
Swal.fire({icon:'error', title:'¡Usuario eliminado!', text:'El registro fue eliminado', timer:2000, showConfirmButton:false}).then(()=>{window.location.href='usuarios.php';});
<?php elseif($alert == 'password_changed'): ?>
Swal.fire({icon:'success', title:'¡Contraseña actualizada!', text:'La contraseña se cambió correctamente', timer:2000, showConfirmButton:false});
<?php endif; ?>
</script>
</body>
</html>
