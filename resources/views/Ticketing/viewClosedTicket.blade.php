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
                <input type="hidden" name="TICKET_HISTORY_ID" class="form-control" id="TICKET_HISTORY_ID" value="{{ $viewDataTicket[0]->TICKET_HISTORY_ID }}"required>
                {{ csrf_field() }}
                <div class="form-group">
                    <label>Judul Ticket<span style="color: red;">*</span></label>
                    <input type="text" readonly="readonly" class="form-control" autocomplete=off placeholder="Isi Judul Ticket" id="judul" name="judul" value="{{ $viewDataTicket[0]->JUDUL_TICKET }}" required>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>No Ticket<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly"  name="TRANS_TICKET_NOCHAR" autocomplete='off' class="form-control" id="TRANS_TICKET_NOCHAR" value="{{ $viewDataTicket[0]->TRANS_TICKET_NOCHAR }}" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Nama<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly"  name="nama" autocomplete='off' class="form-control" id="nama" value="{{ $viewDataTicket[0]->created_by }}" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Type<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly"  name="TYPE" autocomplete='off' class="form-control" id="TYPE" value="{{ $viewDataTicket[0]->DESC_CHAR_MD_TYPE_KELUHAN_TICKETING }}" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Aplikasi<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly" name="APLIKASI" autocomplete='off' class="form-control" id="APLIKASI" value="{{ $viewDataTicket[0]->DESC_CHAR_APLIKASI }}" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>PIC<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly" name="PIC" autocomplete='off' class="form-control" id="PIC" value="{{ $viewDataTicket[0]->NAMA }}" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Tanggal Ticket<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly" name="TGL_TICKET" autocomplete='off' class="form-control" id="TGL_TICKET" value="{{ $viewDataTicket[0]->TRX_DATE }}" required>
                        </div>
                    </div>
                </div>
                    <div class="">
                        <div class="form-group ">
                            <label>Deskripsi<span style="color: red;">*</span></label>
                            <textarea class="form-control"  id="DESC_CHAR" name="DESC_CHAR" rows="7" required>{{ $viewDataTicket[0]->DESC_CHAR }}</textarea>
                        </div>
                    </div>
                    <input type="hidden" name="Url" class="form-control" id="Url" value=" {{base64_encode($viewDataTicket[0]->TRANS_TICKET_NOCHAR)}}"required>
                    @if($isAdmin == false &&  $viewDataTicket[0]->DIFF_DATES <= 3)
                    <div class="">
                        <div class="form-group ">
                            <label>Respond User<span style="color: red;">*</span></label>
                            <textarea class="form-control" id="RESPOND"  name="RESPOND" required></textarea>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Status<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly" name="status" autocomplete='off' class="form-control" id="status" value="{{ $viewDataTicket[0]->DESC_STATUS }}" required>
                        </div>
                    </div>
                    <div class="row" style="margin-left:70%">      
                        @if($isAdmin == false &&  $viewDataTicket[0]->DIFF_DATES <= 3 )
                            <div class="col-sm-2" style="margin-right:4%">
                                <div class="form-group">
                                    <button type="submit" id= "reopen" class="btn btn-warning" onclick="post({{1}})">Reopen</button>
                                </div>
                            </div>
                        @endif
                    @if($isAdmin == false &&  $viewDataTicket[0]->DIFF_DATES <= 3 )
                        <div class="col-sm-2" style="margin-right:3%">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id= "submit" onclick="post({{2}})" >Submit</button>
                            </div>
                        </div>
                    @endif
                    <div class="col-xl-5">
                            <div class="form-group">
                                <a class="form-control btn btn-info btn-lg" href="{{ route('viewListTicketing') }}">
                                    Back
                                </a>
                            </div>
                        </div>
                    </div>
                    <form>       
                        <br />
                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">                            
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>User</th>
                                        <th>Deskripsi</th>
                                        <th>Attachment</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                @php
                                    $no = 1;
                                @endphp
                                <tbody>
                                    @foreach($listTicketHistory as $data)
                                <tr>
                                    <td style="text-align: left;">{{ $no++ }}</td>
                                    <td style="text-align: left;">{{$data->created_history}}</td>
                                    <td style="text-align: left;"><?php echo($data->HISTORY_DESC); ?></td>
                                    <td>
                                        <a href="{{URL('/downloadFile/'.$data->ATTACHMENT_NAME)}}">{{$data->ATTACHMENT_NAME}}</a>
                                        <a href="{{URL('/downloadFile/'.$data->ATTACHMENT_NAME2)}}">{{$data->ATTACHMENT_NAME2}}</a>
                                        <a href="{{URL('/downloadFile/'.$data->ATTACHMENT_NAME3)}}">{{$data->ATTACHMENT_NAME3}}</a>
                                    </td>
                                    <td style="text-align: left;">{{$data->DESC_CHAR}}</td>
                                    <td style="text-align: left;">{{date('d/m/Y , h:i:s A',strtotime($data->created_at))}}</td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    CKEDITOR.replace('HISTORY_DESC', {readOnly: true, versionCheck: false});
    CKEDITOR.replace('RESPOND', {versionCheck: false});
    $("form").submit( function(e) {
            var messageLength = CKEDITOR.instances['RESPOND'].getData().replace(/<[^>]*>/gi, '').length;
            if( !messageLength ) {
                Swal.fire({
            html: 'Foam Deskripsi Kosong',
            icon: 'warning',
            cancelButtonText: 'Back',
            allowOutsideClick: false,
        });
                e.preventDefault();
            }
        });
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
        
    function post(id){

        var sweet_loader = '<div class="sweet_loader"><svg viewBox="0 0 140 140" width="140" height="140"><g class="outline"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="rgba(0,0,0,0.1)" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g><g class="circle"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="#71BBFF" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-dashoffset="200" stroke-dasharray="300"></path></g></svg></div>';
        var Url = $("#Url").val();
        var RESPOND = CKEDITOR.instances.RESPOND.getData()
        var messageLength = CKEDITOR.instances['RESPOND'].getData().replace(/<[^>]*>/gi, '').length;
        if( !messageLength ) {
            Swal.fire({
            html: 'Form Respond Kosong',
            icon: 'warning',
            cancelButtonText: 'Back',
            allowOutsideClick: false,
        });
            e.preventDefault();
        }else{ 
            if(id == 1){
                var rute = "{{ route('reopenDataTicketing') }}";
            }else{
                var rute =  "{{ route('respondCloseTicketing') }}";
            }
            $.ajax({
                type: "POST",
                url: rute,
                data: {
                    _token: "{{ csrf_token() }}",
                    Url : Url,
                    RESPOND : RESPOND
                    },
                    beforeSend: function() {
                    swal.fire({
                        html: '<h5>Loading...</h5>',
                        showConfirmButton: false,
                        onRender: function() {
                            // there will only ever be one sweet alert open.
                            $('.swal2-content').prepend(sweet_loader);
                        }
                    });
                },
                dataType: 'json',
                cache: false,
                success: function (data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success.'
                    })
                    .then(function(){
                        window.location = "/viewListTicketing";
                    }); 
                },
                error: function(errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: errorThrown
                    }).then(function(){
                        window.location = "/viewListTicketing";
                    }); 
                }
            });
        }
}
</script>

@endsection