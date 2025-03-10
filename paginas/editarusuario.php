<?php
if (!isset($_GET['id'])) {
    echo "ID de usuario no proporcionado.";
    exit;
}
$usuario_id = intval($_GET['id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuario'])) {
    $stmt = $db->prepare("UPDATE usuarios SET usuario=?, email=?, nombre=?, password=? WHERE id=?");
    $stmt->execute([
        $_POST['usuario'],
        $_POST['email'],
        $_POST['nombre'],
        $_POST['password'],
        $usuario_id
    ]);
    echo "<script>window.location.href='rosybrown.php?page=usuarios';</script>";
    exit;
}
$stmt = $db->prepare("SELECT * FROM usuarios WHERE id=?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2 title="Editar datos del usuario">Editar Usuario</h2>
    <form method="post" action="rosybrown.php?page=usuario_editar&id=<?php echo $usuario_id; ?>" class="form-full">
        <div class="form-row">
            <div class="form-label">
                <label title="Nombre de usuario">Usuario:</label>
            </div>
            <div class="form-field">
                <input type="text" name="usuario" value="<?php echo htmlspecialchars($usuario['usuario']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label title="Correo electrónico">Email:</label>
            </div>
            <div class="form-field">
                <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label title="Nombre completo">Nombre:</label>
            </div>
            <div class="form-field">
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label title="Contraseña">Contraseña:</label>
            </div>
            <div class="form-field">
                <input type="password" name="password" value="<?php echo htmlspecialchars($usuario['password']); ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-field" style="margin-left: auto;">
                <button type="submit" class="btn-submit" title="Actualizar usuario">Actualizar Usuario</button>
            </div>
        </div>
    </form>
</body>
</html>

