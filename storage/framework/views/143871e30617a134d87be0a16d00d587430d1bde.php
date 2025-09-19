<?php
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\BarChart;
    use \koolreport\pivot\widgets\PivotTable;
    use \koolreport\widgets\google\ColumnChart;
    use \koolreport\drilldown\LegacyDrillDown;
    use \koolreport\drilldown\DrillDown;
?>

<style>
    .pivot-column-header, .pivot-data-header {
        font-weight: bold;
        text-align: center;
    }
    .pivot-column {
        background-color: #081c5c;
        color: white;
        font-weight: bold;
        text-align: center;
    }
</style>



<?php $__env->startSection('navbar_header'); ?>
    <b><?php echo session('current_project_char') ?></b> - Marketing - Analisa Net Present Value (NPV)
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
    Marketing - Analisa Net Present Value (NPV)
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
                        <?php if($project != null && $start_date != null && $cut_off != null): ?>
                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">
                                <br />
                                <div style='text-align: center;margin-bottom:30px;'>
                                    <input type="button" class="btn btn-success" value="Download Excel" onclick="getExcel();">
                                    <input type="button" class="btn btn-danger" value="Download PDF" onclick="getPDF();">
                                    <input type="button" class="btn btn-primary" value="Print" onclick="getPrint();">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">
                                <p><b>Harga Jual Tanah Rata2/m2</b></p>
                                <span style="color: red;"><b>* Rp (Dalam Ribuan) | Interest Rate : <?php echo (float) $report->interest_rate . '%'; ?></b></span>
                                <?php
                                    PivotTable::create(array(
                                        "dataStore"=>$report->dataStore('marketing_analisa_npv_table_land'),
                                        'rowCollapseLevels' => array(0),
                                        'columnCollapseLevels' => array(0),
                                        'map' => array(
                                            'dataHeader' => function($dataField, $fieldInfo) {
                                                $v = $dataField;
                                                if ($v === 'NPV_TANAH - sum')
                                                    $v = 'NPV (Rp/m2)';
                                                else if ($v === 'ALL_PAYMENT_TANAH - sum')
                                                    $v = 'All Payment (Rp/m2)';
                                                else if ($v === 'GROWTH_NPV - sum')
                                                    $v = 'Growth NPV (%)';
                                                else if ($v === 'GROWTH_ALL_PAYMENT - sum')
                                                    $v = 'Growth All Payment (%)';
                                                return $v;
                                            },
                                        ),
                                        'hideTotalColumn' => true,
                                        'showDataHeaders' => true,
                                        'hideTotalRow' => true,
                                        // 'totalName' => '<strong>TOTAL</strong>',
                                    ));
                                ?>
                                <p style="text-align: right;">
                                    <b style="border-style: groove;">
                                        &emsp; Harga Jual Tanah Rata2/m2 
                                        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; 
                                        NPV: <?php echo session('npvLand') ?> 
                                        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; 
                                        All Payment: <?php echo session('paymentLand') ?> &emsp;
                                    </b>
                                </p>
                                <br />
                                <p><b>Harga Jual Bangunan Rata2/m2</b></p>
                                <span style="color: red;"><b>* Rp (Dalam Ribuan) | Interest Rate : <?php echo (float) $report->interest_rate . '%'; ?></b></span>
                                <?php
                                    PivotTable::create(array(
                                        "dataStore"=>$report->dataStore('marketing_analisa_npv_table_build'),
                                        'rowCollapseLevels' => array(0),
                                        'columnCollapseLevels' => array(0),
                                        'map' => array(
                                            'dataHeader' => function($dataField, $fieldInfo) {
                                                $v = $dataField;
                                                if ($v === 'NPV_BANGUNAN - sum')
                                                    $v = 'NPV (Rp/m2)';
                                                else if ($v === 'ALL_PAYMENT_BANGUNAN - sum')
                                                    $v = 'All Payment (Rp/m2)';
                                                else if ($v === 'GROWTH_NPV - sum')
                                                    $v = 'Growth NPV (%)';
                                                else if ($v === 'GROWTH_ALL_PAYMENT - sum')
                                                    $v = 'Growth All Payment (%)';
                                                return $v;
                                            },
                                        ),
                                        'hideTotalColumn' => true,
                                        'showDataHeaders' => true,
                                        'hideTotalRow' => true,
                                        // 'totalName' => '<strong>TOTAL</strong>',
                                    ));
                                ?>
                                <p style="text-align: right;">
                                    <b style="border-style: groove;">
                                        &emsp; Harga Jual Building Rata2/m2 
                                        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; 
                                        NPV: <?php echo session('npvBuild') ?> 
                                        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; 
                                        All Payment: <?php echo session('paymentBuild') ?> &emsp;
                                    </b>
                                </p>
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
        var url = '<?php echo e(url("marketing_analisa_npv_report_excel/proyek/cut_off")); ?>';
        url = url.replace('proyek', proyek);
        url = url.replace('cut_off', cut_off);
        window.location.href = url;
    }

    function getPDF() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("marketing_analisa_npv_report_pdf/proyek/cut_off")); ?>';
        url = url.replace('proyek', proyek);
        url = url.replace('cut_off', cut_off);
        window.location.href = url;
    }

    function getPrint() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("marketing_analisa_npv_report_print/proyek/cut_off")); ?>';
        url = url.replace('proyek', proyek);
        url = url.replace('cut_off', cut_off);
        window.open(url);
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
            var url = '<?php echo e(url("marketing_analisa_npv_report/proyek/cut_off")); ?>';
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
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dashboard/public_html/metland_reporting/resources/views/Marketing/AnalisaNPV/analisa_npv.blade.php ENDPATH**/ ?>