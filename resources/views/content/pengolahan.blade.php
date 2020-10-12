@extends('template.sidebar')
@section('title','Pengolahan Data')
@section('content')
<div class="row">
    <div class="col-md-6">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Data</th>
                    <th>Outlier</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i=0;
                @endphp
                @foreach ($data as $data_)
                @php
                    $i++;
                @endphp
                <tr>
                    <td>@php echo $i @endphp</td>
                    <td>{{$data_->data}}</td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <ul>
            <li>Mean : {{$mean ?? 'NaN'}}</li>
            <li>Mean : {{$mean ?? 'NaN'}}</li>
            <li>Mean : {{$mean ?? 'NaN'}}</li>
            <li>Mean : {{$mean ?? 'NaN'}}</li>
            <li>Mean : {{$mean ?? 'NaN'}}</li>
            <li>Mean : {{$mean ?? 'NaN'}}</li>
            <li>Mean : {{$mean ?? 'NaN'}}</li>
            <li>Mean : {{$mean ?? 'NaN'}}</li>
            <li>Mean : {{$mean ?? 'NaN'}}</li>
        </ul>
    </div>
</div>
<script>
    $(document).ready(function () {
    $('table').DataTable();
  });
</script>
@endsection
