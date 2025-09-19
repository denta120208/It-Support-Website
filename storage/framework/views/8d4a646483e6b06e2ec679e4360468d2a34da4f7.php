<?php
use \koolreport\drilldown\DrillDown;
use \koolreport\processes\CopyColumn;
use \koolreport\processes\DateTimeFormat;
use \koolreport\widgets\google\ColumnChart;
use \koolreport\datagrid\DataTables;

$menu = explode(",", session('menu'));
$project = explode(",", session('proyek'));
$level = session('level');
$default_project_no_char = session('default_project_no_char');

if(empty(session('id'))) {
  header("Location: http://support.metropolitanland.com/logout");
  die();
}
else {
if(session('level') != NULL) {
    $project_arr_raw = $project;
    $project_arr = array();
    for($i = 0; $i < count($project_arr_raw); $i++) {
      $project_tmp = DB::select("SELECT * FROM MD_PROJECT WHERE PROJECT_NO_CHAR = '".$project_arr_raw[$i]."'");
      array_push($project_arr, $project_tmp[0]->PROJECT_NO_CHAR);
      if(empty(session('current_project'))) {
        session(['current_project' => $project_tmp[0]->PROJECT_NO_CHAR]);          
        session(['current_project_char' => strtoupper($project_tmp[0]->PROJECT_NAME)]);
      }
    }

    $project_arr_tmp = $project_arr;
    $project_arr_tmp = implode("','",$project_arr_tmp);
    $proyek = DB::select("SELECT * FROM MD_PROJECT WHERE PROJECT_NO_CHAR IN ('".$project_arr_tmp."')");
    session(['isLogin' => 1]);
  }
  else {
    header("Location: http://support.metropolitanland.com/logout");
    die();
  }
}
?>


<?php $__env->startSection('navbar_header'); ?>
DASHBOARD - <b><?php echo e(session('current_project_char')); ?></b>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
Dashboard
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
.hover:hover {background-color: lightblue;}
.dt-nowrap text-center:hover {background-color: lightblue;}
.datatable3 {
        border-bottom: 0 !important;
    }

</style>

<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <div style="padding-left: 5px;">
                        <div class="row">
                            <div class="col-md">
                                
                            </div>
                        </div>
                    </div>
                <?php if($isAdmin == true): ?>
                    <?php echo e(csrf_field()); ?>

                    <div class="row justify-content-center">
                        <div class="row" style="padding-left: 5px; width:100%;">
                            <div class="col-md" style="overflow-x:auto;">
                                <?php
                                DrillDown::create(array(
                                    "name"=>"DrilldownProject",
                                    "title"=>" ",
                                    "btnBack"=>array("text"=>"Back","class"=>"btn btn-primary"),
                                    "themeBase"=>"bs4",
                                    "scope"  => array(
                                        "report" => $report,
                                        "dataSource" => $report->dataStore("collection_Report_Dashboard_Project_Datatable")->data(),
                                        "dataEmail" => $dataEmail
                                    ),
                                    "levels"=>array(
                                        array(
                                            "title"=>"All Project",
                                            "content"=>function($params, $scope)
                                            {
                                                DataTables::create(array(
                                                    "name" => "datatable1",
                                                    "dataSource"=>$scope['report']->dataStore("collection_Report_Dashboard_Project_Datatable"),
                                                    "themeBase"=>"bs4",
                                                    "showFooter" => true,

                                                    "cssClass"=>array(
                                                        "table"=>"table table-bordered table-striped",
                                                        "td"=>function($row, $colName) {
                                                            if($colName == "PROJECT_NAME" ){
                                                                return "dt-nowrap hover";
                                                            }else{
                                                                return "normal-font-size";
                                                            }
                                                        }
                                                    ),
                                                    "columns" => array(
                                                        "indexColumn" => ["label" => "No", "formatValue" => function($value, $row) { return ""; }, "footerText" => "Total"],
                                                        "PROJECT_NAME" => [ "label" => "Project", "formatValue" => function($value, $row) { return $value; }],
                                                        "AMOUNT" => ["label" => "Amount", "formatValue" => function($value, $row) { return $value; }, "footer" => "sum", "footerText" => "@value"]
                                                    ),
                                                    "options"=>array(
                                                        "paging"=>true,
                                                        "searching"=>true,
                                                        "autoheight"=>true,
                                                        "responsive"=> false,
                                                        "lengthChange"=> false,
                                                        "autoWidth"=> false,
                                                        "select" => true,
                                                        "order"=>array(
                                                            array(0,"asc")
                                                        )
                                                    ),
                                                    "onReady" => "function() {
                                                        datatable1.on( 'order.dt search.dt', function () {
                                                            datatable1.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                                                cell.innerHTML = i+1;
                                                            } );
                                                        } ).draw();
                                                    }",
                                                    "searchOnEnter" => false,
                                                    "searchMode" => "or",
                                                    "clientEvents"=>array(
                                                        "select"=>"function(e, dt, type, indexes) {
                                                            // console.log(indexes);
                                                            DrilldownProject.next({INDEXES:indexes[0]});
                                                        }",
                                                    )
                                                ));
                                            }
                                        ),
                                        array(
                                            "title"=>function($params, $scope)
                                            {
                                                $dt = $scope['dataSource'];
                                                for($i = 0; $i < count($dt); $i++) {
                                                    if($i == $params["INDEXES"]) {
                                                        return $dt[$i]['PROJECT_NAME'];
                                                    }
                                                }
                                                // return 'test';
                                            },
                                            "content"=>function($params, $scope)
                                            {
                                                $index = $params["INDEXES"];
                                                $data = $scope['dataSource'];
                                                $dataEmail = $scope['dataEmail'];
    
                                                $PROJECT = NULL;
                                                $dataCP = NULL;
    
                                                // Get ID PROJECT
                                                for($i = 0; $i < count($data); $i++) {
                                                    if($i == $params["INDEXES"]) {
                                                        $PROJECT = $data[$i]['PROJECT'];
                                                        break;
                                                    }
                                                }
                                    
                                            $dataCP = DB::select("SELECT a.TRANS_TICKET_NOCHAR, a.REQUEST_BY_USER, b.DESC_CHAR, a.TRX_DATE, a.JUDUL_TICKET, a.created_by, a.PIC, c.NAMA, d.DESC_CHAR AS DESC_STATUS, a.status, a.PIC
                                            FROM TRANS_TICKET AS a
                                            LEFT JOIN MD_TYPE_KELUHAN_TICKETING AS b ON b.MD_TYPE_KELUHAN_TICKETING_ID_INT = a.TYPE
                                            LEFT JOIN MD_USER_IT AS c ON c.MD_USER_IT_ID_INT = a.PIC
                                            LEFT JOIN TRANS_TICKET_STATUS AS d ON d.TRANS_TICKET_STATUS_ID_INT = a.status 
                                            WHERE PROJECT = '".$PROJECT."'
                                            ORDER BY a.TRX_DATE DESC, a.status ASC");

                                                DataTables::create(array(
                                                    "name" => "datatable2",
                                                    "dataSource"=>$dataCP,
                                                    "themeBase"=>"bs4",
                                                    "showFooter" => false,
                                                    "cssClass"=>array(
                                                        "table"=>"table table-striped table-bordered",
                                                        "td"=>function($row, $colName) {
                                                            if($colName == "BTN_VIEW") {
                                                            return "dt-nowrap text-center";
                                                            }    
                                                        }
                                                    ),
                                                    "columns" => array(
                                                        "indexColumn" => ["label" => "No", "formatValue" => function($value, $row) { return ""; }],
                                                        "TRANS_TICKET_NOCHAR" => ["label" => "No.Ticket", "formatValue" => function($value, $row) { return $value; }],
                                                        "REQUEST_BY_USER" => ["label" => "User Request", "formatValue" => function($value, $row) { return $value; }],
                                                        "DESC_CHAR" => ["label" => "Type", "formatValue" => function($value, $row) { return $value; }],
                                                        "TRX_DATE" => ["label" => "Trx.Date", "formatValue" => function($value, $row) { return $value; }],
                                                        "JUDUL_TICKET" => ["label" => "Title", "formatValue" => function($value, $row) { return $value; }],
                                                        "created_by" => ["label" => "Created By", "formatValue" => function($value, $row) { return $value; }],
                                                        "NAMA" => ["label" => "PIC", "formatValue" => function($value, $row) { return $value; }],
                                                        "DESC_STATUS" => ["label" => "Status", "formatValue" => function($value, $row) { return $value; }],
                                                        "BTN_VIEW" => [
                                                            "label" => "View",
                                                            "formatValue" => function($value, $row) use ($dataEmail) {     
                                                                if($dataEmail['MD_USER_IT_ID_INT'] == $row['PIC']) {                                               
                                                                ?>
                                                                <a class="btn bg-gradient-primary btn-sm" href="javascript:void(0)"onclick="viewDataTicket('<?php  echo base64_encode($row['TRANS_TICKET_NOCHAR']) ?>','<?php echo $row['status'] ?>','<?php echo $row['PIC'] ?>')">
                                                                    View
                                                                </a>
                                                                <?php
                                                                }else{
                                                                ?>
                                                                    <a class="btn bg-gradient-default btn-sm" href="javascript:void(0)">
                                                                        View
                                                                    </a>
                                                                <?php 
                                                                }
                                                                return "";
                                                            },
                                                            "footerText" => "<b>-</b>"
                                                        ],
                                                    ),  
                                                    "options"=>array(
                                                        "paging"=>true,
                                                        "searching"=>true,
                                                        "autoheight"=>true,
                                                        "responsive"=> false,
                                                        "lengthChange"=> false,
                                                        "autoWidth"=> false,
                                                        "select" => true,
                                                        "order"=>array(
                                                            array(0,"asc")
                                                        )
                                                    ),
                                                    "onReady" => "function() {
                                                        datatable2.on( 'order.dt search.dt', function () {
                                                            datatable2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                                                cell.innerHTML = i+1;
                                                            } );
                                                        } ).draw();
                                                    }",
                                                    "searchOnEnter" => false,
                                                    "searchMode" => "or"
                                                ));
                                            }
                                        ),
                                    ),
                                ));  
                            ?> 
                        </div>
                    </div>
                </div>
        <div class="card">  
            <form>       
                <div class="row">
                    <div class="col-md" style="overflow-x:auto; padding:15px;">                            
                    <table id="datatable3" name="datatable3" class="table table-bordered table-striped datatable3" >
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Project</th>
                                <th>Komplain</th>
                                <th>Permintaan</th>
                                <th>Informasi</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <?php
                            $no = 1;
                        ?>
                        <tbody>
                            <?php $__currentLoopData = $datatable3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="text-align: left;"><?php echo e($no++); ?></td>
                            <td style="text-align: left;" id="id" name="id"><?php echo e($data->PROJECT_NAME); ?></td>
                            <td style="text-align: left;" class="komplain hover" id="komplain" name="komplain" onclick="getKomplain(<?php echo e($data->PROJECT_NO_CHAR); ?>)"><?php echo e($data->KOMPLAIN); ?></td>
                            <td style="text-align: left;" class="permintaan hover" id="permintaan" name="permintaan"onclick="getPermintaan(<?php echo e($data->PROJECT_NO_CHAR); ?>)"><?php echo e($data->PERMINTAAN); ?></td>
                            <td style="text-align: left;" class="informasi hover"id="informasi" name="informasi"onclick="getInformasi(<?php echo e($data->PROJECT_NO_CHAR); ?>)"><?php echo e($data->INFORMASI); ?></td>
                            <td style="text-align: left;"><?php echo e($data->AMOUNT); ?></td>
                        </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <th colspan="5" style="text-align:left">Total:</th>
                            <th><?php echo e($totalTicket[0]->TOTAL_TICKET); ?></th>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </form>
        </div>
            <div class="container">
                <div id="popUpDatatableType" class="modal fade popUpDatatableType">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">  
                                <button type="button" class="close" id="close1" data-dismiss="modal" aria-hidden="true">&times;</button>    
                            </div>
                            <div class="modal-body">
                                <div class="col-md" style="overflow-x:auto;">
                                    <table class="table table-bordered table-striped datatableType" id="datatableType" name="datatableType">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No Ticket</th>
                                                <th>User Request</th>
                                                <th>Project</th>
                                                <th>Trx.Date</th>
                                                <th>Title</th>
                                                <th>Created By</th>
                                                <th>PIC</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="card">  
            <form>       
                <div class="row">
                    <div class="col-md" style="overflow-x:auto; padding:15px;">                            
                    <table id="datatable5" name="datatable5" class="table table-bordered table-striped" >
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>PIC</th>
                                <th>Open</th>
                                <th>Hold</th>
                                <th>Progress</th>
                                <th>Close</th>
                                <th>Reject</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <?php
                            $no = 1;
                        ?>
                        <tbody>
                            <?php $__currentLoopData = $listTicketByPic; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="text-align: left;"><?php echo e($no++); ?></td>
                            <td style="text-align: left;"><?php echo e($data->NAMA); ?></td>
                            <td style="text-align: left;"class="open hover" id="open" name="open" onclick="getOpen(<?php echo e($data->MD_USER_IT_ID_INT); ?>)"><?php echo e($data->OPEN); ?></td>
                            <td style="text-align: left;"class="hold hover" id="hold" name="hold" onclick="getHold(<?php echo e($data->MD_USER_IT_ID_INT); ?>)"><?php echo e($data->HOLD); ?></td>
                            <td style="text-align: left;"class="progress1 hover" onclick="getProgress(<?php echo e($data->MD_USER_IT_ID_INT); ?>)"><?php echo e($data->PROGRESS); ?></td>
                            <td style="text-align: left;"class="close2 hover" onclick="getClose(<?php echo e($data->MD_USER_IT_ID_INT); ?>)"><?php echo e($data->CLOSE); ?></td>
                            <td style="text-align: left;"class="reject hover" id="reject" name="reject" onclick="getReject(<?php echo e($data->MD_USER_IT_ID_INT); ?>)"><?php echo e($data->REJECT); ?></td>
                            <td style="text-align: left;"><?php echo e($data->JUMLAH_TICKET); ?></td>
                        </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <th colspan="7" style="text-align:left">Total:</th>
                            <th><?php echo e($totalTicket[0]->TOTAL_TICKET); ?></th>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </form>
        </div>
            <div class="col-xl popup">
                <div id="popUpDatatablePic" class="modal fade popUpDatatablePic">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">  
                                <button type="button" class="close" id="close2" data-dismiss="modal" aria-hidden="true">&times;</button>    
                            </div>
                            <div class="modal-body">
                                <div class="col-md" style="overflow-x:auto;">
                                    <table class="table table-bordered table-striped datatablePic" id="datatablePic" name="datatablePic" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No Ticket</th>
                                                <th>User Request</th>
                                                <th>Project</th>
                                                <th>Trx.Date</th>
                                                <th>Title</th>
                                                <th>Created By</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>         
            </div>
        </div>
    </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $("#datatable3").DataTable({
            "autoheight":true, "autowidth":true,
            "responsive": false, "lengthChange": false, "autoWidth": false,
        }).buttons().container().adjust().draw().columns().adjust().appendTo('#datatable3 .col-md-6:eq(0)');

    })
    $('#close1').click(function(){
        $('.popUpDatatableType').modal('toggle');
    })
    $('#close2').click(function(){
        $('.popUpDatatablePic').modal('toggle');
    })

        function viewDataTicket(TRANS_TICKET_NOCHAR, status, PIC) {
            if(status == 5){
                window.location = "/viewCloseTicketing/" + TRANS_TICKET_NOCHAR;
            }else{
                window.location = "/viewDataTicketing/" + TRANS_TICKET_NOCHAR;
            }
        }

    function getKomplain(project){
        $('.datatableType').DataTable().clear().destroy();
        $('.datatableType').DataTable({
            "processing": true,
            "serverSide": true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 5,
            dom: 'frtip',
            "ajax":{
                "url": "<?php echo e(url('dashboardType')); ?>",
                "dataType": "json",
                "type": "POST",
                "data": { 
                        _token: "<?php echo e(csrf_token()); ?>",
                        project:project,
                        type:'1'
                    }                   
            },
            "columns": [
                { "data": null },
                { "data":"TRANS_TICKET_NOCHAR"},
                { "data":"REQUEST_BY_USER"},
                { "data":"PROJECT_NAME"},
                { "data":"TRX_DATE"},
                { "data":"JUDUL_TICKET"},
                { "data":"created_by"},
                { "data":"NAMA"},
                { "data":"DESC_STATUS"},
                { "data":"VIEW"}
            ],
            "columnDefs": [
                {
                    "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    "targets": 0,
                    'checkboxes': true
                }
            ]
        });
        $('.popUpDatatableType').modal('show');
    };
    function getPermintaan(project){
        $('.datatableType').DataTable().clear().destroy();
        $('.datatableType').DataTable({
            "processing": true,
            "serverSide": true,
            "paging":true,
            "destroy":true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 5,
            dom: 'frtip',
            "ajax":{
                "url": "<?php echo e(url('dashboardType')); ?>",
                "dataType": "json",
                "type": "POST",
                "data": {
                        _token: "<?php echo e(csrf_token()); ?>",
                        project:project,
                        type:'2'
                    }                   
            },
            "columns": [
                { "data": null },
                { "data":"TRANS_TICKET_NOCHAR"},
                { "data":"REQUEST_BY_USER"},
                { "data":"PROJECT_NAME"},
                { "data":"TRX_DATE"},
                { "data":"JUDUL_TICKET"},
                { "data":"created_by"},
                { "data":"NAMA"},
                { "data":"DESC_STATUS"},
                { "data":"VIEW"}
            ],
            "columnDefs": [
                {
                    "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    "targets": 0,
                    'checkboxes': true
                }
            ]
        });
        setInterval( function () {
            table;
        }, 30000 );
        $('.popUpDatatableType').modal('show');
    };
    function getInformasi(project){
        $('.datatableType').DataTable().clear().destroy();
        $('.datatableType').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy":true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 5,
            dom: 'frtip',
            "ajax":{
                "url": "<?php echo e(url('dashboardType')); ?>",
                "dataType": "json",
                "type": "POST",
                "data": { 
                        _token: "<?php echo e(csrf_token()); ?>",
                        project:project,
                        type:'3'
                    }                   
            },
            "columns": [
                { "data": null },
                { "data":"TRANS_TICKET_NOCHAR"},
                { "data":"REQUEST_BY_USER"},
                { "data":"PROJECT_NAME"},
                { "data":"TRX_DATE"},
                { "data":"JUDUL_TICKET"},
                { "data":"created_by"},
                { "data":"NAMA"},
                { "data":"DESC_STATUS"},
                { "data":"VIEW"}

            ],
            "columnDefs": [
                {
                    "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    "targets": 0,
                    'checkboxes': true
                }
            ]
        });
        $('.popUpDatatableType').modal('show');
    };

</script>


<script type="text/javascript">
    $(document).ready(function() {
        var table5 = $("#datatable5").DataTable({
            "order": [[0, 'asc']],
            "autoheight":true, "responsive": false,
            "lengthChange": false, "autoWidth": false,
        }).buttons().container().appendTo('#datatable5_wrapper .col-md-6:eq(0)');
    })
    function getOpen(pic){
        $('.datatablePic').DataTable().clear().destroy();
        $('.datatablePic').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy":true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 5,
            dom: 'frtip',
            "ajax":{
                "url": "<?php echo e(url('dashboardPic')); ?>",
                "dataType": "json",
                "type": "POST",
                "data": { 
                        _token: "<?php echo e(csrf_token()); ?>",
                        pic:pic,
                        status:'2'
                    }
            },
            "columns": [
                { "data": null },
                { "data":"TRANS_TICKET_NOCHAR"},
                { "data":"REQUEST_BY_USER"},
                { "data":"PROJECT_NAME"},
                { "data":"TRX_DATE"},
                { "data":"JUDUL_TICKET"},
                { "data":"created_by"},
                { "data":"VIEW"}
            ],
            "columnDefs": [
                {
                    "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    "targets": 0
                }
            ]
        });
        $('.popUpDatatablePic').modal('show');
    };
    function getHold(pic){
        $('.datatablePic').DataTable().clear().destroy();
        $('.datatablePic').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy":true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 5,
            dom: 'frtip',
            "ajax":{
                "url": "<?php echo e(url('dashboardPic')); ?>",
                "dataType": "json",
                "type": "POST",
                "data": { 
                        _token: "<?php echo e(csrf_token()); ?>",
                        pic:pic,
                        status:'3'
                    }
            },
            "columns": [
                { "data": null },
                { "data":"TRANS_TICKET_NOCHAR"},
                { "data":"REQUEST_BY_USER"},
                { "data":"PROJECT_NAME"},
                { "data":"TRX_DATE"},
                { "data":"JUDUL_TICKET"},
                { "data":"created_by"},
                { "data":"VIEW"}
            ],
            "columnDefs": [
                {
                    "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    "targets": 0
                }
            ]
        });
        $('.popUpDatatablePic').modal('show');
    };
    function getProgress(pic){
        $('.datatablePic').DataTable().clear().destroy();
        $('.datatablePic').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy":true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 5,
            dom: 'frtip',
            "ajax":{
                "url": "<?php echo e(url('dashboardPic')); ?>",
                "dataType": "json",
                "type": "POST",
                "data": { 
                        _token: "<?php echo e(csrf_token()); ?>",
                        pic:pic,
                        status:'4'
                    }
            },
            "columns": [
                { "data": null },
                { "data":"TRANS_TICKET_NOCHAR"},
                { "data":"REQUEST_BY_USER"},
                { "data":"PROJECT_NAME"},
                { "data":"TRX_DATE"},
                { "data":"JUDUL_TICKET"},
                { "data":"created_by"},
                { "data":"VIEW"}
            ],
            "columnDefs": [
                {
                    "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    "targets": 0
                }
            ]
        });
        $('.popUpDatatablePic').modal('show');
    };
    function getClose(pic){
        $('.datatablePic').DataTable().clear().destroy();
        $('.datatablePic').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy":true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 5,
            dom: 'frtip',
            "ajax":{
                "url": "<?php echo e(url('dashboardPic')); ?>",
                "dataType": "json",
                "type": "POST",
                "data": { 
                        _token: "<?php echo e(csrf_token()); ?>",
                        pic:pic,
                        status:'5'
                    }
            },
            "columns": [
                { "data": null },
                { "data":"TRANS_TICKET_NOCHAR"},
                { "data":"REQUEST_BY_USER"},
                { "data":"PROJECT_NAME"},
                { "data":"TRX_DATE"},
                { "data":"JUDUL_TICKET"},
                { "data":"created_by"},
                { "data":"VIEW"}
            ],
            "columnDefs": [
                {
                    "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    "targets": 0
                }
            ]
        });
        $('.popUpDatatablePic').modal('show');
    };
    function getReject(pic){
        $('.datatablePic').DataTable().clear().destroy();
        $('.datatablePic').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy":true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 5,
            dom: 'frtip',
            "ajax":{
                "url": "<?php echo e(url('dashboardPic')); ?>",
                "dataType": "json",
                "type": "POST",
                "data": { 
                        _token: "<?php echo e(csrf_token()); ?>",
                        pic:pic,
                        status:'0'
                    }
            },
            "columns": [
                { "data": null },
                { "data":"TRANS_TICKET_NOCHAR"},
                { "data":"REQUEST_BY_USER"},
                { "data":"PROJECT_NAME"},
                { "data":"TRX_DATE"},
                { "data":"JUDUL_TICKET"},
                { "data":"created_by"},
                { "data":"VIEW"}
            ],
            "columnDefs": [
                {
                    "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    "targets": 0
                }
            ]
        });
        $('.popUpDatatablePic').modal('show');
    };
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\metland_support\resources\views/home.blade.php ENDPATH**/ ?>