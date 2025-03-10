<?php
?>
<h2 title="Listado de facturas">Facturas</h2>
<p>Esta área le permite crear, visualizar, editar, imprimir y eliminar facturas. Desde aquí se puede revisar el historial de facturación, consultar detalles de cada operación y realizar modificaciones en caso de ser necesario. Es la sección central para la gestión de su actividad comercial y administrativa.

</p>
<a href="rosybrown.php?page=factura_crear" class="btn-submit" title="Crear nueva factura" style="margin-bottom:20px; display:inline-block;">Crear Factura</a>
<table title="Tabla de facturas">
    <tr>
        <th>Nº Factura</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Epígrafe</th>
        <th>Total</th>
        <th>Acciones</th>
    </tr>
    <?php
    // Now join with epigrafes to retrieve epigrafe name and IVA percentage
    $stmt = $db->query("SELECT f.*, c.name as cliente_nombre, e.name as epigrafe_name 
                         FROM facturas f 
                         LEFT JOIN clientes c ON f.cliente_id = c.id 
                         LEFT JOIN epigrafes e ON f.epigrafe_id = e.id");
    while ($factura = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($factura['invoice_number']) . "</td>";
        echo "<td>" . htmlspecialchars($factura['fecha']) . "</td>";
        echo "<td>" . htmlspecialchars($factura['cliente_nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($factura['epigrafe_name'] ? $factura['epigrafe_name'] : 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($factura['total']) . "€</td>";
        echo "<td>";
        echo "<a href='rosybrown.php?page=factura_ver&id=" . $factura['id'] . "' title='Ver factura'>Ver</a> | ";
        echo "<a href='rosybrown.php?page=factura_editar&id=" . $factura['id'] . "' title='Editar factura'>Editar</a> | ";
        echo "<a href='rosybrown.php?page=factura_imprimir&id=" . $factura['id'] . "' title='Imprimir factura'>Imprimir</a> | ";
        echo "<a href='rosybrown.php?page=facturas&accion=eliminar&id=" . $factura['id'] . "' onclick='return confirm(\"¿Está seguro?\")' title='Eliminar factura'>Eliminar</a>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>
<?php
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    $stmt = $db->prepare("DELETE FROM facturas WHERE id=?");
    $stmt->execute([$_GET['id']]);
    echo "<p class='success' title='Factura eliminada'>Factura eliminada.</p>";
}
?>

