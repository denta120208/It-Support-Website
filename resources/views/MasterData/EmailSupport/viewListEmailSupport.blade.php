<?php
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\BarChart;
    use \koolreport\widgets\google\PieChart;
    use \koolreport\pivot\widgets\PivotTable;
    use \koolreport\widgets\google\ColumnChart;
    use \koolreport\drilldown\LegacyDrillDown;
    use \koolreport\drilldown\DrillDown;
    use \koolreport\widgets\google\LineChart;
    use \koolreport\barcode\QRCode;
?>

<style>
    div .dt-buttons{
        float : left;
    }
    .dataTables_length{
        float : left;
        padding-left: 10px;
    }
   
</style>

@extends('layouts.mainLayouts')

@section('navbar_header')
Ticket HelpDesk - <b>{{session('current_project_char')}}</b>
@endsection

@section('header_title')
    MasterData
@endsection

@section('content')

<style>
    #DESC_CHAR {
        height: 200px;
    }
    #HISTORY_DESC {
        height: 400px;
    }
</style>


<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <div style="padding-left: 5px;">
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ session()->get('success') }}</strong>
                            </div>
                        @endif
                        @if(session()->has('error'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ session()->get('error') }}</strong>
                            </div>
                        @endif
                        @if(session()->has('warning'))
                            <div class="alert alert-warning alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ session()->get('warning') }}</strong>
                            </div>
                        @endif
                        @if(session()->has('info'))
                            <div class="alert alert-info alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ session()->get('info') }}</strong>
                            </div>
                        @endif
                    </div>
                     <div class="row" style="padding-left: 5px;">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <a class="form-control btn btn-info" href="{{ route('viewAddEmailSupport') }}">
                                    Add Email Support
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="example1" name="example1" border-style="hidden">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Nomor Hp</th>
                                    <th>Sandi Aplikasi</th>
                                    <th>Counter</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            @php
                                $no = 1;
                            @endphp
                            <tbody>
                                @foreach($listEmailSupp as $data)
                                    <tr>
                                        <td style="text-align: center;">{{$no++}}</td>
                                        <td style="text-align: center;">{{$data->NAMA}}</td>
                                        <td style="text-align: center;">{{$data->EMAIL}}</td>
                                        <td style="text-align: center;">{{$data->PASSWORD}}</td>
                                        <td style="text-align: center;">{{$data->VERIF_TELP}}</td>
                                        <td style="text-align: center;">{{$data->SANDI_APLIKASI}}</td>
                                        <td style="text-align: center;">{{$data->COUNTER}}</td>
                                         <td>
                                            <a href="{{URL('/viewDataEmailSupport/'.$data->MD_EMAIL_SUPPORT_ID_INT)}}" class="btn btn-warning">View</a>
                                             <a href="#" class="btn btn-danger"onclick="swalDeleteData({{$data->MD_EMAIL_SUPPORT_ID_INT}})">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    CKEDITOR.replace('DESC_CHAR', {readOnly: true, versionCheck: false});
</script>

<script type="text/javascript">

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        
        $("#example1").DataTable({
            // "order": [[0,'DESC']],
            "scrollY": true, "scrollX": true,
            "responsive": true, "lengthChange": false, "autoWidth": false,
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        
    });

    function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    
    function swalDeleteData(param1) {
        
        Swal.fire({
        html: 'Anda Yakin Ingin Menghapus <b style="color: red;">Data</b> ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/deleteDataEmailSupport/" + param1;
            }
        });
    }
</script>

@endsection