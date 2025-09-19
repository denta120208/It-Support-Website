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
    <b><?php echo session('current_project_char') ?></b> - Finance & Accounting - Posisi Escrow Berdasarkan Tahun & Bank
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
    Finance & Accounting - Posisi Escrow Berdasarkan Tahun & Bank
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
                                <span style="color: red;"><b>* Angka Dalam Jutaan</b></span>
                                <?php
                                    Table::create(array(
                                        "dataStore"=>$report->dataStore('finance_accounting_posisi_escrow_tahun_bank_table1'),
                                        "headers"=>array(
                                            array(
                                                ""=>array("colSpan"=>1),
                                                "TAHAPAN PENCAIRAN"=>array("colSpan"=>2),
                                                "TOTAL"=>array("colSpan"=>2),
                                                " "=>array("colSpan"=>1),
                                            )
                                        ),
                                        "showFooter"=>"bottom",
                                        "themeBase"=>"bs4",
                                        "cssClass"=>array(
                                            "table"=>"table table-striped table-bordered"
                                        ),
                                        "columns"=>array(
                                            "TAHUN"=>array(
                                                "label" => "TH ESCROW",
                                                "type" => "string",
                                            ),
                                            "SISA_BUILDING"=>array(
                                                "label" => "BUILDING",
                                            ),
                                            "SISA_CERTIFICATE"=>array(
                                                "label" => "CERTIFICATE",
                                            ),
                                            "TOTAL_NOMINAL"=>array(
                                                "label" => "Rp",
                                            ),
                                            "TOTAL_UNIT"=>array(
                                                "label" => "Unit",
                                            ),
                                            "RENCANA_PENCAIRAN_SELANJUTNYA"=>array(
                                                "label" => "RENCANA PENCAIRAN SELANJUTNYA",
                                            ),
                                            
                                            "TAHUN"=>array(
                                                "footerText"=>"<p style='text-align: left;'><b>TOTAL</b></p>"
                                                // "footerText"=>"<p><b>TOTAL</b></p>"
                                            ),
                                            "SISA_BUILDING"=>array(
                                                "footer"=>"sum",
                                                "footerText"=>"<b>@value</b>",
                                            ),
                                            "SISA_CERTIFICATE"=>array(
                                                "footer"=>"sum",
                                                "footerText"=>"<b>@value</b>",
                                            ),
                                            "TOTAL_NOMINAL"=>array(
                                                "footer"=>"sum",
                                                "footerText"=>"<b>@value</b>",
                                            ),
                                            "TOTAL_UNIT"=>array(
                                                "footer"=>"sum",
                                                "footerText"=>"<b>@value</b>",
                                            ),
                                            "RENCANA_PENCAIRAN_SELANJUTNYA"=>array(
                                                "footerText"=>"<p style='text-align: left;'><b>-</b></p>"
                                            ),
                                        ),
                                        'options' => [
                                            'ordering' => false
                                        ]
                                    ));
                                ?>
                                <br />
                                <span style="color: red;"><b>* Angka Dalam Jutaan</b></span>
                                <?php
                                    Table::create(array(
                                        "dataStore"=>$report->dataStore('finance_accounting_posisi_escrow_tahun_bank_table2'),
                                        "headers"=>array(
                                            array(
                                                ""=>array("colSpan"=>1),
                                                "TAHAPAN PENCAIRAN"=>array("colSpan"=>2),
                                                "TOTAL"=>array("colSpan"=>2),
                                            )
                                        ),
                                        "showFooter"=>"bottom",
                                        "themeBase"=>"bs4",
                                        "cssClass"=>array(
                                            "table"=>"table table-striped table-bordered"
                                        ),
                                        "columns"=>array(
                                            "BANK"=>array(
                                                "label" => "BANK",
                                                "type" => "string",
                                            ),
                                            "SISA_BUILDING"=>array(
                                                "label" => "BUILDING",
                                            ),
                                            "SISA_CERTIFICATE"=>array(
                                                "label" => "CERTIFICATE",
                                            ),
                                            "TOTAL_NOMINAL"=>array(
                                                "label" => "Rp",
                                            ),
                                            "TOTAL_UNIT"=>array(
                                                "label" => "Unit",
                                            ),
                                            
                                            "BANK"=>array(
                                                "footerText"=>"<p style='text-align: left;'><b>TOTAL</b></p>"
                                                // "footerText"=>"<p><b>TOTAL</b></p>"
                                            ),
                                            "SISA_BUILDING"=>array(
                                                "footer"=>"sum",
                                                "footerText"=>"<b>@value</b>",
                                            ),
                                            "SISA_CERTIFICATE"=>array(
                                                "footer"=>"sum",
                                                "footerText"=>"<b>@value</b>",
                                            ),
                                            "TOTAL_NOMINAL"=>array(
                                                "footer"=>"sum",
                                                "footerText"=>"<b>@value</b>",
                                            ),
                                            "TOTAL_UNIT"=>array(
                                                "footer"=>"sum",
                                                "footerText"=>"<b>@value</b>",
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
        var url = '<?php echo e(url("finance_accounting_posisi_escrow_tahun_bank_report_excel/proyek/cut_off")); ?>';
        url = url.replace('proyek', proyek);
        url = url.replace('cut_off', cut_off);
        window.location.href = url;
    }

    function getPDF() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("finance_accounting_posisi_escrow_tahun_bank_report_pdf/proyek/cut_off")); ?>';
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
            var url = '<?php echo e(url("finance_accounting_posisi_escrow_tahun_bank_report/proyek/cut_off")); ?>';
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
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dashboard/public_html/metland_reporting/resources/views/FinanceAccounting/PosisiEscrowTahunBank/posisi_escrow_tahun_bank.blade.php ENDPATH**/ ?>