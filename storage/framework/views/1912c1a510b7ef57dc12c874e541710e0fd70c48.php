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
                
        <form action="<?php echo e(url('saveDataTicket')); ?>" method="post" enctype="multipart/form-data">
            <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <label>Judul Ticket<span style="color: red;">*</span></label>
                        <input type="text" class="form-control" autocomplete=off placeholder="Isi Judul Ticket" id="judul" name="judul" required>
                    </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" value="<?php echo e($userName); ?>" class="form-control" id="userName" name="userName" autocomplete="off" >
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" value="<?php echo e($email); ?>" class="form-control"id="email" name="email" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Type<span style="color: red;">*</span></label>
                            <select class="form-control" aria-label="Default select example" id="TYPE" name="TYPE"required>
                                <option value="">Pilih Type</option>
                                <?php $__currentLoopData = $dataType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type->MD_TYPE_KELUHAN_TICKETING_ID_INT); ?>"><?php echo e($type->DESC_CHAR); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" aria-label="Default select example" id="CATEGORY" name="CATEGORY" >
                                <option value="" selected>Category Not Selected</option>
                                <?php $__currentLoopData = $dataRole; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->MD_ROLE_IT_ID_INT); ?>"><?php echo e($role->DESC_CHAR); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/support/public_html/metland_support/resources/views/Ticketing/viewInputTicketing.blade.php ENDPATH**/ ?>