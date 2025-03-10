<?php
// páginas/informes_libro_ingresos.php
?>
<h2 title="Libro de Ingresos">Libro de Ingresos</h2>
<table title="Listado de ingresos">
    <tr>
        <th>Nº Factura</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Total</th>
    </tr>
    <?php
    $stmt = $db->query("SELECT f.*, c.name as cliente_nombre FROM facturas f LEFT JOIN clientes c ON f.cliente_id = c.id ORDER BY f.fecha");
    while ($factura = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($factura['invoice_number']) . "</td>";
        echo "<td>" . htmlspecialchars($factura['fecha']) . "</td>";
        echo "<td>" . htmlspecialchars($factura['cliente_nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($factura['total']) . "€</td>";
        echo "</tr>";
    }
    ?>
</table>

