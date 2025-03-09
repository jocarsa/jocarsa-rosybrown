<?php
$config = require 'config.php';
session_start();

// Conexión a la base de datos usando la URL de config.php
try {
    $db = new PDO($config['db_url']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $ex) {
    die("Error al conectar con la base de datos");
}

include "inc/inicializardb.php";
inicializarDB($db);

include "inc/cerrarsesion.php";

include "inc/login.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>jocarsa | rosybrown - Panel de Administración</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>

</head>
<body>
    <header class="admin-header" title="Panel de administración de jocarsa | rosybrown">
        <h1>jocarsa | rosybrown</h1>
        <div class="admin-logout">
            <a href="index.php?accion=logout" title="Cerrar sesión">Cerrar Sesión</a>
        </div>
    </header>
    <div class="admin-container">
        <nav class="admin-nav">
            <ul>
                <li><a href="index.php" title="Ir a la página de inicio">Inicio</a></li>
                <li><a href="index.php?page=mis_datos" title="Editar mis datos de factura">Mis Datos</a></li>
                <li><a href="index.php?page=clientes" title="Gestionar clientes">Clientes</a></li>
                <li><a href="index.php?page=productos" title="Gestionar productos">Productos</a></li>
                <li><a href="index.php?page=facturas" title="Gestionar facturas">Facturas</a></li>
                <li><a href="index.php?page=usuarios" title="Gestionar usuarios">Usuarios</a></li>
            </ul>
        </nav>
        <main class="admin-content">
            <?php
            // Enrutado de páginas mediante "page"
            $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
            switch ($page) {
                case 'dashboard':
                    include "paginas/escritorio.php";
                    break;
                case 'mis_datos':
                    include "paginas/misdatos.php";
                    break;
                case 'clientes':
                    include "paginas/clientes.php";
                    break;
                case 'cliente_crear':
                    include "paginas/crearcliente.php";
                    exit;
                case 'cliente_editar':
                    include "paginas/editarcliente.php";
                    exit;
                case 'productos':
                    include "paginas/productos.php";
                    break;
                case 'producto_crear':
                    include "paginas/crearproducto.php";
                    exit;
                case 'producto_editar':
                    include "paginas/editarproducto.php";
                    exit;
                case 'facturas':
                    include "paginas/facturas.php";
                    break;
                case 'factura_crear':
                    include "paginas/crearfactura.php";
                    exit;
                case 'factura_editar':
                    include "paginas/editarfactura.php";
                    exit;
                case 'factura_ver':
                    include "paginas/verfactura.php";
                    exit;
                case 'factura_imprimir':
                    include "paginas/imprimirfactura.php";
                    exit;
                case 'usuarios':
                    include "paginas/usuarios.php";
                    break;
                case 'usuario_crear':
                    include "paginas/crearusuario.php";
                    exit;
                case 'usuario_editar':
                    include "paginas/editarusuario.php";
                    exit;
                default:
                    echo "<p title='Página no encontrada'>Página no encontrada.</p>";
            }
            ?>
        </main>
    </div>
    <script src="https://ghostwhite.jocarsa.com/analytics.js?user=rosybrown.jocarsa.com"></script>
<link rel="stylesheet" href="https://jocarsa.github.io/jocarsa-pink/jocarsa%20%7C%20pink.css">
<script src="https://jocarsa.github.io/jocarsa-pink/jocarsa%20%7C%20pink.js"></script>
</body>
</html>

