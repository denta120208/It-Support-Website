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
list Data Ticket
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

            <!-- /.col -->
            <div class="col-xl">
                <div class="row" style="padding-left: 5px;">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <a class="form-control btn btn-info" href="<?php echo e(route('viewInputTicketing')); ?>">
                                Add Ticket
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#ticket" data-toggle="tab">Ticket </a></li>
                        <?php if($isAdmin == true): ?>
                        <li class="nav-item"><a class="nav-link" href="#notAssign" data-toggle="tab">Not Assign</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="#close" data-toggle="tab">Close</a></li>
                        <?php if($isAdmin == true): ?>
                        <li class="nav-item"><a class="nav-link" href="#group" data-toggle="tab">Group</a></li>
                        <?php endif; ?>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <!-- /.tab-pane -->
                        <div class="active tab-pane" id="ticket">
                            <form action="<?php echo e(url('filterDataTicket')); ?>" method="post">
                                <?php echo e(csrf_field()); ?>

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
                                            <?php $__currentLoopData = $dataStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($status->TRANS_TICKET_STATUS_ID_INT); ?>"><?php echo e($status->DESC_CHAR); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                    <th>Judul Ticket</th>
                                    <th>Nama Pembuat</th>
                                    <th>Tgl Tiket</th>
                                    <th>Status</th>
                                    <th>PIC</th>
                                    <th>Project</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <?php
                                $no = 1;
                            ?>
                            <tbody>
                                <?php $__currentLoopData = $listDataTicket; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo e($no++); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->TRANS_TICKET_NOCHAR); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->JUDUL_TICKET); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->created_by); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->TRX_DATE); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->DESC_CHAR); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->NAMA); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->PROJECT_NAME); ?></td>
                                    <td>
                                        <a href="<?php echo e(URL('/viewDataTicketing/'.base64_encode($data->TRANS_TICKET_NOCHAR))); ?>" class="btn btn-warning">View</a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        </div>
                    </div>

                </div>
                <?php if($isAdmin == true): ?>
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
                                        <th>Judul Ticket</th>
                                        <th>Nama Pembuat</th>
                                        <th>Tgl Tiket</th>
                                        <th>Project</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <?php
                                    $no = 1;
                                ?>
                                <tbody>
                                    <?php $__currentLoopData = $listDataTicket2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo e($no++); ?></td>
                                        <td style="text-align: center;"><?php echo e($data->TRANS_TICKET_NOCHAR); ?></td>
                                        <td style="text-align: center;"><?php echo e($data->JUDUL_TICKET); ?></td>
                                        <td style="text-align: center;"><?php echo e($data->created_by); ?></td>
                                        <td style="text-align: center;"><?php echo e($data->created_at); ?></td>
                                        <td style="text-align: center;"><?php echo e($data->PROJECT_NAME); ?></td>
                                        <td>
                                            <a href="<?php echo e(URL('/viewNotAssignTicket/'.base64_encode($data->TRANS_TICKET_NOCHAR))); ?>" class="btn btn-warning">View</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </form>
                        <!-- /.post -->
                    </div>
                <?php endif; ?>
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
                                        <th>Judul Ticket</th>
                                        <th>Nama Pembuat</th>
                                        <th>Tgl Tiket</th>
                                        <th>Status</th>
                                        <th>PIC</th>
                                        <th>Project</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <?php
                                    $no = 1;
                                ?>
                                <tbody>
                                    <?php $__currentLoopData = $listDataTicket3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo e($no++); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->TRANS_TICKET_NOCHAR); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->JUDUL_TICKET); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->created_by); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->created_at); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->DESC_STATUS); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->NAMA); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->PROJECT_NAME); ?></td>
                                    <td>
                                        
                                        <a href="<?php echo e(URL('/viewCloseTicketing/'.base64_encode($data->TRANS_TICKET_NOCHAR))); ?>" class="btn btn-warning">View</a>
                                    </td>
                                </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </form>
                <!-- /.post -->
            </div>
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
                                        <th>Judul Ticket</th>
                                        <th>Nama Pembuat</th>
                                        <th>Tgl Tiket</th>
                                        <th>Status</th>
                                        <th>PIC</th>
                                        <th>Project</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <?php
                                    $no = 1;
                                ?>
                                <tbody>
                                    <?php $__currentLoopData = $listDataTicket4; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo e($no++); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->TRANS_TICKET_NOCHAR); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->JUDUL_TICKET); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->created_by); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->created_at); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->DESC_STATUS); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->NAMA); ?></td>
                                    <td style="text-align: center;"><?php echo e($data->PROJECT_NAME); ?></td>
                                    <td>
                                        
                                        <a href="<?php echo e(URL('/viewDataTicketing/'.base64_encode($data->TRANS_TICKET_NOCHAR))); ?>" class="btn btn-warning">View</a>                                    </td>
                                </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </form>
                <!-- /.post -->
            </div>
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
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/support/public_html/metland_support/resources/views/Ticketing/listViewTicketing4.blade.php ENDPATH**/ ?>