<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['invoice_number'])) {
                        // Insertar factura (el total se guarda inicialmente como el subtotal; se actualizará al imprimir)
                        $stmt = $db->prepare("INSERT INTO facturas (invoice_number, fecha, cliente_id, mis_datos_id, total) VALUES (?,?,?,?,?)");
                        $stmt->execute([$_POST['invoice_number'], $_POST['fecha'], $_POST['cliente_id'], 1, 0]);
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
                        // Se guarda el subtotal en la factura (los impuestos se calcularán al imprimir)
                        $stmt = $db->prepare("UPDATE facturas SET total=? WHERE id=?");
                        $stmt->execute([$subtotal, $factura_id]);
                        echo "<script>window.location.href='index.php?page=facturas';</script>";
                        exit;
                    }
                    $misDatosStmt = $db->query("SELECT * FROM mis_datos WHERE id=1");
                    $misDatos = $misDatosStmt->fetch(PDO::FETCH_ASSOC);
                    $clientes = $db->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
                    $productos = $db->query("SELECT * FROM productos")->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <title>Crear Factura</title>
                        <link rel="stylesheet" href="css/style.css">
                    </head>
                    <body>
                        <h2 title="Crear nueva factura">Crear Factura</h2>
                        <form method="post" action="index.php?page=factura_crear" class="form-full">
                            <div class="invoice-preview">
                                <div class="header">
                                    <h1 title="Título de la factura"><?php echo htmlspecialchars($misDatos['invoice_title']); ?></h1>
                                    <h2 title="Subtítulo de la factura"><?php echo htmlspecialchars($misDatos['invoice_subtitle']); ?></h2>
                                </div>
                                <div class="invoice-number">
                                    <div>
                                        <strong title="Número de factura">FACTURA NÚMERO:</strong>
                                        <input type="text" name="invoice_number" placeholder="Nº Factura" required title="Ingrese el número de factura">
                                    </div>
                                    <div>
                                        <strong title="Fecha de emisión">FECHA:</strong>
                                        <input type="date" name="fecha" required title="Seleccione la fecha">
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
                                                <option value="<?php echo $cliente['id']; ?>"><?php echo htmlspecialchars($cliente['name']); ?></option>
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
                                        <tr class="linea_factura">
                                            <td>
                                                <input type="number" name="lineas[0][cantidad]" placeholder="Cantidad" required title="Ingrese la cantidad">
                                            </td>
                                            <td>
                                                <select name="lineas[0][producto_id]" required title="Seleccione un producto">
                                                    <option value="">--Selecciona Producto--</option>
                                                    <?php foreach ($productos as $producto): ?>
                                                        <option value="<?php echo $producto['id']; ?>"><?php echo htmlspecialchars($producto['product_name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="lineas[0][precio_unitario]" placeholder="Precio" required title="Ingrese el precio unitario">
                                            </td>
                                            <td>
                                                <span class="total_linea" title="Total de la línea">0,00€</span>
                                            </td>
                                        </tr>
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
                                    <button type="submit" class="btn-submit" title="Guardar factura">Guardar Factura</button>
                                </div>
                            </div>
                        </form>
                    </body>
                    </html>
                    <?php
?>
