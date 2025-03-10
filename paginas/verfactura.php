<?php
if (!isset($_GET['id'])) {
    echo "ID de factura no proporcionado.";
    exit;
}
$factura_id = intval($_GET['id']);
$stmt = $db->prepare("SELECT f.*, c.name as cliente_nombre, c.address as cliente_address, c.postal_code as cliente_postal, c.city as cliente_city, c.id_number as cliente_id_number FROM facturas f LEFT JOIN clientes c ON f.cliente_id = c.id WHERE f.id=?");
$stmt->execute([$factura_id]);
$factura = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $db->prepare("SELECT * FROM lineas_factura WHERE factura_id=?");
$stmt->execute([$factura_id]);
$lineas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$misDatosStmt = $db->query("SELECT * FROM mis_datos WHERE id=1");
$misDatos = $misDatosStmt->fetch(PDO::FETCH_ASSOC);

// Calculate totals and taxes.
$subtotal = $factura['total'];
$iva = $subtotal * 0.21;
$irpf = $subtotal * 0.15;
$total_final = $subtotal + $iva - $irpf;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Factura</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Ver Factura</h2>
    <?php include 'invoice_template.php'; ?>
</body>
</html>

