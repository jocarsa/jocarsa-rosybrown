<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_name'])) {
                        $stmt = $db->prepare("INSERT INTO productos (product_name, description1, description2, description3, description4, description5, description6, description7, description8, description9, description10) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                        $stmt->execute([
                            $_POST['product_name'],
                            $_POST['description1'],
                            $_POST['description2'],
                            $_POST['description3'],
                            $_POST['description4'],
                            $_POST['description5'],
                            $_POST['description6'],
                            $_POST['description7'],
                            $_POST['description8'],
                            $_POST['description9'],
                            $_POST['description10']
                        ]);
                        echo "<script>window.location.href='index.php?page=productos';</script>";
                        exit;
                    }
                    ?>
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <title>Crear Producto</title>
                        <link rel="stylesheet" href="css/style.css">
                    </head>
                    <body>
                        <h2 title="Crear nuevo producto">Crear Producto</h2>
                        <form method="post" action="index.php?page=producto_crear" class="form-full">
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Nombre del producto (ej: Diseño Web)">Nombre del Producto:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="product_name" required>
                                </div>
                            </div>
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Línea de descripción <?php echo $i; ?> (ej: Diseño responsive)">Línea de descripción <?php echo $i; ?>:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="description<?php echo $i; ?>">
                                </div>
                            </div>
                            <?php endfor; ?>
                            <div class="form-row">
                                <div class="form-field" style="margin-left: auto;">
                                    <button type="submit" class="btn-submit" title="Crear producto">Crear Producto</button>
                                </div>
                            </div>
                        </form>
                    </body>
                    </html>
                    <?php
?>
