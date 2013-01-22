var chart;
$(document).ready(function() {
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container',
			zoomType: 'x',
			spacingRight: 20
		},
		title: {
			text: 'Gains d\'expérience'
		},
		subtitle: {
			text: document.ontouchstart === undefined ?
				'Click and drag in the plot area to zoom in' :
				'Drag your finger over the plot to zoom in'
		},
		xAxis: {
			type: 'datetime',
			maxZoom: 7 * 24 * 3600000, // fourteen days
			title: {
				text: null
			}
		},
		yAxis: {
			title: {
				text: 'Expérience'
			},
			min: 0,
			startOnTick: false,
			showFirstLabel: false
		},
		tooltip: {
			shared: true
		},
		legend: {
			enabled: false
		},
		plotOptions: {
			area: {
				fillColor: {
					linearGradient: [0, 0, 0, 300],
					stops: [
						[0, Highcharts.getOptions().colors[0]],
						[1, 'rgba(2,0,0,0)']
					]
				},
				lineWidth: 1,
				marker: {
					enabled: false,
					states: {
						hover: {
							enabled: true,
							radius: 5
						}
					}
				},
				shadow: false,
				states: {
					hover: {
						lineWidth: 1
					}
				}
			}
		},

		series: [{
			type: 'area',
			name: 'XP',
			pointInterval: 24 * 3600 * 1000,
			pointStart: Date.UTC(utc),
			data: [ progressionXp ]
		}]
	});
});