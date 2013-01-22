$(document).ready(function() {
        var chart = new Highcharts.Chart({
                chart: {
                        renderTo: 'container', 
                        defaultSeriesType: 'area'
                },
                title: {
                        text: 'Nombres de joueurs total'
                },
                subtitle: {
                        text: ''
                },
                xAxis: {
                        categories: [ liste_joueurs_cat ]
                },
                yAxis: {
                        min: 0,
                        title: {
                                text: 'Nombres de joueurs'
                        }
                },
                tooltip: {
                        formatter: function() {
                                return this.series.name +' : <b>'+
                                        Highcharts.numberFormat(this.y, 0, null, ' ') +'</b><br/>inscrit le '+ this.x;
                        }
                },
                plotOptions: {
                  areaspline: {
                     fillOpacity: 0.5
                  }
                },
                series: [{
                        name: 'Joueurs',
                                data: [ liste_joueurs_data ]
                        }]
        });


});

$(document).ready(function() {
        var chart = new Highcharts.Chart({
                chart: {
                        renderTo: 'contain', 
                        defaultSeriesType: 'area'
                },
                title: {
                        text: 'Nombres de personnages total'
                },
                subtitle: {
                        text: ''
                },
                xAxis: {
                        categories: [ liste_persos_cat ]
                },
                yAxis: {
                        min: 0,
                        title: {
                                text: 'Nombres de personnages'
                        }
                },
                tooltip: {
                        formatter: function() {
                                return this.series.name +' : <b>'+
                                        Highcharts.numberFormat(this.y, 0, null, ' ') +'</b><br/>créé le '+ this.x;
                        }
                },
                plotOptions: {
                  areaspline: {
                     fillOpacity: 0.5
                  }
                },
                series: [ liste_persos_data ]
        });


});                