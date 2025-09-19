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
                
        <form action="{{ url('saveEditUserIt') }}" method="post"> 
            <input type="hidden" name="id" class="form-control" id="id" value="{{ $listDataIt[0]->MD_USER_IT_ID_INT }}"required>
            {{ csrf_field() }}
                <div class="form-group">
                    <label>Nama<span style="color: red;">*</span></label>
                    <input type="text"  name="nama" autocomplete='off' class="form-control" id="nama" value="{{ $listDataIt[0]->NAMA }}" required>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                       <div class="form-group">
                            <label>Email<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" autocomplete=off id="Email" name="Email" value="{{ $listDataIt[0]->EMAIL }}" required>
                        </div>
                    </div>
                      <div class="col-sm-2">
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" aria-label="Default select example" id="Role" name="Role" required>
                                <option value="{{$listDataIt[0]->ROLE}}">{{ $listDataIt[0]->DESC_CHAR }}</option>
                                @foreach($listRoleIt as $role)
                                <option value="{{$role->MD_ROLE_IT_ID_INT}}">{{$role->DESC_CHAR}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                      <div class="col-sm-2">
                        <div class="form-group">
                            <label>Parent</label>
                            <select class="form-control" aria-label="Default select example" id="parent" name="parent" required>
                                <option value="{{$listParentIt2->MD_USER_IT_ID_INT}}">{{ $listParentIt2->NAMA }}</option>
                                 @foreach($listParentIt as $parent)
                                <option value="{{$parent->MD_USER_IT_ID_INT}}">{{$parent->NAMA}}</option>
                                @endforeach
                            </select>
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