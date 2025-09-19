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
    text-align: left;
}


</style>



@extends('layouts.mainLayouts')

@section('navbar_header')
Ticketing - <b>{{session('current_project_char')}}</b>
@endsection

@section('header_title')
list Data Ticket
@endsection

@section('content')

<div class="container-xxl">
<div class="row justify-content-left">
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

            <!-- /.col -->
            <div class="col-xl">
                <div class="row" style="padding-left: 5px;">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <a class="form-control btn btn-info" href="{{ route('viewInputTicketing') }}">
                                Add Ticket
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#ticket" data-toggle="tab">Ticket </a></li>
                        @if($isAdmin == true)
                        <li class="nav-item"><a class="nav-link" href="#notAssign" data-toggle="tab">Not Assign</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="#close" data-toggle="tab">Close</a></li>
                        @if($isAdmin == true)
                        <li class="nav-item"><a class="nav-link" href="#group" data-toggle="tab">Group</a></li>
                        @endif
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <!-- /.tab-pane -->
                        <div class="active tab-pane" id="ticket">
                            <form action="{{ url('filterDataTicket') }}" method="post">
                                {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xl-3 form-group">
                                    <div class="btn-group submitter-group filter">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">No Ticket</div>
                                        </div>
                                        <input type="text" class="form-control input-xs filter"style="width: 100%;"placeholder="noTicket"  id="noTicket" name="noTicket"/>
                                    </div>
                                </div>
                                <div class="col-xl-3 form-group">
                                    <div class="btn-group submitter-group filter">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Start Date</div>
                                        </div>
                                        <input type="date" class="form-control input-xs "style="width: 100%;" id="startDate" name="startDate"/>
                                    </div>
                                </div>
                                <div class="col-xl-3 form-group">
                                    <div class="btn-group submitter-group filter">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">End Date</div>
                                        </div>
                                        <input type="date"  class="form-control input-xs filter"style="width: 100%;" id="endDate" name="endDate"/>
                                    </div>
                                </div>
                                <div class="col-xl-3 form-group">
                                    <div class="btn-group submitter-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Status</div>
                                        </div>
                                        <select class="form-control status-dropdown filter" id="status" name="status">
                                            <option value='all' selected>All Status</option>
                                            @foreach($dataStatus as $status)
                                            <option value="{{$status->TRANS_TICKET_STATUS_ID_INT}}">{{$status->DESC_CHAR}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <button class="btn btn-primary form-group col-2" style="margin-left:70%;" data-toggle="modal">Submit</button>
                    </form>    
                    <br />
                    <div class="row">
                        <div class="col-md" style="overflow-x:auto;">                            
                        <table id="TABLE_1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Ticket</th>
                                    <th>User Request</th>
                                    <th>Trx.Date</th>
                                    <th>Title</th>
                                    <th>Project</th>
                                    <th>Created By</th>
                                    <th>PIC</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            @php
                                $no = 1;
                            @endphp
                            <tbody>
                                @foreach($listDataTicket as $data)
                                <tr>
                                    <td style="text-align: left;">{{ $no++ }}</td>
                                    <td style="text-align: left;">{{$data->TRANS_TICKET_NOCHAR}}</td>
                                    <td style="text-align: left;">{{ucwords($data->REQUEST_BY_USER)}}</td>
                                    <td style="text-align: left;">{{date('d/m/Y , h:i:s A',strtotime($data->created_at))}}</td>
                                    <td style="text-align: left;">{{$data->JUDUL_TICKET}}</td>
                                    <td style="text-align: left;">{{$data->PROJECT_NAME}}</td>
                                    <td style="text-align: left;">{{ucwords($data->created_by)}}</td>
                                    <td style="text-align: left;">{{$data->NAMA}}</td>
                                    <td style="text-align: left;">{{$data->DESC_CHAR}}</td>
                                    <td>
                                        <a href="{{URL('/viewDataTicketing/'.base64_encode($data->TRANS_TICKET_NOCHAR))}}" class="btn btn-warning">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>

                </div>
                @if($isAdmin == true)
                <!-- /.tab-pane -->
                    <div class="tab-pane" id="notAssign">
                        <!-- Post -->
                    <form>       
                        <br />
                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">                            
                            <table id="TABLE_2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Ticket</th>
                                        <th>User Request</th>
                                        <th>Trx.Date</th>
                                        <th>Title</th>
                                        <th>Project</th>
                                        <th>Created By</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                @php
                                    $no = 1;
                                @endphp
                                <tbody>
                                    @foreach($listDataTicket2 as $data)
                                    <tr>
                                        <td style="text-align: left;">{{ $no++ }}</td>
                                        <td style="text-align: left;">{{$data->TRANS_TICKET_NOCHAR}}</td>
                                        <td style="text-align: left;">{{ucwords($data->REQUEST_BY_USER)}}</td>
                                        <td style="text-align: left;">{{date('d/m/Y , h:i:s A',strtotime($data->created_at))}}</td>
                                        <td style="text-align: left;">{{$data->JUDUL_TICKET}}</td>
                                        <td style="text-align: left;">{{$data->PROJECT_NAME}}</td>
                                        <td style="text-align: left;">{{ucwords($data->created_by)}}</td>
                                        <td>
                                            <a href="{{URL('/viewNotAssignTicket/'.base64_encode($data->TRANS_TICKET_NOCHAR))}}" class="btn btn-warning">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </form>
                        <!-- /.post -->
                    </div>
                @endif
                <div class="tab-pane" id="close">
                    <!-- Post -->
                    <form>       
                        <br />
                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">                            
                            <table id="TABLE_3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Ticket</th>
                                        <th>User Request</th>
                                        <th>Trx.Date</th>
                                        <th>Trx.Close Date</th>
                                        <th>Title</th>
                                        <th>Project</th>
                                        <th>Created By</th>
                                        <th>PIC</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                @php
                                    $no = 1;
                                @endphp
                                <tbody>
                                    @foreach($listDataTicket3 as $data)
                                <tr>
                                    <td style="text-align: left;">{{ $no++ }}</td>
                                    <td style="text-align: left;">{{$data->TRANS_TICKET_NOCHAR}}</td>
                                    <td style="text-align: left;">{{ucwords($data->REQUEST_BY_USER)}}</td>
                                    <td style="text-align: left;">{{date('d/m/Y , h:i:s A',strtotime($data->created_at))}}</td>
                                    <td style="text-align: left;">{{date('d/m/Y , h:i:s A',strtotime($data->updated_at))}}</td>
                                    <td style="text-align: left;">{{$data->JUDUL_TICKET}}</td>
                                    <td style="text-align: left;">{{$data->PROJECT_NAME}}</td>
                                    <td style="text-align: left;">{{ucwords($data->created_by)}}</td>
                                    <td style="text-align: left;">{{$data->NAMA}}</td>
                                    <td style="text-align: left;">{{$data->DESC_STATUS}}</td>
                                    <td>
                                        {{-- <a href="#" class="btn btn-primary"onclick="swalReopenTicket({{$data->TRANS_TICKET_NOCHAR}})">Reopen</a> --}}
                                        <a href="{{URL('/viewCloseTicketing/'.base64_encode($data->TRANS_TICKET_NOCHAR))}}" class="btn btn-warning">View</a>
                                    </td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </form>
                <!-- /.post -->
            </div>
            @if($isAdmin == true)
            <div class="tab-pane" id="group">
                    <!-- Post -->
                    <form>       
                        <br />
                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">                            
                            <table id="TABLE_4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                    <th>No</th>
                                    <th>No Ticket</th>
                                    <th>User Request</th>
                                    <th>Trx.Date</th>
                                    <th>Title</th>
                                    <th>Project</th>
                                    <th>Created By</th>
                                    <th>PIC</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                    </tr>
                                </thead>
                                @php
                                    $no = 1;
                                @endphp
                                <tbody>
                                    @foreach($listDataTicket4 as $data)
                                <tr>
                                    <td style="text-align: left;">{{ $no++ }}</td>
                                    <td style="text-align: left;">{{$data->TRANS_TICKET_NOCHAR}}</td>
                                    <td style="text-align: left;">{{ucwords($data->REQUEST_BY_USER)}}</td>
                                    <td style="text-align: left;">{{date('d/m/Y , h:i:s A',strtotime($data->created_at))}}</td>
                                    <td style="text-align: left;">{{$data->JUDUL_TICKET}}</td>
                                    <td style="text-align: left;">{{$data->PROJECT_NAME}}</td>
                                    <td style="text-align: left;">{{ucwords($data->created_by)}}</td>
                                    <td style="text-align: left;">{{$data->NAMA}}</td>
                                    <td style="text-align: left;">{{$data->DESC_CHAR}}</td>
                                    <td>
                                        {{-- <a href="#" class="btn btn-primary"onclick="swalReopenTicket({{$data->TRANS_TICKET_NOCHAR}})">Reopen</a> --}}
                                        <a href="{{URL('/viewDataTicketing/'.base64_encode($data->TRANS_TICKET_NOCHAR))}}" class="btn btn-warning">View</a>                                    </td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                   
                    </form>
                <!-- /.post -->
                </div>
            @endif
            
        </div>
        <!-- /.tab-content -->
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<script type="text/javascript">


$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    
    $("table[id^='TABLE']").DataTable({
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