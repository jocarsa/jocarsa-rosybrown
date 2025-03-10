<?php
// páginas/informes_libro_mayor.php
?>
<h2 title="Libro Mayor">Libro Mayor (Ingresos y Gastos)</h2>
<table title="Libro mayor">
    <tr>
        <th>Fecha</th>
        <th>Tipo</th>
        <th>Descripción</th>
        <th>Monto</th>
    </tr>
    <?php
    // Ingresos (facturas)
    $ingresosStmt = $db->query("SELECT fecha, invoice_number as descripcion, total as monto FROM facturas");
    // Gastos
    $gastosStmt = $db->query("SELECT fecha_emision as fecha, 'Gasto' as descripcion, gasto_deducible as monto FROM gastos");
    $items = [];
    while ($row = $ingresosStmt->fetch(PDO::FETCH_ASSOC)) {
        $row['tipo'] = 'Ingreso';
        $items[] = $row;
    }
    while ($row = $gastosStmt->fetch(PDO::FETCH_ASSOC)) {
        $row['tipo'] = 'Gasto';
        $items[] = $row;
    }
    // Ordenar por fecha
    usort($items, function($a, $b) {
        return strtotime($a['fecha']) - strtotime($b['fecha']);
    });
    foreach ($items as $item) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($item['fecha']) . "</td>";
        echo "<td>" . htmlspecialchars($item['tipo']) . "</td>";
        echo "<td>" . htmlspecialchars($item['descripcion']) . "</td>";
        echo "<td>" . htmlspecialchars($item['monto']) . "€</td>";
        echo "</tr>";
    }
    ?>
</table>

