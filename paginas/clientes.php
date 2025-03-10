<?php
	?>
                    <h2 title="Listado de clientes">Clientes</h2>
                    <p>Esta sección está dedicada a la administración de la información de sus clientes. Podrá crear nuevos registros, editar datos existentes o eliminar clientes cuando ya no sean necesarios. Contar con un listado actualizado de clientes facilita la emisión de facturas y el seguimiento de la relación comercial.

</p>
                    <a href="index.php?page=cliente_crear" class="btn-submit" title="Crear nuevo cliente" style="margin-bottom:20px; display:inline-block;">Crear Cliente</a>
                    <table title="Tabla de clientes">
                        <tr>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Código Postal</th>
                            <th>Ciudad</th>
                            <th>Nº Identificación</th>
                            <th>Acciones</th>
                        </tr>
                        <?php
                        $stmt = $db->query("SELECT * FROM clientes");
                        while ($cliente = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($cliente['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($cliente['address']) . "</td>";
                            echo "<td>" . htmlspecialchars($cliente['postal_code']) . "</td>";
                            echo "<td>" . htmlspecialchars($cliente['city']) . "</td>";
                            echo "<td>" . htmlspecialchars($cliente['id_number']) . "</td>";
                            echo "<td>";
                            echo "<a href='index.php?page=cliente_ver&id=" . $cliente['id'] . "' title='Ver cliente'>Ver</a> | ";
                            echo "<a href='index.php?page=cliente_editar&id=" . $cliente['id'] . "' title='Editar cliente'>Editar</a> | ";
                            echo "<a href='index.php?page=clientes&accion=eliminar&id=" . $cliente['id'] . "' onclick='return confirm(\"¿Está seguro?\")' title='Eliminar cliente'>Eliminar</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                    <?php
                    if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
                        $stmt = $db->prepare("DELETE FROM clientes WHERE id=?");
                        $stmt->execute([$_GET['id']]);
                        echo "<p class='success' title='Cliente eliminado'>Cliente eliminado.</p>";
                    }
?>
