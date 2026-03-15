<?php
include("conexion.php");

$dni = $_POST['dni'];
$cliente = $_POST['cliente'];
$categoria = $_POST['categoria'];
$producto = $_POST['producto'];
$variacion = $_POST['variacion'];
$tipo_hoja = $_POST['tipo_hoja'];

// Generar número de boleta
$sql = "SELECT MAX(id) AS ultimo FROM almacen";
$res = $conn->query($sql);
$next = ($res->num_rows > 0) ? $res->fetch_assoc()['ultimo'] + 1 : 1;

// Guardar boleta
$sql_insert = "INSERT INTO almacen (codigo, nombre, categoria, proveedor, stock, precio_compra, precio_venta, estado, fecha_ingreso)
VALUES ('BOL-$next', '$producto $variacion $tipo_hoja', '$categoria', '$cliente', 1, 0, 0, 'Activo', NOW())";

if ($conn->query($sql_insert) === TRUE) {
    $id = $conn->insert_id;
    header("Location: imprimir_boleta.php?id=$id&dni=$dni&cliente=$cliente");
    exit;
} else {
    echo "Error al guardar la boleta: " . $conn->error;
}
$conn->close();
?>
