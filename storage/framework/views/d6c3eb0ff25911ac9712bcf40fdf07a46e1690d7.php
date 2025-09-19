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
    <b><?php echo session('current_project_char') ?></b> - Marketing - Demografi Usia & Pekerjaan
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
    Marketing - Demografi Usia & Pekerjaan
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
                                    ColumnChart::create(array(
                                        "title"=>"Demografi Pekerjaan",
                                        "dataSource"=>session('demografi_usia_pekerjaan_chart_pekerjaan'),
                                        "columns"=>array(
                                            "PEKERJAAN_CHAR",
                                            (date('Y', strtotime($cut_off))-1)=>array(
                                                "label"=>(date('Y', strtotime($cut_off))-1),
                                                "type"=>"number",
                                                "annotation"=>function($row)
                                                {
                                                    if(array_key_exists((date('Y', strtotime(session('demografi_usia_pekerjaan_chart_cut_off')))-1), $row)) {
                                                        return number_format($row[(date('Y', strtotime(session('demografi_usia_pekerjaan_chart_cut_off')))-1)]);
                                                    }
                                                    else {
                                                        return number_format(0);
                                                    }
                                                }
                                            ),
                                            date('Y', strtotime($cut_off))=>array(
                                                "label"=>date('Y', strtotime($cut_off)),
                                                "type"=>"number",
                                                "annotation"=>function($row)
                                                {
                                                    if(array_key_exists(date('Y', strtotime(session('demografi_usia_pekerjaan_chart_cut_off'))), $row)) {
                                                        return number_format($row[date('Y', strtotime(session('demografi_usia_pekerjaan_chart_cut_off')))]);
                                                    }
                                                    else {
                                                        return number_format(0);
                                                    }
                                                }
                                            ),
                                        )
                                    ));
                                ?>
                                <br />
                                <?php
                                    ColumnChart::create(array(
                                        "title"=>"Demografi Usia",
                                        "dataSource"=>session('demografi_usia_pekerjaan_chart_age'),
                                        "columns"=>array(
                                            "TAHUN",
                                            "17-30"=>array(
                                                "label"=>"17-30",
                                                "type"=>"number",
                                                "annotation"=>function($row) {
                                                    return $row['17-30'];
                                                }
                                            ),
                                            "31-40"=>array(
                                                "label"=>"31-40",
                                                "type"=>"number",
                                                "annotation"=>function($row) {
                                                    return $row['31-40'];
                                                }
                                            ),
                                            "41-55"=>array(
                                                "label"=>"41-55",
                                                "type"=>"number",
                                                "annotation"=>function($row) {
                                                    return $row['41-55'];
                                                }
                                            ),
                                            ">56"=>array(
                                                "label"=>"> 56",
                                                "type"=>"number",
                                                "annotation"=>function($row) {
                                                    return $row['>56'];
                                                }
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
        var url = '<?php echo e(url("marketing_demografi_usia_pekerjaan_report_excel/proyek/cut_off")); ?>';
        url = url.replace('proyek', proyek);
        url = url.replace('cut_off', cut_off);
        window.location.href = url;
    }

    function getPDF() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("marketing_demografi_usia_pekerjaan_report_pdf/proyek/cut_off")); ?>';
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
            var url = '<?php echo e(url("marketing_demografi_usia_pekerjaan_report/proyek/cut_off")); ?>';
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
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dashboard/public_html/metland_reporting/resources/views/Marketing/DemografiUsiaPekerjaan/demografi_usia_pekerjaan.blade.php ENDPATH**/ ?>