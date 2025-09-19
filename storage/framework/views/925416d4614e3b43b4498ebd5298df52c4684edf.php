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
    <b><?php echo session('current_project_char') ?></b> - Finance & Accounting - Collection & Aging Schedule
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
    Finance & Accounting - Collection & Aging Schedule
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
                                <span style="color: red;"><b>* Rp (Dalam Jutaan), Exclude PPN</b></span>
                                <?php
                                    Table::create(array(
                                        "dataStore"=>$report->dataStore('finance_accounting_collection_aging_schedule_table1'),
                                        "headers"=>array(
                                            array(
                                                ""=>array("colSpan"=>1),
                                                (date('Y', strtotime($cut_off)) - 1)=>array("colSpan"=>1),
                                                strtoupper(date('M Y', strtotime($cut_off)))=>array("colSpan"=>4),
                                            )
                                        ),
                                        "themeBase"=>"bs4",
                                        "cssClass"=>array(
                                            "table"=>"table table-striped table-bordered"
                                        ),
                                        'options' => [
                                            'ordering' => false
                                        ]
                                    ));
                                ?>
                                <br />
                                <?php
                                    if(count($report->dataStore('finance_accounting_collection_aging_schedule_table2')->data()) > 1) {
                                        session(['indexColorChart' => 1]);
                                        ColumnChart::create(array(
                                            "title"=>"Collection & Aging Schedule (Juta)",
                                            "dataSource"=>session('collection_aging_schedule_chart'),
                                            "columns"=>array(
                                                "category",
                                                "jumlah"=>array(
                                                    "label"=>"Jumlah",
                                                    "type"=>"number",
                                                    "style"=>function($row){
                                                        if(session('indexColorChart') == 1) {
                                                            session(['indexColorChart' => session('indexColorChart')+1]);
                                                            return "color:#4472c4";
                                                        }
                                                        else if(session('indexColorChart') == 2) {
                                                            session(['indexColorChart' => session('indexColorChart')+1]);
                                                            return "color:#ed7d31";
                                                        }
                                                        else {
                                                            session(['indexColorChart' => 1]);
                                                            return "color:#a5a5a5";
                                                        }
                                                    },
                                                    "annotation"=>function($row)
                                                    {
                                                        return number_format($row["jumlah"]);
                                                    }
                                                ),
                                            ),
                                            "options"=>array(
                                                "legend"=>"none"
                                            ),
                                        ));
                                    }
                                ?>
                                <br />
                                <span style="color: red;"><b>* Rp (Dalam Jutaan), Exclude PPN</b></span>
                                <?php
                                    Table::create(array(
                                        "dataStore"=>$report->dataStore('finance_accounting_collection_aging_schedule_table2'),
                                        "headers"=>array(
                                            array(
                                                ""=>array("colSpan"=>1),
                                                "TOTAL"=>array("colSpan"=>2),
                                                "< 30 HARI"=>array("colSpan"=>2),
                                                "30 - 60 HARI"=>array("colSpan"=>2),
                                                "60 - 90 HARI"=>array("colSpan"=>2),
                                                "> 90 HARI"=>array("colSpan"=>2),
                                            )
                                        ),
                                        "themeBase"=>"bs4",
                                        "cssClass"=>array(
                                            "table"=>"table table-striped table-bordered"
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
        var url = '<?php echo e(url("finance_accounting_collection_aging_schedule_report_excel/proyek/cut_off")); ?>';
        url = url.replace('proyek', proyek);
        url = url.replace('cut_off', cut_off);
        window.location.href = url;
    }

    function getPDF() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("finance_accounting_collection_aging_schedule_report_pdf/proyek/cut_off")); ?>';
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
            var url = '<?php echo e(url("finance_accounting_collection_aging_schedule_report/proyek/cut_off")); ?>';
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
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dashboard/public_html/metland_reporting/resources/views/FinanceAccounting/CollectionAgingSchedule/collection_aging_schedule.blade.php ENDPATH**/ ?>