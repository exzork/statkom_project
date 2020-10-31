@extends('template.sidebar')
@section('title','Penyajian Data')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>Data Kelompok</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Range</th>
                            <th>Distribusi Frekuensi</th>
                            <th>Distribusi Frekuensi Kumulatif</th>
                            <th>Distribusi Frekuensi Relatif</th>
                            <th>Distribusi Frekuensi Relatif Kumulatif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_penyajian as $item)
                            <tr>
                                <td>{{$item['min']}}-{{$item['max']}}</td>
                                <td>{{$item['frek']}}</td>
                                <td>{{$item['frek_k']}}</td>
                                <td>{{$item['frek_r']}}%</td>
                                <td>{{$item['frek_rk']}}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Diagram Lingkaran Distribusi Frekuensi</h5>
                    </div>
                    <div class="card-body">
                        <div class="wrapper">
                            <canvas id="ling_frek"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-1">
                <div class="card">
                    <div class="card-header">
                        <h5>Diagram Lingkaran Distribusi Frekuensi Kumulatif</h5>
                    </div>
                    <div class="card-body">
                        <div class="wrapper">
                            <canvas id="ling_frek_k"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-1">
                <div class="card">
                    <div class="card-header">
                        <h5>Diagram Lingkaran Distribusi Frekuensi Relatif(%)</h5>
                    </div>
                    <div class="card-body">
                        <div class="wrapper">
                            <canvas id="ling_frek_r"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-1">
                <div class="card">
                    <div class="card-header">
                        <h5>Diagram Lingkaran Distribusi Frekuensi Relatif Kumulatif(%)</h5>
                    </div>
                    <div class="card-body">
                        <div class="wrapper">
                            <canvas id="ling_frek_rk"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-1">
                <div class="card">
                    <div class="card-header">
                        <h5>Diagram Ogive Distribusi Frekuensi dan Frekuensi Kumulatif</h5>
                    </div>
                    <div class="card-body">
                        <div class="wrapper">
                            <canvas id="ogive_frek"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-1">
                <div class="card">
                    <div class="card-header">
                        <h5>Diagram Ogive Distribusi Frekuensi Relatif dan Frekuensi Relatif Kumulatif(%)</h5>
                    </div>
                    <div class="card-body">
                        <div class="wrapper">
                            <canvas id="ogive_frek_r"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-1">
                <div class="card">
                    <div class="card-header">
                        <h5>Diagram Histogram Distribusi Frekuensi dan Frekuensi Kumulatif</h5>
                    </div>
                    <div class="card-body">
                        <div class="wrapper">
                            <canvas id="histogram_frek"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-1">
                <div class="card">
                    <div class="card-header">
                        <h5>Diagram Histogram Distribusi Frekuensi Relatif dan Frekuensi Relatif Kumulatif(%)</h5>
                    </div>
                    <div class="card-body">
                        <div class="wrapper">
                            <canvas id="histogram_frek_r"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function calculatePoint(i, intervalSize, colorRangeInfo) {
                    var { colorStart, colorEnd, useEndAsStart } = colorRangeInfo;
                    return (useEndAsStart
                        ? (colorEnd - (i * intervalSize))
                        : (colorStart + (i * intervalSize)));
                }
                /* Must use an interpolated color scale, which has a range of [0, 1] */
                function interpolateColors(dataLength, colorScale, colorRangeInfo) {
                    var { colorStart, colorEnd } = colorRangeInfo;
                    var colorRange = colorEnd - colorStart;
                    var intervalSize = colorRange / dataLength;
                    var i, colorPoint;
                    var colorArray = [];
                    for (i = 0; i < dataLength; i++) {
                        colorPoint = calculatePoint(i, intervalSize, colorRangeInfo);
                        colorArray.push(colorScale(colorPoint));
                    }
                    return colorArray;
                }
                /* Set up Chart.js Pie Chart */
                function createChart(type,chartId, chartData, colorScale, colorRangeInfo) {

                    const chartElement = document.getElementById(chartId).getContext('2d');

                    const dataLength = chartData.data.length;

                    var COLORS = interpolateColors(dataLength, colorScale, colorRangeInfo);


                    const myChart = new Chart(chartElement, {
                        type: type,
                        data: {
                            labels: chartData.labels,
                            datasets: [
                                {
                                    backgroundColor: COLORS,
                                    hoverBackgroundColor: COLORS,
                                    data: chartData.data
                                }
                            ],
                        },
                        options: {
                            responsive: true,
                            legend: {
                                display: true,
                            },
                            hover: {
                                onHover: function(e) {
                                    var point = this.getElementAtEvent(e);
                                    e.target.style.cursor = point.length ? 'pointer' : 'default';
                                },
                            },
                            plugins:{
                                datalabels:{
                                    anchor:'end',
                                    align:'start',
                                    offset:'3',
                                    formatter:(value,chartElement)=>{
                                        let dataArr=chartElement.chart.data.datasets[0].data;
                                        return value;
                                    },
                                    color:'#FFF',
                                }
                            }
                        }
                    });
                    return myChart;
                }
                var label=[];
                var data_frek=[];
                var data_frek_k=[];
                var data_frek_r=[];
                var data_frek_rk=[];
            </script>
            @foreach ($data_penyajian as $item)
                <script>
                    data_frek.push({{$item['frek']}});
                    data_frek_k.push({{$item['frek_k']}});
                    data_frek_r.push({{$item['frek_r']}});
                    data_frek_rk.push({{$item['frek_rk']}});
                    label.push({{$item['min']}}+"-"+{{$item['max']}});
                </script>
            @endforeach
            <script>
                const ling_frek_data={
                    labels:label,
                    data:data_frek
                }
                const ling_frek_k_data={
                    labels:label,
                    data:data_frek_k
                }
                const ling_frek_r_data={
                    labels:label,
                    data:data_frek_r
                }
                const ling_frek_rk_data={
                    labels:label,
                    data:data_frek_rk
                }
                const colorScale = d3.interpolateRainbow;
                const colorRangeInfo = {
                    colorStart:0,
                    colorEnd:1,
                    useEndAsStart:false
                }
                const ogive_frek_config={
                    type:'line',
                    data:{
                        labels:label,
                        datasets:[{
                            label:'Frekuensi',
                            fill:false,
                            tension:0,
                            backgroundColor:'#009933',
                            borderColor:'#009933',
                            data:data_frek
                        },{

                            label:'Frekuensi Kumulatif',
                            fill:false,
                            tension:0,
                            backgroundColor:'#0066cc',
                            borderColor:'#0066cc',
                            data:data_frek_k
                        }]
                    },
                    options:{
                        plugins:{
                            datalabels:{
                                display:false
                            }
                        },
                        responsive:true,
                        tooltips:{
                            mode:'index',
                            intersect:false
                        },
                        hover: {
					        mode: 'nearest',
					        intersect: true
				        },
				        scales: {
					        xAxes: [{
						        display: true,
						        scaleLabel: {
							        display: true,
							        labelString: 'Range'
						        }
					        }],
					        yAxes: [{
						        display: true,
						        scaleLabel: {
							        display: true,
							        labelString: 'Jumlah'
						        }
					        }]
				        }
                    }
                }
                const ogive_frek_r_config={
                    type:'line',
                    data:{
                        labels:label,
                        datasets:[{
                            label:'Frekuensi Relatif',
                            fill:false,
                            tension:0,
                            backgroundColor:'#009933',
                            borderColor:'#009933',
                            data:data_frek_r
                        },{

                            label:'Frekuensi Relatif Kumulatif',
                            fill:false,
                            tension:0,
                            backgroundColor:'#0066cc',
                            borderColor:'#0066cc',
                            data:data_frek_rk
                        }]
                    },
                    options:{
                        plugins:{
                            datalabels:{
                                display:false
                            }
                        },
                        responsive:true,
                        tooltips:{
                            mode:'index',
                            intersect:false
                        },
                        hover: {
					        mode: 'nearest',
					        intersect: true
				        },
				        scales: {
					        xAxes: [{
						        display: true,
						        scaleLabel: {
							        display: true,
							        labelString: 'Range'
						        }
					        }],
					        yAxes: [{
						        display: true,
						        scaleLabel: {
							        display: true,
							        labelString: 'Jumlah(%)'
						        }
					        }]
				        }
                    }
                }
                const histogram_frek_config={
                    type:'bar',
                    data:{
                        labels:label,
                        datasets:[{
                            label:'Frekuensi',
                            backgroundColor:'#009933',
                            data:data_frek
                        },{
                            label:'Frekuensi Kumulatif',
                            backgroundColor:'#0066CC',
                            data:data_frek_k
                        }]
                    },
                    options:{
                        plugins:{
                            datalabels:{
                                display:false
                            }
                        },
                        responsive:true,
                        tooltips:{
                            mode:'index',
                            intersect:false
                        },
                    }
                };
                const histogram_frek_r_config={
                    type:'bar',
                    data:{
                        labels:label,
                        datasets:[{
                            label:'Frekuensi Relatif',
                            backgroundColor:'#009933',
                            data:data_frek_r
                        },{
                            label:'Frekuensi Relatif Kumulatif',
                            backgroundColor:'#0066CC',
                            data:data_frek_rk
                        }]
                    },
                    options:{
                        plugins:{
                            datalabels:{
                                display:false
                            }
                        },
                        responsive:true,
                        tooltips:{
                            mode:'index',
                            intersect:false
                        },
                    }
                };
                createChart('pie','ling_frek',ling_frek_data,colorScale,colorRangeInfo);
                createChart('pie','ling_frek_k',ling_frek_k_data,colorScale,colorRangeInfo);
                createChart('pie','ling_frek_r',ling_frek_r_data,colorScale,colorRangeInfo);
                createChart('pie','ling_frek_rk',ling_frek_rk_data,colorScale,colorRangeInfo);
                window.onload=function(){
                    var ogive_frek_canvas=document.getElementById('ogive_frek').getContext('2d');
                    window.ogive_frek = new Chart(ogive_frek_canvas,ogive_frek_config);
                    var ogive_frek_r_canvas=document.getElementById('ogive_frek_r').getContext('2d');
                    window.ogive_frek_r = new Chart(ogive_frek_r_canvas,ogive_frek_r_config);
                    var histogram_frek_canvas=document.getElementById('histogram_frek').getContext('2d');
                    window.histogram_frek=new Chart(histogram_frek_canvas,histogram_frek_config);
                    var histogram_frek_r_canvas=document.getElementById('histogram_frek_r').getContext('2d');
                    window.histogram_frek_r=new Chart(histogram_frek_r_canvas,histogram_frek_r_config);
                };
            </script>
        </div>
    </div>
@endsection
