<?php
?>
                    <h2 title="Listado de facturas">Facturas</h2>
                    <a href="index.php?page=factura_crear" class="btn-submit" title="Crear nueva factura" style="margin-bottom:20px; display:inline-block;">Crear Factura</a>
                    <table title="Tabla de facturas">
                        <tr>
                            <th>Nº Factura</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                        <?php
                        $stmt = $db->query("SELECT f.*, c.name as cliente_nombre FROM facturas f LEFT JOIN clientes c ON f.cliente_id = c.id");
                        while ($factura = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($factura['invoice_number']) . "</td>";
                            echo "<td>" . htmlspecialchars($factura['fecha']) . "</td>";
                            echo "<td>" . htmlspecialchars($factura['cliente_nombre']) . "</td>";
                            echo "<td>" . htmlspecialchars($factura['total']) . "</td>";
                            echo "<td>";
                            echo "<a href='index.php?page=factura_ver&id=" . $factura['id'] . "' title='Ver factura'>Ver</a> | ";
                            echo "<a href='index.php?page=factura_editar&id=" . $factura['id'] . "' title='Editar factura'>Editar</a> | ";
                            echo "<a href='index.php?page=factura_imprimir&id=" . $factura['id'] . "' title='Imprimir factura'>Imprimir</a> | ";
                            echo "<a href='index.php?page=facturas&accion=eliminar&id=" . $factura['id'] . "' onclick='return confirm(\"¿Está seguro?\")' title='Eliminar factura'>Eliminar</a>";
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
