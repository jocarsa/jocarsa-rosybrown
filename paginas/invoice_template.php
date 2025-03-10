<?php
// invoice_template.php
// This file renders the complete invoice view.
// It assumes that $misDatos, $factura, $lineas, and $subtotal are already defined.

// Use the joined epígrafe fields if available.
if (isset($factura['epigrafe_iva']) && $factura['epigrafe_iva'] !== null) {
    $iva_percentage = $factura['epigrafe_iva'] / 100;
} else {
    $iva_percentage = 0.21;
}
$iva = $subtotal * $iva_percentage;
$irpf = $subtotal * 0.15;
$total_final = $subtotal + $iva - $irpf;
?>
<div class="invoice-preview" id="factura">
    <div class="header" style="background: black; color:white; padding:20px; text-align:center;">
        <h1><?php echo htmlspecialchars($misDatos['invoice_title']); ?></h1>
        <h2><?php echo htmlspecialchars($misDatos['invoice_subtitle']); ?></h2>
    </div>
    <div class="invoice-number" style="display:flex; justify-content:space-between; padding:10px;">
        <div><strong>FACTURA NÚMERO:</strong> <?php echo htmlspecialchars($factura['invoice_number']); ?></div>
        <div><strong>FECHA:</strong> <?php echo htmlspecialchars($factura['fecha']); ?></div>
    </div>
    <div class="invoice-details" style="display:flex; justify-content:space-between; padding:10px;">
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
    <?php if (isset($factura['epigrafe_name'])): ?>
    <div class="epigrafe-details" style="padding:10px; border-top:1px solid #ddd;">
        <strong>Epígrafe:</strong> <?php echo htmlspecialchars($factura['epigrafe_name']); ?> (IVA: <?php echo htmlspecialchars($factura['epigrafe_iva']); ?>%)
    </div>
    <?php endif; ?>
    <table class="invoice-table" style="width:100%; border-collapse:collapse; margin:10px 0;">
        <thead>
            <tr>
                <th>UNIDADES</th>
                <th>DESCRIPCIÓN</th>
                <th style="text-align:right;">PRECIO</th>
                <th style="text-align:right;">TOTAL</th>
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
                    <strong><?php echo htmlspecialchars($prod['nombre']); ?></strong>
                    <ul>
                        <?php 
                        // If desired, you could list additional product details here.
                        ?>
                    </ul>
                </td>
                <td style="text-align:right;"><?php echo number_format($linea['precio_unitario'], 2, ',', '.'); ?>€</td>
                <td style="text-align:right;"><?php echo number_format($linea['total'], 2, ',', '.'); ?>€</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="total-section" style="text-align:right; padding:10px;">
        <div class="total-row">
            <div>TOTAL LÍNEAS:</div>
            <div><?php echo number_format($subtotal, 2, ',', '.'); ?>€</div>
        </div>
        <div class="total-row">
            <div>Total IVA (<?php echo htmlspecialchars($factura['epigrafe_iva'] ?? 21); ?>%):</div>
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
    <div class="bank-details" style="padding:10px;">
        <strong>CUENTA:</strong> <?php echo htmlspecialchars($misDatos['bank_account']); ?>
    </div>
    <div class="footer" style="font-size:10px; text-align:center; border-top:1px solid #ddd; padding:10px;">
        <?php echo htmlspecialchars($misDatos['invoice_footer']); ?>
    </div>
</div>
<button onclick="window.print();" class="btn-submit" style="margin-top:20px;">Imprimir Factura</button>

