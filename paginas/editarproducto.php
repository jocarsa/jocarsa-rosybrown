<?php
if (!isset($_GET['id'])) {
    echo "ID de producto no proporcionado.";
    exit;
}
$producto_id = intval($_GET['id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'])) {
    $stmt = $db->prepare("UPDATE productos SET nombre=?, descripcion=?, price=? WHERE id=?");
    $stmt->execute([
        $_POST['nombre'],
        $_POST['descripcion'],
        $_POST['price'],
        $producto_id
    ]);
    echo "<script>window.location.href='rosybrown.php?page=productos';</script>";
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
    <form method="post" action="rosybrown.php?page=producto_editar&id=<?php echo $producto_id; ?>" class="form-full">
        <div class="form-row">
            <div class="form-label">
                <label title="Nombre del producto">Nombre del Producto:</label>
            </div>
            <div class="form-field">
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label title="Descripción del producto">Descripción:</label>
            </div>
            <div class="form-field">
                <textarea name="descripcion" rows="4" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label title="Precio del producto">Precio:</label>
            </div>
            <div class="form-field">
                <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($producto['price']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-field" style="margin-left: auto;">
                <button type="submit" class="btn-submit" title="Actualizar producto">Actualizar Producto</button>
            </div>
        </div>
    </form>
</body>
</html>

