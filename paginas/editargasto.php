<?php
if (!isset($_GET['id'])) {
    echo "ID de gasto no proporcionado.";
    exit;
}
$gasto_id = intval($_GET['id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['gasto_deducible'])) {
    $stmt = $db->prepare("UPDATE gastos SET gasto_deducible=?, fecha_emision=?, fecha_operaciones=?, numero_factura=?, fecha_factura=?, proveedor_id=?, total_factura=?, base_imponible=?, tipo_retencion=?, importe_retencion=?, tipo_iva=?, cuota_iva=?, iva_deducido=? WHERE id=?");
    $stmt->execute([
        $_POST['gasto_deducible'],
        $_POST['fecha_emision'],
        $_POST['fecha_operaciones'],
        $_POST['numero_factura'],
        $_POST['fecha_factura'],
        $_POST['proveedor_id'],
        $_POST['total_factura'],
        $_POST['base_imponible'],
        $_POST['tipo_retencion'],
        $_POST['importe_retencion'],
        $_POST['tipo_iva'],
        $_POST['cuota_iva'],
        $_POST['iva_deducido'],
        $gasto_id
    ]);
    echo "<script>window.location.href='index.php?page=gastos';</script>";
    exit;
}
$stmt = $db->prepare("SELECT * FROM gastos WHERE id=?");
$stmt->execute([$gasto_id]);
$gasto = $stmt->fetch(PDO::FETCH_ASSOC);
$proveedores = $db->query("SELECT * FROM proveedores")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Gasto</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2 title="Editar gasto">Editar Gasto</h2>
    <form method="post" action="index.php?page=editargasto&id=<?php echo $gasto_id; ?>" class="form-full">
        <div class="form-row">
            <div class="form-label"><label>Gasto Deducible (€):</label></div>
            <div class="form-field"><input type="number" step="0.01" name="gasto_deducible" value="<?php echo htmlspecialchars($gasto['gasto_deducible']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Fecha Emisión:</label></div>
            <div class="form-field"><input type="date" name="fecha_emision" value="<?php echo htmlspecialchars($gasto['fecha_emision']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Fecha Operaciones:</label></div>
            <div class="form-field"><input type="date" name="fecha_operaciones" value="<?php echo htmlspecialchars($gasto['fecha_operaciones']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Nº Factura:</label></div>
            <div class="form-field"><input type="text" name="numero_factura" value="<?php echo htmlspecialchars($gasto['numero_factura']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Fecha Factura:</label></div>
            <div class="form-field"><input type="date" name="fecha_factura" value="<?php echo htmlspecialchars($gasto['fecha_factura']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Proveedor:</label></div>
            <div class="form-field">
                <select name="proveedor_id" required>
                    <option value="">--Selecciona Proveedor--</option>
                    <?php foreach($proveedores as $prov): ?>
                        <option value="<?php echo $prov['id']; ?>" <?php if($prov['id'] == $gasto['proveedor_id']) echo "selected"; ?>><?php echo htmlspecialchars($prov['razon_social']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <!-- Resto de los campos -->
        <div class="form-row">
            <div class="form-label"><label>Total Factura (€):</label></div>
            <div class="form-field"><input type="number" step="0.01" name="total_factura" value="<?php echo htmlspecialchars($gasto['total_factura']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Base Imponible (€):</label></div>
            <div class="form-field"><input type="number" step="0.01" name="base_imponible" value="<?php echo htmlspecialchars($gasto['base_imponible']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Tipo de Retención:</label></div>
            <div class="form-field"><input type="text" name="tipo_retencion" value="<?php echo htmlspecialchars($gasto['tipo_retencion']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Importe Retención (€):</label></div>
            <div class="form-field"><input type="number" step="0.01" name="importe_retencion" value="<?php echo htmlspecialchars($gasto['importe_retencion']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Tipo de IVA:</label></div>
            <div class="form-field"><input type="text" name="tipo_iva" value="<?php echo htmlspecialchars($gasto['tipo_iva']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Cuota IVA (€):</label></div>
            <div class="form-field"><input type="number" step="0.01" name="cuota_iva" value="<?php echo htmlspecialchars($gasto['cuota_iva']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>IVA Deducido (€):</label></div>
            <div class="form-field"><input type="number" step="0.01" name="iva_deducido" value="<?php echo htmlspecialchars($gasto['iva_deducido']); ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-field" style="margin-left: auto;">
                <button type="submit" class="btn-submit" title="Actualizar gasto">Actualizar Gasto</button>
            </div>
        </div>
    </form>
</body>
</html>

