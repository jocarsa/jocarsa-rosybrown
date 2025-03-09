<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cliente_nombre'])) {
                        $stmt = $db->prepare("INSERT INTO clientes (name, address, postal_code, city, id_number) VALUES (?,?,?,?,?)");
                        $stmt->execute([
                            $_POST['cliente_nombre'],
                            $_POST['address'],
                            $_POST['postal_code'],
                            $_POST['city'],
                            $_POST['id_number']
                        ]);
                        echo "<script>window.location.href='index.php?page=clientes';</script>";
                        exit;
                    }
                    ?>
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <title>Crear Cliente</title>
                        <link rel="stylesheet" href="css/style.css">
                    </head>
                    <body>
                        <h2 title="Crear nuevo cliente">Crear Cliente</h2>
                        <form method="post" action="index.php?page=cliente_crear" class="form-full">
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Nombre completo del cliente">Nombre:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="cliente_nombre" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Dirección del cliente">Dirección:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="address" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Código postal del cliente">Código Postal:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="postal_code" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Ciudad del cliente">Ciudad:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="city" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Número de identificación fiscal">Nº Identificación:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="id_number" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-field" style="margin-left: auto;">
                                    <button type="submit" class="btn-submit" title="Crear cliente">Crear Cliente</button>
                                </div>
                            </div>
                        </form>
                    </body>
                    </html>
                    <?php
?>
