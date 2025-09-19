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

    .ck.ck-CHAT__main>.ck-editor__editable:not(.ck-focused) {
         border:0px !important;
     }
    
    #chat {
        border-style: hidden;
    }
     th, td {
    border-style: hidden;
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
                
        <form action="<?php echo e(url('editDataTicketHistory')); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="TRANS_TICKET_NOCHAR" class="form-control" id="TRANS_TICKET_NOCHAR" value="<?php echo e($viewDataTicket[0]->TRANS_TICKET_NOCHAR); ?>"required>
            <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <label>Judul Ticket<span style="color: red;">*</span></label>
                        <input type="text" readonly="readonly" class="form-control" autocomplete=off placeholder="Isi Judul Ticket" id="judul" name="judul" value="<?php echo e($viewDataTicket[0]->JUDUL_TICKET); ?>" required>
                    </div>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Nama<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly"  name="nama" autocomplete='off' class="form-control" id="nama" value="<?php echo e($viewDataTicket[0]->created_by); ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Type<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly"  name="TYPE" autocomplete='off' class="form-control" id="TYPE" value="<?php echo e($viewDataTicket[0]->DESC_CHAR_MD_TYPE_KELUHAN_TICKETING); ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Aplikasi<span style="color: red;">*</span></label>
                            <input type="text" readonly="readonly" name="APLIKASI" autocomplete='off' class="form-control" id="APLIKASI" value="<?php echo e($viewDataTicket[0]->DESC_CHAR_APLIKASI); ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>PIC</label>
                            <select class="form-control" aria-label="Default select example" id="PIC" name="PIC"  <?php if($isAdmin == false): ?> readonly <?php endif; ?> required>
                                <option value="<?php echo e($viewDataTicket[0]->PIC); ?>"><?php echo e($viewDataTicket[0]->NAMA); ?></option>
                                <?php if($isAdmin == true): ?>
                                <?php $__currentLoopData = $dataPic; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pic->MD_USER_IT_ID_INT); ?>"><?php echo e($pic->NAMA); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                    <div class="">
                        <div class="form-group ">
                            <label>Deskripsi<span style="color: red;">*</span></label>
                        <textarea disabled class="form-control" id="DESC_CHAR" name="DESC_CHAR" rows="7"  ><?php echo e($viewDataTicket[0]->DESC_CHAR); ?></textarea>
                        </div>  
                    </div>
                      <div class="table-responsive">
                        <table class="table table-bordered" id="chat" name="chat" border-style="hidden">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <?php
                                $no = 1;
                            ?>
                            <tbody>
                                <?php $__currentLoopData = $dataChat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo e($chat->created_by); ?></td>
                                        <td style="text-align: center;"><?php echo $chat->HISTORY_DESC; ?></td>
                                        <td style="text-align: center;"><?php echo e($chat->created_at); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                        
                        <div class="form-group ">
                            <label>Respond <span style="color: red;">*</span></label>
                            <textarea class=" form-control" id="HISTORY_DESC" name="HISTORY_DESC"  rows="7" required></textarea>
                        </div>
                    <?php if($isStatus == false): ?>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" aria-label="Default select example" id="status" name="status"  <?php if($isAdmin == false): ?> readonly <?php endif; ?>  >
                                <option value="<?php echo e($viewDataTicket[0]->status); ?>"><?php echo e($viewDataTicket[0]->DESC_STATUS); ?></option>
                                <?php if($isAdmin == true): ?>
                                <?php $__currentLoopData = $dataStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status->TRANS_TICKET_STATUS_ID_INT); ?>"><?php echo e($status->DESC_CHAR); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Attach</label>
                            <input type="file" id="ATTACH" name="ATTACH[]" multiple="true"> <br>
                            <span style="color: red;"><b>* File Dibawah 1 MB (PerFile) </b></span> <br>
                            <span style="color: red;"><b>* Bisa Pilih Lebih Dari 1 File</b></span>
                        </div>
                    </div>
                    <?php if($isFile == true): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Tanggal</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <?php
                                $no = 1;
                            ?>
                            <tbody>
                                <?php $__currentLoopData = $viewAttachment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo e($no++); ?></td>C
                                        <td>
                                            <a href="<?php echo e(URL('/downloadFile/'.$att->ID)); ?>"><?php echo e($att->ATTACHMENT_NAME); ?></a>
                                        </td>
                                        <td style="text-align: center;"><?php echo e($att->created_at); ?></td>
                                        <td style="text-align: center;"><?php echo e($att->created_by); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                    
                    
                    
                        <div class="row" style="padding-left: 70%;">
                        <button type="submit"class="btn btn-primary col-sm-2" style="">Submit</button>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <a class="form-control btn btn-info" href="<?php echo e(route('viewListTicketing')); ?>">
                                    Back
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </form>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    CKEDITOR.replace('DESC_CHAR', {readOnly:true});
    CKEDITOR.replace('CHAT', {readOnly:true});

    CKEDITOR.replace('HISTORY_DESC');
    $("form").submit( function(e) {
            var messageLength = CKEDITOR.instances['HISTORY_DESC'].getData().replace(/<[^>]*>/gi, '').length;
            if( !messageLength ) {
                Swal.fire({
            html: 'Foam Respond Kosong',
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

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/support/public_html/metland_support/resources/views/Ticketing/viewDataTicket4.blade.php ENDPATH**/ ?>