$( document ).ready(function() {
var gaugeOptions = {

    chart: {
        type: 'solidgauge'
    },

    title: null,

    pane: {
        center: ['50%', '85%'],
        size: '140%',
        startAngle: -90,
        endAngle: 90,
        background: {
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || '#EEE',
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
        }
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        stops: [
            [0.1, '#fd3995'], // red
            [0.5, '#ffb822'], // yellow
            [0.9, '#34bfa3'] // gree
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: 2,
        title: {
            y: -70
        },
        labels: {
            y: 16
        }
    },

    plotOptions: {
        solidgauge: {
            dataLabels: {
                y: 5,
                borderWidth: 0,
                useHTML: true
            }
        }
    }
};

// The speed gauge
var chartSpeed = Highcharts.chart('container-speed', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: 100,
        title: {
            text: 'With FATAL'
        }
    },

    credits: {
        enabled: false
    },

    series: [{
        name: 'Speed',
        data: [22],
        dataLabels: {
            format:
                '<div style="text-align:center">' +
                '<span style="font-size:25px">{y}%</span><br/>' +
                '<span style="font-size:12px;color:silver">Score</span>' +
                '</div>'
        },
        tooltip: {
            valueSuffix: ' km/h'
        }
    }]

}));

// The RPM gauge
var chartRpm = Highcharts.chart('container-rpm', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: 100,
        title: {
            text: 'Without FATAL'
        }
    },

    series: [{
        name: 'Without FATAL',
        data: [62],
        dataLabels: {
            format:
                '<div style="text-align:center">' +
                '<span style="font-size:25px">{y:.1f}%</span><br/>' +
                '<span style="font-size:12px;color:silver">Score</span>' +
                '</div>'
        },
        tooltip: {
            valueSuffix: ' revolutions/min'
        }
    }]

}));



Highcharts.chart('container_1', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Be Welcoming (63%)'
    },
    xAxis: {
        categories: ['Initial warmth & greeting', 'Identification & Verification', 'Acknowledgement']
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    legend: {
        reversed: true
    },
    plotOptions: {
        series: {
            stacking: 'normal'
        }
    },
    series: [{
        name: 'Score',
        data: [76,71,26]
    },]
});

Highcharts.chart('container_2', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Be Inquisitive (73%)'
    },
    xAxis: {
        categories: ['Issue Understanding','Listening Skills','Confirmation of Details']
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    legend: {
        reversed: true
    },
    plotOptions: {
        series: {
            stacking: 'normal'
        }
    },
    series: [{
        name: 'Score',
        data: [87,81,26]
    },]
});

Highcharts.chart('container_3', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Be Knowledgeable (67%)'
    },
    xAxis: {
        categories: ['FTR','System Documentation','Internal/External Handoff']
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    legend: {
        reversed: true
    },
    plotOptions: {
        series: {
            stacking: 'normal'
        }
    },
    series: [{
        name: 'Score',
        data: [78,57,38]
    },]
});

Highcharts.chart('container_4', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Be Professional (51%)'
    },
    xAxis: {
        categories: ['Hold/Transfer Procedures','Call Control','Language Proficiency','Usage of Customer Name']
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    legend: {
        reversed: true
    },
    plotOptions: {
        series: {
            stacking: 'normal'
        }
    },
    series: [{
        name: 'Score',
        data: [56,72,76,3]
    },]
});

Highcharts.chart('container_5', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Be Memorable (65%)'
    },
    xAxis: {
        categories: ['Sense Check','Further Assistance','Branded Farewell']
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    legend: {
        reversed: true
    },
    plotOptions: {
        series: {
            stacking: 'normal'
        }
    },
    series: [{
        name: 'Score',
        data: [0,27,63]
    },]
});

Highcharts.chart('container_6', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Be Knowledgeable (0%)'
    },
    xAxis: {
        categories: ['In Customer Shoes']
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    legend: {
        reversed: true
    },
    plotOptions: {
        series: {
            stacking: 'normal'
        }
    },
    series: [{
        name: 'Score',
        data: [0]
    },]
});


});