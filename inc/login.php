<?php

	// Procesar login si el usuario no está autenticado
if (!isset($_SESSION['usuario'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuario'], $_POST['password'])) {
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$_POST['usuario']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && $user['password'] === $_POST['password']) {
            $_SESSION['usuario'] = $user;
            header("Location: index.php");
            exit;
        } else {
            $error_login = "Usuario o contraseña incorrectos.";
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>jocarsa | rosybrown - Iniciar Sesión</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="login-container">
            <!-- Cabecera con nombre de la aplicación y logo -->
            <div class="app-header" title="jocarsa | rosybrown">
                <img src="rosybrown.png" alt="Logo jocarsa | rosybrown" title="Logo jocarsa | rosybrown" style="max-width:100%; display:block; margin:0 auto;">
                <h1 style="text-align:center">jocarsa | rosybrown</h1>
            </div>
            
            <?php if(isset($error_login)) echo "<p class='error' title='Mensaje de error'>$error_login</p>"; ?>
            <form method="post" action="index.php">
                <div class="form-row">
                    <div class="form-label">
                        <label title="Ingrese su usuario (ej: jocarsa)">Usuario:</label>
                    </div>
                    <div class="form-field">
                        <input type="text" name="usuario" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label title="Ingrese su contraseña">Contraseña:</label>
                    </div>
                    <div class="form-field">
                        <input type="password" name="password" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field" style="margin-left: auto;">
                        <button type="submit" title="Haga clic para acceder">Acceder</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

?>
