<?php
// Vista de impresión de la factura (adaptada para A4 y con desglose de impuestos)
                    if (!isset($_GET['id'])) {
                        echo "ID de factura no proporcionado.";
                        exit;
                    }
                    $factura_id = intval($_GET['id']);
                    $stmt = $db->prepare("SELECT f.*, c.name as cliente_nombre, c.address as cliente_address, c.postal_code as cliente_postal, c.city as cliente_city, c.id_number as cliente_id_number FROM facturas f LEFT JOIN clientes c ON f.cliente_id = c.id WHERE f.id=?");
                    $stmt->execute([$factura_id]);
                    $factura = $stmt->fetch(PDO::FETCH_ASSOC);
                    $stmt = $db->prepare("SELECT * FROM lineas_factura WHERE factura_id=?");
                    $stmt->execute([$factura_id]);
                    $lineas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $misDatosStmt = $db->query("SELECT * FROM mis_datos WHERE id=1");
                    $misDatos = $misDatosStmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Recalcular impuestos: se asume que "total" almacenado es el subtotal (total líneas)
                    $subtotal = $factura['total'];
                    $iva = $subtotal * 0.21;
                    $irpf = $subtotal * 0.15;
                    $total_final = $subtotal + $iva - $irpf;
                    ?>
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <title>Imprimir Factura</title>
                        <link rel="stylesheet" href="css/style.css">
                        <style>
                          @media print {
                            header, nav, .admin-container, .btn-submit { display: none !important; }
                            .container, .invoice-preview { 
                              display: block !important; 
                              visibility: visible;
                              position: absolute;
                              left: 0;
                              top: 0;
                              width: 100%;
                            }
                          }
                          @page { size: A4; margin: 20mm; }
                          body { margin: 0; padding: 0; }
                          .container { width: 210mm; margin: auto; }
                        </style>
                    </head>
                    <body onload="window.print()">
                        <div class="container" title="Factura lista para imprimir">
                            <div class="header" style="background: black; color:white; padding:20px; text-align:center;" title="Encabezado de la factura">
                                <h1><?php echo htmlspecialchars($misDatos['invoice_title']); ?></h1>
                                <h2><?php echo htmlspecialchars($misDatos['invoice_subtitle']); ?></h2>
                            </div>
                            <div class="invoice-number" style="display:flex; justify-content:space-between; padding:10px;" title="Número y fecha">
                                <div><strong>FACTURA NÚMERO:</strong> <?php echo htmlspecialchars($factura['invoice_number']); ?></div>
                                <div><strong>FECHA:</strong> <?php echo htmlspecialchars($factura['fecha']); ?></div>
                            </div>
                            <div class="invoice-details" style="display:flex; justify-content:space-between; padding:10px;" title="Datos del emisor y del cliente">
                                <div class="sender-details">
                                    <h3>Emisor</h3>
                                    <p>
                                        <?php echo htmlspecialchars($misDatos['my_name']); ?><br>
                                        <?php echo htmlspecialchars($misDatos['address']); ?><br>
                                        <?php echo htmlspecialchars($misDatos['postal_code']); ?>, <?php echo htmlspecialchars($misDatos['city']); ?><br>
                                        <?php echo htmlspecialchars($misDatos['id_number']); ?>
                                    </p>
                                </div>
                                <div class="recipient-details">
                                    <h3>Cliente</h3>
                                    <p>
                                        <?php echo htmlspecialchars($factura['cliente_nombre']); ?><br>
                                        <?php echo htmlspecialchars($factura['cliente_address']); ?><br>
                                        <?php echo htmlspecialchars($factura['cliente_postal']); ?>, <?php echo htmlspecialchars($factura['cliente_city']); ?><br>
                                        <?php echo htmlspecialchars($factura['cliente_id_number']); ?>
                                    </p>
                                </div>
                            </div>
                            <table class="invoice-table" style="width:100%; border-collapse:collapse; margin:10px 0;" title="Detalle de la factura">
                                <thead>
                                    <tr>
                                        <th title="Cantidad">UNIDADES</th>
                                        <th title="Producto y descripción">DESCRIPCIÓN</th>
                                        <th style="text-align:right;" title="Precio unitario">PRECIO</th>
                                        <th style="text-align:right;" title="Total de la línea">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lineas as $linea): 
                                        $stmtProd = $db->prepare("SELECT * FROM productos WHERE id=?");
                                        $stmtProd->execute([$linea['producto_id']]);
                                        $prod = $stmtProd->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($linea['cantidad']); ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($prod['product_name']); ?></strong>
                                            <ul>
                                            <?php 
                                            for ($i = 1; $i <= 10; $i++) {
                                                if (!empty($prod["description$i"])) {
                                                    echo "<li>" . htmlspecialchars($prod["description$i"]) . "</li>";
                                                }
                                            }
                                            ?>
                                            </ul>
                                        </td>
                                        <td style="text-align:right;"><?php echo number_format($linea['precio_unitario'], 2, ',', '.'); ?>€</td>
                                        <td style="text-align:right;"><?php echo number_format($linea['total'], 2, ',', '.'); ?>€</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="total-section" style="text-align:right; padding:10px;" title="Desglose de impuestos">
                                <div class="total-row">
                                    <div>TOTAL LÍNEAS:</div>
                                    <div><?php echo number_format($subtotal, 2, ',', '.'); ?>€</div>
                                </div>
                                <div class="total-row">
                                    <div>Total IVA (21%):</div>
                                    <div><?php echo number_format($iva, 2, ',', '.'); ?>€</div>
                                </div>
                                <div class="total-row">
                                    <div>IRPF (15%):</div>
                                    <div><?php echo number_format($irpf, 2, ',', '.'); ?>€</div>
                                </div>
                                <div class="total-row final">
                                    <div>TOTAL:</div>
                                    <div><?php echo number_format($total_final, 2, ',', '.'); ?>€</div>
                                </div>
                            </div>
                            <div class="bank-details" style="padding:10px;" title="Datos bancarios">
                                <strong>CUENTA:</strong> <?php echo htmlspecialchars($misDatos['bank_account']); ?>
                            </div>
                            <div class="footer" style="font-size:10px; text-align:center; border-top:1px solid #ddd; padding:10px;" title="Pie de factura">
                                <?php echo htmlspecialchars($misDatos['invoice_footer']); ?>
                            </div>
                        </div>
                    </body>
                    </html>
                    <?php
?>
