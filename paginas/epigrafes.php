<?php
// epigrafes.php
echo "<h2 title='Listado de epígrafes'>Epígrafes</h2>";
echo "<a href='index.php?page=epigrafes_crear' class='btn-submit' title='Crear nuevo epígrafe' style='margin-bottom:20px; display:inline-block;'>Crear Epígrafe</a>";
echo "<table title='Tabla de epígrafes'>
        <tr>
            <th>Nombre</th>
            <th>IVA (%)</th>
            <th>Acciones</th>
        </tr>";
$stmt = $db->query("SELECT * FROM epigrafes");
while ($epi = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($epi['name']) . "</td>";
    echo "<td>" . htmlspecialchars($epi['iva_percentage']) . "</td>";
    echo "<td>";
    echo "<a href='index.php?page=epigrafes_editar&id=" . $epi['id'] . "' title='Editar epígrafe'>Editar</a> | ";
    echo "<a href='index.php?page=epigrafes&accion=eliminar&id=" . $epi['id'] . "' onclick='return confirm(\"¿Está seguro?\")' title='Eliminar epígrafe'>Eliminar</a>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    $stmt = $db->prepare("DELETE FROM epigrafes WHERE id=?");
    $stmt->execute([$_GET['id']]);
    echo "<p class='success' title='Epígrafe eliminado'>Epígrafe eliminado.</p>";
}
?>

