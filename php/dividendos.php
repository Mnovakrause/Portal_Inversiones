<?php
	include 'db_queries.php';
	//$consolidado_anual = informacion_dividendos();
	$consolidado_anual = informacion_dividendos_v2();
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
						<h5>Dividendos sobre inversión hasta última fecha de pago.</h5>
					</div>					
					<table id="myTable1">
						<thead>
							<tr>
								<th>Accion</th> <!-- Asigna un ancho específico a la columna de Fecha -->
								<?php
									foreach ($consolidado_anual as $anio => $acciones) {
										if($anio != '2020'){
											echo "<th>Total Invertido $anio</th>";
											echo "<th>Total Acciones $anio</th>";
											echo "<th>Dividendos $anio</th>";
											echo "<th>Ratio Div/Compra $anio</th>";
											echo "<th>Ratio Div/Accion $anio</th>";
										}
									}
								?>
							</tr>
						</thead>
						<tbody>
							<?php 
							// Crear una lista de acciones únicas
							$acciones_unicas = [];
							foreach ($consolidado_anual as $anio => $acciones) {
								if($anio != '2020'){
									foreach ($acciones as $accion => $datos) {
										$acciones_unicas[$accion] = true; // Marcar acción como existente
									}
								}
							}

							// Recorrer las acciones únicas y mostrar los datos
							foreach ($acciones_unicas as $accion => $_) {
								echo "<tr>";
								echo "<td>" . htmlspecialchars($accion) . "</td>"; // Mostrar acción

								foreach ($consolidado_anual as $anio => $acciones) {
									if($anio != '2020'){
										// Comprobar si hay datos para esa acción en el año actual
										if (isset($acciones[$accion])) {
											echo "<td style='text-align: center;'>$" . number_format($acciones[$accion]['total'], 0, ',', '.') . "</td>"; // Total formateado
											echo "<td style='text-align: center;'>" . number_format($acciones[$accion]['cantidad'], 0, ',', '.') . "</td>"; // Total formateado
											echo "<td style='text-align: center;'>$" . number_format($acciones[$accion]['dividendos'], 0, ',', '.') . "</td>"; // Dividendos formateados
											
											// Ratio dividendo / compra total
											if (isset($acciones[$accion]['dividendos']) && isset($acciones[$accion]['total']) && $acciones[$accion]['total'] > 0 ) {
												$porcentaje = ($acciones[$accion]['dividendos'] / $acciones[$accion]['total']) * 100;
												echo "<td style='text-align: center;'>" . number_format($porcentaje, 2, ',', '.') . "%</td>"; // Valor formateado
											} else {
												echo "<td style='text-align: center;' >0,00%</td>"; // En caso de que no esté definido o el total sea 0
											}
											
											// Ratio dividendo / cantidad de acciones
											if (isset($acciones[$accion]['dividendos']) && isset($acciones[$accion]['cantidad']) && $acciones[$accion]['cantidad'] > 0 ) {
												$porcentaje = ($acciones[$accion]['dividendos'] / $acciones[$accion]['cantidad']);
												echo "<td style='text-align: center;'>" . number_format($porcentaje, 2, ',', '.') . "</td>"; // Valor formateado
											} else {
												echo "<td style='text-align: center;' >0,00</td>"; // En caso de que no esté definido o el total sea 0
											}


										} else {
											// Si no existen datos para esta acción en el año, agregar celdas vacías
											echo "<td>0,00</td>"; // Total vacío
											echo "<td>0,00</td>"; // Dividendos vacío
										}
									}
								}

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
				$('#myTable1').DataTable({
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