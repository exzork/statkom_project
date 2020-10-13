@extends('template.sidebar')
@section('title','Pengolahan Data')
@section('content')
<div>
    <ul>
        <li>Mean : {{$mean_all}}</li>
    </ul>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data beserta Z-Score untuk menentukan outlier</h4>
                <table class="table" id="data_mentah">
                    <thead>
                        <tr>
                            <th>Nomor Data</th>
                            <th>Data</th>
                            <th>Z-Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table_mentah as $table_data)
                        <tr>
                            <td>{{$table_data->id}}</td>
                            <td>{{$table_data->data}}</td>
                            <td @if ($table_data->z_score>3||$table_data->z_score<-3)
                                class="bg-warning"
                            @endif>{{$table_data->z_score}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data setelah diurutkan dan outlier tidak dipakai</h4>
                <table class="table card-text" id="data_urut">
                    <thead>
                        <tr>
                            <th>Nomor Data</th>
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
</div>
<script>
    $(document).ready(function () {
        $('#data_mentah').DataTable({
            ordering:false
        });
        $('#data_urut').DataTable({
            ordering:false
        });
    });
</script>
@endsection
