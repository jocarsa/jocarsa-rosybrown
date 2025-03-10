<?php
// inc/inicializardb.php
function inicializarDB($db) {
    // Tabla de usuarios (unchanged)
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

    // Tabla de clientes (unchanged)
    $db->exec("CREATE TABLE IF NOT EXISTS clientes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        address TEXT,
        postal_code TEXT,
        city TEXT,
        id_number TEXT
    )");

    // --- NEW: Actualización de productos ---
    // Drop old structure if needed or create new structure:
    // (In a migration you might rename the old table and import data.)
    $db->exec("CREATE TABLE IF NOT EXISTS productos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT,
        descripcion TEXT,
        price REAL
    )");

    // Tabla de epígrafe (new)
    $db->exec("CREATE TABLE IF NOT EXISTS epigrafes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        iva_percentage INTEGER
    )");
    // (Optional: insert default epígrafes)
    /*
    $stmt = $db->query("SELECT COUNT(*) FROM epigrafes");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO epigrafes (name, iva_percentage) VALUES 
            ('General', 21),
            ('Reducido', 10),
            ('Superreducido', 4),
            ('Exento', 0)
        ");
    }*/

    // Tabla de facturas (add epigrafe_id)
    $db->exec("CREATE TABLE IF NOT EXISTS facturas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        invoice_number TEXT,
        fecha TEXT,
        cliente_id INTEGER,
        mis_datos_id INTEGER,
        total REAL,
        epigrafe_id INTEGER,
        FOREIGN KEY(cliente_id) REFERENCES clientes(id),
        FOREIGN KEY(mis_datos_id) REFERENCES mis_datos(id),
        FOREIGN KEY(epigrafe_id) REFERENCES epigrafes(id)
    )");

    // Tabla de líneas de factura (unchanged)
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
// Tabla de proveedores
$db->exec("CREATE TABLE IF NOT EXISTS proveedores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    razon_social TEXT,
    direccion TEXT,
    codigo_postal TEXT,
    poblacion TEXT,
    identificacion_fiscal TEXT,
    contacto_nombre TEXT,
    contacto_email TEXT,
    contacto_telefono TEXT
)");

// Tabla de gastos
$db->exec("CREATE TABLE IF NOT EXISTS gastos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    gasto_deducible REAL,
    fecha_emision TEXT,
    fecha_operaciones TEXT,
    numero_factura TEXT,
    fecha_factura TEXT,
    proveedor_id INTEGER,
    total_factura REAL,
    base_imponible REAL,
    tipo_retencion TEXT,
    importe_retencion REAL,
    tipo_iva TEXT,
    cuota_iva REAL,
    iva_deducido REAL,
    FOREIGN KEY(proveedor_id) REFERENCES proveedores(id)
)");

?>

