<?php





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
					<table id="myTable">
						<thead><tr><th>Fecha</th><th>Accion</th><th>Precio de Compra</th></thead>
						<tbody>
						<?php 
							foreach ($data1 as $row) {
								echo "<tr>";
								echo "<td>" . $row['tec'] . "</td>";
								echo "<td>" . $row['tipo_tec'] . "</td>";
								echo "<td>" . $row['mo'] . "</td>";							
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
					lengthMenu: [[-1, 10, 25, 50], ["Todo", 10, 25, 50]],
				});
			});
		</script>
		
	</body>
	</html>