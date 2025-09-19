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
    .dt-nowrap {
        white-space: nowrap;
    }
    .bgfffc04 {
        background-color:#fffc04;
        color: black;
        font-weight: bold;
    }
    .bge8ecdc {
        background-color:#e8ecdc;
        color: black;
    }
    .bg90acdc {
        background-color:#90acdc;
        color: black;
        font-weight: bold;
    }
    .bg081c5c {
        background-color:#081c5c;
        color: white;
        font-weight: bold;
    }
</style>



<?php $__env->startSection('navbar_header'); ?>
    <b><?php echo session('current_project_char') ?></b> - Finance & Accounting - GOP
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
    Finance & Accounting - GOP
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
                                    <input type="text" class="form-control" id="txtCutOff" placeholder="Input Cut Off..." value="<?php echo e($cut_off); ?>" required>
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
                                <span style="color: red;"><b>* Angka Dalam Jutaan</b></span>
                                <?php
                                    $dateParam = DateTime::createFromFormat('Ym', $cut_off);
                                    Table::create(array(
                                        "dataStore"=>$report->dataStore('finance_accounting_gop_table'),
                                        "headers"=>array(
                                            array(
                                                ""=>array("colSpan"=>1),
                                                " "=>array("colSpan"=>1),
                                                // "CURRENT MONTH"=>array("colSpan"=>3),
                                                "CURRENT MONTH"=>array("colSpan"=>2),
                                                "  "=>array("colSpan"=>5)
                                            ),
                                        ),
                                        // "showFooter"=>"bottom",
                                        "themeBase"=>"bs4",
                                        "cssClass"=>array(
                                            "table"=>"table table-striped table-bordered",
                                            "td"=>function($row, $colName) {
                                                if($colName == "GOP_GROUP_DASHBOARD_NAME") {
                                                    return "text-left bg" . str_replace('#', '', $row['COLOR']);
                                                }
                                                else if($colName == "ACTUAL_YTD_BACKWARD" || $colName == "CURRENT_MONTH_RES" || $colName == "CURRENT_MONTH_SPORT_CLUB" || $colName == "TOTAL_CURRENT_MONTH" || $colName == "ACTUAL_YTD_CURRENT" || $colName == "BUDGET_YTD_CURRENT" || $colName == "BUDGET_FY_CURRENT") {
                                                    return "text-right bg" . str_replace('#', '', $row['COLOR']);
                                                }
                                                else {
                                                    return "bg" . str_replace('#', '', $row['COLOR']);
                                                }
                                            }
                                        ),
                                        "columns" => array(
                                            "GOP_GROUP_DASHBOARD_NAME" => [
                                                "label" => "KETERANGAN",
                                                "formatValue" => function($value, $row) {
                                                    return $value;
                                                }
                                            ],
                                            "ACTUAL_YTD_BACKWARD" => [
                                                "label" => "ACTUAL YTD " . $dateParam->format('F') . ' ' . ($dateParam->format('Y') - 1),
                                                "formatValue" => function($value, $row) {
                                                    if($row['GOP_GROUP_DASHBOARD_NAME'] == '%' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TOTAL' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% GA EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% MKT EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TANAH' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN BANGUNAN') {
                                                        return $value == "0" ? "" : number_format($value,0,',','.') . "%";
                                                    }
                                                    else {
                                                        return $value == "0" ? "" : number_format($value / 1000000,0,',','.');
                                                    }
                                                }
                                            ],
                                            "CURRENT_MONTH_RES" => [
                                                "label" => "Res",
                                                "formatValue" => function($value, $row) {
                                                    if($row['GOP_GROUP_DASHBOARD_NAME'] == '%' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TOTAL' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% GA EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% MKT EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TANAH' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN BANGUNAN') {
                                                        return $value == "0" ? "" : number_format($value,0,',','.') . "%";
                                                    }
                                                    else {
                                                        return $value == "0" ? "" : number_format($value / 1000000,0,',','.');
                                                    }
                                                }
                                            ],
                                            // "CURRENT_MONTH_SPORT_CLUB" => [
                                            //     "label" => "Sport Facility",
                                            //     "formatValue" => function($value, $row) {
                                            //         if($row['GOP_GROUP_DASHBOARD_NAME'] == '%' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TOTAL' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% GA EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% MKT EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TANAH' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN BANGUNAN') {
                                            //             return $value == "0" ? "" : number_format($value,0,',','.') . "%";
                                            //         }
                                            //         else {
                                            //             return $value == "0" ? "" : number_format($value / 1000000,0,',','.');
                                            //         }
                                            //     }
                                            // ],
                                            "TOTAL_CURRENT_MONTH" => [
                                                "label" => "Total",
                                                "formatValue" => function($value, $row) {
                                                    if($row['GOP_GROUP_DASHBOARD_NAME'] == '%' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TOTAL' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% GA EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% MKT EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TANAH' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN BANGUNAN') {
                                                        return $value == "0" ? "" : number_format($value,0,',','.') . "%";
                                                    }
                                                    else {
                                                        return $value == "0" ? "" : number_format($value / 1000000,0,',','.');
                                                    }
                                                }
                                            ],
                                            "ACTUAL_YTD_CURRENT" => [
                                                "label" => "ACTUAL YTD " . $dateParam->format('F Y'),
                                                "formatValue" => function($value, $row) {
                                                    if($row['GOP_GROUP_DASHBOARD_NAME'] == '%' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TOTAL' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% GA EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% MKT EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TANAH' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN BANGUNAN') {
                                                        return $value == "0" ? "" : number_format($value,0,',','.') . "%";
                                                    }
                                                    else {
                                                        return $value == "0" ? "" : number_format($value / 1000000,0,',','.');
                                                    }
                                                }
                                            ],
                                            "BUDGET_YTD_CURRENT" => [
                                                "label" => "BUDGET YTD " . $dateParam->format('F Y'),
                                                "formatValue" => function($value, $row) {
                                                    if($row['GOP_GROUP_DASHBOARD_NAME'] == '%' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TOTAL' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% GA EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% MKT EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TANAH' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN BANGUNAN') {
                                                        return $value == "0" ? "" : number_format($value,0,',','.') . "%";
                                                    }
                                                    else {
                                                        return $value == "0" ? "" : number_format($value / 1000000,0,',','.');
                                                    }
                                                }
                                            ],
                                            "ACHIEVEMENT" => [
                                                "label" => "ACHIEVEMENT UP TO",
                                                "formatValue" => function($value, $row) {
                                                    return $value == "0" ? "" : number_format($value,0,',','.') . '%';
                                                }
                                            ],
                                            "GROWTH" => [
                                                "label" => "GROWTH",
                                                "formatValue" => function($value, $row) {
                                                    return $value == "0" ? "" : number_format($value,0,',','.') . '%';
                                                }
                                            ],
                                            "BUDGET_FY_CURRENT" => [
                                                "label" => "BUDGET FY " . $dateParam->format('Y'),
                                                "formatValue" => function($value, $row) {
                                                    if($row['GOP_GROUP_DASHBOARD_NAME'] == '%' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TOTAL' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% GA EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% MKT EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TANAH' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN BANGUNAN') {
                                                        return $value == "0" ? "" : number_format($value,0,',','.') . "%";
                                                    }
                                                    else {
                                                        return $value == "0" ? "" : number_format($value / 1000000,0,',','.');
                                                    }
                                                }
                                            ]
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

        $("#txtCutOff").datepicker( {
            format: "yyyymm",
            startView: "months", 
            minViewMode: "months"
        });

        $('#txtCutOff').on('changeDate', function(ev){
            $(this).datepicker('hide');
        });
    });

    function getExcel() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("finance_accounting_gop_report_excel/proyek/cut_off")); ?>';
        url = url.replace('proyek', proyek);
        url = url.replace('cut_off', cut_off);
        window.location.href = url;
    }

    function getPDF() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("finance_accounting_gop_report_pdf/proyek/cut_off")); ?>';
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
            var url = '<?php echo e(url("finance_accounting_gop_report/project/cut_off")); ?>';
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
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dashboard/public_html/metland_reporting/resources/views/FinanceAccounting/GOP/gop.blade.php ENDPATH**/ ?>