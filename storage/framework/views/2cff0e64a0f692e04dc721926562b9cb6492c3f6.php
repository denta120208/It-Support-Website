<?php
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\BarChart;
    use \koolreport\widgets\google\PieChart;
    use \koolreport\pivot\widgets\PivotTable;
    use \koolreport\widgets\google\ColumnChart;
    use \koolreport\drilldown\LegacyDrillDown;
    use \koolreport\drilldown\DrillDown;
    use \koolreport\widgets\google\LineChart;
?>

<style>
    .pivot-column-header, .pivot-data-header {
        font-weight: bold;
        text-align: center;
    }
    .pivot-data-cell-text {
        text-align: center;
    }
    .table {
        text-align: center;
        white-space: nowrap;
    }
    th {
        background-color: #081c5c;
        color: white;
    }
    .pivot-column {
        background-color: #081c5c;
        color: white;
        font-weight: bold;
        text-align: center;
    }
</style>



<?php $__env->startSection('navbar_header'); ?>
    <b><?php echo session('current_project_char') ?></b> - Teknik - Serah Terima
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
    Teknik - Serah Terima
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
                                    <input type="date" class="form-control" id="txtCutOff" placeholder="Input Cut Off..." value="<?php echo e($cut_off); ?>" required>
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
                                <span style="color: red;"><b>* Rp (Dalam Jutaan)</b></span>
                                <?php
                                    Table::create(array(
                                        "dataStore"=>$report->dataStore('teknik_serah_terima_table1'),
                                        "headers"=>array(
                                            array(
                                                ""=>array("colSpan"=>1),
                                                "ST S/D"=>array("colSpan"=>1),
                                                date('Y', strtotime($report->cut_off))=>array("colSpan"=>4),
                                                (date('Y', strtotime($report->cut_off)) + 1)=>array("colSpan"=>1),
                                                "KUMULATIF S/D ".strtoupper(date('M Y', strtotime($report->cut_off)))=>array("colSpan"=>2),
                                            )
                                        ),
                                        // "showFooter"=>"bottom",
                                        "themeBase"=>"bs4",
                                        "cssClass"=>array(
                                            "table"=>"table table-striped table-bordered"
                                        ),
                                        "columns"=>array(
                                            "TOWER_NAME"=>array(
                                                "label" => "KAWASAN",
                                                'type' => 'string'
                                            ),
                                            "REALISASI_COUNT_BACKWARD_YEAR"=>array(
                                                "label" => strtoupper(date('M', strtotime($report->cut_off))).' '.(date('Y', strtotime($report->cut_off)) - 1),
                                                'type' => 'string'
                                            ),
                                            "TARGET_COUNT_CURRENT_YEAR"=>array(
                                                "label" => "T",
                                                'type' => 'string'
                                            ),
                                            "REALISASI_COUNT_CURRENT_YEAR"=>array(
                                                "label" => "R",
                                                'type' => 'string'
                                            ),
                                            "PERSEN_CURRENT_YEAR"=>array(
                                                "label" => "P%",
                                                'type' => 'string',
                                                'suffix' => "%"
                                            ),
                                            "HUNI_BANGUN_CURRENT_YEAR"=>array(
                                                "label" => "HUNI/BANGUN",
                                                'type' => 'string'
                                            ),
                                            "TARGET_ST_NEXT_YEAR"=>array(
                                                "label" => "TARGET ST",
                                                'type' => 'string'
                                            ),
                                            "KUMULATIF_CUTOFF_CURRENT_YEAR"=>array(
                                                "label" => "REAL ST",
                                                'type' => 'string'
                                            ),
                                            "KUMULATIF_HUNI_BANGUN_CURRENT_YEAR"=>array(
                                                "label" => "HUNI/BANGUN",
                                                'type' => 'string'
                                            ),
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    function getExcel() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("teknik_serah_terima_report_excel/proyek/cut_off")); ?>';
        url = url.replace('proyek', proyek);
        url = url.replace('cut_off', cut_off);
        window.location.href = url;
    }

    function getPDF() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("teknik_serah_terima_report_pdf/proyek/cut_off")); ?>';
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
            var url = '<?php echo e(url("teknik_serah_terima_report/proyek/cut_off")); ?>';
            url = url.replace('proyek', proyek);
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
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dashboard/public_html/metland_reporting/resources/views/Teknik/SerahTerima/serah_terima.blade.php ENDPATH**/ ?>