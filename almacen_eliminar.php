<?php
include 'conexion.php';
session_start();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM almacen WHERE id=$id";
    if ($conn->query($sql)) {
        echo "<script>
            alert('🗑️ Producto eliminado correctamente.');
            window.location='almacen.php';
        </script>";
    } else {
        echo "<script>
            alert('❌ Error al eliminar: ".addslashes($conn->error)."');
            window.location='almacen.php';
        </script>";
    }
}
header("Location: almacen.php?success=eliminado");

?>
