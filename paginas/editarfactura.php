<?php
if (!isset($_GET['id'])) {
    echo "ID de factura no proporcionado.";
    exit;
}
$factura_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['invoice_number'])) {
    // Update invoice header data, now including epigrafe_id.
    $stmt = $db->prepare("UPDATE facturas SET invoice_number=?, fecha=?, cliente_id=?, epigrafe_id=? WHERE id=?");
    $stmt->execute([
        $_POST['invoice_number'],
        $_POST['fecha'],
        $_POST['cliente_id'],
        $_POST['epigrafe_id'],
        $factura_id
    ]);
    // Delete existing invoice lines and insert new ones.
    $db->prepare("DELETE FROM lineas_factura WHERE factura_id=?")->execute([$factura_id]);
    $subtotal = 0;
    if (isset($_POST['lineas']) && is_array($_POST['lineas'])) {
        foreach ($_POST['lineas'] as $linea) {
            $subtotal_linea = $linea['cantidad'] * $linea['precio_unitario'];
            $subtotal += $subtotal_linea;
            $stmt_linea = $db->prepare("INSERT INTO lineas_factura (factura_id, producto_id, cantidad, precio_unitario, total) VALUES (?,?,?,?,?)");
            $stmt_linea->execute([$factura_id, $linea['producto_id'], $linea['cantidad'], $linea['precio_unitario'], $subtotal_linea]);
        }
    }
    $stmt = $db->prepare("UPDATE facturas SET total=? WHERE id=?");
    $stmt->execute([$subtotal, $factura_id]);

    // Fetch updated invoice data.
    $stmt = $db->prepare("SELECT * FROM facturas WHERE id=?");
    $stmt->execute([$factura_id]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $db->prepare("SELECT * FROM lineas_factura WHERE factura_id=?");
    $stmt->execute([$factura_id]);
    $lineas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch client details.
    $stmtCliente = $db->prepare("SELECT * FROM clientes WHERE id=?");
    $stmtCliente->execute([$factura['cliente_id']]);
    $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);
    $factura['cliente_nombre']    = $cliente['name'];
    $factura['cliente_address']   = $cliente['address'];
    $factura['cliente_postal']    = $cliente['postal_code'];
    $factura['cliente_city']      = $cliente['city'];
    $factura['cliente_id_number'] = $cliente['id_number'];

    // Recalculate taxes.
    $iva = $subtotal * 0.21; // Will be recalculated in invoice_template.php using epígrafe data.
    $irpf = $subtotal * 0.15;
    $total_final = $subtotal + $iva - $irpf;
    $clientes = $db->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
    $productos = $db->query("SELECT * FROM productos")->fetchAll(PDO::FETCH_ASSOC);
    $epigrafes = $db->query("SELECT * FROM epigrafes")->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Factura Actualizada - Vista Completa</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h2>Factura Actualizada</h2>
        <?php include 'invoice_template.php'; ?>
    </body>
    </html>
    <?php
    exit;
}

// For GET: fetch invoice data.
$stmt = $db->prepare("SELECT * FROM facturas WHERE id=?");
$stmt->execute([$factura_id]);
$factura = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $db->prepare("SELECT * FROM lineas_factura WHERE factura_id=?");
$stmt->execute([$factura_id]);
$lineas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$misDatosStmt = $db->query("SELECT * FROM mis_datos WHERE id=1");
$misDatos = $misDatosStmt->fetch(PDO::FETCH_ASSOC);
$clientes = $db->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
$productos = $db->query("SELECT * FROM productos")->fetchAll(PDO::FETCH_ASSOC);
$epigrafes = $db->query("SELECT * FROM epigrafes")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Factura</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
</head>
<body>
    <h2>Editar Factura</h2>
    <form method="post" action="index.php?page=factura_editar&id=<?php echo $factura_id; ?>" class="form-full">
        <div class="form-row">
            <div class="form-label">
                <label>Nº Factura:</label>
            </div>
            <div class="form-field">
                <input type="text" name="invoice_number" value="<?php echo htmlspecialchars($factura['invoice_number']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label>Fecha:</label>
            </div>
            <div class="form-field">
                <input type="date" name="fecha" value="<?php echo htmlspecialchars($factura['fecha']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label>Cliente:</label>
            </div>
            <div class="form-field">
                <select name="cliente_id" required>
                    <option value="">--Selecciona Cliente--</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?php echo $cliente['id']; ?>" <?php if($cliente['id'] == $factura['cliente_id']) echo "selected"; ?>>
                            <?php echo htmlspecialchars($cliente['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <!-- New Epígrafe selection -->
        <div class="form-row">
            <div class="form-label">
                <label>Epígrafe:</label>
            </div>
            <div class="form-field">
                <select name="epigrafe_id" required>
                    <option value="">--Selecciona Epígrafe--</option>
                    <?php foreach ($epigrafes as $epi): ?>
                        <option value="<?php echo $epi['id']; ?>" <?php if($epi['id'] == $factura['epigrafe_id']) echo "selected"; ?>>
                            <?php echo htmlspecialchars($epi['name']) . " (" . $epi['iva_percentage'] . "% IVA)"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>UNIDADES</th>
                    <th>DESCRIPCIÓN</th>
                    <th>PRECIO</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody id="lineas_factura">
                <?php 
                $i = 0;
                foreach ($lineas as $linea):
                ?>
                <tr class="linea_factura">
                    <td>
                        <input type="number" name="lineas[<?php echo $i; ?>][cantidad]" value="<?php echo htmlspecialchars($linea['cantidad']); ?>" required>
                    </td>
                    <td>
                        <select name="lineas[<?php echo $i; ?>][producto_id]" required>
                            <option value="">--Selecciona Producto--</option>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?php echo $producto['id']; ?>" <?php if($producto['id'] == $linea['producto_id']) echo "selected"; ?> data-price="<?php echo $producto['price']; ?>">
                                    <?php echo htmlspecialchars($producto['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="lineas[<?php echo $i; ?>][precio_unitario]" value="<?php echo htmlspecialchars($linea['precio_unitario']); ?>" required>
                    </td>
                    <td>
                        <span class="total_linea"><?php echo number_format($linea['total'], 2, ',', '.'); ?>€</span>
                        <button type="button" onclick="this.parentElement.parentElement.remove();">Eliminar</button>
                    </td>
                </tr>
                <?php $i++; endforeach; ?>
            </tbody>
        </table>
        <button type="button" onclick="agregarLinea()">Agregar Línea</button>
        <div class="form-row">
            <div class="form-field" style="margin-left: auto;">
                <button type="submit" class="btn-submit">Actualizar Factura</button>
            </div>
        </div>
    </form>
    <p>Después de actualizar, la vista completa de la factura se mostrará.</p>
</body>
</html>

