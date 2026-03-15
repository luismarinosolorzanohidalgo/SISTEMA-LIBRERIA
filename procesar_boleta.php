<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $_POST['cliente'];
    $dni = $_POST['dni'];
    $productos = $_POST['productos'] ?? [];

    if (empty($productos)) {
        die("<script>alert('No hay productos en la venta.'); window.history.back();</script>");
    }

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Calcular total
        $total = 0;
        foreach ($productos as $p) {
            $total += $p['cantidad'] * $p['precio'];
        }

        // Insertar venta
        $stmt = $conn->prepare("INSERT INTO ventas (cliente, dni, total, fecha) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("ssd", $cliente, $dni, $total);
        $stmt->execute();
        $venta_id = $stmt->insert_id;

        // Insertar detalle y actualizar stock
        foreach ($productos as $id => $p) {
            $cantidad = intval($p['cantidad']);
            $precio = floatval($p['precio']);
            $subtotal = $cantidad * $precio;

            $stmt_det = $conn->prepare("INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario, subtotal)
                                        VALUES (?, ?, ?, ?, ?)");
            $stmt_det->bind_param("iiidd", $venta_id, $id, $cantidad, $precio, $subtotal);
            $stmt_det->execute();

            // 🔥 Disminuir el stock en la tabla almacen
            $conn->query("UPDATE almacen SET stock = stock - $cantidad WHERE id = $id");
        }

        // Confirmar transacción
        $conn->commit();

        echo "<script>alert('✅ Venta registrada correctamente'); window.location='boleta.php';</script>";

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('❌ Error al registrar la venta: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>
