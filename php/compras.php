<?php

include 'db_queries.php';
$consolidado_anual = informacion_compras();

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
						<h5>Historial de compras.</h5>
					</div>			
					<table id="myTable">
						<thead><tr><th>Fecha</th><th>Accion</th><th>Cantidad</th><th>Precio</th><th>Total</th></thead>
						<tbody>
						<?php 
							foreach ($consolidado_anual as $row) {
								echo "<tr>";
								echo "<td>" . $row['Fecha'] . "</td>";
								echo "<td>" . $row['Accion'] . "</td>";
								echo "<td>" . $row['Cantidad'] . "</td>";		
								echo "<td>$" . number_format($row['Precio'],0, ',', '.') . "</td>";	
								echo "<td>$" . number_format($row['Total'],0, ',', '.') . "</td>";						
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
				$('#myTable').DataTable({
					dom: 'Bfltip', // 'B' para botones, 'f' para la búsqueda, 'l' para longitud, 't' para la tabla, 'i' para la info, 'p' para la paginación
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
					order: [[0, 'desc']],
				});
			});
		</script>
		
	</body>
	</html>