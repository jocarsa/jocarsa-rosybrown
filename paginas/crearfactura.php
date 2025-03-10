<?php
// crearfactura.php
$config = require 'config.php';
// No need for session_start() here since it’s already called in index.php.
try {
    $db = new PDO($config['db_url']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $ex) {
    die("Error al conectar con la base de datos");
}

include_once "inc/inicializardb.php";
inicializarDB($db);

// Fetch mis_datos, clientes, productos, and epigrafes for use in the form.
$misDatosStmt = $db->query("SELECT * FROM mis_datos WHERE id=1");
$misDatos = $misDatosStmt->fetch(PDO::FETCH_ASSOC);
$clientes = $db->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
$productos = $db->query("SELECT * FROM productos")->fetchAll(PDO::FETCH_ASSOC);
$epigrafes = $db->query("SELECT * FROM epigrafes")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['invoice_number'])) {
    // Insert invoice (total will be updated after processing line items).
    $stmt = $db->prepare("INSERT INTO facturas (invoice_number, fecha, cliente_id, mis_datos_id, total, epigrafe_id) VALUES (?,?,?,?,?,?)");
    $stmt->execute([
        $_POST['invoice_number'],
        $_POST['fecha'],
        $_POST['cliente_id'],
        1,
        0,
        $_POST['epigrafe_id']
    ]);
    $factura_id = $db->lastInsertId();
    $subtotal = 0;
    if (isset($_POST['lineas']) && is_array($_POST['lineas'])) {
        foreach ($_POST['lineas'] as $linea) {
            $subtotal_linea = $linea['cantidad'] * $linea['precio_unitario'];
            $subtotal += $subtotal_linea;
            $stmt_linea = $db->prepare("INSERT INTO lineas_factura (factura_id, producto_id, cantidad, precio_unitario, total) VALUES (?,?,?,?,?)");
            $stmt_linea->execute([$factura_id, $linea['producto_id'], $linea['cantidad'], $linea['precio_unitario'], $subtotal_linea]);
        }
    }
    // Update invoice total.
    $stmt = $db->prepare("UPDATE facturas SET total=? WHERE id=?");
    $stmt->execute([$subtotal, $factura_id]);
    // Redirect to the invoice view page (which uses the same design as print invoice).
    header("Location: rosybrown.php?page=factura_ver&id=" . $factura_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Factura</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
    <style>
        /* Extra styling to ensure the invoice design is maintained */
        .container {
            max-width: 800px;
            margin: 20px auto;
            border: 1px solid #ddd;
            padding: 20px;
            background: #fff;
        }
        .invoice-number input,
        .invoice-details select,
        .invoice-details input,
        .form-field input,
        .form-field textarea {
            border: 1px solid #bbb;
            padding: 5px;
            border-radius: 3px;
        }
        .invoice-number input { width: 150px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Invoice Header -->
        <div class="header" style="background: black; color:white; padding:20px; text-align:center;">
            <h1><?php echo htmlspecialchars($misDatos['invoice_title']); ?></h1>
            <h2><?php echo htmlspecialchars($misDatos['invoice_subtitle']); ?></h2>
        </div>
        
        <!-- Invoice Number and Date -->
        <form method="post" action="rosybrown.php?page=factura_crear" class="form-full">
            <div class="invoice-number" style="display:flex; justify-content:space-between; margin:20px 0;">
                <div>
                    <strong>FACTURA NÚMERO:</strong> 
                    <input type="text" name="invoice_number" placeholder="Nº Factura" required>
                </div>
                <div>
                    <strong>FECHA:</strong>
                    <input type="date" name="fecha" required>
                </div>
            </div>
            
            <!-- Invoice Details: Emisor and Cliente -->
            <div class="invoice-details" style="display:flex; justify-content:space-between; margin-bottom:20px;">
                <div class="sender-details" style="width:48%;">
                    <h3>Emisor</h3>
                    <p>
                        <?php echo htmlspecialchars($misDatos['my_name']); ?><br>
                        <?php echo htmlspecialchars($misDatos['address']); ?><br>
                        <?php echo htmlspecialchars($misDatos['postal_code']); ?>, <?php echo htmlspecialchars($misDatos['city']); ?><br>
                        <?php echo htmlspecialchars($misDatos['id_number']); ?>
                    </p>
                </div>
                <div class="recipient-details" style="width:48%;">
                    <h3>Cliente</h3>
                    <select name="cliente_id" required>
                        <option value="">--Selecciona Cliente--</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?php echo $cliente['id']; ?>"><?php echo htmlspecialchars($cliente['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <!-- Epígrafe Selection -->
            <div class="form-row" style="margin-bottom:20px;">
                <div class="form-label">
                    <label>Epígrafe:</label>
                </div>
                <div class="form-field">
                    <select name="epigrafe_id" required>
                        <option value="">--Selecciona Epígrafe--</option>
                        <?php foreach ($epigrafes as $epi): ?>
                            <option value="<?php echo $epi['id']; ?>">
                                <?php echo htmlspecialchars($epi['name']) . " (" . $epi['iva_percentage'] . "% IVA)"; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <!-- Invoice Lines Table -->
            <table class="invoice-table" style="width:100%; border-collapse:collapse; margin-bottom:20px;">
                <thead>
                    <tr>
                        <th>UNIDADES</th>
                        <th>DESCRIPCIÓN (Producto)</th>
                        <th>PRECIO</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody id="lineas_factura">
                    <tr class="linea_factura">
                        <td>
                            <input type="number" name="lineas[0][cantidad]" placeholder="Cantidad" required>
                        </td>
                        <td>
                            <select name="lineas[0][producto_id]" required>
                                <option value="">--Selecciona Producto--</option>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?php echo $producto['id']; ?>" data-price="<?php echo $producto['price']; ?>">
                                        <?php echo htmlspecialchars($producto['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="lineas[0][precio_unitario]" placeholder="Precio" required>
                        </td>
                        <td>
                            <span class="total_linea">0,00€</span>
                            <button type="button" onclick="this.parentElement.parentElement.remove();">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="button" onclick="agregarLinea()">Agregar Línea</button>
            
            <!-- Totals Section (read-only; values recalculated after submission) -->
            <div class="total-section" style="display:flex; flex-direction:column; align-items:flex-end; margin:20px 0;">
                <div class="total-row">
                    <div>TOTAL LÍNEAS:</div>
                    <div id="total_lineas">0,00€</div>
                </div>
                <div class="total-row">
                    <div>Total IVA (21%):</div>
                    <div id="total_iva">0,00€</div>
                </div>
                <div class="total-row">
                    <div>IRPF (15%):</div>
                    <div id="total_irpf">0,00€</div>
                </div>
                <div class="total-row final" style="font-weight:bold; border-top:1px solid #ddd; padding-top:10px;">
                    <div>TOTAL:</div>
                    <div id="total_final">0,00€</div>
                </div>
            </div>
            
            <!-- Bank Details and Footer -->
            <div class="bank-details" style="margin-top:20px; border-top:1px solid #ddd; padding-top:10px;">
                <strong>CUENTA:</strong> <?php echo htmlspecialchars($misDatos['bank_account']); ?>
            </div>
            <div class="footer" style="font-size:10px; color:#666; text-align:center; border-top:1px solid #ddd; padding-top:10px; margin-top:20px;">
                <?php echo htmlspecialchars($misDatos['invoice_footer']); ?>
            </div>
            
            <!-- Submit Button -->
            <div class="form-row" style="text-align:right; margin-top:20px;">
                <button type="submit" class="btn-submit">Guardar Factura</button>
            </div>
        </form>
    </div>
</body>
</html>

