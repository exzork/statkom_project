@extends('template.sidebar')
@section('title','Pengolahan Data')
@section('content')
<div class="row">
    @if ($message = Session::get('success'))
        <div class="alert alert-success w-100 alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <form action="add_data" class="form-inline w-100" method="POST">
                    @csrf
                    <div class="w-100 input-group">
                        <label for="data_add">Data : </label>
                        <input type="number" name="data" min="1" max="5000" class="form-control" placeholder="Masukkan data" id="data_add">
                        <button class="btn btn-primary" type="Submit">Tambahkan</button>
                    </div>
                </form>
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
                        @php
                            $x=1;
                        @endphp
                        @foreach ($table_mentah as $table_data)
                        <tr @if ($table_data->z_score>3||$table_data->z_score<-3)
                            class="bg-warning"
                        @endif>
                            <td>@php
                                echo $x;
                                $x++;
                            @endphp</td>
                            <td>{{$table_data->data}}</td>
                            <td align="right">{{$table_data->z_score}}</td>
                            <td>
                                <button class="btn btn-success fa fa-edit mr-1" onclick="update({{$table_data->id}});"></button>
                                <button class="btn btn-danger fa fa-trash" onclick="delete_data({{$table_data->id}},{{$table_data->data}});"></button>
                            </td>
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
<div class="modal fade" id="edit_data">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit data</h4>
                <button class="close" type="button" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="/edit_data" method="POST">
                    @csrf
                    <div class="w-100 input-group">
                        <input type="hidden" name="id" id="id_data" value="">
                        <input type="number" name="data" min="1" max="5000" class="form-control" placeholder="Masukkan data" id="data_edit">
                        <button class="btn btn-primary" type="Submit">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete_data">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete data</h4>
                <button class="close" type="button" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Anda akan <strong>menghapus</strong> data dengan nilai <span id="nilai_data"></span></p>
                <form action="/delete_data" method="POST" id="delete_form">
                    @csrf
                    <div class="w-100 input-group">
                        <input type="hidden" name="id" id="id_data_del" value="">
                        <button class="btn btn-danger" type="Submit">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function update(id){
        $("#id_data").val(id);
        $("#edit_data").modal("show");
    };
    function delete_data(id,nilai) {
        $("#id_data_del").val(id);
        $("#nilai_data").html(nilai);
        $("#delete_form").attr('action','/delete_data/'+id);
        $("#delete_data").modal("show");
    }
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
