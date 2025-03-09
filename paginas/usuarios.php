<h2 title="Listado de usuarios">Usuarios</h2>
<a href="index.php?page=usuario_crear" class="btn-submit" title="Crear nuevo usuario" style="margin-bottom:20px; display:inline-block;">Crear Usuario</a>
<table title="Tabla de usuarios">
    <tr>
        <th>Usuario</th>
        <th>Email</th>
        <th>Nombre</th>
        <th>Acciones</th>
    </tr>
    <?php
    $stmt = $db->query("SELECT * FROM usuarios");
    while ($usuario = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($usuario['usuario']) . "</td>";
        echo "<td>" . htmlspecialchars($usuario['email']) . "</td>";
        echo "<td>" . htmlspecialchars($usuario['nombre']) . "</td>";
        echo "<td>";
        echo "<a href='index.php?page=usuario_editar&id=" . $usuario['id'] . "' title='Editar usuario'>Editar</a> | ";
        echo "<a href='index.php?page=usuarios&accion=eliminar&id=" . $usuario['id'] . "' onclick='return confirm(\"¿Está seguro?\")' title='Eliminar usuario'>Eliminar</a>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>
<?php
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    $stmt = $db->prepare("DELETE FROM usuarios WHERE id=?");
    $stmt->execute([$_GET['id']]);
    echo "<p class='success' title='Usuario eliminado'>Usuario eliminado.</p>";
}
?>

