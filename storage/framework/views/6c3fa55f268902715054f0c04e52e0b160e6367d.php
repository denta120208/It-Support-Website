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
    <b><?php echo session('current_project_char') ?></b> - Marketing - Channel Distribution
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
    Marketing - Channel Distribution
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
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" style="overflow-x:auto;">
                                <?php
                                    session(['indexColorChart' => 1]);
                                    LegacyDrillDown::create(array(
                                        "name"=>"channelDistributionSalesDrillDown",
                                        "title"=>"<h5 style='text-align: right'><b>Sales (Juta)</b></h5>",
                                        "btnBack"=>array("text"=>"Back","class"=>"btn btn-primary"),
                                        "themeBase"=>"bs4",
                                        "dataSource"=>$report->dataStore('marketing_channel_distribution_chart'),
                                        "calculate"=>array(
                                            "sum"=>"total_sales"
                                        ),
                                        "levels"=>array(
                                            array(
                                                "groupBy"=>"tahun",
                                                "widget"=>array(ColumnChart::class,array(
                                                    "columns"=>array(
                                                        "tahun",
                                                        "total_sales"=>array(
                                                            "label"=>"Sales",
                                                            "prefix"=>'Rp. ',
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
                                                                return number_format($row["total_sales"]);
                                                            }
                                                        )
                                                    ),
                                                    "options"=>array(
                                                        "legend"=>"none"
                                                    ),
                                                    // "colorScheme"=>array("#3b9b00"),
                                                )),
                                                "title"=>"All Years"
                                            ),
                                            array(
                                                "groupBy"=>"bulan",
                                                "widget"=>array(ColumnChart::class,array(
                                                    "columns"=>array(
                                                        "bulan"=>array(
                                                            "formatValue"=>function($value, $row){
                                                                // Merubah angka menjadi format bulan
                                                                $value = DateTime::createFromFormat('!m', $value)->format('F');
                                                                // Mengambil 3 huruf didepan bulannya
                                                                $value = substr($value, 0, 3);
                                                                return $value;
                                                            },
                                                        ),
                                                        "total_sales"=>array(
                                                            "label"=>"Sales",
                                                            "prefix"=>'Rp. ',
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
                                                                return number_format($row["total_sales"]);
                                                            }
                                                        )
                                                    ),
                                                    "options"=>array(
                                                        "legend"=>"none"
                                                    ),
                                                    // "colorScheme"=>array("#af17b5"),
                                                )),
                                                "title"=>function($params)
                                                {
                                                    return "Year ".$params["tahun"];
                                                },
                                            ),
                                            array(
                                                "groupBy"=>"NAMA_BROKER_CHAR",
                                                "widget"=>array(ColumnChart::class,array(
                                                    "columns"=>array(
                                                        "NAMA_BROKER_CHAR"=>array(
                                                            "formatValue"=>function($value, $row){
                                                                return $value;
                                                            },
                                                        ),
                                                        "total_sales"=>array(
                                                            "label"=>"Sales",
                                                            "prefix"=>'Rp. ',
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
                                                                return number_format($row["total_sales"]);
                                                            }
                                                        )
                                                    ),
                                                    "options"=>array(
                                                        "legend"=>"none"
                                                    ),
                                                    // "colorScheme"=>array("#CC0033"),
                                                )),
                                                "title"=>function($params)
                                                {
                                                    $bulanStr = DateTime::createFromFormat('!m', $params["bulan"])->format('F');
                                                    return $bulanStr;
                                                },
                                            ),
                                        ),
                                    ));
                                ?>
                            </div>

                            <div class="col-md-6" style="overflow-x:auto;">
                                <?php
                                    LegacyDrillDown::create(array(
                                        "name"=>"channelDistributionUnitDrillDown",
                                        "title"=>"<h5 style='text-align: right'><b>Unit</b></h5>",
                                        "btnBack"=>array("text"=>"Back","class"=>"btn btn-primary"),
                                        "themeBase"=>"bs4",
                                        "dataSource"=>$report->dataStore('marketing_channel_distribution_chart'),
                                        "calculate"=>array(
                                            "sum"=>"total_unit"
                                        ),
                                        "levels"=>array(
                                            array(
                                                "groupBy"=>"tahun",
                                                "widget"=>array(ColumnChart::class,array(
                                                    "columns"=>array(
                                                        "tahun",
                                                        "total_unit"=>array(
                                                            "label"=>"Unit",
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
                                                                return number_format($row["total_unit"]);
                                                            }
                                                        )
                                                    ),
                                                    "options"=>array(
                                                        "legend"=>"none"
                                                    ),
                                                    // "colorScheme"=>array("#af17b5"),
                                                )),
                                                "title"=>"All Years"
                                            ),
                                            array(
                                                "groupBy"=>"bulan",
                                                "widget"=>array(ColumnChart::class,array(
                                                    "columns"=>array(
                                                        "bulan"=>array(
                                                            "formatValue"=>function($value, $row){
                                                                // Merubah angka menjadi format bulan
                                                                $value = DateTime::createFromFormat('!m', $value)->format('F');
                                                                // Mengambil 3 huruf didepan bulannya
                                                                $value = substr($value, 0, 3);
                                                                return $value;
                                                            },
                                                        ),
                                                        "total_unit"=>array(
                                                            "label"=>"Unit",
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
                                                                return number_format($row["total_unit"]);
                                                            }
                                                        )
                                                    ),
                                                    "options"=>array(
                                                        "legend"=>"none"
                                                    ),
                                                    // "colorScheme"=>array("#CC0033"),
                                                )),
                                                "title"=>function($params)
                                                {
                                                    return "Year ".$params["tahun"];
                                                },
                                            ),
                                            array(
                                                "groupBy"=>"NAMA_BROKER_CHAR",
                                                "widget"=>array(ColumnChart::class,array(
                                                    "columns"=>array(
                                                        "NAMA_BROKER_CHAR"=>array(
                                                            "formatValue"=>function($value, $row){
                                                                return $value;
                                                            },
                                                        ),
                                                        "total_unit"=>array(
                                                            "label"=>"Unit",
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
                                                                return number_format($row["total_unit"]);
                                                            }
                                                        )
                                                    ),
                                                    "options"=>array(
                                                        "legend"=>"none"
                                                    ),
                                                    // "colorScheme"=>array("#3b9b00"),
                                                )),
                                                "title"=>function($params)
                                                {
                                                    $bulanStr = DateTime::createFromFormat('!m', $params["bulan"])->format('F');
                                                    return $bulanStr;
                                                },
                                            ),
                                        ),
                                    ));
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">
                                <br />
                                <span style="color: red;"><b>* Rp (Dalam Jutaan)</b></span>
                                <?php
                                    PivotTable::create(array(
                                        "dataStore"=>$report->dataStore('marketing_channel_distribution_table'),
                                        'rowCollapseLevels' => array(0),
                                        'columnCollapseLevels' => array(0),
                                        'map' => array(
                                            'dataHeader' => function($dataField, $fieldInfo) {
                                                $v = $dataField;
                                                if ($v === 'total_sales - sum')
                                                    $v = 'Rp';
                                                else if ($v === 'total_unit - sum')
                                                    $v = 'Unit';
                                                else if ($v === 'total_sales_percent - sum')
                                                    $v = '%';
                                                else if ($v === 'total_unit_percent - sum')
                                                    $v = '%';
                                                return $v;
                                            },
                                        ),
                                        'hideTotalColumn' => true,
                                        'showDataHeaders' => true,
                                        'totalName' => '<strong>TOTAL</strong>',
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
        var url = '<?php echo e(url("marketing_channel_distribution_report_excel/proyek/cut_off")); ?>';
        url = url.replace('proyek', proyek);
        url = url.replace('cut_off', cut_off);
        window.location.href = url;
    }

    function getPDF() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("marketing_channel_distribution_report_pdf/proyek/cut_off")); ?>';
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
            var url = '<?php echo e(url("marketing_channel_distribution_report/proyek/cut_off")); ?>';
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
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dashboard/public_html/metland_reporting/resources/views/Marketing/ChannelDistribution/channel_distribution.blade.php ENDPATH**/ ?>