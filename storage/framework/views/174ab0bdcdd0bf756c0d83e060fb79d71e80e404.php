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
    Ticket HelpDesk
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
        <form action="<?php echo e(url('saveEditUserIt')); ?>" method="post"> 
            <input type="hidden" name="id" class="form-control" id="id" value="<?php echo e($listDataIt[0]->MD_USER_IT_ID_INT); ?>"required>
            <?php echo e(csrf_field()); ?>

                        <div class="form-group">
                            <label>Nama<span style="color: red;">*</span></label>
                            <input type="text"  name="nama" autocomplete='off' class="form-control" id="nama" value="<?php echo e($listDataIt[0]->NAMA); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" autocomplete=off id="Email" name="Email" value="<?php echo e($listDataIt[0]->EMAIL); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Id Sso<span style="color: red;">*</span></label>
                            <input type="number" class="form-control" autocomplete=off id="idSso" name="idSso" value="<?php echo e($listDataIt[0]->ID_SSO); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" aria-label="Default select example" id="Role" name="Role" required>
                                <option value="<?php echo e($listDataIt[0]->ROLE); ?>"><?php echo e($listDataIt[0]->DESC_CHAR); ?></option>
                                <?php $__currentLoopData = $listRoleIt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->MD_ROLE_IT_ID_INT); ?>"><?php echo e($role->DESC_CHAR); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Parent</label>
                            <select class="form-control" aria-label="Default select example" id="parent" name="parent" required>
                                <option value="<?php echo e($listParentIt2->MD_USER_IT_ID_INT); ?>"><?php echo e($listParentIt2->NAMA); ?></option>
                                <?php $__currentLoopData = $listParentIt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($parent->MD_USER_IT_ID_INT); ?>"><?php echo e($parent->NAMA); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    <button class="btn btn-primary form-group col-2" style="margin-left:70%;" data-toggle="modal">Submit</button>
                    </form>
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
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/support/public_html/metland_support/resources/views/MasterData/viewDataUserIt2.blade.php ENDPATH**/ ?>