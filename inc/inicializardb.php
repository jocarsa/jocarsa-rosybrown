<?php
// Función para inicializar las tablas (si no existen)
function inicializarDB($db) {
    // Tabla de usuarios
    $db->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario TEXT UNIQUE,
        email TEXT,
        nombre TEXT,
        password TEXT
    )");
    $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ?");
    $stmt->execute(['jocarsa']);
    if ($stmt->fetchColumn() == 0) {
        // En producción, usar password_hash / password_verify
        $stmt = $db->prepare("INSERT INTO usuarios (usuario, email, nombre, password) VALUES (?,?,?,?)");
        $stmt->execute(['jocarsa', 'info@josevicentecarratala.com', 'José Vicente Carratala', 'jocarsa']);
    }
    // Tabla de mis_datos (datos de factura)
    $db->exec("CREATE TABLE IF NOT EXISTS mis_datos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        invoice_title TEXT,
        invoice_subtitle TEXT,
        my_name TEXT,
        address TEXT,
        postal_code TEXT,
        city TEXT,
        id_number TEXT,
        bank_account TEXT,
        invoice_footer TEXT
    )");
    $stmt = $db->query("SELECT COUNT(*) FROM mis_datos");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO mis_datos (invoice_title, invoice_subtitle, my_name, address, postal_code, city, id_number, bank_account, invoice_footer)
        VALUES ('FACTURA', 'SERVICIO PROFESIONAL', 'Su Nombre', 'Su Dirección', '00000', 'Ciudad', 'ID123456', 'ESXX XXXX XXXX XXXX XXXX XXXX', 'Gracias por su compra')");
    }
    // Tabla de clientes
    $db->exec("CREATE TABLE IF NOT EXISTS clientes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        address TEXT,
        postal_code TEXT,
        city TEXT,
        id_number TEXT
    )");
    // Tabla de productos
    $db->exec("CREATE TABLE IF NOT EXISTS productos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_name TEXT,
        description1 TEXT,
        description2 TEXT,
        description3 TEXT,
        description4 TEXT,
        description5 TEXT,
        description6 TEXT,
        description7 TEXT,
        description8 TEXT,
        description9 TEXT,
        description10 TEXT
    )");
    // Tabla de facturas
    $db->exec("CREATE TABLE IF NOT EXISTS facturas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        invoice_number TEXT,
        fecha TEXT,
        cliente_id INTEGER,
        mis_datos_id INTEGER,
        total REAL,
        FOREIGN KEY(cliente_id) REFERENCES clientes(id),
        FOREIGN KEY(mis_datos_id) REFERENCES mis_datos(id)
    )");
    // Tabla de líneas de factura
    $db->exec("CREATE TABLE IF NOT EXISTS lineas_factura (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        factura_id INTEGER,
        producto_id INTEGER,
        cantidad INTEGER,
        precio_unitario REAL,
        total REAL,
        FOREIGN KEY(factura_id) REFERENCES facturas(id),
        FOREIGN KEY(producto_id) REFERENCES productos(id)
    )");
}
?>
