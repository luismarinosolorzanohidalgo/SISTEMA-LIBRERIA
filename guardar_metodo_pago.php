<?php
session_start();
include 'conexion.php';
header('Content-Type: application/json');

$metodo = $_POST['metodo_pago'] ?? '';

if (!$metodo) {
    echo json_encode(['success' => false, 'message' => 'Método de pago no recibido']);
    exit;
}

// Buscar la última venta del usuario actual
$usuario = $_SESSION['usuario'] ?? '';
if (!$usuario) {
    echo json_encode(['success' => false, 'message' => 'Usuario no identificado']);
    exit;
}

$sql = "SELECT id FROM ventas WHERE usuario = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $usuario);
$stmt->execute();
$stmt->bind_result($id_venta);
$stmt->fetch();
$stmt->close();

if (!$id_venta) {
    echo json_encode(['success' => false, 'message' => 'No se encontró la venta para este usuario']);
    exit;
}

// Actualizar el método de pago en la última venta
$update = $conn->prepare("UPDATE ventas SET metodo_pago = ? WHERE id = ?");
$update->bind_param('si', $metodo, $id_venta);
if ($update->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar en la base de datos']);
}
$update->close();
$conn->close();
?>
