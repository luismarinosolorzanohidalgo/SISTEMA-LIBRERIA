<?php
include 'conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = trim($_POST['nombre']);
    $categoria = trim($_POST['categoria']);
    $proveedor = trim($_POST['proveedor']);
    $stock = $_POST['stock'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $estado = ($stock > 0) ? 'Disponible' : 'Agotado';

    $sql = "UPDATE almacen SET 
            nombre='$nombre',
            categoria='$categoria',
            proveedor='$proveedor',
            stock='$stock',
            precio_compra='$precio_compra',
            precio_venta='$precio_venta',
            estado='$estado'
            WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: almacen.php?success=actualizado");
        exit;
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}
?>
