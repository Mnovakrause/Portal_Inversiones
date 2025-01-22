<?php

include 'db_connection.php'; // Asegúrate de incluir la conexión a la base de datos


function procesarData($sql){
	global $conn;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $data;
}


# grafico de porcentajes del deashboard
function grafico_porcentaje_inversion() {
    $sql = "SELECT Accion, sum(Total) as Total, sum(Cantidad) as Cantidad FROM compras group by Accion"; // Obtiene los datos
	$data = procesarData($sql);
   

    // Sumar el total
	$total = 0;   
    foreach ($data as $row) {
        $total += $row['Total'];
    }

	// Calcular % y preparar el conjunto de datos para Highcharts
    $chartData = [];
    $categories = [];
	foreach ($data as $row) {
        $category = $row['Accion'];
        $value = ($total > 0) ? ($row['Total'] / $total) * 100 : 0; // Calcula el porcentaje
        $absoluteValue = $row['Total']; // Guarda el valor absoluto
        $categories[] = $category; // Guardar las categorías
        // Guarda tanto el % como el valor absoluto en el gráfico
        $chartData[] = [
            'name' => $category,
            'y' => (float)$value,
            'absolute' => $absoluteValue, // Cambiando el nombre a 'absolute' para mejor claridad
			'absoluteQty' => $row['Cantidad'] // Cambiando el nombre a 'absolute' para mejor claridad
        ];
    }

    return [
        'data' => $chartData,
        'categories' => $categories,
        'type' => 'pie' // Especificar el tipo de gráfico aquí
    ];
}


# grafico de porcentajes del deashboard con respecto al precio actual 
function grafico_porcentaje_inversion_precioactual() {
    $sql = "
	select t.Accion, t.Cantidad, round(t.Cantidad*t.precio) as Total 
	from(
		select
			c.Accion,
			sum(c.Cantidad) as Cantidad,
			p.Precio  as precio
		from
			compras as c
		inner join precios p 
		on (c.Accion = p.Accion)
		where year(p.Fecha) = year(now())
		group by
			c.Accion
	) as t
	"; // Obtiene los datos
	$data = procesarData($sql);
   

    // Sumar el total
	$total = 0;   
    foreach ($data as $row) {
        $total += $row['Total'];
    }

	// Calcular % y preparar el conjunto de datos para Highcharts
    $chartData = [];
    $categories = [];
	foreach ($data as $row) {
        $category = $row['Accion'];
        $value = ($total > 0) ? ($row['Total'] / $total) * 100 : 0; // Calcula el porcentaje
        $absoluteValue = $row['Total']; // Guarda el valor absoluto
        $categories[] = $category; // Guardar las categorías
        // Guarda tanto el % como el valor absoluto en el gráfico
        $chartData[] = [
            'name' => $category,
            'y' => (float)$value,
            'absolute' => $absoluteValue, // Cambiando el nombre a 'absolute' para mejor claridad
			'absoluteQty' => $row['Cantidad'] // Cambiando el nombre a 'absolute' para mejor claridad
        ];
    }

    return [
        'data' => $chartData,
        'categories' => $categories,
        'type' => 'pie' // Especificar el tipo de gráfico aquí
    ];
}


# grafico de porcentajes del deashboard con respecto al precio actual 
function grafico_porcentaje_sector() {
    $sql = "
	select Sector, sum(Total) as Total, sum(Cantidad) as Cantidad
	from(
		select
			P.Accion,
			sum(c.Cantidad) as Cantidad,
			p.Precio  as precio,
			sum(c.Cantidad) * p.Precio as Total
		from
			informacion_financiera.compras as c
		inner join precios p 
		on (c.Accion = p.Accion)
		where p.fecha = (select max(fecha) from precios)
		group by p.Accion 	
	) as t
	inner join (
		select Accion, Sector from informacion_financiera.sectores 
	) as y
	on (y.Accion = t.Accion)
	group by Sector "; // Obtiene los datos
	$data = procesarData($sql);
   

    // Sumar el total
	$total = 0;   
    foreach ($data as $row) {
        $total += $row['Total'];
    }

	// Calcular % y preparar el conjunto de datos para Highcharts
    $chartData = [];
    $categories = [];
	foreach ($data as $row) {
        $category = $row['Sector'];
        $value = ($total > 0) ? ($row['Total'] / $total) * 100 : 0; // Calcula el porcentaje
        $absoluteValue = $row['Total']; // Guarda el valor absoluto
        $categories[] = $category; // Guardar las categorías
        // Guarda tanto el % como el valor absoluto en el gráfico
        $chartData[] = [
            'name' => $category,
            'y' => (float)$value,
            'absolute' => $absoluteValue, // Cambiando el nombre a 'absolute' para mejor claridad
			'absoluteQty' => $row['Cantidad'] // Cambiando el nombre a 'absolute' para mejor claridad
        ];
    }

    return [
        'data' => $chartData,
        'categories' => $categories,
        'type' => 'pie' // Especificar el tipo de gráfico aquí
    ];
}



# funcion para rentabilidad
function informacion_rentabilidad() {
	$sql = "SELECT Accion, SUM(Total) AS Total, SUM(Cantidad) AS Cantidad FROM compras GROUP BY Accion"; // Obtiene los datos
	$data_compra = procesarData($sql);

	// Consulta para obtener datos de dividendos
	$sql = "SELECT Accion, SUM(Total) AS TotalDividendos FROM dividendos GROUP BY Accion"; // Cambiamos el alias para claridad
	$data_dividendos = procesarData($sql);

	// Consulta para obtener datos de precios
	$sql = "SELECT Accion, Precio FROM precios where year(Fecha) = year(Now())"; // Consulta para obtener los precios
	$data_precios = procesarData($sql);
	$resultado_precios = []; // Inicializa el array
	foreach ($data_precios as $precios) {
		$resultado_precios[$precios['Accion']] = $precios['Precio']; // Asigna el precio a la acción correspondiente
	}
	// Crear un array para almacenar el resultado combinado
	$resultado = [];
	foreach ($data_compra as $compra) {
		$accion = $compra['Accion'];
		$totalCompra = $compra['Total'];
		$cantidad = $compra['Cantidad'];
		$precio_accion = $resultado_precios[$accion];
		// Busca si hay dividendos para esta acción
		$dividendo = 0; // Valor por defecto
		foreach ($data_dividendos as $dividend) {
			if ($dividend['Accion'] === $accion) {
				$dividendo = $dividend['TotalDividendos']; // Si existe dividendo, asignar
				break; // Salir del bucle una vez encontrado
			}
		}

		// Agregar al resultado final
		$resultado[] = [
			'Accion' => $accion,
			'TotalCompra' => $totalCompra,
			'TotalActual' => $cantidad*$precio_accion,
			'TotalDividendos' => $dividendo,
		];
	}
	return $resultado;
}


# funcion para dividendos anuales
function informacion_dividendos() {
	global $conn;
	
	// Obtiene los datos de acciones desde compras
	$sql = "SELECT distinct(Accion) as Accion FROM compras GROUP BY Accion"; 
	$data = procesarData($sql);
	$acciones = [];
	foreach ($data as $accion){
		$acciones[] = $accion['Accion'] ;
	}
	
	// Obtiene los datos de años invertidos
	$sql = "SELECT distinct Year(Fecha) as year FROM informacion_financiera.dividendos group by Fecha"; 
	$data = procesarData($sql);
	$years = [];
	foreach ($data as $year){
		$years[] = $year['year']; // Accede a la clave 'year'
	}

	// inicializamos la data de acciones por año a 0
	$consolidado_anual = [];
	foreach($years as $anio){
		foreach($acciones as $accion){
			$consolidado_anual[$anio][$accion]['cantidad'] = 0;
			$consolidado_anual[$anio][$accion]['total'] = 0;
			$consolidado_anual[$anio][$accion]['dividendos'] = 0;
		}
	}


	// Obtiene los datos de compras de acciones hasta el año analizado
	foreach ($years as $anio){
		$sql = "
			select A.Accion, A.Cantidad, round(A.Cantidad*B.Precio) as Total
			from (
			select
				Accion,
				sum(Cantidad) as Cantidad
			from
				informacion_financiera.compras
			where year(Fecha) <= :anio
			group by Accion 
			) as A
			inner join 
			(
			select 
				Accion,
				Precio
			from 
				informacion_financiera.precios 
			where year(Fecha) = :anio
			) as B
			on A.Accion = B.Accion
		";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':anio', $anio, PDO::PARAM_INT); // Binding del parámetro
		$stmt->execute();    
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtiene los resultados
		foreach($data as $resultado_anual){
			$accion = $resultado_anual['Accion'];
			$total_cantidad_anual = $resultado_anual['Cantidad'];
			$total_anual = $resultado_anual['Total'];
			$consolidado_anual[$anio][$accion]['cantidad'] = $total_cantidad_anual;
			$consolidado_anual[$anio][$accion]['total'] = $total_anual;
		}

		$sql = "
			select
				Accion,
				round(sum(Total)) as total_dividendos
			from
				informacion_financiera.dividendos
			where year(Fecha) = :anio
			group by Accion
		";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':anio', $anio, PDO::PARAM_INT); // Binding del parámetro
		$stmt->execute();    
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtiene los resultados
		foreach($data as $resultado_anual){
			$accion = $resultado_anual['Accion'];
			$total_dividendo_anual = $resultado_anual['total_dividendos'];
			$consolidado_anual[$anio][$accion]['dividendos'] = $total_dividendo_anual;
		}
	}
	
	return $consolidado_anual;
}


function informacion_dividendos_v2() {
	global $conn;
	$consolidado_anual = [];
	
	// Obtiene los datos de acciones desde compras
	$sql = "SELECT distinct(Accion) as Accion FROM compras GROUP BY Accion"; 
	$data = procesarData($sql);
	$acciones = [];
	foreach ($data as $accion){
		$acciones[] = $accion['Accion'] ;
	}
	
	// Obtiene los datos de años invertidos
	$sql = "SELECT distinct Year(Fecha) as year FROM dividendos group by Fecha"; 
	$data = procesarData($sql);
	$years = [];
	foreach ($data as $year){
		$years[] = $year['year']; // Accede a la clave 'year'
	}

	// por cada accion calculamos el total de compras por cada año
	$ultima_fecha_pago_x_anio = [];
	foreach($acciones as $accion){
		foreach($years as $anio){	
			// obtenemos la fecha del ultimo pago de dividendos del año para cada accion
			$sql = "			
				select
					max(Fecha) as max_fecha
				from
					dividendos
				where year(Fecha) = :anio and Accion = :accion				
			";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':anio', $anio, PDO::PARAM_STR); // Binding del parámetro
			$stmt->bindParam(':accion', $accion, PDO::PARAM_STR);
			$stmt->execute();    
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtiene los resultados
			if (isset($data[0]['max_fecha'])){
				$ultima_fecha_pago_x_anio[$accion][$anio]['fecha_ultimo_div'] = $data[0]['max_fecha'];
			}
			else {
				$ultima_fecha_pago_x_anio[$accion][$anio]['fecha_ultimo_div'] = null;
			}
		
		
			// obtenemos el total y la cantidad usando la fecha de pago del ultimo dividendo
			$sql = "
				select
					Accion,
					sum(Cantidad) as Cantidad,
					sum(Total) as Total
				from
					compras
				where Fecha <= :fecha_utlimo_div and Accion = :accion
				group by Accion 
			";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':fecha_utlimo_div', $ultima_fecha_pago_x_anio[$accion][$anio]['fecha_ultimo_div'] , PDO::PARAM_STR); // Binding del parámetro
			$stmt->bindParam(':accion', $accion, PDO::PARAM_STR);
			$stmt->execute();    
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtiene los resultados
			if (isset($data[0])){
				$consolidado_anual[$anio][$accion]['cantidad'] = $data[0]['Cantidad'];
				$consolidado_anual[$anio][$accion]['total'] = $data[0]['Total'];
			}else{
				$consolidado_anual[$anio][$accion]['cantidad'] = 0;
				$consolidado_anual[$anio][$accion]['total'] = 0;	
			}
		
		
			// obtenemos el total de dividendos pagados en el año
			$sql = "
				select
					Accion,
					round(sum(Total)) as total_dividendos
				from
					dividendos
				where year(Fecha) = :anio and Accion = :accion
				group by Accion
			";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':anio', $anio, PDO::PARAM_INT); // Binding del parámetro
			$stmt->bindParam(':accion', $accion, PDO::PARAM_STR);
			$stmt->execute();    
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtiene los resultados
			if (isset($data[0])){
				$total_dividendo_anual = $data[0]['total_dividendos'];
				$consolidado_anual[$anio][$accion]['dividendos'] = $total_dividendo_anual;
			}else{
				$consolidado_anual[$anio][$accion]['dividendos'] = 0;
			}
		
				
		}
	}
	return $consolidado_anual;
}


function roe_accion() {
	global $conn;
    $sql = "select accion, roe from informacion_financiera.roe "; // Obtiene los datos
	$stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$result = [];   
    foreach ($data as $row) {
       $result[$row['accion']] = $row['roe'];
    }
	return $result;
}


function sector_accion() {
	global $conn;
    $sql = "select accion, sector from informacion_financiera.sectores "; // Obtiene los datos
	$stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$result = [];   
    foreach ($data as $row) {
       $result[$row['accion']] = $row['sector'];
    }
	return $result;
}

?>