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





<?php $__env->startSection('navbar_header'); ?>
Ticketing - <b><?php echo e(session('current_project_char')); ?></b>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
Report Ticket Summary
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
                <form action="<?php echo e(url('filterViewListReportSummary')); ?>" method="post">
                    <?php echo e(csrf_field()); ?>

                    <div class = row>
                        <div class="col-2 form-group">
                            <div class="btn-group submitter-group filter">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Cut Off</div>
                                </div>
                                <input type="date"  class="form-control input-xs filter"style="width: 100%;" id="cutOff" name="cutOff"/>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary form-group col-2" data-toggle="modal">Submit</button>
                </form>
                <?php if($dataReporting <> 0): ?>
                <div>
                    <b>Proyek : <?php echo e(strtoupper($project_name)); ?></b><br />
                    <b>Cut Off : <?php echo e(strtoupper($cut_off_param)); ?></b>
                </div>
                <br />
                <a target="_blank" class="btn btn-danger" href="<?php echo e(URL('printReportSummary/'. $cut_off_param . '/'. $pic)); ?>" onclick="window.open(this.href).print(); return false">
                    Print
                </a>
                <div>
                    <a class="btn btn-success" href="<?php echo e(URL('excelReportSummary/' . $cut_off_param . '/'. $pic)); ?>">Export Excel</a>
                </div>
                    <div class="" style="padding-left: 5px;">
                        <div class="col-md" style="overflow-x:auto;">
                            <?php
                                DataTables::create(array(
                                   "name" => "reportSummary",
                                   "dataSource"=>$report->dataStore("collection_Report_Summary_Datatable"),
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
                                       "NAMA" => [
                                           "label" => "Nama",
                                           "formatValue" => function($value, $row) {
                                            return $value;
                                           },
                                           "footer" => "sum",
                                           "footerText" => "<b>@value</b>"
                                       ],
                                       "OPEN" => [
                                           "label" => "Open",
                                           "formatValue" => function($value, $row) {
                                            return $value;
                                           },
                                           "footer" => "sum",
                                           "footerText" => "<b>@value</b>"
                                       ],
                                       "HOLD" => [
                                           "label" => "Hold",
                                           "formatValue" => function($value, $row) {
                                                return $value;
                                           },
                                           "footerText" => "<b>Hold</b>"
                                       ],
                                       "PROGRESS" => [
                                           "label" => "Progress",
                                           "formatValue" => function($value, $row) {
                                            return $value;
                                           },
                                           "footer" => "sum",
                                           "footerText" => "<b>@value</b>"
                                       ],
                                       "CLOSE" => [
                                           "label" => "Close",
                                           "formatValue" => function($value, $row) {
                                                return $value;
                                           },
                                           "footerText" => "<b>Close</b>"
                                       ],
                                       "REJECT" => [
                                           "label" => "Reject",
                                           "formatValue" => function($value, $row) {
                                                return $value;
                                           },
                                           "footerText" => "<b>Close</b>"
                                       ],
                                       "JUMLAH_TICKET" => [
                                           "label" => "Total Ticket",
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
                                            reportSummary.on( 'order.dt search.dt', function () {                
                                                reportSummary.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
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
                           "name" => "reportSummaryGrouping",
                           "dataSource"=>$report->dataStore("collection_Report_Summary_Grouping_Datatable"),
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
                               "NAMA" => [
                                   "label" => "Nama",
                                   "formatValue" => function($value, $row) {
                                    return $value;
                                   },
                                   "footer" => "sum",
                                   "footerText" => "<b>@value</b>"
                               ],
                               "OPEN" => [
                                   "label" => "Open",
                                   "formatValue" => function($value, $row) {
                                    return $value;
                                   },
                                   "footer" => "sum",
                                   "footerText" => "<b>@value</b>"
                               ],
                               "HOLD" => [
                                   "label" => "Hold",
                                   "formatValue" => function($value, $row) {
                                        return $value;
                                   },
                                   "footerText" => "<b>Hold</b>"
                               ],
                               "PROGRESS" => [
                                   "label" => "Progress",
                                   "formatValue" => function($value, $row) {
                                    return $value;
                                   },
                                   "footer" => "sum",
                                   "footerText" => "<b>@value</b>"
                               ],
                               "CLOSE" => [
                                   "label" => "Close",
                                   "formatValue" => function($value, $row) {
                                        return $value;
                                   },
                                   "footerText" => "<b>Close</b>"
                               ],
                               "REJECT" => [
                                   "label" => "Reject",
                                   "formatValue" => function($value, $row) {
                                        return $value;
                                   },
                                   "footerText" => "<b>Close</b>"
                               ],
                               "JUMLAH_TICKET" => [
                                   "label" => "Total Ticket",
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
                                    reportSummaryGrouping.on( 'order.dt search.dt', function () {                
                                        reportSummaryGrouping.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\metland_support\resources\views/ReportTicketing/viewListReportSummary.blade.php ENDPATH**/ ?>