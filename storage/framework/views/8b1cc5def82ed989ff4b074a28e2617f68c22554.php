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





<?php $__env->startSection('navbar_header'); ?>
Ticketing - <b><?php echo e(session('current_project_char')); ?></b>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
Report Ticket
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
                <form action="<?php echo e(url('filterViewListReport')); ?>" method="post">
                    <?php echo e(csrf_field()); ?>

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
                                <?php
                                $no = 1;
                            ?>
                            <tbody>
                                <?php $__currentLoopData = $listTicketReport; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td style="text-align: center;"><?php echo e($no++); ?></td>
                                <td style="text-align: center;"><?php echo e($data->NAMA); ?></td>
                                <td style="text-align: center;"><?php echo e($data->JUMLAH_TICKET); ?></td>
                                <td style="text-align: center;"><?php echo e($data->OPEN); ?></td>
                                <td style="text-align: center;"><?php echo e($data->PROGRESS); ?></td>
                                <td style="text-align: center;"><?php echo e($data->HOLD); ?></td>
                                <td style="text-align: center;"><?php echo e($data->CLOSE); ?></td>
                            </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/support/public_html/metland_support/resources/views/Ticketing/viewListReport.blade.php ENDPATH**/ ?>