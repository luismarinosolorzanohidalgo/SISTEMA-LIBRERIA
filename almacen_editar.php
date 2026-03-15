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

    // Estado automático según el stock
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

    echo "<!DOCTYPE html><html><head>
          <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <meta charset='UTF-8'>
          </head><body>";

    if ($conn->query($sql)) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: '¡Actualizado!',
                text: 'El producto se actualizó correctamente.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location = 'almacen.php?success=actualizado';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al actualizar: ".addslashes($conn->error)."',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location = 'almacen.php';
            });
        </script>";
    }

    echo "</body></html>";
}
?>
