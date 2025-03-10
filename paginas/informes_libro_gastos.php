<?php
// páginas/informes_libro_gastos.php
?>
<h2 title="Libro de Gastos">Libro de Gastos</h2>
<table title="Listado de gastos">
    <tr>
        <th>ID Gasto</th>
        <th>Fecha Emisión</th>
        <th>Proveedor</th>
        <th>Total Factura</th>
        <th>Gasto Deducible</th>
    </tr>
    <?php
    $stmt = $db->query("SELECT g.*, p.razon_social as proveedor_razon FROM gastos g LEFT JOIN proveedores p ON g.proveedor_id = p.id ORDER BY g.fecha_emision");
    while ($gasto = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($gasto['id']) . "</td>";
        echo "<td>" . htmlspecialchars($gasto['fecha_emision']) . "</td>";
        echo "<td>" . htmlspecialchars($gasto['proveedor_razon']) . "</td>";
        echo "<td>" . htmlspecialchars($gasto['total_factura']) . "€</td>";
        echo "<td>" . htmlspecialchars($gasto['gasto_deducible']) . "€</td>";
        echo "</tr>";
    }
    ?>
</table>

