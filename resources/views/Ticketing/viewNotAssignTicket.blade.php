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
    Ticket HelpDesk
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
                
        <form action="{{ url('editDataTicketNotAssign') }}" method="post">
            <input type="hidden" name="TRANS_TICKET_NOCHAR" class="form-control" id="TRANS_TICKET_NOCHAR" value="{{ $viewDataTicket[0]->TRANS_TICKET_NOCHAR }}"required>
            {{ csrf_field() }}
                <div class="form-group">
                    <label>Judul Ticket<span style="color: red;">*</span></label>
                    <input type="text" readonly="readonly" class="form-control" autocomplete=off placeholder="Isi Judul Ticket" id="judul" name="judul" value="{{ $viewDataTicket[0]->JUDUL_TICKET }}" required>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Nama<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly"  name="nama" autocomplete='off' class="form-control" id="nama" value="{{ $viewDataTicket[0]->created_by }}" required>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Type<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly"  name="TYPE" autocomplete='off' class="form-control" id="TYPE" value="{{ $viewDataTicket[0]->DESC_CHAR_MD_TYPE_KELUHAN_TICKETING }}" required>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Aplikasi<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly" name="APLIKASI" autocomplete='off' class="form-control" id="APLIKASI" value="{{ $viewDataTicket[0]->DESC_CHAR_APLIKASI }}" required>
                        </div>
                    </div>
                    @if($isAdmin == true)
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>PIC</label>
                            <select class="form-control" aria-label="Default select example" id="PIC" name="PIC" required>
                                <option value="{{$viewDataTicket[0]->PIC}}">{{ $viewDataTicket[0]->NAMA }}</option>
                                @foreach($dataPic as $pic)
                                <option value="{{$pic->MD_USER_IT_ID_INT}}">{{$pic->NAMA}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
                    <div class="form-group ">
                        <label>Deskripsi<span style="color: red;">*</span></label>
                        <textarea disabled class="form-control" id="DESC_CHAR" name="DESC_CHAR" rows="7"  >{{ $viewDataTicket[0]->DESC_CHAR }}</textarea>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            @php
                                $no = 1;
                            @endphp
                            <tbody>
                                @foreach($viewAttachment as $att)
                                    <tr>
                                        <td style="text-align: center;">{{ $no++ }}</td>C
                                        <td>
                                            <a href="{{URL('/downloadFile/'.$att->ID)}}">{{$att->ATTACHMENT_NAME}}</a>
                                        </td>
                                        <td style="text-align: center;">{{$att->created_at}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row"  style="margin-left:65%">
                        <button type="submit"class="form-control btn btn-primary col-sm-2">Submit</button>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <a class="form-control btn btn-info" href="{{ route('viewListTicketing') }}">
                                        Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </form>
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
            "order": [[0, 'asc']],
            "scrollY": true, "scrollX": true,
            "responsive": true, "lengthChange": false, "autoWidth": false,
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        
    });

    function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    

</script>

@endsection