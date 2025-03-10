<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['gasto_deducible'])) {
    $stmt = $db->prepare("INSERT INTO gastos (gasto_deducible, fecha_emision, fecha_operaciones, numero_factura, fecha_factura, proveedor_id, total_factura, base_imponible, tipo_retencion, importe_retencion, tipo_iva, cuota_iva, iva_deducido) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
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
        $_POST['iva_deducido']
    ]);
    echo "<script>window.location.href='index.php?page=gastos';</script>";
    exit;
}
$proveedores = $db->query("SELECT * FROM proveedores")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Gasto</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2 title="Crear nuevo gasto">Crear Gasto</h2>
    <form method="post" action="index.php?page=creargasto" class="form-full">
        <div class="form-row">
            <div class="form-label"><label>Gasto Deducible (€):</label></div>
            <div class="form-field"><input type="number" step="0.01" name="gasto_deducible" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Fecha Emisión:</label></div>
            <div class="form-field"><input type="date" name="fecha_emision" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Fecha Operaciones:</label></div>
            <div class="form-field"><input type="date" name="fecha_operaciones" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Nº Factura:</label></div>
            <div class="form-field"><input type="text" name="numero_factura" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Fecha Factura:</label></div>
            <div class="form-field"><input type="date" name="fecha_factura" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Proveedor:</label></div>
            <div class="form-field">
                <select name="proveedor_id" required>
                    <option value="">--Selecciona Proveedor--</option>
                    <?php foreach ($proveedores as $prov): ?>
                        <option value="<?php echo $prov['id']; ?>"><?php echo htmlspecialchars($prov['razon_social']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Total Factura (€):</label></div>
            <div class="form-field"><input type="number" step="0.01" name="total_factura" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Base Imponible (€):</label></div>
            <div class="form-field"><input type="number" step="0.01" name="base_imponible" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Tipo de Retención:</label></div>
            <div class="form-field"><input type="text" name="tipo_retencion" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Importe Retención (€):</label></div>
            <div class="form-field"><input type="number" step="0.01" name="importe_retencion" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Tipo de IVA:</label></div>
            <div class="form-field"><input type="text" name="tipo_iva" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>Cuota IVA (€):</label></div>
            <div class="form-field"><input type="number" step="0.01" name="cuota_iva" required></div>
        </div>
        <div class="form-row">
            <div class="form-label"><label>IVA Deducido (€):</label></div>
            <div class="form-field"><input type="number" step="0.01" name="iva_deducido" required></div>
        </div>
        <div class="form-row">
            <div class="form-field" style="margin-left: auto;">
                <button type="submit" class="btn-submit" title="Crear gasto">Crear Gasto</button>
            </div>
        </div>
    </form>
</body>
</html>

