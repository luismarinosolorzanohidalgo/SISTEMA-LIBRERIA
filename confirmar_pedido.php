<?php
session_start();
include 'conexion.php';

// Si el carrito está vacío
if (empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Confirmar Pedido | Librería JK</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
:root {
    --azul: #0a3d62;
    --dorado: #feca57;
    --fondo: linear-gradient(135deg, #1e3799, #2980b9, #6dd5fa);
}
body {
    background: var(--fondo);
    font-family: 'Poppins', sans-serif;
    color: #2d3436;
    padding: 40px 20px;
    min-height: 100vh;
}
.container {
    max-width: 800px;
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    padding: 30px;
}
h1 {
    color: var(--azul);
    font-weight: 700;
    text-align: center;
    margin-bottom: 30px;
}
label {
    font-weight: 600;
}
.btn-metodo {
    border-radius: 10px;
    padding: 10px 15px;
    font-weight: 600;
    width: 100%;
    transition: all 0.3s ease;
}
.btn-metodo.active {
    background-color: var(--dorado);
    color: #000;
}
#operacion-container {
    display: none;
    margin-top: 15px;
}
.btn-guardar {
    background: var(--azul);
    color: #fff;
    border-radius: 10px;
    padding: 12px 25px;
    font-weight: 600;
}
.btn-guardar:hover {
    background: var(--dorado);
    color: #000;
}
</style>
</head>
<body>
<div class="container">
    <h1>Confirmar Pedido</h1>

    <form id="formVenta">
        <div class="mb-3">
            <label>Cliente:</label>
            <input type="text" name="cliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>DNI:</label>
            <input type="text" name="dni" class="form-control" maxlength="8" required>
        </div>

        <div class="mb-3">
            <label>Método de Pago:</label>
            <div class="row g-2">
                <div class="col-6 col-md-4">
                    <button type="button" class="btn btn-outline-primary btn-metodo" onclick="seleccionarPago('Efectivo')"><i class="bi bi-cash"></i> Efectivo</button>
                </div>
                <div class="col-6 col-md-4">
                    <button type="button" class="btn btn-outline-success btn-metodo" onclick="seleccionarPago('Yape')"><i class="bi bi-phone"></i> Yape</button>
                </div>
                <div class="col-6 col-md-4">
                    <button type="button" class="btn btn-outline-info btn-metodo" onclick="seleccionarPago('Plin')"><i class="bi bi-phone-flip"></i> Plin</button>
                </div>
                <div class="col-6 col-md-4">
                    <button type="button" class="btn btn-outline-danger btn-metodo" onclick="seleccionarPago('Tarjeta')"><i class="bi bi-credit-card"></i> Tarjeta</button>
                </div>
                <div class="col-6 col-md-4">
                    <button type="button" class="btn btn-outline-warning btn-metodo" onclick="seleccionarPago('Transferencia')"><i class="bi bi-bank"></i> Transferencia</button>
                </div>
            </div>
        </div>

        <div id="operacion-container">
            <label for="num_operacion">N° de Operación:</label>
            <input type="text" name="num_operacion" id="num_operacion" class="form-control" placeholder="Ingrese el número de operación">
        </div>

        <input type="hidden" name="metodo_pago" id="metodo_pago">

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-guardar"><i class="bi bi-check-circle"></i> Confirmar Venta</button>
        </div>
    </form>
</div>

<script>
let metodoSeleccionado = '';

function seleccionarPago(metodo) {
    metodoSeleccionado = metodo;
    document.getElementById('metodo_pago').value = metodo;

    // Resalta botón activo
    document.querySelectorAll('.btn-metodo').forEach(btn => btn.classList.remove('active'));
    event.target.closest('button').classList.add('active');

    // Mostrar campo de número de operación solo si NO es efectivo
    const opContainer = document.getElementById('operacion-container');
    if (['Yape', 'Plin', 'Tarjeta', 'Transferencia'].includes(metodo)) {
        opContainer.style.display = 'block';
    } else {
        opContainer.style.display = 'none';
        document.getElementById('num_operacion').value = '';
    }
}

document.getElementById('formVenta').addEventListener('submit', async e => {
    e.preventDefault();
    const formData = new FormData(e.target);

    const res = await fetch('guardar_venta.php', {
        method: 'POST',
        body: formData
    });
    const data = await res.json();

    if (data.success) {
        Swal.fire({
            icon: 'success',
            title: '¡Venta registrada!',
            text: data.message,
            confirmButtonColor: '#0a3d62'
        }).then(() => {
            window.location.href = 'ventas.php';
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error al guardar la venta',
            text: data.message
        });
    }
});
</script>
</body>
</html>
