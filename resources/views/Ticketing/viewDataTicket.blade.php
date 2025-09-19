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
 
#display-image{
    width: 100%;
    justify-content: center;
    padding: 5px;
    margin: 15px;
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
                
        <form action="{{ url('editDataTicketHistory') }}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="TRANS_TICKET_NOCHAR" class="form-control" id="TRANS_TICKET_NOCHAR" value="{{ $viewDataTicket[0]->TRANS_TICKET_NOCHAR }}"required>
            {{ csrf_field() }}
                    <div class="form-group">
                        <label>Judul Ticket<span style="color: red;">*</span></label>
                        <input type="text" readonly="readonly" class="form-control" autocomplete=off placeholder="Isi Judul Ticket" id="judul" name="judul" value="{{ $viewDataTicket[0]->JUDUL_TICKET }}" required>
                    </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>No Ticket<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly"  name="nama" autocomplete='off' class="form-control" id="nama" value="{{ $viewDataTicket[0]->TRANS_TICKET_NOCHAR }}" required>
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
                            <label>Tanggal Ticket<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly" name="TGL_TICKET" autocomplete='off' class="form-control" id="TGL_TICKET" value="{{ $viewDataTicket[0]->TRX_DATE }}" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>PIC</label>
                            <select class="form-control" aria-label="Default select example" id="PIC" name="PIC"  @if($isAdmin == false) readonly @endif required>
                                <option value="{{$viewDataTicket[0]->PIC}}">{{ $viewDataTicket[0]->NAMA }}</option>
                                @if($isAdmin == true)
                                @foreach($dataPic as $pic)
                                <option value="{{$pic->MD_USER_IT_ID_INT}}">{{$pic->NAMA}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                    <div class="form-group ">
                        <label>Deskripsi<span style="color: red;">*</span></label>
                        <textarea disabled class="form-control" id="DESC_CHAR" name="DESC_CHAR" rows="5"  >{{ $viewDataTicket[0]->DESC_CHAR }}</textarea>
                    </div>  
                    <div class="form-group ">
                        <label>Respond <span style="color: red;">*</span></label>
                        <textarea class=" form-control" id="HISTORY_DESC" name="HISTORY_DESC"  rows="5" style="height:auto;" required></textarea>
                    </div>
                <div class = "row">
                    <div class="col-sm-3" style="margin-top:1%; margin-left:3%;">
                        <div class="form-group">
                            <label>Attach1</label>
                            <input type="file" id="ATTACH" name="ATTACH[]" > <br>
                            <span style="color: red;"><b>* File Dibawah 1 MB  </b></span> <br>
                            {{-- <span style="color: red;"><b>* Bisa Pilih Lebih Dari 1 File</b></span>(PerFile) --}}
                        </div>
                    </div>
                    <div class="col-sm-3" style="margin-top:1%; margin-left:3%;">
                        <div class="form-group">
                            <label>Attach2</label>
                            <input type="file" id="ATTACH2" name="ATTACH2[]" > <br>
                            <span style="color: red;"><b>* File Dibawah 1 MB  </b></span> <br>
                            {{-- <span style="color: red;"><b>* Bisa Pilih Lebih Dari 1 File</b></span>(PerFile) --}}
                        </div>
                    </div>
                    <div class="col-sm-3" style="margin-top:1%; margin-left:3%;">
                        <div class="form-group">
                            <label>Attach3</label>
                            <input type="file" id="ATTACH3" name="ATTACH3[]" > <br>
                            <span style="color: red;"><b>* File Dibawah 1 MB  </b></span> <br>
                            {{-- <span style="color: red;"><b>* Bisa Pilih Lebih Dari 1 File</b></span>(PerFile) --}}
                        </div>
                    </div>
                </div>
                @if($isStatus == false)
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" aria-label="Default select example" id="status" name="status"  @if($isAdmin == false) readonly @endif  >
                            <option value="{{$viewDataTicket[0]->status}}">{{ $viewDataTicket[0]->DESC_STATUS}}</option>
                            @if($isAdmin == true)
                            @foreach($dataStatus as $status)
                            <option value="{{$status->TRANS_TICKET_STATUS_ID_INT}}">{{$status->DESC_CHAR}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="form-group">
                        <a class="form-control btn btn-info "  style="width:120%; height:100%" href="{{ route('viewListTicketing') }}">
                            Back
                        </a>
                    </div>
                    <button type="submit" class="btn btn-primary " style="width:10%; margin-left:80%">Submit</button>
                </div>
                      <div class="table-responsive" style="padding-top:3%">
                        <table class="table table-bordered" id="chat" name="chat" border-style="hidden">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User</th>
                                    <th>Deskripsi</th>
                                    <th>Attach</th>
                                    <th>status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            @php
                                $no = 1;
                            @endphp
                            <tbody>
                                @foreach($dataHistory as $data)
                                    <tr>
                                        <td style="text-align: left;">{{$no++}}</td>
                                        <td style="text-align: left;">{{$data->created_by}}</td>
                                        <td style="text-align: left;"><?php echo $data->HISTORY_DESC; ?></td>
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
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    CKEDITOR.replace('DESC_CHAR', {readOnly: true, versionCheck: false});

    CKEDITOR.replace('HISTORY_DESC',{enterMode: CKEDITOR.ENTER_BR, versionCheck: false});
    $("form").submit( function(e) {
            var messageLength = CKEDITOR.instances['HISTORY_DESC'].getData().replace(/<[^>]*>/gi, '').length;
            if( !messageLength ) {
                Swal.fire({
            html: 'Foam Respond Kosong',
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

</script>

@endsection