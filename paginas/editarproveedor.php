<?php
if (!isset($_GET['id'])) {
    echo "ID de proveedor no proporcionado.";
    exit;
}
$prov_id = intval($_GET['id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['razon_social'])) {
    $stmt = $db->prepare("UPDATE proveedores SET razon_social=?, direccion=?, codigo_postal=?, poblacion=?, identificacion_fiscal=?, contacto_nombre=?, contacto_email=?, contacto_telefono=? WHERE id=?");
    $stmt->execute([
        $_POST['razon_social'],
        $_POST['direccion'],
        $_POST['codigo_postal'],
        $_POST['poblacion'],
        $_POST['identificacion_fiscal'],
        $_POST['contacto_nombre'],
        $_POST['contacto_email'],
        $_POST['contacto_telefono'],
        $prov_id
    ]);
    echo "<script>window.location.href='index.php?page=proveedores';</script>";
    exit;
}
$stmt = $db->prepare("SELECT * FROM proveedores WHERE id=?");
$stmt->execute([$prov_id]);
$prov = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proveedor</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2 title="Editar proveedor">Editar Proveedor</h2>
    <form method="post" action="index.php?page=editarproveedor&id=<?php echo $prov_id; ?>" class="form-full">
        <div class="form-row">
            <div class="form-label"><label>Razón Social:</label></div>
            <div class="form-field"><input type="text" name="razon_social" value="<?php echo htmlspecialchars($prov['razon_social']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Dirección:</label></div>
            <div class="form-field"><input type="text" name="direccion" value="<?php echo htmlspecialchars($prov['direccion']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Código Postal:</label></div>
            <div class="form-field"><input type="text" name="codigo_postal" value="<?php echo htmlspecialchars($prov['codigo_postal']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Población:</label></div>
            <div class="form-field"><input type="text" name="poblacion" value="<?php echo htmlspecialchars($prov['poblacion']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Identificación Fiscal:</label></div>
            <div class="form-field"><input type="text" name="identificacion_fiscal" value="<?php echo htmlspecialchars($prov['identificacion_fiscal']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Nombre de Contacto:</label></div>
            <div class="form-field"><input type="text" name="contacto_nombre" value="<?php echo htmlspecialchars($prov['contacto_nombre']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Email de Contacto:</label></div>
            <div class="form-field"><input type="email" name="contacto_email" value="<?php echo htmlspecialchars($prov['contacto_email']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Teléfono de Contacto:</label></div>
            <div class="form-field"><input type="text" name="contacto_telefono" value="<?php echo htmlspecialchars($prov['contacto_telefono']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-field" style="margin-left: auto;">
                <button type="submit" class="btn-submit" title="Actualizar proveedor">Actualizar Proveedor</button>
            </div>
        </div>
    </form>
</body>
</html>

