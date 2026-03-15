<?php
include 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Generar Boleta</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-4">

  <h2 class="mb-4">Generar Boleta de Venta</h2>

  <form id="formBoleta" method="POST" action="procesar_boleta.php">
    <div class="row mb-3">
      <div class="col-md-4">
        <label>Cliente:</label>
        <input type="text" name="cliente" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label>DNI:</label>
        <input type="text" name="dni" class="form-control" maxlength="8" required>
      </div>
    </div>

    <hr>

    <h5>Buscar Producto</h5>
    <div class="row mb-3">
      <div class="col-md-6">
        <input type="text" id="buscar" class="form-control" placeholder="Buscar por nombre o código...">
      </div>
    </div>

    <table class="table table-bordered" id="tablaProductos">
      <thead>
        <tr>
          <th>Código</th>
          <th>Nombre</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Cantidad</th>
          <th>Agregar</th>
        </tr>
      </thead>
      <tbody id="resultados"></tbody>
    </table>

    <hr>

    <h5>Detalle de Venta</h5>
    <table class="table table-striped" id="detalleVenta">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Precio</th>
          <th>Subtotal</th>
          <th>Quitar</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <div class="text-end">
      <h4>Total: S/. <span id="total">0.00</span></h4>
    </div>

    <button type="submit" class="btn btn-success">Registrar Venta</button>
  </form>

<script>
$(document).ready(function(){
  // Buscar productos
  $('#buscar').on('keyup', function(){
    let q = $(this).val();
    $.get('buscar_producto.php', {q}, function(data){
      $('#resultados').html(data);
    });
  });

  // Agregar producto al detalle
  $(document).on('click', '.agregar', function(){
    let id = $(this).data('id');
    let nombre = $(this).data('nombre');
    let precio = parseFloat($(this).data('precio'));
    let cantidad = parseInt($('#cant_'+id).val());
    let stock = parseInt($(this).data('stock'));

    if (cantidad > 0 && cantidad <= stock) {
      let subtotal = precio * cantidad;
      $('#detalleVenta tbody').append(`
        <tr data-id="${id}">
          <td>${nombre}</td>
          <td>${cantidad}</td>
          <td>${precio.toFixed(2)}</td>
          <td>${subtotal.toFixed(2)}</td>
          <td><button type="button" class="btn btn-danger btn-sm quitar">X</button></td>
          <input type="hidden" name="productos[${id}][cantidad]" value="${cantidad}">
          <input type="hidden" name="productos[${id}][precio]" value="${precio}">
        </tr>
      `);
      actualizarTotal();
    } else {
      alert('Cantidad inválida o sin stock.');
    }
  });

  // Quitar producto
  $(document).on('click', '.quitar', function(){
    $(this).closest('tr').remove();
    actualizarTotal();
  });

  function actualizarTotal(){
    let total = 0;
    $('#detalleVenta tbody tr').each(function(){
      total += parseFloat($(this).find('td').eq(3).text());
    });
    $('#total').text(total.toFixed(2));
  }
});
</script>
</body>
</html>
