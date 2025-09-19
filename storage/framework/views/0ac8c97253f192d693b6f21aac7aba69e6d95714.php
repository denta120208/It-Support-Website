<?php
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\BarChart;
    use \koolreport\pivot\widgets\PivotTable;
    use \koolreport\widgets\google\ColumnChart;
    use \koolreport\drilldown\LegacyDrillDown;
    use \koolreport\drilldown\DrillDown;
    use \koolreport\datagrid\DataTables;
?>

<style>
    .pivot-column-header, .pivot-data-header {
        font-weight: bold;
        text-align: center;
    }
    .table {
        text-align: center;
    }
    th {
        background-color: #081c5c;
        color: white;
    }
</style>



<?php $__env->startSection('navbar_header'); ?>
    <b><?php echo session('current_project_char') ?></b> - Teknik - Progress Konstruksi Prasarana
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
    Teknik - Progress Konstruksi Prasarana
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Cut Off</label>
                                    <select class="form-control select2" id="txtCutOff" style="width: 100%;" required>
                                        <option value="" selected="selected">-- CHOOSE ONE --</option>
                                        <?php $__currentLoopData = $ddlTahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ddlTahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($ddlTahun == $cut_off): ?>
                                            <option value="<?php echo e($ddlTahun); ?>" selected="selected"><?php echo e($ddlTahun); ?></option>
                                            <?php else: ?>
                                            <option value="<?php echo e($ddlTahun); ?>"><?php echo e($ddlTahun); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label style="visibility: hidden">Button</label>
                                    <input type="button" class="form-control btn btn-info" value="View Data" onclick="getSubmit();">
                                </div>
                            </div>
                        </div>
                        <?php if($project != null && $cut_off != null): ?>
                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">
                                <br />
                                <div style='text-align: center;margin-bottom:30px;'>
                                    <input type="button" class="btn btn-success" value="Download Excel" onclick="getExcel();">
                                    <input type="button" class="btn btn-danger" value="Download PDF" onclick="getPDF();">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">
                                <br />
                                <?php
                                    Table::create(array(
                                        "dataStore"=>$report->dataStore('teknik_progress_konstruksi_prasarana_table'),
                                        "headers"=>array(
                                            array(
                                                ""=>array("colSpan"=>3),
                                                "PROGRESS"=>array("colSpan"=>3),
                                                " "=>array("colSpan"=>1),
                                            )
                                        ),
                                        // "showFooter"=>"bottom",
                                        "themeBase"=>"bs4",
                                        "cssClass"=>array(
                                            "table"=>"table table-striped table-bordered"
                                        ),
                                        "columns"=>array(
                                            "#"=>array(
                                                "label"=>"NO",
                                                "start"=>1,
                                            ),
                                            "MD_VENDOR_NAME_CHAR"=>array(
                                                "label" => "KONTRAKTOR",
                                                "type" => "string",
                                                // "footerText"=>"<p><b>NET BACKLOG</b></p>"
                                            ),
                                            "NAMA_PEKERJAAN"=>array(
                                                "label" => "PEKERJAAN",
                                                // "footer"=>"sum",
                                                // "footerText"=>"<b>@value</b>",
                                            ),
                                            "Target"=>array(
                                                "label" => "TARGET",
                                                "suffix" => "%",
                                                // "footer"=>"sum",
                                                // "footerText"=>"<b>@value</b>",
                                            ),
                                            "Realisasi"=>array(
                                                "label" => "R ".$cut_off,
                                                "suffix" => "%",
                                                // "footer"=>"sum",
                                                // "footerText"=>"<b>@value</b>",
                                            ),
                                            "Target_x_Realisasi"=>array(
                                                "label" => "+/-",
                                                // "suffix" => "%",
                                                "formatValue"=>function($value, $row){
                                                    $color = number_format($value)<0?"red":"black";
                                                    return "<p style='color:$color;'>".number_format($value)."%</p>";
                                                }
                                                // "footer"=>"sum",
                                                // "footerText"=>"<b>@value</b>",
                                            ),
                                            "SPK_TRANS_END_DATE"=>array(
                                                "label" => "TARGET SELESAI",
                                                // "footer"=>"sum",
                                                // "footerText"=>"<b>@value</b>",
                                            ),
                                        ),
                                        "paging"=>array(
                                            "pageSize"=>10,
                                            "pageIndex"=>0,
                                        ),
                                        'options' => [
                                            'ordering' => false
                                        ]
                                    ));
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.pivot-data-field-content').remove();
        // $("td").each(function() {
        //     if (this.innerText === '') {
        //         this.closest('td').remove();
        //     }
        // });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    function getExcel() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("teknik_progress_konstruksi_prasarana_report_excel/proyek/cut_off")); ?>';
        url = url.replace('proyek', proyek);
        url = url.replace('cut_off', cut_off);
        window.location.href = url;
    }

    function getPDF() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("teknik_progress_konstruksi_prasarana_report_pdf/proyek/cut_off")); ?>';
        url = url.replace('proyek', proyek);
        url = url.replace('cut_off', cut_off);
        window.location.href = url;
    }

    function getSubmit() {
        message = validasi();
        isValid = false;
        if(message == "") {
            isValid = true;
        }
        
        if(isValid == false) {
            Swal.fire(
                'Failed',
                message,
                'error'
            );
        }
        else {
            var proyek = <?php echo session('current_project') ?>;
            var cut_off = document.getElementById("txtCutOff").value;
            var url = '<?php echo e(url("teknik_progress_konstruksi_prasarana_report/project/cut_off")); ?>';
            url = url.replace('project', proyek);
            url = url.replace('cut_off', cut_off);
            window.location.href = url;
        }
    }

    function validasi() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var totalField = 2;
        var message = "";

        for(var i = 1; i <= totalField; i++) {
            if (message == "") {
                if(proyek == "") {
                    message += "Proyek";
                    proyek = "DONE";
                }
                else if(cut_off == "") {
                    message += "Cut Off";
                    cut_off = "DONE";
                }
            }
            else {
                if(proyek == "") {
                    message += ", Proyek";
                    proyek = "DONE";
                }
                else if(cut_off == "") {
                    message += ", Cut Off";
                    cut_off = "DONE";
                }
            }

            if(message != "" && i == totalField) {
                message += " Tidak Boleh Kosong!";
            }
        }

        return message;
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dashboard/public_html/metland_reporting/resources/views/Teknik/ProgressKonstruksiPrasarana/progress_konstruksi_prasarana.blade.php ENDPATH**/ ?>