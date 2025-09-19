<?php
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\BarChart;
    use \koolreport\pivot\widgets\PivotTable;
    use \koolreport\widgets\google\ColumnChart;
    use \koolreport\drilldown\LegacyDrillDown;
    use \koolreport\drilldown\DrillDown;
    use \koolreport\datagrid\DataTables;
    use \koolreport\d3\PieChart;
    use \koolreport\d3\ColumnChart1;
?>


<?php $__env->startSection('navbar_header'); ?>
    Reporting Detail - <b><?php echo e(session('current_project_char')); ?></b>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
    Reporting Data Detail
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <div style="padding-left: 5px;">
                        <?php if(session()->has('success')): ?>
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong><?php echo e(session()->get('success')); ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if(session()->has('error')): ?>
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong><?php echo e(session()->get('error')); ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if(session()->has('warning')): ?>
                            <div class="alert alert-warning alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong><?php echo e(session()->get('warning')); ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if(session()->has('info')): ?>
                            <div class="alert alert-info alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong><?php echo e(session()->get('info')); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    <form action="<?php echo e(url('viewReportingDetail')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel-body">
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="CutOff">Cut Off</label>
                                            <input type="date" name="CutOff" class="form-control" id="CutOff"required>
                                        </div>
                                    </div>
                                    <div class="col-sm-4" style="width:10%">
                                        <div class="form-group">    
                                            <button type="submit" class="form-control btn btn-info" id="BTN_ADD_NEW" name="BTN_ADD_NEW">
                                                view
                                            </button>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                        </div>
                    </form>
                    <?php if($dataReporting <> 0 ): ?>
                    <div>
                        <b>Proyek : <?php echo e(strtoupper($project_name)); ?></b><br />
                        <b>Cut Off : <?php echo e(strtoupper($cut_off_param)); ?></b>
                    </div>
                    <br />
                    <div>
                        <div>
                        <a class="btn btn-success" href="<?php echo e(URL('excelReportDetail/' . $cut_off_param . '/' . $project_no. '/' . $pic)); ?>">Export Excel</a>
                        </div>
                        <a target="_blank" class="btn btn-sm btn-danger" href="<?php echo e(URL('printReportDetail/' . $cut_off_param. '/' . $project_no. '/' . $pic)); ?>" onclick="window.open(this.href).print(); return false">
                            Print
                        </a>
                    </div>

                    <div class="" style="padding-left: 5px;">
                        <div class="col-md" style="overflow-x:auto;">
                            <?php
                                DataTables::create(array(
                                   "name" => "reportDetail",
                                   "dataSource"=>$report->dataStore("collection_Report_Detail_Datatable"),
                                   "themeBase"=>"bs4",
                                   "showFooter" => false,
                                   "cssClass"=>array(
                                       "table"=>"table table-striped table-bordered"
                                   ),
                                   "columns" => array(
                                        "indexColumn" => [
                                           "label" => "No",
                                           "formatValue" => function($value, $row) {
                                            return $value;
                                           },
                                           "footer" => "sum",
                                           "footerText" => "<b>@value</b>"
                                       ],
                                       "TRANS_TICKET_NOCHAR" => [
                                           "label" => "No Ticket",
                                           "formatValue" => function($value, $row) {
                                            return $value;
                                           },
                                           "footer" => "sum",
                                           "footerText" => "<b>@value</b>"
                                       ],
                                       "REQUEST_BY_USER" => [
                                           "label" => "User Request",
                                           "formatValue" => function($value, $row) {
                                            return $value;
                                           },
                                           "footer" => "sum",
                                           "footerText" => "<b>@value</b>"
                                       ],
                                       "created_at" => [
                                           "label" => "Trx.Date",
                                           "formatValue" => function($value, $row) {
                                                return $value;
                                           },
                                           "footerText" => "<b>Hold</b>"
                                       ],
                                       "JUDUL_TICKET" => [
                                           "label" => "Title",
                                           "formatValue" => function($value, $row) {
                                            return $value;
                                           },
                                           "footer" => "sum",
                                           "footerText" => "<b>@value</b>"
                                       ],
                                       "PROJECT_NAME" => [
                                           "label" => "Project",
                                           "formatValue" => function($value, $row) {
                                                return $value;
                                           },
                                           "footerText" => "<b>Close</b>"
                                       ],
                                       "created_by" => [
                                           "label" => "Created By",
                                           "formatValue" => function($value, $row) {
                                                return $value;
                                           },
                                           "footerText" => "<b>Close</b>"
                                       ],
                                       "NAMA" => [
                                           "label" => "PIC",
                                           "formatValue" => function($value, $row) {
                                               return $value;
                                           },
                                           "footerText" => "<b>Jumlah Ticket</b>"
                                        ],
                                       "DESC_CHAR" => [
                                           "label" => "Status",
                                           "formatValue" => function($value, $row) {
                                               return $value;
                                           },
                                           "footerText" => "<b>Jumlah Ticket</b>"
                                        ],
                                   ),
                                    "fastRender" => true,
                                        "options"=>array(
                                            "scrollX" => false,
                                            "dom" => 'Bfrtip',
                                            "buttons" => [
                                                'excel'
                                            ],
                                            "paging"=>true,
                                            "pageLength" => 10,
                                            "searching"=>true,
                                            'autoWidth' => true,
                                            "select" => false,
                                            // "order"=>array(
                                            //     array(0,"asc")
                                            // )
                                        ),
                                        "onReady" => "function() {
                                            reportDetail.on( 'order.dt search.dt', function () {                
                                                reportDetail.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                                    cell.innerHTML = i+1;
                                                } );
                                            } ).draw();
                                        }",
                                        "searchOnEnter" => false,
                                        "searchMode" => "or"
                                    ));
                           ?>
                        </div>
               
                        <div class="col-md" style="overflow-x:auto; padding-top:2%">
                            <p><b>Grouping</b></p>
                            <?php
                                DataTables::create(array(
                                   "name" => "reportDetailGrouping",
                                   "dataSource"=>$report->dataStore("collection_Report_Detail_Grouping_Datatable"),
                                   "themeBase"=>"bs4",
                                   "showFooter" => false,
                                   "cssClass"=>array(
                                       "table"=>"table table-striped table-bordered"
                                   ),
                                   "columns" => array(
                                        "indexColumn" => [
                                           "label" => "No",
                                           "formatValue" => function($value, $row) {
                                            return $value;
                                           },
                                           "footer" => "sum",
                                           "footerText" => "<b>@value</b>"
                                       ],
                                       "TRANS_TICKET_NOCHAR" => [
                                           "label" => "No Ticket",
                                           "formatValue" => function($value, $row) {
                                            return $value;
                                           },
                                           "footer" => "sum",
                                           "footerText" => "<b>@value</b>"
                                       ],
                                       "REQUEST_BY_USER" => [
                                           "label" => "User Request",
                                           "formatValue" => function($value, $row) {
                                            return $value;
                                           },
                                           "footer" => "sum",
                                           "footerText" => "<b>@value</b>"
                                       ],
                                       "created_at" => [
                                           "label" => "Trx.Date",
                                           "formatValue" => function($value, $row) {
                                                return $value;
                                           },
                                           "footerText" => "<b>Hold</b>"
                                       ],
                                       "JUDUL_TICKET" => [
                                           "label" => "Title",
                                           "formatValue" => function($value, $row) {
                                            return $value;
                                           },
                                           "footer" => "sum",
                                           "footerText" => "<b>@value</b>"
                                       ],
                                       "PROJECT_NAME" => [
                                           "label" => "Project",
                                           "formatValue" => function($value, $row) {
                                                return $value;
                                           },
                                           "footerText" => "<b>Close</b>"
                                       ],
                                       "created_by" => [
                                           "label" => "Created By",
                                           "formatValue" => function($value, $row) {
                                                return $value;
                                           },
                                           "footerText" => "<b>Close</b>"
                                       ],
                                       "NAMA" => [
                                           "label" => "PIC",
                                           "formatValue" => function($value, $row) {
                                               return $value;
                                           },
                                           "footerText" => "<b>Jumlah Ticket</b>"
                                        ],
                                       "DESC_CHAR" => [
                                           "label" => "Status",
                                           "formatValue" => function($value, $row) {
                                               return $value;
                                           },
                                           "footerText" => "<b>Jumlah Ticket</b>"
                                        ],
                                   ),
                                    "fastRender" => true,
                                        "options"=>array(
                                            "scrollX" => false,
                                            "dom" => 'Bfrtip',
                                            "buttons" => [
                                                'excel'
                                            ],
                                            "paging"=>true,
                                            "pageLength" => 10,
                                            "searching"=>true,
                                            'autoWidth' => true,
                                            "select" => false,
                                            // "order"=>array(
                                            //     array(0,"asc")
                                            // )
                                        ),
                                        "onReady" => "function() {
                                            reportDetailGrouping.on( 'order.dt search.dt', function () {                
                                                reportDetailGrouping.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                                    cell.innerHTML = i+1;
                                                } );
                                            } ).draw();
                                        }",
                                        "searchOnEnter" => false,
                                        "searchMode" => "or"
                                    ));
                           ?>
                            <?php endif; ?>
                        </div>
                    </div>
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
        
        
        $("#example1").DataTable({
            "order": [[0, 'asc']],
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
                window.location.href = "/deleteAssignDriverCar/" + param1;
            }
        });

    }

</script>

                            

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/support/public_html/metland_support/resources/views/ReportTicketing/viewListReportDetail.blade.php ENDPATH**/ ?>