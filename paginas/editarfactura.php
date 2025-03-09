<?php
if (!isset($_GET['id'])) {
                        echo "ID de factura no proporcionado.";
                        exit;
                    }
                    $factura_id = intval($_GET['id']);
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['invoice_number'])) {
                        $stmt = $db->prepare("UPDATE facturas SET invoice_number=?, fecha=?, cliente_id=? WHERE id=?");
                        $stmt->execute([$_POST['invoice_number'], $_POST['fecha'], $_POST['cliente_id'], $factura_id]);
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
                        echo "<script>window.location.href='index.php?page=facturas';</script>";
                        exit;
                    }
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
                    ?>
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <title>Editar Factura</title>
                        <link rel="stylesheet" href="css/style.css">
                    </head>
                    <body>
                        <h2 title="Editar factura">Editar Factura</h2>
                        <form method="post" action="index.php?page=factura_editar&id=<?php echo $factura_id; ?>" class="form-full">
                            <div class="invoice-preview">
                                <div class="header">
                                    <h1 title="Título de la factura"><?php echo htmlspecialchars($misDatos['invoice_title']); ?></h1>
                                    <h2 title="Subtítulo de la factura"><?php echo htmlspecialchars($misDatos['invoice_subtitle']); ?></h2>
                                </div>
                                <div class="invoice-number">
                                    <div>
                                        <strong title="Número de factura">FACTURA NÚMERO:</strong>
                                        <input type="text" name="invoice_number" value="<?php echo htmlspecialchars($factura['invoice_number']); ?>" required title="Ingrese el número de factura">
                                    </div>
                                    <div>
                                        <strong title="Fecha de emisión">FECHA:</strong>
                                        <input type="date" name="fecha" value="<?php echo htmlspecialchars($factura['fecha']); ?>" required title="Seleccione la fecha">
                                    </div>
                                </div>
                                <div class="invoice-details">
                                    <div class="sender-details">
                                        <h3 title="Datos del emisor">Emisor</h3>
                                        <p>
                                            <?php echo htmlspecialchars($misDatos['my_name']); ?><br>
                                            <?php echo htmlspecialchars($misDatos['address']); ?><br>
                                            <?php echo htmlspecialchars($misDatos['postal_code']); ?>, <?php echo htmlspecialchars($misDatos['city']); ?><br>
                                            <?php echo htmlspecialchars($misDatos['id_number']); ?>
                                        </p>
                                    </div>
                                    <div class="recipient-details">
                                        <h3 title="Seleccione el cliente">Cliente</h3>
                                        <select name="cliente_id" required title="Seleccione un cliente">
                                            <option value="">--Selecciona Cliente--</option>
                                            <?php foreach ($clientes as $cliente): ?>
                                                <option value="<?php echo $cliente['id']; ?>" <?php if($cliente['id'] == $factura['cliente_id']) echo "selected"; ?>><?php echo htmlspecialchars($cliente['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <table class="invoice-table">
                                    <thead>
                                        <tr>
                                            <th title="Cantidad de unidades">UNIDADES</th>
                                            <th title="Producto y descripción">DESCRIPCIÓN</th>
                                            <th class="price-column" title="Precio unitario">PRECIO</th>
                                            <th class="price-column" title="Total por línea">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lineas_factura">
                                        <?php 
                                        $i = 0;
                                        foreach ($lineas as $linea):
                                        ?>
                                        <tr class="linea_factura">
                                            <td>
                                                <input type="number" name="lineas[<?php echo $i; ?>][cantidad]" value="<?php echo $linea['cantidad']; ?>" required title="Ingrese la cantidad">
                                            </td>
                                            <td>
                                                <select name="lineas[<?php echo $i; ?>][producto_id]" required title="Seleccione un producto">
                                                    <option value="">--Selecciona Producto--</option>
                                                    <?php foreach ($productos as $producto): ?>
                                                        <option value="<?php echo $producto['id']; ?>" <?php if($producto['id'] == $linea['producto_id']) echo "selected"; ?>><?php echo htmlspecialchars($producto['product_name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="lineas[<?php echo $i; ?>][precio_unitario]" value="<?php echo $linea['precio_unitario']; ?>" required title="Ingrese el precio unitario">
                                            </td>
                                            <td>
                                                <span class="total_linea" title="Total de la línea"><?php echo htmlspecialchars($linea['total']); ?>€</span>
                                            </td>
                                        </tr>
                                        <?php $i++; endforeach; ?>
                                    </tbody>
                                </table>
                                <button type="button" onclick="agregarLinea()" title="Agregar línea de factura">Agregar Línea</button>
                                <div class="total-section">
                                    <div class="total-row final">
                                        <div title="Suma total de las líneas">TOTAL:</div>
                                        <div><span id="total_factura" title="Total de la factura">0,00€</span></div>
                                    </div>
                                </div>
                                <div class="bank-details">
                                    <strong title="Cuenta bancaria"><?php echo "CUENTA: " . htmlspecialchars($misDatos['bank_account']); ?></strong>
                                </div>
                                <div class="footer">
                                    <p title="Pie de factura"><?php echo htmlspecialchars($misDatos['invoice_footer']); ?></p>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-field" style="margin-left: auto;">
                                    <button type="submit" class="btn-submit" title="Actualizar factura">Actualizar Factura</button>
                                </div>
                            </div>
                        </form>
                    </body>
                    </html>
                    <?php
?>
