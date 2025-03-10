<?php
// Procesar cierre de sesión
if (isset($_GET['accion']) && $_GET['accion'] == 'logout') {
    session_destroy();
    header("Location: rosybrown.php");
    exit;
}
?>
