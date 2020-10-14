@extends('template.sidebar')
@section('title','Pengolahan Data')
@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <button class="btn w-100 btn-primary">Tambah data</button>
            </div>
            <div class="card-body">
                <h4 class="card-title">Data beserta Z-Score untuk menentukan outlier</h4>
                <table class="table" id="data_mentah">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Data</th>
                            <th>Z-Score</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table_mentah as $table_data)
                        <tr @if ($table_data->z_score>3||$table_data->z_score<-3)
                            class="bg-warning"
                        @endif>
                            <td>{{$table_data->id}}</td>
                            <td>{{$table_data->data}}</td>
                            <td align="right">{{$table_data->z_score}}</td>
                            <td><button class="btn btn-success fa fa-edit mr-1" onclick="alert('test');"></button><button class="btn btn-danger fa fa-trash" onclick="alert('test');"></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data setelah diurutkan dan outlier tidak dipakai</h4>
                <table class="table card-text" id="data_urut">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table_urut as $table_data)
                        <tr>
                            <td>{{$table_data->id}}</td>
                            <td>{{$table_data->data}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h4>Pengolahan</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Isi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Mean</td>
                            <td>{{$mean}}</td>
                        </tr>
                        <tr>
                            <td>Median</td>
                            <td>{{$median}}</td>
                        </tr>
                        <tr>
                            <td>Modus</td>
                            <td>{{$modus}}</td>
                        </tr>
                        <tr>
                            <td>Quartil 1</td>
                            <td>{{$quartil1}}</td>
                        </tr>
                        <tr>
                            <td>Quartil3</td>
                            <td>{{$quartil3}}</td>
                        </tr>
                        <tr>
                            <td>Varian Populasi</td>
                            <td>{{$var_p}}</td>
                        </tr>
                        <tr>
                            <td>Varian Sample</td>
                            <td>{{$var_s}}</td>
                        </tr>
                        <tr>
                            <td>Standar Deviasi Populasi</td>
                            <td>{{$std_p}}</td>
                        </tr>
                        <tr>
                            <td>Standar Deviasi Sample</td>
                            <td>{{$std_s}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#data_mentah').DataTable({
            ordering:false,
            "pagingType": "simple"
        });
        $('#data_urut').DataTable({
            ordering:false,
            "pagingType": "simple"
        });
    });
</script>
@endsection
