<?php
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
                    ?>
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <title>Ver Factura</title>
                        <link rel="stylesheet" href="css/style.css">
                    </head>
                    <body>
                        <div class="container" title="Vista de la factura">
                            <div class="header" style="background: black; color:white; padding:20px; text-align:center;" title="Encabezado de la factura">
                                <h1><?php echo htmlspecialchars($misDatos['invoice_title']); ?></h1>
                                <h2><?php echo htmlspecialchars($misDatos['invoice_subtitle']); ?></h2>
                            </div>
                            <div class="invoice-number" style="display:flex; justify-content:space-between; padding:10px;" title="Número y fecha de la factura">
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
                                        <td style="text-align:right;"><?php echo htmlspecialchars($linea['precio_unitario']); ?></td>
                                        <td style="text-align:right;"><?php echo htmlspecialchars($linea['total']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </body>
                    </html>
                    <?php
?>
