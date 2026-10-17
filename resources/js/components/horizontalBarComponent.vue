<template>
	<highcharts :options="chartOptionsSubParamater"></highcharts>
</template>
<script>
	export default {
        props:['paramName','spsList','spsScore','spsDetail'],
		data() {
	    	return {
                chartOptionsSubParamater:{
        			chart: {
        				height: 250,
                        type: 'bar'
                    },
                    credits: {
                        enabled: false
                    },
                    title: {
                        text: this.paramName
                    },
                    tooltip: {
                        valueSuffix: ' %',
                        outside: true,
                        useHTML: true,
                        backgroundColor: "rgba(246, 246, 246, 1)",
                        borderColor: "#bbbbbb",
                        borderWidth: 1.5,
                        style: {
                          opacity: 10,
                          background: "rgba(246, 246, 246, 1)"
                        }                        
                    },
                    categories: {
                        enabled: 'true'                        
                    },
                    legend: {
                        enabled:false,
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        x: -40,
                        y: 80,
                        floating: true,
                        borderWidth: 1,                        
                        shadow: false,                        
                        itemHoverStyle: {
                            color: 'grey'
                        }                       
                    },       
                    xAxis: {
                         categories: this.spsList,
                         myString:this.spsDetail,
		                         labels: {
						                useHTML:true,
						                formatter:function(){
						                    return '<div title="'+this.axis.options.myString+'" class="label">'+this.value+'</div>';
						                			}
		            					},
                        // tickInterval: 1,
                        // title: {
                        //     enabled: false
                        // },
                        // myString:this.spsDetail,
                        // labels: {
                        //     overflow: 'justify',
                        //     enabled: true,                            
                        //     formatter: function() {
                        //         //console.log(this.axis.options.labels.myString);
                        //         return '<span title="'+this.axis.options.myString+'">' + this.value + '8888888888888888888888888888888888888</span>';
                        //     },
                        //     useHTML: true
                        // },                       
                        // gridLineColor: '#222222'
                    },
                    yAxis: {
                        min: 0,
                        max: 100,
                        title: {
                            text: 'Score',
                            align: 'high'
                        },
                        labels: {
                            overflow: 'justify'
                        }
                    },                    
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.0f}%'
                            }
                        }
                    },                                 
                    series: [{
                        name: 'Score',
                        data: this.spsScore
                    }]
                },
                		
            }
	    }
	}
</script>
<style>
	.label {
text-align:left;
width:100px;
padding-right:250px;
/*width:500px;*/
/*word-wrap: normal;
*/
}
</style>