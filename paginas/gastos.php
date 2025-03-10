<?php
// páginas/gastos.php
?>
<h2 title="Listado de gastos">Gastos</h2>
<p>Gestione el registro de gastos. Cada gasto se almacena con datos fiscales y deducibles.</p>
<a href="index.php?page=creargasto" class="btn-submit" title="Crear nuevo gasto" style="margin-bottom:20px; display:inline-block;">Crear Gasto</a>
<table title="Tabla de gastos">
    <tr>
        <th>Gasto Deducible</th>
        <th>Fecha Emisión</th>
        <th>Fecha Operaciones</th>
        <th>Nº Factura</th>
        <th>Fecha Factura</th>
        <th>Proveedor</th>
        <th>Total Factura</th>
        <th>Base Imponible</th>
        <th>Tipo Retención</th>
        <th>Importe Retención</th>
        <th>Tipo IVA</th>
        <th>Cuota IVA</th>
        <th>IVA Deducido</th>
        <th>Acciones</th>
    </tr>
    <?php
    $stmt = $db->query("SELECT g.*, p.razon_social as proveedor_razon FROM gastos g LEFT JOIN proveedores p ON g.proveedor_id = p.id");
    while ($gasto = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($gasto['gasto_deducible']) . "€</td>";
        echo "<td>" . htmlspecialchars($gasto['fecha_emision']) . "</td>";
        echo "<td>" . htmlspecialchars($gasto['fecha_operaciones']) . "</td>";
        echo "<td>" . htmlspecialchars($gasto['numero_factura']) . "</td>";
        echo "<td>" . htmlspecialchars($gasto['fecha_factura']) . "</td>";
        echo "<td>" . htmlspecialchars($gasto['proveedor_razon']) . "</td>";
        echo "<td>" . htmlspecialchars($gasto['total_factura']) . "€</td>";
        echo "<td>" . htmlspecialchars($gasto['base_imponible']) . "€</td>";
        echo "<td>" . htmlspecialchars($gasto['tipo_retencion']) . "</td>";
        echo "<td>" . htmlspecialchars($gasto['importe_retencion']) . "€</td>";
        echo "<td>" . htmlspecialchars($gasto['tipo_iva']) . "</td>";
        echo "<td>" . htmlspecialchars($gasto['cuota_iva']) . "€</td>";
        echo "<td>" . htmlspecialchars($gasto['iva_deducido']) . "€</td>";
        echo "<td>";
        echo "<a href='index.php?page=editargasto&id=" . $gasto['id'] . "' title='Editar gasto'>Editar</a> | ";
        echo "<a href='index.php?page=gastos&accion=eliminar&id=" . $gasto['id'] . "' onclick='return confirm(\"¿Está seguro?\")' title='Eliminar gasto'>Eliminar</a>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>
<?php
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    $stmt = $db->prepare("DELETE FROM gastos WHERE id=?");
    $stmt->execute([$_GET['id']]);
    echo "<p class='success' title='Gasto eliminado'>Gasto eliminado.</p>";
}
?>

