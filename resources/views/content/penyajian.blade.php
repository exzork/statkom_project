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
                function colorize(opaque, hover, ctx) {
			        var v = ctx.dataset.data[ctx.dataIndex];
			        var c = v < -50 ? '#D60000'
				            : v < 0 ? '#F46300'
				            : v < 50 ? '#0358B6'
				            : '#44DE28';

			    var opacity = hover ? 1 - Math.abs(v / 150) - 0.2 : 1 - Math.abs(v / 150);
                var alpha = opacity === undefined ? 0.5 : 1 - opacity;
			    var op= Color(c).alpha(alpha).rgbString();
			return opaque ? c : op;
		}

		function hoverColorize(ctx) {
			return colorize(false, true, ctx);
		}
		var data = {
			datasets: {{$dis_frek}}
		};

		var options = {
            responsive:true,
			legend: false,
			tooltips: false,
			elements: {
				arc: {
					backgroundColor: colorize.bind(null, false, false),
					hoverBackgroundColor: hoverColorize
				}
			}
		};

		var chart = new Chart('ling_frek', {
			type: 'pie',
			data: data,
			options: options
		});

            </script>
        </div>
    </div>
@endsection
