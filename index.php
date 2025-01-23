<?php
	include 'php/db_queries.php';
	$data2 = grafico_porcentaje_inversion_precioactual();
	$data3 = grafico_porcentaje_sector();
	$roe_accion = roe_accion();
	$sector_accion = sector_accion();
	dsad
?>

<!DOCTYPE html>
<html>
<?php include 'php/head.php'; ?>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Incluir el Sidebar -->
            <div class="col-md-2 col-lg-2 sidebar">
                <?php include 'php/sidebar.php'; ?>
            </div>
            <!-- Contenido Principal -->
            <div class="col-md-10 col-lg-10 main-content">
				<div class="border rounded p-3 bg-light mb-4"><h5>Resumen general</h5></div>
				<div class="row">
					<div class="col-md-6 col-lg-6">
						<div id="container2" style="height: 400px;"></div>
					</div>
					<div class="col-md-6 col-lg-6">
						<div id="container3" style="height: 400px;"></div>
					</div>					
				</div>
				<br>
				<table id="detalle_tabla2">
				<thead><tr><th>Accion (ROE-2023)</th><th>Sector</th><th>Cantidad</th><th>Total</th><th>%</th></tr></thead>
				<tbody>
				<?php 
					foreach ($data2['data'] as $row) {
						$roe = $roe_accion[$row['name']];
						echo "<tr>";
						echo "<td><a href='https://www.bolsadesantiago.com/resumen_instrumento/".urlencode($row['name'])."'> " . $row['name'] . "</a> (".$roe."%)</td>";	
						echo "<td>" . $sector_accion[$row['name']] . "</td>";	
						echo "<td>" . $row['absoluteQty'] . "</td>";	
						echo "<td> $" . number_format($row['absolute'], 0, ',', '.') . "</td>";
						echo "<td>" . round($row['y'],2) . "%</td>";	
						echo "</tr>";
					}
				?>
				</tbody>
				</table>
            </div>
        </div>
    </div>
	<script>
        actualizar_graficos_principales( 
										<?php echo json_encode($data2); ?>,
										<?php echo json_encode($data3); ?>
										);

		$(document).ready(function() {
			$('#detalle_tabla2').DataTable({
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
