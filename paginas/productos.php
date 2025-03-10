<h2 title="Listado de productos">Productos</h2>
<p>En "Productos" se gestionan los artículos o servicios que ofrece. Aquí puede definir o modificar el nombre, descripción y precio de cada producto o servicio. Estos datos se utilizarán al generar facturas, permitiendo una selección rápida y precisa de los ítems a facturar.

</p>
<a href="index.php?page=producto_crear" class="btn-submit" title="Crear nuevo producto" style="margin-bottom:20px; display:inline-block;">Crear Producto</a>
<table title="Tabla de productos">
    <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Acciones</th>
    </tr>
    <?php
    $stmt = $db->query("SELECT * FROM productos");
    while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($producto['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($producto['descripcion']) . "</td>";
        echo "<td>" . htmlspecialchars($producto['price']) . "€</td>";
        echo "<td>";
        echo "<a href='index.php?page=producto_ver&id=" . $producto['id'] . "' title='Ver producto'>Ver</a> | ";
        echo "<a href='index.php?page=producto_editar&id=" . $producto['id'] . "' title='Editar producto'>Editar</a> | ";
        echo "<a href='index.php?page=productos&accion=eliminar&id=" . $producto['id'] . "' onclick='return confirm(\"¿Está seguro?\")' title='Eliminar producto'>Eliminar</a>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>
<?php
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    $stmt = $db->prepare("DELETE FROM productos WHERE id=?");
    $stmt->execute([$_GET['id']]);
    echo "<p class='success' title='Producto eliminado'>Producto eliminado.</p>";
}
?>

