function actualizar_graficos_principales(data2, data3) {
    
	Highcharts.charts.forEach(chart => chart.destroy());
	Highcharts.chart('container2', {
		chart: { type: data2.type }, // Utiliza el tipo de gráfico proporcionado
		title: { text: '% Respecto al precio actual' }, // Título del gráfico
		tooltip: {
			formatter: function() {
				return this.point.name + ': <b>$' + formatNumber(this.point.absolute) + ' (' + this.point.y.toFixed(1) + '%)</b>';
			}
		},
		series: [{
			data: data2.data, // Datos desde PHP
			dataLabels: {
                enabled: true, // Habilita las etiquetas de datos
                format: '{point.name} ({point.y:.1f})%', // Formato de la etiqueta: porcentaje con un decimal
                style: {
                    fontWeight: 'bold',
                    color: 'black' // Color de texto puede ser ajustado
                }
            }
		}]
	});
	Highcharts.chart('container3', {
		chart: { type: data3.type }, // Utiliza el tipo de gráfico proporcionado
		title: { text: '% por Sector' }, // Título del gráfico
		tooltip: {
			formatter: function() {
				return this.point.name + ': <b>$' + formatNumber(this.point.absolute) + ' (' + this.point.y.toFixed(1) + '%)</b>';
			}
		},
		series: [{
			data: data3.data, // Datos desde PHP
			dataLabels: {
                enabled: true, // Habilita las etiquetas de datos
                format: '{point.name} ({point.y:.1f})%', // Formato de la etiqueta: porcentaje con un decimal
                style: {
                    fontWeight: 'bold',
                    color: 'black' // Color de texto puede ser ajustado
                }
            }
		}],

	});
	
}


function formatNumber(num) {
    // Convertir el número a formato con punto y coma
    num = num.toString().replace(',', '.'); // Asegúrate de que sea un número float
    var parts = num.split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Añadir el punto como separador de miles
    return parts.join(','); // Volver a unir con la coma como separador decimal
}
