<?php
if (!isset($_GET['id'])) {
                        echo "ID de producto no proporcionado.";
                        exit;
                    }
                    $producto_id = intval($_GET['id']);
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_name'])) {
                        $stmt = $db->prepare("UPDATE productos SET product_name=?, description1=?, description2=?, description3=?, description4=?, description5=?, description6=?, description7=?, description8=?, description9=?, description10=? WHERE id=?");
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
                            $_POST['description10'],
                            $producto_id
                        ]);
                        echo "<script>window.location.href='index.php?page=productos';</script>";
                        exit;
                    }
                    $stmt = $db->prepare("SELECT * FROM productos WHERE id=?");
                    $stmt->execute([$producto_id]);
                    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <title>Editar Producto</title>
                        <link rel="stylesheet" href="css/style.css">
                    </head>
                    <body>
                        <h2 title="Editar producto">Editar Producto</h2>
                        <form method="post" action="index.php?page=producto_editar&id=<?php echo $producto_id; ?>" class="form-full">
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Nombre del producto (ej: Diseño Web)">Nombre del Producto:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="product_name" value="<?php echo htmlspecialchars($producto['product_name']); ?>" required>
                                </div>
                            </div>
                            <?php for ($i = 1; $i <= 10; $i++): 
                                $desc = isset($producto["description$i"]) ? htmlspecialchars($producto["description$i"]) : '';
                            ?>
                            <div class="form-row">
                                <div class="form-label">
                                    <label title="Línea de descripción <?php echo $i; ?>">Línea de descripción <?php echo $i; ?>:</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="description<?php echo $i; ?>" value="<?php echo $desc; ?>">
                                </div>
                            </div>
                            <?php endfor; ?>
                            <div class="form-row">
                                <div class="form-field" style="margin-left: auto;">
                                    <button type="submit" class="btn-submit" title="Actualizar producto">Actualizar Producto</button>
                                </div>
                            </div>
                        </form>
                    </body>
                    </html>
                    <?php
?>
