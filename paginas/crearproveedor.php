<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['razon_social'])) {
    $stmt = $db->prepare("INSERT INTO proveedores (razon_social, direccion, codigo_postal, poblacion, identificacion_fiscal, contacto_nombre, contacto_email, contacto_telefono) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $_POST['razon_social'],
        $_POST['direccion'],
        $_POST['codigo_postal'],
        $_POST['poblacion'],
        $_POST['identificacion_fiscal'],
        $_POST['contacto_nombre'],
        $_POST['contacto_email'],
        $_POST['contacto_telefono']
    ]);
    echo "<script>window.location.href='rosybrown.php?page=proveedores';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Proveedor</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2 title="Crear nuevo proveedor">Crear Proveedor</h2>
    <form method="post" action="rosybrown.php?page=crearproveedor" class="form-full">
        <div class="form-row">
            <div class="form-label"><label>Razón Social:</label></div>
            <div class="form-field"><input type="text" name="razon_social" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Dirección:</label></div>
            <div class="form-field"><input type="text" name="direccion" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Código Postal:</label></div>
            <div class="form-field"><input type="text" name="codigo_postal" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Población:</label></div>
            <div class="form-field"><input type="text" name="poblacion" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Identificación Fiscal:</label></div>
            <div class="form-field"><input type="text" name="identificacion_fiscal" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Nombre de Contacto:</label></div>
            <div class="form-field"><input type="text" name="contacto_nombre" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Email de Contacto:</label></div>
            <div class="form-field"><input type="email" name="contacto_email" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Teléfono de Contacto:</label></div>
            <div class="form-field"><input type="text" name="contacto_telefono" required></div>
        </div>
        <div class="form-row">
            <div class="form-field" style="margin-left: auto;">
                <button type="submit" class="btn-submit" title="Crear proveedor">Crear Proveedor</button>
            </div>
        </div>
    </form>
</body>
</html>

