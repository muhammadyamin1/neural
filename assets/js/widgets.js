( function ( $ ) {
    "use strict";


    // Counter Number
    $('.count').each(function () {
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        }, {
            duration: 3000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });

    //WidgetChart 1
    var ctx = document.getElementById("widgetChart1").getContext('2d');
    ctx.height = 150;
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['2019', '2020', '2021', '2022', '2023'],
            datasets: [{
                data: [270, 185, 152, 189, 163],
                label: 'Dataset',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(75, 192, 192, 1)',
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: true,
                position: 'top',
                labels: {
                    fontColor: 'rgb(75, 75, 75)'
                }
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: 'rgba(0, 0, 0, 0.1)',
                    },
                    ticks: {
                        fontColor: 'rgba(75, 75, 75, 0.7)'
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: 'rgba(0, 0, 0, 0.1)',
                    },
                    ticks: {
                        fontColor: 'rgba(75, 75, 75, 0.7)'
                    }
                }]
            },
            title: {
                display: true,
                text: 'Data Tahunan',
                fontColor: 'rgb(75, 75, 75)'
            },
            elements: {
                line: {
                    tension: 0.4 // smooth curves
                },
                point: {
                    radius: 5,
                    hitRadius: 10,
                    hoverRadius: 6
                }
            }
        }
    });
    
} )( jQuery );
