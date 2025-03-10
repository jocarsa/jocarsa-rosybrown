<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $stmt = $db->prepare("UPDATE mis_datos SET invoice_title=?, invoice_subtitle=?, my_name=?, address=?, postal_code=?, city=?, id_number=?, bank_account=?, invoice_footer=? WHERE id=1");
                        $stmt->execute([
                            $_POST['invoice_title'],
                            $_POST['invoice_subtitle'],
                            $_POST['my_name'],
                            $_POST['address'],
                            $_POST['postal_code'],
                            $_POST['city'],
                            $_POST['id_number'],
                            $_POST['bank_account'],
                            $_POST['invoice_footer']
                        ]);
                        echo "<p class='success' title='Operación exitosa'>Datos actualizados correctamente.</p>";
                    }
                    $stmt = $db->query("SELECT * FROM mis_datos WHERE id=1");
                    $misDatos = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <h2 title="Editar datos de la factura">Mis Datos</h2>
                    
                    <p>En esta sección podrá actualizar y configurar la información que aparece en sus facturas. Esto incluye datos personales, razón social, dirección, código postal, ciudad, número de identificación, cuenta bancaria y el pie de factura. Mantener estos datos actualizados es fundamental para que la facturación refleje correctamente su identidad y obligaciones fiscales.

</p>
                    <form method="post" action="index.php?page=mis_datos" class="form-full">
                        <div class="form-row">
                            <div class="form-label">
                                <label title="Ingrese el título de la factura (ej: FACTURA)">Título de Factura:</label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="invoice_title" value="<?php echo htmlspecialchars($misDatos['invoice_title']); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label title="Ingrese el subtítulo de la factura (ej: SERVICIO PROFESIONAL)">Subtítulo de Factura:</label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="invoice_subtitle" value="<?php echo htmlspecialchars($misDatos['invoice_subtitle']); ?>" required>
                            </div>
                        </div>
                        <!-- Additional form rows follow the same two-column layout -->
                        <div class="form-row">
                            <div class="form-label">
                                <label title="Ingrese su nombre o razón social">Mi Nombre:</label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="my_name" value="<?php echo htmlspecialchars($misDatos['my_name']); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label title="Ingrese la dirección completa">Dirección:</label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="address" value="<?php echo htmlspecialchars($misDatos['address']); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label title="Ingrese el código postal">Código Postal:</label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="postal_code" value="<?php echo htmlspecialchars($misDatos['postal_code']); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label title="Ingrese la ciudad">Ciudad:</label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="city" value="<?php echo htmlspecialchars($misDatos['city']); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label title="Ingrese el número de identificación fiscal (NIF/CIF)">Nº Identificación:</label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="id_number" value="<?php echo htmlspecialchars($misDatos['id_number']); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label title="Ingrese la cuenta bancaria completa">Cuenta Bancaria:</label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="bank_account" value="<?php echo htmlspecialchars($misDatos['bank_account']); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label title="Ingrese el pie de factura (mensaje final)">Pie de Factura:</label>
                            </div>
                            <div class="form-field">
                                <textarea name="invoice_footer" required><?php echo htmlspecialchars($misDatos['invoice_footer']); ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-field" style="margin-left: auto;">
                                <button type="submit" class="btn-submit" title="Guardar datos de la factura">Guardar Datos</button>
                            </div>
                        </div>
                    </form>
                    <?php
?>
