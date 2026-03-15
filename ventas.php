<?php
session_start();
include 'conexion.php';

// Seguridad básica
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Cajero')) {
    header("Location: login.php");
    exit;
}

// Obtener todas las ventas
$ventas = $conn->query("SELECT * FROM ventas ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial de Ventas | Librería JK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --azul: #0a3d62;
            --dorado: #feca57;
            --fondo: linear-gradient(135deg, #e0e0e0ff, #0d9c31ff);
            --hover-card: rgba(255, 202, 80, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--fondo);
            margin: 0;
            padding: 30px;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            color: white;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .logo-header {
            display: block;
            margin: 0 auto 20px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: white;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
            background-color: var(--hover-card);
        }

        .btn-custom {
            background-color: var(--azul);
            color: white;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background-color: var(--dorado);
            color: #2d3436;
            transform: scale(1.05);
        }

        .btn-clear {
            background-color: #e84118;
            color: white;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-clear:hover {
            background-color: #c23616;
            transform: scale(1.05);
        }

        .table th {
            background-color: var(--azul);
            color: white;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: var(--hover-card);
        }

        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: var(--dorado);
            color: #2d3436;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 28px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            z-index: 100;
        }

        .floating-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.4);
        }

        .filter-group input,
        .filter-group select {
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .badge {
            font-size: 0.85rem;
            padding: 6px 10px;
            border-radius: 8px;
            text-transform: capitalize;
        }

        /* 💰 Total */
        .total-box {
            background: var(--dorado);
            color: #2d3436;
            padding: 15px 25px;
            border-radius: 15px;
            font-weight: 700;
            font-size: 1.3rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            display: inline-block;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="logo_jk.png" alt="Logo Librería JK" class="logo-header">
        <h1>Historial de Ventas</h1>

        <div class="text-center mt-3">
            <a href="dashboard.php" class="btn btn-custom">
                <i class="bi bi-house-door-fill"></i> Volver al Dashboard
            </a>
        </div>

        <div class="card p-3 mb-4 mt-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 filter-group">
                <input type="text" class="form-control w-25" id="filtroCliente" placeholder="Buscar por cliente">
                <input type="text" class="form-control w-25" id="filtroDNI" placeholder="Buscar por DNI">
                <input type="date" class="form-control w-25" id="filtroFecha">
                <select class="form-select w-25" id="filtroMetodo">
                    <option value="">Filtrar por método de pago</option>
                    <option value="efectivo">Efectivo</option>
                    <option value="yape">Yape</option>
                    <option value="plin">Plin</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="no registrado">No registrado</option>
                </select>
                <button class="btn btn-clear" id="btnLimpiarFiltros">🧹 Limpiar Filtros</button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID Venta</th>
                            <th>Cliente</th>
                            <th>DNI</th>
                            <th>Método de Pago</th>
                            <th>N° Operación</th>
                            <th>Total (S/)</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="ventasTable">
                        <?php if ($ventas): ?>
                            <?php foreach ($ventas as $v): ?>
                                <tr>
                                    <td><?= $v['id'] ?></td>
                                    <td><?= htmlspecialchars($v['cliente']) ?></td>
                                    <td><?= htmlspecialchars($v['dni']) ?></td>

                                    <?php
                                    $metodo = htmlspecialchars($v['metodo_pago']);
                                    $color = match (strtolower($metodo)) {
                                        'efectivo' => 'secondary',
                                        'yape' => 'success',
                                        'plin' => 'info',
                                        'tarjeta' => 'warning',
                                        'transferencia' => 'primary',
                                        default => 'dark'
                                    };
                                    ?>
                                    <td>
                                        <span class="badge bg-<?= $color ?>"><?= $metodo ?: 'No registrado' ?></span>
                                    </td>

                                    <td><?= !empty($v['numero_operacion']) ? htmlspecialchars($v['numero_operacion']) : '-' ?></td>
                                    <td class="total-venta"><?= number_format($v['total'], 2) ?></td>
                                    <td><?= !empty($v['fecha']) ? date('Y-m-d H:i', strtotime($v['fecha'])) : 'Sin fecha' ?></td>
                                    <td><?= htmlspecialchars($v['usuario']) ?></td>
                                    <td class="d-flex flex-wrap gap-1">
                                        <a href="detalle_venta.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-custom">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                        <a href="boleta_generada.php?id=<?= $v['id'] ?>" target="_blank" class="btn btn-sm btn-success">
                                            <i class="bi bi-file-earmark-text"></i> Boleta
                                        </a>
                                        <a href="ticket.php?id=<?= $v['id'] ?>" target="_blank" class="btn btn-sm btn-warning">
                                            <i class="bi bi-ticket-perforated"></i> Ticket
                                        </a>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-white">No se han registrado ventas aún.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- 💰 TOTAL FILTRADO -->
            <div class="text-end">
                <div class="total-box">
                    💵 Total mostrado: S/ <span id="totalFiltrado">0.00</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón flotante -->
    <div class="floating-btn" onclick="window.location.href='boleta.php'" title="Nueva Venta">
        <i class="bi bi-plus-lg"></i>
    </div>

    <script>
        const filtroCliente = document.getElementById('filtroCliente');
        const filtroDNI = document.getElementById('filtroDNI');
        const filtroFecha = document.getElementById('filtroFecha');
        const filtroMetodo = document.getElementById('filtroMetodo');
        const btnLimpiar = document.getElementById('btnLimpiarFiltros');
        const totalFiltrado = document.getElementById('totalFiltrado');

        function filtrar() {
            const clienteText = filtroCliente.value.toLowerCase();
            const dniText = filtroDNI.value.toLowerCase();
            const fechaText = filtroFecha.value;
            const metodoText = filtroMetodo.value.toLowerCase();

            let total = 0;

            document.querySelectorAll('#ventasTable tr').forEach(tr => {
                const cliente = tr.children[1]?.textContent.toLowerCase() || '';
                const dni = tr.children[2]?.textContent.toLowerCase() || '';
                const metodo = tr.children[3]?.textContent.toLowerCase() || '';
                const fecha = tr.children[6]?.textContent || '';
                const totalVenta = parseFloat(tr.querySelector('.total-venta')?.textContent.replace(',', '') || 0);

                const visible =
                    cliente.includes(clienteText) &&
                    dni.includes(dniText) &&
                    (fecha.includes(fechaText) || fechaText === '') &&
                    (metodo.includes(metodoText) || metodoText === '');

                tr.style.display = visible ? '' : 'none';
                if (visible) total += totalVenta;
            });

            totalFiltrado.textContent = total.toFixed(2);
        }

        filtroCliente.addEventListener('input', filtrar);
        filtroDNI.addEventListener('input', filtrar);
        filtroFecha.addEventListener('input', filtrar);
        filtroMetodo.addEventListener('change', filtrar);

        btnLimpiar.addEventListener('click', () => {
            filtroCliente.value = '';
            filtroDNI.value = '';
            filtroFecha.value = '';
            filtroMetodo.value = '';
            filtrar();
        });

        window.addEventListener('DOMContentLoaded', filtrar);
    </script>
</body>

</html>