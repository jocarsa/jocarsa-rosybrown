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

$subtotal = $factura['total'];
$iva = $subtotal * 0.21;
$irpf = $subtotal * 0.15;
$total_final = $subtotal + $iva - $irpf;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Imprimir Factura</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        @media print {
            header, nav,button {
                display: none !important;
            }
            #factura {
                display: block !important;
                visibility: visible !important;
                position: relative;
                left: 0;
                top: 0;
                width: 100%;
                border:none;
            }
            #factura ul{
            	padding-left:20px;
            }
            body {
                margin: 0;
                padding: 0;
                height:100%;
            }
            .footer,.bank-details{
            	position:relative;
            	bottom:0px;
            }
            .invoice-preview{
            	height:90%;
            }
            @page {
                size: A4;
               height:100%;
            }
        }
    </style>
    <script>
        function printInvoice() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</head>
<body onload="printInvoice()">
    <?php include 'invoice_template.php'; ?>
</body>
</html>

