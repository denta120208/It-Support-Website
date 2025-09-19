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
td {
    white-space: nowrap;
    text-align: center;
}
</style>



@extends('layouts.mainLayouts')

@section('navbar_header')
Ticketing - <b>{{session('current_project_char')}}</b>
@endsection

@section('header_title')
Report Ticket
@endsection

@section('content')

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
                <form action="{{ url('filterViewListReport') }}" method="post">
                    {{ csrf_field() }}
                    <div class = row>
                        <div class="col-2 form-group">
                            <div class="btn-group submitter-group filter">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">End Date</div>
                                </div>
                                <input type="date"  class="form-control input-xs filter"style="width: 100%;" id="endDate" name="endDate"/>
                            </div>
                        </div>
                        <button class="btn btn-primary form-group col-2" data-toggle="modal">Submit</button>
                    </div>
                </form>    
                    <div class="row">
                        <div class="col-md" style="overflow-x:auto;">                            
                            <table id="TABLE_1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>PIC</th>
                                        <th>Jumlah Ticket</th>
                                        <th>Open</th>
                                        <th>Progress</th>
                                        <th>Hold</th>
                                        <th>Close</th>
                                    </tr>
                                </thead>
                                @php
                                $no = 1;
                            @endphp
                            <tbody>
                                @foreach($listTicketReport as $data)
                            <tr>
                                <td style="text-align: center;">{{ $no++ }}</td>
                                <td style="text-align: center;">{{$data->NAMA}}</td>
                                <td style="text-align: center;">{{$data->JUMLAH_TICKET}}</td>
                                <td style="text-align: center;">{{$data->OPEN}}</td>
                                <td style="text-align: center;">{{$data->PROGRESS}}</td>
                                <td style="text-align: center;">{{$data->HOLD}}</td>
                                <td style="text-align: center;">{{$data->CLOSE}}</td>
                            </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

<script type="text/javascript">


$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    
    $("TABLE_1").DataTable({
        "order": [[1, 'DESC']],
        "autoheight":true, "autowidth":true,
        "responsive": false, "lengthChange": false, "autoWidth": false,
    }).buttons().container().adjust().draw().columns().adjust().appendTo('#TABLE_wrapper .col-md-6:eq(0)');

});
function swalReopenTicket(param1) {
        
        Swal.fire({
        html: 'Anda Yakin Ingin Approve <b style="color: blue;">Data</b> ini?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/reopenDataTicketing/" + param1;
            }
        });
    }
</script>

@endsection