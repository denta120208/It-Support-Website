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



<?php $__env->startSection('navbar_header'); ?>
Ticket HelpDesk - <b><?php echo e(session('current_project_char')); ?></b>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
    MasterData
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

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
                    <form action="<?php echo e(url('saveAddRoleIt')); ?>" method="post">
                    <?php echo e(csrf_field()); ?>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tambah Role</label>
                                <input type="text" class="form-control" id="role" name="role" autocomplete="off" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="example1" name="example1" border-style="hidden">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php
                                $no = 1;
                            ?>
                            <tbody>
                                <?php $__currentLoopData = $listRoleIt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo e($no++); ?></td>
                                        <td style="text-align: center;"><?php echo e($data->DESC_CHAR); ?></td>
                                         <td>
                                            <a href="<?php echo e(URL('/viewDataRoleIt/'.$data->MD_ROLE_IT_ID_INT)); ?>" class="btn btn-warning">Edit</a>
                                            <a href="#" class="btn btn-danger"onclick="swalDeleteData(<?php echo e($data->MD_ROLE_IT_ID_INT); ?>)">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    CKEDITOR.replace('DESC_CHAR', {readOnly:true});
</script>

<script type="text/javascript">

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        
        $("#example1").DataTable({
            // "order": [[0,'DESC']],
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
                window.location.href = "/deleteDataRoleIt/" + param1;
            }
        });
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/support/public_html/metland_support/resources/views/MasterData/RoleIt/viewListRoleIt.blade.php ENDPATH**/ ?>