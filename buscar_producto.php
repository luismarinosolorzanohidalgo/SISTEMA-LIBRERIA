<?php
include 'conexion.php';

// Texto ingresado por el usuario
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$q = $conn->real_escape_string($q);

// Si el campo está vacío, no mostramos nada
if ($q === '') {
    echo "<tr><td colspan='6' class='text-center text-muted'>Escriba algo para buscar...</td></tr>";
    exit;
}

// Buscamos productos por coincidencia parcial (LIKE)
$sql = "SELECT id, codigo, nombre, precio_venta, stock 
        FROM almacen 
        WHERE nombre LIKE '%$q%' OR codigo LIKE '%$q%' 
        LIMIT 10";

$result = $conn->query($sql);

// Mostramos los resultados
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['codigo']}</td>
                <td>{$row['nombre']}</td>
                <td>S/. {$row['precio_venta']}</td>
                <td>{$row['stock']}</td>
                <td>
                    <input type='number' id='cant_{$row['id']}' 
                           class='form-control' min='1' max='{$row['stock']}' placeholder='0'>
                </td>
                <td>
                    <button type='button' class='btn btn-primary agregar'
                        data-id='{$row['id']}'
                        data-nombre='{$row['nombre']}'
                        data-precio='{$row['precio_venta']}'
                        data-stock='{$row['stock']}'>Agregar</button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center text-muted'>No se encontraron coincidencias</td></tr>";
}
?>
