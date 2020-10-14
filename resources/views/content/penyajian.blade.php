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
                                <td>{{$item['jumlah']}}</td>
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
                        <h4>Diagram Lingkaran Distribusi Frekuensi</h4>
                    </div>
                    <div class="card-body">
                        <div class="wrapper">
                            <canvas id="ling_frek"></canvas>
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
                function createChart(chartId, chartData, colorScale, colorRangeInfo) {

                    const chartElement = document.getElementById(chartId);

                    const dataLength = chartData.data.length;

                    var COLORS = interpolateColors(dataLength, colorScale, colorRangeInfo);


                    const myChart = new Chart(chartElement, {
                        type: 'pie',
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
                                display: false,
                            },
                            hover: {
                                onHover: function(e) {
                                    var point = this.getElementAtEvent(e);
                                    e.target.style.cursor = point.length ? 'pointer' : 'default';
                                },
                            },
                        }
                    });
                    return myChart;
                }
                var data_ling_frek=[];
                var label_ling_frek=[];
                var data_penyajian={{$data_json}};
                $.each(data_penyajian,function(key,value){
                    data_ling_frek.push(value['frek']);
                    label_ling_frek.push(value['min']+"-"+value['max']);
                });
                console.log(data_ling_frek);
            </script>
        </div>
    </div>
@endsection
