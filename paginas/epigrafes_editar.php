<?php
if (!isset($_GET['id'])) {
    echo "ID de epígrafe no proporcionado.";
    exit;
}
$epi_id = intval($_GET['id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $stmt = $db->prepare("UPDATE epigrafes SET name=?, iva_percentage=? WHERE id=?");
    $stmt->execute([
        $_POST['name'],
        $_POST['iva_percentage'],
        $epi_id
    ]);
    echo "<script>window.location.href='index.php?page=epigrafes';</script>";
    exit;
}
$stmt = $db->prepare("SELECT * FROM epigrafes WHERE id=?");
$stmt->execute([$epi_id]);
$epi = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Epígrafe</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2 title="Editar epígrafe">Editar Epígrafe</h2>
    <form method="post" action="index.php?page=epigrafes_editar&id=<?php echo $epi_id; ?>" class="form-full">
        <div class="form-row">
            <div class="form-label">
                <label title="Nombre del epígrafe">Nombre:</label>
            </div>
            <div class="form-field">
                <input type="text" name="name" value="<?php echo htmlspecialchars($epi['name']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label title="IVA (%)">IVA (%):</label>
            </div>
            <div class="form-field">
                <select name="iva_percentage" required>
                    <option value="21" <?php if($epi['iva_percentage']==21) echo "selected"; ?>>General (21%)</option>
                    <option value="10" <?php if($epi['iva_percentage']==10) echo "selected"; ?>>Reducido (10%)</option>
                    <option value="4" <?php if($epi['iva_percentage']==4) echo "selected"; ?>>Superreducido (4%)</option>
                    <option value="0" <?php if($epi['iva_percentage']==0) echo "selected"; ?>>Exento (0%)</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-field" style="margin-left: auto;">
                <button type="submit" class="btn-submit" title="Actualizar epígrafe">Actualizar Epígrafe</button>
            </div>
        </div>
    </form>
</body>
</html>

