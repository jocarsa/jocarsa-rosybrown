<?php
// escritorio.php

// Display welcome message
echo "<h2 title='Bienvenido a la página de inicio'>Inicio</h2>";
echo "<p title='Mensaje de bienvenida'>Bienvenido, " . htmlspecialchars($_SESSION['usuario']['nombre']) . "!</p>";

// Fetch data for monthly totals (for line chart)
// Group by year-month (e.g. "2024-03")
$stmt = $db->query("SELECT strftime('%Y-%m', fecha) as month, SUM(total) as total FROM facturas GROUP BY strftime('%Y-%m', fecha) ORDER BY month");
$monthlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch data for quarterly totals (for bar chart)
$stmt = $db->query("
    SELECT
        CASE
            WHEN CAST(STRFTIME('%m', fecha) AS INTEGER) BETWEEN 1 AND 3 THEN 'Q1-' || CAST(STRFTIME('%Y', fecha) AS TEXT)
            WHEN CAST(STRFTIME('%m', fecha) AS INTEGER) BETWEEN 4 AND 6 THEN 'Q2-' || CAST(STRFTIME('%Y', fecha) AS TEXT)
            WHEN CAST(STRFTIME('%m', fecha) AS INTEGER) BETWEEN 7 AND 9 THEN 'Q3-' || CAST(STRFTIME('%Y', fecha) AS TEXT)
            ELSE 'Q4-' || CAST(STRFTIME('%Y', fecha) AS TEXT)
        END AS quarter,
        SUM(total) as total
    FROM facturas
    GROUP BY quarter
    ORDER BY quarter
");
$quarterlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch data for total per customer (for pie chart)
$stmt = $db->query("SELECT c.name as cliente, SUM(f.total) as total FROM facturas f JOIN clientes c ON f.cliente_id = c.id GROUP BY c.name ORDER BY total DESC");
$customerData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Escritorio - Panel de Administración</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Include your custom chart library -->
    <script src="js/charts.js"></script>
    <style>
      /* CSS Grid for charts layout */
      #charts-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: 20px;
         padding: 20px;
      }
      /* Optional styling for chart titles */
      .chart-title {
          text-align: center;
          font-size: 18px;
          font-weight: bold;
          margin-bottom: 10px;
      }
    </style>
</head>
<body>
    <div id="charts-container">
        <div>
            <div class="chart-title" title="Total facturado por mes">Total Facturado Por Mes</div>
            <div id="line-chart" title="Total facturado por mes"></div>
        </div>
        <div>
            <div class="chart-title" title="Total facturado por trimestre">Total Facturado Por Trimestre</div>
            <div id="bar-chart" title="Total facturado por trimestre"></div>
        </div>
        <div>
            <div class="chart-title" title="Total facturado por cliente">Total Facturado Por Cliente</div>
            <div id="pie-chart" title="Total facturado por cliente"></div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Process monthly data: use the "YYYY-MM" string, append "-01" to get a valid date
            const monthlyDataRaw = <?php echo json_encode(array_map(function($row){
                return [$row['month'], (float)$row['total']];
            }, $monthlyData)); ?>;
            const monthlyData = monthlyDataRaw.map(d => ({
                // Create a date using the first day of the month
                x: new Date(d[0] + "-01").getTime(),
                y: d[1]
            }));

            // Process quarterly data for bar chart
            const quarterlyDataRaw = <?php echo json_encode(array_map(function($row){
                return [$row['quarter'], (float)$row['total']];
            }, $quarterlyData)); ?>;
            const quarterlyData = quarterlyDataRaw.map(d => ({
                label: d[0],
                value: d[1]
            }));

            // Process customer data for pie chart
            const customerDataRaw = <?php echo json_encode(array_map(function($row){
                return [$row['cliente'], (float)$row['total']];
            }, $customerData)); ?>;
            const customerData = customerDataRaw.map(d => ({
                label: d[0],
                value: d[1]
            }));

            // Create and append the interactive charts using the custom library
            const lineChart = MyCharts.createLineChart(monthlyData, {
                width: 600,
                height: 400,
                stroke: "green",
                strokeWidth: 2,
                circleRadius: 4,
                margin: { top: 60, right: 20, bottom: 50, left: 50 }
            });
            document.getElementById('line-chart').appendChild(lineChart);

            const barChart = MyCharts.createBarChart(quarterlyData, {
                width: 600,
                height: 400,
                barColor: "purple",
                margin: { top: 20, right: 20, bottom: 40, left: 40 }
            });
            document.getElementById('bar-chart').appendChild(barChart);

            const pieChart = MyCharts.createPieChart(customerData, {
                width: 400,
                height: 400,
                colors: ["#4daf4a", "#377eb8", "#ff7f00", "#984ea3", "#e41a1c"]
            });
            document.getElementById('pie-chart').appendChild(pieChart);
        });
    </script>
</body>
</html>

