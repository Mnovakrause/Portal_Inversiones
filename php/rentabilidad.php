<?php
include 'db_queries.php';
$data = informacion_rentabilidad();
?>



<!DOCTYPE html>
<html>
    <?php include 'head.php'; ?>
    <body>
		<div class="container-fluid">
			<div class="row">
				<!-- Incluir el Sidebar -->
				<div class="col-md-2 col-lg-2 sidebar">
					<?php include 'sidebar.php'; ?>
				</div>		
				<div class="col-md-10 col-lg-10 main-content">
					<div class="border rounded p-3 bg-light mb-4"> <!-- Se añade un borde, padding y fondo -->
						<h5>Consolidado Total: Precio actual + Dividendos </h5>
					</div>
					<table id="detalle_dividendo">
					<thead>
					<tr>
					<th>Accion</th>
					<th>Total Precio Inicial</th>
					<th>Total Precio Actual</th>
					<th>Dividendo</th>
					<th>Total + Dividendo</th>
					<th>Rentabilidad</th>
					</tr>
					</thead>
					<tbody>
					<?php 
						foreach ($data as $row) {
							echo "<tr>";
							echo "<td>" . $row['Accion'] . "</td>";	
							echo "<td> $" . number_format($row['TotalCompra'], 0, ',', '.') . "</td>";
							echo "<td> $" . number_format($row['TotalActual'], 0, ',', '.') . "</td>";
							echo "<td> $" . number_format($row['TotalDividendos'], 0, ',', '.') . "</td>";
							echo "<td> $" . number_format($row['TotalDividendos']+$row['TotalActual'], 0, ',', '.') . "</td>";
							echo "<td> " . number_format(($row['TotalDividendos']+$row['TotalActual'])/$row['TotalCompra']*100-100, 2, ',', '.') . "%</td>";
							echo "</tr>";
						}
					?>
					</tbody>
					</table>
				</div>
			</div>
        </div>
		<script>
		$(document).ready(function() {
			$('#detalle_dividendo').DataTable({
				dom: 'Bfltip',
				buttons: [
					{
						extend: 'copy',
						text: 'Copiar',
						titleAttr: 'Copiar datos a la portapapeles'
					},
					{
						extend: 'excel',
						text: 'Exportar a Excel',
						titleAttr: 'Exportar datos a un archivo Excel'
					}
				],
				lengthMenu: [[25, 50, -1], [25, 50, "Todo"]],
			});
		});
		</script>
    </body>
</html>