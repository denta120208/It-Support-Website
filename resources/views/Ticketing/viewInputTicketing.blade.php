


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
        width: 1000px;
    }

    .ck-editor__editable_inline[role="textbox"] {
    min-height: 400px;
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
                
        <form action="{{ url('saveDataTicket') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
                    <div class="form-group">
                        <label>Judul Ticket<span style="color: red;">*</span></label>
                        <input type="text" class="form-control" autocomplete=off placeholder="Isi Judul Ticket" id="judul" name="judul" required>
                    </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" value="{{$userName}}" class="form-control" id="userName" name="userName" autocomplete="off" >
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" value="{{$email}}" class="form-control"id="email" name="email" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Type<span style="color: red;">*</span></label>
                            <select class="form-control" aria-label="Default select example" id="TYPE" name="TYPE"required>
                                <option value="">Pilih Type</option>
                                @foreach($dataType as $type)
                                <option value="{{$type->MD_TYPE_KELUHAN_TICKETING_ID_INT}}">{{$type->DESC_CHAR}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" aria-label="Default select example" id="CATEGORY" name="CATEGORY" >
                                <option value="" selected>Category Not Selected</option>
                                @foreach($dataRole as $role)
                                <option value="{{$role->MD_ROLE_IT_ID_INT}}">{{$role->DESC_CHAR}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Aplikasi <span style="color: red;">*</span></label>
                            <select class="form-control" aria-label="Default select example" id="APLIKASI" name="APLIKASI" required>
                                 <option value="">Select Aplikasi</option>
                                 
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>tgl request</label>
                            <input type="date" value="<?php echo date('Y-m-d'); ?>" readonly="readonly" class="form-control"id="TRX_DATE" name="TRX_DATE" >
                        </div>
                    </div>
                </div>
                    <div class="form-group ">
                        <label>Deskripsi<span style="color: red;">*</span></label>
                        <textarea class="form-control" id="DESC_CHAR" name="DESC_CHAR"></textarea>
                    </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Attach 1</label>
                            <input type="file" id="ATTACH" name="ATTACH[]"><br>
                            <span style="color: red;"><b>* File Dibawah 1 MB (PerFile) </b></span> <br>
                            <span style="color: red;"><b>* Hanya Bisa Pilih 1 File</b></span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Attach 2</label>
                            <input type="file" id="ATTACH2" name="ATTACH2[]"><br>
                            <span style="color: red;"><b>* File Dibawah 1 MB (PerFile) </b></span> <br>
                            <span style="color: red;"><b>* Hanya Bisa Pilih 1 File</b></span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Attach 3</label>
                            <input type="file" id="ATTACH3" name="ATTACH3[]"><br>
                            <span style="color: red;"><b>* File Dibawah 1 MB (PerFile) </b></span> <br>
                            <span style="color: red;"><b>* Hanya Bisa Pilih 1 File</b></span>
                        </div>
                    </div>
                </div>
                    <button type="submit" class="btn btn-primary col-sm-2" style="margin-left:75%">Submit</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    CKEDITOR.replace('DESC_CHAR', {versionCheck: false});
    $("form").submit( function(e) {
            var messageLength = CKEDITOR.instances['DESC_CHAR'].getData().replace(/<[^>]*>/gi, '').length;
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
    
    // document.getElementById('DESC_CHAR').setAttribute('required','required')

</script>
<script>
    $('#CATEGORY').on('change', function() {
               var ID_ROLE = $(this).val();
               if(ID_ROLE) {
                   $.ajax({
                       url: '/getAplikasi/' + ID_ROLE,
                       type: "GET",
                       dataType: "json",
                       success: function (data) {
                           $('#APLIKASI').empty().append('<option value="">Select Aplikasi</option>');
                           $.each(data.aplikasi, function(index, item)
                           {
                            $('#APLIKASI').append('<option value="' + item.MD_APLIKASI_ID_INT + '">' + item.DESC_CHAR + '</option>');
                           });
                       },

                   });
               }else{
                    $('#APLIKASI').empty().append('<option value="">Select Aplikasi</option>');
               }
    });

</script>


@endsection