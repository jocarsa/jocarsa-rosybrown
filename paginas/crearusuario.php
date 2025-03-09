<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuario'])) {
    $stmt = $db->prepare("INSERT INTO usuarios (usuario, email, nombre, password) VALUES (?,?,?,?)");
    $stmt->execute([
        $_POST['usuario'],
        $_POST['email'],
        $_POST['nombre'],
        $_POST['password']
    ]);
    echo "<script>window.location.href='index.php?page=usuarios';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2 title="Crear nuevo usuario">Crear Usuario</h2>
    <form method="post" action="index.php?page=usuario_crear" class="form-full">
        <div class="form-row">
            <div class="form-label">
                <label title="Nombre de usuario">Usuario:</label>
            </div>
            <div class="form-field">
                <input type="text" name="usuario" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label title="Correo electrónico">Email:</label>
            </div>
            <div class="form-field">
                <input type="email" name="email" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label title="Nombre completo">Nombre:</label>
            </div>
            <div class="form-field">
                <input type="text" name="nombre" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label title="Contraseña">Contraseña:</label>
            </div>
            <div class="form-field">
                <input type="password" name="password" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-field" style="margin-left: auto;">
                <button type="submit" class="btn-submit" title="Crear usuario">Crear Usuario</button>
            </div>
        </div>
    </form>
</body>
</html>

