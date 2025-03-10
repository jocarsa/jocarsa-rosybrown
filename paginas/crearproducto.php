<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'])) {
    $stmt = $db->prepare("INSERT INTO productos (nombre, descripcion, price) VALUES (?,?,?)");
    $stmt->execute([
        $_POST['nombre'],
        $_POST['descripcion'],
        $_POST['price']
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
                <label title="Nombre del producto">Nombre del Producto:</label>
            </div>
            <div class="form-field">
                <input type="text" name="nombre" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label title="Descripción del producto">Descripción:</label>
            </div>
            <div class="form-field">
                <textarea name="descripcion" rows="4" required></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label title="Precio del producto">Precio:</label>
            </div>
            <div class="form-field">
                <input type="number" step="0.01" name="price" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-field" style="margin-left: auto;">
                <button type="submit" class="btn-submit" title="Crear producto">Crear Producto</button>
            </div>
        </div>
    </form>
</body>
</html>

