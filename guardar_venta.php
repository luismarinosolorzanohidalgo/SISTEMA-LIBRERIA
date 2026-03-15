<?php
session_start();
include 'conexion.php';
header('Content-Type: application/json');

if (empty($_SESSION['carrito'])) {
    echo json_encode(['success' => false, 'message' => 'No hay productos en el carrito']);
    exit;
}

$cliente = $_POST['cliente'] ?? '';
$dni = $_POST['dni'] ?? '';
$metodo_pago = $_POST['metodo_pago'] ?? '';
$numero_operacion = $_POST['numero_operacion'] ?? '';

if (!$cliente || !$dni) {
    echo json_encode(['success' => false, 'message' => 'Datos del cliente incompletos']);
    exit;
}
if (!$metodo_pago) {
    echo json_encode(['success' => false, 'message' => 'Seleccione un método de pago']);
    exit;
}

try {
    $conn->begin_transaction();

    $total = array_sum(array_column($_SESSION['carrito'], 'subtotal'));
    $usuario = $_SESSION['usuario'] ?? 'Invitado';
    $fecha = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO ventas (cliente, dni, usuario, fecha, total, metodo_pago, numero_operacion)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $cliente, $dni, $usuario, $fecha, $total, $metodo_pago, $numero_operacion);
    $stmt->execute();
    $id_venta = $stmt->insert_id;
    $stmt->close();

    $stmt_detalle = $conn->prepare("INSERT INTO venta_detalle (id_venta, nombre, cantidad, precio, subtotal)
                                    VALUES (?, ?, ?, ?, ?)");
    foreach ($_SESSION['carrito'] as $item) {
        $stmt_detalle->bind_param("isidd", $id_venta, $item['nombre'], $item['cantidad'], $item['precio'], $item['subtotal']);
        $stmt_detalle->execute();
    }
    $stmt_detalle->close();

    $conn->commit();

    $_SESSION['carrito'] = [];
    unset($_SESSION['cliente'], $_SESSION['dni']);

    echo json_encode(['success' => true, 'message' => 'Venta registrada correctamente', 'id_venta' => $id_venta]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
