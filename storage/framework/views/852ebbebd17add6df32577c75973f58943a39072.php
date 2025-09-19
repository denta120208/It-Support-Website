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
    <b><?php echo session('current_project_char_commercial') ?></b> - Marketing - Growth Updated Member Metland Card
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
    Marketing - Growth Updated Member Metland Card
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
                                <?php
                                    Table::create(array(
                                        "dataSource"=> $report->dataStore('marketing_growth_member_metland_card_table1'),
                                        "themeBase"=>"bs4",
                                        "showFooter"=>"bottom",
                                        "cssClass"=>array(
                                            "table"=>"table table-striped table-bordered"
                                        ),
                                        "columns"=>array(
                                            "project"=>array(
                                                'label' => '',
                                                "footerText"=>"<b>TOTAL</b>"
                                            ),
                                            "JUMLAH_MEMBER"=>array(
                                                'label' => 'MEMBER',
                                                "footer"=>"sum",
                                                "footerText"=>"<b>@value</b>",
                                            ),
                                        )
                                    ));
                                ?>
                            </div>
                        </div>

                        <br />

                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">
                                <?php
                                    ColumnChart::create(array(
                                        "title"=>"Growth Updated Member Metland Card (".date('Y', strtotime($cut_off)).")",
                                        "dataSource"=>session('growth_member_metland_card_chart1'),
                                        "columns"=>array(
                                            "bulan"=>array(
                                                'formatValue'=>function($value, $row){
                                                    // Merubah angka menjadi format bulan
                                                    $value = strtoupper(DateTime::createFromFormat('!m', $value)->format('M'));
                                                    return $value;
                                                }
                                            ),
                                            "GrandMetropolitan Mall"=>array(
                                                "label"=>"GMM",
                                                "type"=>"number",
                                                "annotation"=>function($row) {
                                                    return $row['GrandMetropolitan Mall'];
                                                }
                                            ),
                                            "Metropolitan Mall"=>array(
                                                "label"=>"MM",
                                                "type"=>"number",
                                                "annotation"=>function($row) {
                                                    return $row['Metropolitan Mall'];
                                                }
                                            ),
                                            "Mall Metropolitan Cileungsi"=>array(
                                                "label"=>"MMC",
                                                "type"=>"number",
                                                "annotation"=>function($row) {
                                                    return $row['Mall Metropolitan Cileungsi'];
                                                }
                                            ),
                                            "Hotel"=>array(
                                                "label"=>"HOTEL",
                                                "type"=>"number",
                                                "annotation"=>function($row) {
                                                    return $row['Hotel'];
                                                }
                                            ),
                                            "Residential"=>array(
                                                "label"=>"RESIDENTIAL",
                                                "type"=>"number",
                                                "annotation"=>function($row) {
                                                    return $row['Residential'];
                                                }
                                            ),
                                            "EVENT"=>array(
                                                "label"=>"EVENT",
                                                "type"=>"number",
                                                "annotation"=>function($row) {
                                                    return $row['EVENT'];
                                                }
                                            ),
                                            "Head Office"=>array(
                                                "label"=>"HEAD OFFICE",
                                                "type"=>"number",
                                                "annotation"=>function($row) {
                                                    return $row['Head Office'];
                                                }
                                            ),
                                        )
                                    ));
                                ?>
                            </div>
                        </div>

                        <br />

                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">
                                <h5><b>NEW MEMBER <?php echo session('current_project_char_commercial') ?> YTD <?php echo strtoupper(date('M Y', strtotime($cut_off))) ?></b></h5>
                                <?php
                                    Table::create(array(
                                        "dataSource"=> $report->dataStore('marketing_growth_member_metland_card_table2'),
                                        "themeBase"=>"bs4",
                                        "showFooter"=>"bottom",
                                        "cssClass"=>array(
                                            "table"=>"table table-striped table-bordered"
                                        ),
                                        "columns"=>array(
                                            "bulan"=>array(
                                                'label' => 'BULAN',
                                                'formatValue'=>function($value, $row){
                                                    // Merubah angka menjadi format bulan
                                                    $value = strtoupper(DateTime::createFromFormat('!m', $value)->format('F'));
                                                    return $value;
                                                },
                                                "footerText"=>"<b>TOTAL</b>"
                                            ),
                                            "JUMLAH_MEMBER"=>array(
                                                'label' => 'JUMLAH',
                                                "footer"=>"sum",
                                                "footerText"=>"<b>@value</b>",
                                            ),
                                        )
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
        var proyek = <?php echo session('current_project_commercial') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("marketing_growth_member_metland_card_report_excel/proyek/cut_off")); ?>';
        url = url.replace('proyek', proyek);
        url = url.replace('cut_off', cut_off);
        window.location.href = url;
    }

    function getPDF() {
        var proyek = <?php echo session('current_project_commercial') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("marketing_growth_member_metland_card_report_pdf/proyek/cut_off")); ?>';
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
            var proyek = <?php echo session('current_project_commercial') ?>;
            var cut_off = document.getElementById("txtCutOff").value;
            var url = '<?php echo e(url("marketing_growth_member_metland_card_report/proyek/cut_off")); ?>';
            url = url.replace('proyek', proyek);
            url = url.replace('cut_off', cut_off);
            window.location.href = url;
        }
    }

    function validasi() {
        var proyek = <?php echo session('current_project_commercial') ?>;
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
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dashboard/public_html/metland_reporting/resources/views/Marketing/GrowthMemberMetlandCard/growth_member_metland_card.blade.php ENDPATH**/ ?>