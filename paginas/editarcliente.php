<?php
if (!isset($_GET['id'])) {
                        echo "ID de cliente no proporcionado.";
                        exit;
                    }
                    $cliente_id = intval($_GET['id']);
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cliente_nombre'])) {
                        $stmt = $db->prepare("UPDATE clientes SET name=?, address=?, postal_code=?, city=?, id_number=? WHERE id=?");
                        $stmt->execute([
                            $_POST['cliente_nombre'],
                            $_POST['address'],
                            $_POST['postal_code'],
                            $_POST['city'],
                            $_POST['id_number'],
                            $cliente_id
                        ]);
                        echo "<script>window.location.href='index.php?page=clientes';</script>";
                        exit;
                    }
                    $stmt = $db->prepare("SELECT * FROM clientes WHERE id=?");
                    $stmt->execute([$cliente_id]);
                    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <title>Editar Cliente</title>
                        <link rel="stylesheet" href="css/style.css">
                    </head>
                    <body>
                        <h2 title="Editar datos del cliente">Editar Cliente</h2>
                        <form method="post" action="index.php?page=cliente_editar&id=<?php echo $cliente_id; ?>" class="form-full">
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Nombre completo del cliente">Nombre:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="cliente_nombre" value="<?php echo htmlspecialchars($cliente['name']); ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Dirección del cliente">Dirección:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="address" value="<?php echo htmlspecialchars($cliente['address']); ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Código postal del cliente">Código Postal:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="postal_code" value="<?php echo htmlspecialchars($cliente['postal_code']); ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Ciudad del cliente">Ciudad:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="city" value="<?php echo htmlspecialchars($cliente['city']); ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Número de identificación fiscal">Nº Identificación:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="id_number" value="<?php echo htmlspecialchars($cliente['id_number']); ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-field" style="margin-left: auto;">
                                    <button type="submit" class="btn-submit" title="Actualizar cliente">Actualizar Cliente</button>
                                </div>
                            </div>
                        </form>
                    </body>
                    </html>
                    <?php
?>
