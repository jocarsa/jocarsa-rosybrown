<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $stmt = $db->prepare("INSERT INTO epigrafes (name, iva_percentage) VALUES (?,?)");
    $stmt->execute([
        $_POST['name'],
        $_POST['iva_percentage']
    ]);
    echo "<script>window.location.href='rosybrown.php?page=epigrafes';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Epígrafe</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2 title="Crear nuevo epígrafe">Crear Epígrafe</h2>
    <form method="post" action="rosybrown.php?page=epigrafes_crear" class="form-full">
        <div class="form-row">
            <div class="form-label">
                <label title="Nombre del epígrafe">Nombre:</label>
            </div>
            <div class="form-field">
                <input type="text" name="name" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label title="IVA asociado (%)">IVA (%):</label>
            </div>
            <div class="form-field">
                <select name="iva_percentage" required>
                    <option value="">--Selecciona IVA--</option>
                    <option value="21">General (21%)</option>
                    <option value="10">Reducido (10%)</option>
                    <option value="4">Superreducido (4%)</option>
                    <option value="0">Exento (0%)</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-field" style="margin-left: auto;">
                <button type="submit" class="btn-submit" title="Crear epígrafe">Crear Epígrafe</button>
            </div>
        </div>
    </form>
</body>
</html>

