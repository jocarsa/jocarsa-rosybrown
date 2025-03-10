<?php
// páginas/proveedores.php
?>
<h2 title="Listado de proveedores">Proveedores</h2>
<p>Gestione los proveedores. Puede crear, editar o eliminar registros.</p>
<a href="index.php?page=crearproveedor" class="btn-submit" title="Crear nuevo proveedor" style="margin-bottom:20px; display:inline-block;">Crear Proveedor</a>
<table title="Tabla de proveedores">
    <tr>
        <th>Razón Social</th>
        <th>Dirección</th>
        <th>Código Postal</th>
        <th>Población</th>
        <th>Identificación Fiscal</th>
        <th>Contacto</th>
        <th>Email</th>
        <th>Teléfono</th>
        <th>Acciones</th>
    </tr>
    <?php
    $stmt = $db->query("SELECT * FROM proveedores");
    while ($prov = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($prov['razon_social']) . "</td>";
        echo "<td>" . htmlspecialchars($prov['direccion']) . "</td>";
        echo "<td>" . htmlspecialchars($prov['codigo_postal']) . "</td>";
        echo "<td>" . htmlspecialchars($prov['poblacion']) . "</td>";
        echo "<td>" . htmlspecialchars($prov['identificacion_fiscal']) . "</td>";
        echo "<td>" . htmlspecialchars($prov['contacto_nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($prov['contacto_email']) . "</td>";
        echo "<td>" . htmlspecialchars($prov['contacto_telefono']) . "</td>";
        echo "<td>";
        echo "<a href='index.php?page=editarproveedor&id=" . $prov['id'] . "' title='Editar proveedor'>Editar</a> | ";
        echo "<a href='index.php?page=proveedores&accion=eliminar&id=" . $prov['id'] . "' onclick='return confirm(\"¿Está seguro?\")' title='Eliminar proveedor'>Eliminar</a>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>
<?php
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    $stmt = $db->prepare("DELETE FROM proveedores WHERE id=?");
    $stmt->execute([$_GET['id']]);
    echo "<p class='success' title='Proveedor eliminado'>Proveedor eliminado.</p>";
}
?>

