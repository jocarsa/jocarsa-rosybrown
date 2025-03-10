<?php
// páginas/informes.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Informes</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .informes-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      margin-top: 20px;
    }
    .informe-card {
      background: #fff;
      border: 1px solid #ddd;
      padding: 20px;
      border-radius: 4px;
      width: 300px;
      text-align: center;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .informe-card:hover {
      transform: translateY(-5px);
    }
    .informe-card h3 {
      margin-bottom: 10px;
      color: #333;
    }
    .informe-card p {
      font-size: 14px;
      color: #555;
      margin-bottom: 20px;
    }
    .informe-card a.btn-submit {
      background: #007bff;
      color: #fff;
      padding: 10px 20px;
      border-radius: 4px;
      text-decoration: none;
      font-weight: bold;
    }
    .informe-card a.btn-submit:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <h2 title="Informes">Informes</h2>
  <p>Seleccione el informe que desea visualizar:</p>
  <div class="informes-container">
    <div class="informe-card" title="Libro de Ingresos">
      <h3>Libro de Ingresos</h3>
      <p>Listado detallado de todos los ingresos facturados.</p>
      <a href="rosybrown.php?page=informes_libro_ingresos" class="btn-submit" title="Ver Libro de Ingresos">Ver</a>
    </div>
    <div class="informe-card" title="Libro de Gastos">
      <h3>Libro de Gastos</h3>
      <p>Listado detallado de todos los gastos registrados.</p>
      <a href="rosybrown.php?page=informes_libro_gastos" class="btn-submit" title="Ver Libro de Gastos">Ver</a>
    </div>
    <div class="informe-card" title="Libro Mayor">
      <h3>Libro Mayor</h3>
      <p>Consolidado de ingresos y gastos ordenado cronológicamente.</p>
      <a href="rosybrown.php?page=informes_libro_mayor" class="btn-submit" title="Ver Libro Mayor">Ver</a>
    </div>
  </div>
</body>
</html>

