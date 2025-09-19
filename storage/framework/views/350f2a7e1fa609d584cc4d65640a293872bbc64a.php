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
    <b><?php echo session('current_project_char') ?></b> - Marketing - Sales
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title'); ?>
    Marketing - Sales
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
                                    session(['indexColorChart' => 1]);
                                    LegacyDrillDown::create(array(
                                        "name"=>"marketingSalesAllSalesDrillDown",
                                        "title"=>"<h5 style='text-align: right'><b>All Sales (Unit)</b></h5>",
                                        "btnBack"=>array("text"=>"Back","class"=>"btn btn-primary"),
                                        "themeBase"=>"bs4",
                                        "dataSource"=>$report->dataStore('marketing_sales_all_sales_chart'),
                                        "calculate"=>array(
                                            "sum"=>"total_unit"
                                        ),
                                        "levels"=>array(
                                            array(
                                                "groupBy"=>"PROJECT_NAME",
                                                "widget"=>array(ColumnChart::class,array(
                                                    "columns"=>array(
                                                        "PROJECT_NAME",
                                                        "total_unit"=>array(
                                                            "label"=>"Unit",
                                                            // "prefix"=>'Rp. ',
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
                                                "title"=>"All Project"
                                            ),
                                            array(
                                                "groupBy"=>"thn",
                                                "widget"=>array(ColumnChart::class,array(
                                                    "columns"=>array(
                                                        "thn",
                                                        "total_unit"=>array(
                                                            "label"=>"Unit",
                                                            // "prefix"=>'Rp. ',
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
                                                "title"=>function($params)
                                                {
                                                    return $params["PROJECT_NAME"];
                                                },
                                            ),
                                            array(
                                                "groupBy"=>"bln",
                                                "widget"=>array(ColumnChart::class,array(
                                                    "columns"=>array(
                                                        "bln"=>array(
                                                            "formatValue"=>function($value, $row){
                                                                // Merubah angka menjadi format bulan
                                                                $value = strtoupper(DateTime::createFromFormat('!m', $value)->format('M'));
                                                                return $value;
                                                            },
                                                        ),
                                                        "total_unit"=>array(
                                                            "label"=>"Unit",
                                                            // "prefix"=>'Rp. ',
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
                                                "title"=>function($params)
                                                {
                                                    return $params["thn"];
                                                },
                                            ),
                                        ),
                                    ));
                                ?>
                            </div>
                        </div>

                        <br /><br />
                        
                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">
                            <span style="color: red;"><b>* Harga (Dalam Jutaan)</b></span>
                            <?php
                                DrillDown::create(array(
                                    "name"=>"saleTahapSektorDrillDown",
                                    "title"=>"<h5 style='text-align: left'><b>Sales Tahap/Sektor</b></h5>",
                                    "btnBack"=>array("text"=>"Back","class"=>"btn btn-primary"),
                                    "themeBase"=>"bs4",
                                    "scope"  => array(
                                        "report" => $report,
                                    ),
                                    "levels"=>array(
                                        array(
                                            "title"=>"All Tahap/Sektor",
                                            "content"=>function($params, $scope)
                                            {
                                                Table::create(array(
                                                    "dataSource"=> $scope['report']->dataStore('marketing_sales_tahap_sektor'),
                                                    "themeBase"=>"bs4",
                                                    "showFooter"=>"bottom",
                                                    "cssClass"=>array(
                                                        "table"=>"table table-striped table-bordered"
                                                    ),
                                                    "columns"=>array(
                                                    ),
                                                    "clientEvents"=>array(
                                                        "rowClick"=>"function(e){
                                                            // console.log(e);
                                                            // console.log(e.rowIndex);
                                                            // console.log(e.rowData);
                                                            // console.log(e.table);
                                                            // console.log(e.rowData[0]);
                                                            saleTahapSektorDrillDown.next({TAHAP_SEKTOR:e.rowData[0]});
                                                        }",
                                                    )
                                                ));
                                            }
                                        ),
                                        array(
                                            "title"=>function($params, $scope)
                                            {
                                                return $params["TAHAP_SEKTOR"];
                                            },
                                            "content"=>function($params, $scope)
                                            {
                                                if($params["TAHAP_SEKTOR"] == "SEKTOR") {
                                                    $data = DB::select("SELECT YEAR(a.TGL_BOOKINGENTRY_DTTIME) AS [TAHUN],
                                                        d.SEKTOR_NAME, COUNT(d.SEKTOR_NAME) AS [SEKTOR_NUM], SUM(a.NET_BEFORE_TAX_NUM) AS [NET_BEFORE_TAX_NUM]
                                                        FROM SA_BOOKINGENTRY AS a
                                                        LEFT JOIN MD_STOCK AS b ON a.ID_UNIT_CHAR = b.ID_UNIT_STOCK_INT
                                                        LEFT JOIN MD_TOWER_APT AS c ON b.ID_TOWER_INT = c.ID_TOWER_INT
                                                        LEFT JOIN MD_SEKTOR AS d ON b.ID_SEKTOR_INT = d.ID_SEKTOR_INT
                                                        LEFT JOIN MD_STAGE AS e ON c.ID_STAGE_INT = e.ID_STAGE_INT
                                                        WHERE a.BOOKING_ENTRY_APPROVE_INT = 1 AND a.PROJECT_NO_CHAR = '".session('marketingsalestabletahapsektorproject')."' AND
                                                        a.TGL_BOOKINGENTRY_DTTIME >= '".session('marketingsalestabletahapsektorstartdate')."' AND a.TGL_BOOKINGENTRY_DTTIME <= '".session('marketingsalestabletahapsektorcutoff')."' AND d.SEKTOR_NAME <> 'NULL'
                                                        GROUP BY YEAR(a.TGL_BOOKINGENTRY_DTTIME), d.SEKTOR_NAME
                                                        ORDER BY YEAR(a.TGL_BOOKINGENTRY_DTTIME) ASC");

                                                        Table::create(array(
                                                            "dataSource"=> $data,
                                                            "themeBase"=>"bs4",
                                                            "showFooter"=>"bottom",
                                                            "cssClass"=>array(
                                                                "table"=>"table table-striped table-bordered"
                                                            ),
                                                            "columns"=>array(
                                                                "TAHUN"=>array(
                                                                    "label"=>"TAHUN",
                                                                    "type"=>"string",
                                                                    "footerText"=>"<p><b>TOTAL</b></p>"
                                                                ),
                                                                "SEKTOR_NAME"=>array(
                                                                    "label"=>"SEKTOR",
                                                                    "footerText"=>"<p><b>-</b></p>"
                                                                ),
                                                                "SEKTOR_NUM"=>array(
                                                                    "label"=>"JUMLAH",
                                                                    "footer"=>"sum",
                                                                    "footerText"=>"<b>@value</b>",
                                                                ),
                                                                "NET_BEFORE_TAX_NUM"=>array(
                                                                    "label"=>"HARGA",
                                                                    "type"=>"number",
                                                                    "footer"=>"sum",
                                                                    "footerText"=>"<b>@value</b>",
                                                                    "formatValue"=>function($value, $row)
                                                                    {
                                                                        return number_format($value/1000000);
                                                                    }
                                                                ),
                                                            ),
                                                            "clientEvents"=>array(
                                                                "rowClick"=>"function(e){
                                                                    // console.log(e);
                                                                    // console.log(e.rowIndex);
                                                                    // console.log(e.rowData);
                                                                    // console.log(e.table);
                                                                    // console.log(e.rowData[0]);
                                                                    saleTahapSektorDrillDown.next({TAHAP_SEKTOR_NAME:e.rowData[1],TAHUN:e.rowData[0],TAHAP_SEKTOR:'".$params['TAHAP_SEKTOR']."'});
                                                                }",
                                                            )
                                                        ));
                                                } else {
                                                    $data = DB::select("SELECT YEAR(a.TGL_BOOKINGENTRY_DTTIME) AS [TAHUN],
                                                        e.STAGE_NAME_CHAR, COUNT(e.STAGE_NAME_CHAR) AS [STAGE_NAME_NUM], SUM(a.NET_BEFORE_TAX_NUM) AS [NET_BEFORE_TAX_NUM]
                                                        FROM SA_BOOKINGENTRY AS a
                                                        LEFT JOIN MD_STOCK AS b ON a.ID_UNIT_CHAR = b.ID_UNIT_STOCK_INT
                                                        LEFT JOIN MD_TOWER_APT AS c ON b.ID_TOWER_INT = c.ID_TOWER_INT
                                                        LEFT JOIN MD_SEKTOR AS d ON b.ID_SEKTOR_INT = d.ID_SEKTOR_INT
                                                        LEFT JOIN MD_STAGE AS e ON c.ID_STAGE_INT = e.ID_STAGE_INT
                                                        WHERE a.BOOKING_ENTRY_APPROVE_INT = 1 AND a.PROJECT_NO_CHAR = '".session('marketingsalestabletahapsektorproject')."' AND
                                                        a.TGL_BOOKINGENTRY_DTTIME >= '".session('marketingsalestabletahapsektorstartdate')."' AND a.TGL_BOOKINGENTRY_DTTIME <= '".session('marketingsalestabletahapsektorcutoff')."' AND e.STAGE_NAME_CHAR <> 'NULL'
                                                        GROUP BY YEAR(a.TGL_BOOKINGENTRY_DTTIME), e.STAGE_NAME_CHAR
                                                        ORDER BY YEAR(a.TGL_BOOKINGENTRY_DTTIME) ASC");

                                                    Table::create(array(
                                                        "dataSource"=> $data,
                                                        "showFooter"=>"bottom",
                                                        "themeBase"=>"bs4",
                                                        "cssClass"=>array(
                                                            "table"=>"table table-striped table-bordered"
                                                        ),
                                                        "columns"=>array(
                                                            "TAHUN"=>array(
                                                                "label"=>"TAHUN",
                                                                "type"=>"string",
                                                                "footerText"=>"<p><b>TOTAL</b></p>"
                                                            ),
                                                            "STAGE_NAME_CHAR"=>array(
                                                                "label"=>"TAHAP",
                                                                "footerText"=>"<p><b>-</b></p>"
                                                            ),
                                                            "STAGE_NAME_NUM"=>array(
                                                                "label"=>"JUMLAH",
                                                                "footer"=>"sum",
                                                                "footerText"=>"<b>@value</b>",
                                                            ),
                                                            "NET_BEFORE_TAX_NUM"=>array(
                                                                "label"=>"HARGA",
                                                                "type"=>"number",
                                                                "footer"=>"sum",
                                                                "footerText"=>"<b>@value</b>",
                                                                "formatValue"=>function($value, $row)
                                                                {
                                                                    return number_format($value/1000000);
                                                                }
                                                            ),
                                                        ),
                                                        "clientEvents"=>array(
                                                            "rowClick"=>"function(e){
                                                                // console.log(e);
                                                                // console.log(e.rowIndex);
                                                                // console.log(e.rowData);
                                                                // console.log(e.table);
                                                                // console.log(e.rowData[0]);
                                                                saleTahapSektorDrillDown.next({TAHAP_SEKTOR_NAME:e.rowData[1],TAHUN:e.rowData[0],TAHAP_SEKTOR:'".$params['TAHAP_SEKTOR']."'});
                                                            }",
                                                        )
                                                    ));
                                                }
                                            }        
                                        ),
                                        array(
                                            "title"=>function($params, $scope)
                                            {
                                                return $params["TAHUN"]." - ".$params["TAHAP_SEKTOR_NAME"];
                                            },
                                            "content"=>function($params, $scope)
                                            {
                                                if($params["TAHAP_SEKTOR"] == "SEKTOR") {
                                                    $data = DB::select("SELECT c.TOWER_NAME AS [CLUSTER], COUNT(c.TOWER_NAME) AS [JUMLAH],
                                                        SUM(a.NET_BEFORE_TAX_NUM) AS [HARGA]
                                                        FROM SA_BOOKINGENTRY AS a
                                                        LEFT JOIN MD_STOCK AS b ON a.ID_UNIT_CHAR = b.ID_UNIT_STOCK_INT
                                                        LEFT JOIN MD_TOWER_APT AS c ON b.ID_TOWER_INT = c.ID_TOWER_INT
                                                        LEFT JOIN MD_SEKTOR AS d ON b.ID_SEKTOR_INT = d.ID_SEKTOR_INT
                                                        LEFT JOIN MD_STAGE AS e ON c.ID_STAGE_INT = e.ID_STAGE_INT
                                                        WHERE a.BOOKING_ENTRY_APPROVE_INT = 1 AND a.PROJECT_NO_CHAR = '".session('current_project')."' AND d.SEKTOR_NAME = '".$params['TAHAP_SEKTOR_NAME']."' AND
                                                        YEAR(a.TGL_BOOKINGENTRY_DTTIME) = '".$params['TAHUN']."' AND a.TGL_BOOKINGENTRY_DTTIME <= '".session('marketingsalestabletahapsektorcutoff')."' AND d.SEKTOR_NAME <> 'NULL'
                                                        GROUP BY c.TOWER_NAME
                                                        ORDER BY c.TOWER_NAME ASC");
                                                } else {
                                                    $data = DB::select("SELECT c.TOWER_NAME AS [CLUSTER], COUNT(c.TOWER_NAME) AS [JUMLAH],
                                                        SUM(a.NET_BEFORE_TAX_NUM) AS [HARGA]
                                                        FROM SA_BOOKINGENTRY AS a
                                                        LEFT JOIN MD_STOCK AS b ON a.ID_UNIT_CHAR = b.ID_UNIT_STOCK_INT
                                                        LEFT JOIN MD_TOWER_APT AS c ON b.ID_TOWER_INT = c.ID_TOWER_INT
                                                        LEFT JOIN MD_SEKTOR AS d ON b.ID_SEKTOR_INT = d.ID_SEKTOR_INT
                                                        LEFT JOIN MD_STAGE AS e ON c.ID_STAGE_INT = e.ID_STAGE_INT
                                                        WHERE a.BOOKING_ENTRY_APPROVE_INT = 1 AND a.PROJECT_NO_CHAR = '".session('current_project')."' AND e.STAGE_NAME_CHAR = '".$params['TAHAP_SEKTOR_NAME']."' AND
                                                        YEAR(a.TGL_BOOKINGENTRY_DTTIME) = '".$params['TAHUN']."' AND a.TGL_BOOKINGENTRY_DTTIME <= '".session('marketingsalestabletahapsektorcutoff')."' AND e.STAGE_NAME_CHAR <> 'NULL'
                                                        GROUP BY c.TOWER_NAME
                                                        ORDER BY c.TOWER_NAME ASC");
                                                }

                                                Table::create(array(
                                                    "dataSource"=> $data,
                                                    "themeBase"=>"bs4",
                                                    "showFooter"=>"bottom",
                                                    "cssClass"=>array(
                                                        "table"=>"table table-striped table-bordered"
                                                    ),
                                                    "columns"=>array(
                                                        "CLUSTER"=>array(
                                                            "label"=>"CLUSTER",
                                                            "footerText"=>"<p><b>TOTAL</b></p>"
                                                            // "type"=>"number",
                                                        ),
                                                        "JUMLAH"=>array(
                                                            "label"=>"JUMLAH",
                                                            "footer"=>"sum",
                                                            "footerText"=>"<b>@value</b>",
                                                            // "type"=>"number",
                                                        ),
                                                        "HARGA"=>array(
                                                            "label"=>"HARGA",
                                                            "type"=>"number",
                                                            "footer"=>"sum",
                                                            "footerText"=>"<b>@value</b>",
                                                            "formatValue"=>function($value, $row)
                                                            {
                                                                return number_format($value/1000000);
                                                            }
                                                        ),
                                                    ),
                                                    "clientEvents"=>array(
                                                        "rowClick"=>"function(e){
                                                            // console.log(e);
                                                            // console.log(e.rowIndex);
                                                            // console.log(e.rowData);
                                                            // console.log(e.table);
                                                            // console.log(e.rowData[0]);
                                                            // saleTahapSektorDrillDown.next({TAHAP_SEKTOR_NAME:e.rowData[0]});
                                                        }",
                                                    )
                                                ));
                                            }        
                                        ),
                                    ),
                                ));
                            ?>
                            </div>
                        </div>

                        <br /><br />

                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">
                                <span style="color: red;"><b>* Rp (Dalam Jutaan), LT (Satuan m<sup>2</sup>), LB (Satuan m<sup>2</sup>)</b></span>
                                <?php
                                    PivotTable::create(array(
                                        "dataStore"=>$report->dataStore('marketing_sales_table1'),
                                        'rowCollapseLevels' => array(0),
                                        'columnCollapseLevels' => array(0),
                                        'map' => array(
                                            'dataHeader' => function($dataField, $fieldInfo) {
                                                $v = $dataField;
                                                if ($v === 'total_rp - sum')
                                                    $v = 'Rp';
                                                else if ($v === 'total_unit - sum')
                                                    $v = 'Unit';
                                                else if ($v === 'total_lt - sum')
                                                    $v = 'LT';
                                                else if ($v === 'total_lb - sum')
                                                    $v = 'LB';
                                                else if ($v === 'growth_rp - sum')
                                                    $v = 'Growth';
                                                else if ($v === 'growth_unit - sum')
                                                    $v = 'Growth';
                                                else if ($v === 'growth_lt - sum')
                                                    $v = 'Growth';
                                                else if ($v === 'growth_lb - sum')
                                                    $v = 'Growth';
                                                return $v;
                                            },
                                            'columnHeader' => function($colHeader, $headerInfo) {
                                                $v = $colHeader;
                                                return $v;
                                            },
                                        ),
                                        'hideTotalColumn' => true,
                                        'hideSubtotalRow' => true,
                                        'hideSubtotalColumn' => true,
                                        // 'hideTotalRow' => true,
                                        'showDataHeaders' => true,
                                        'totalName' => '<strong>TOTAL</strong>',
                                    ));
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md" style="overflow-x:auto;">
                                <br />
                                <?php
                                    LegacyDrillDown::create(array(
                                        "name"=>"marketingSalesDrillDown1",
                                        "title"=>"<h5 style='text-align: right'><b>Sales (Juta)</b></h5>",
                                        "btnBack"=>array("text"=>"Back","class"=>"btn btn-primary"),
                                        "themeBase"=>"bs4",
                                        "dataSource"=>$report->dataStore('marketing_sales_chart1'),
                                        "calculate"=>array(
                                            "sum"=>"budget_sales"
                                        ),
                                        "levels"=>array(
                                            array(
                                                "groupBy"=>"tahun",
                                                "widget"=>array(ColumnChart::class,array(
                                                    "columns"=>array(
                                                        "tahun",
                                                        "budget_sales"=>array(
                                                            "label"=>"Sales",
                                                            // "prefix"=>'Rp. ',
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
                                                                return number_format($row["budget_sales"]);
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
                                                                $value = strtoupper(DateTime::createFromFormat('!m', $value)->format('M'));
                                                                return $value;
                                                            },
                                                        ),
                                                        "budget_sales"=>array(
                                                            "label"=>"Sales",
                                                            // "prefix"=>'Rp. ',
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
                                                                return number_format($row["budget_sales"]);
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
                                        ),
                                    ));
                                ?>

                                <br />
                                <span style="color: red;"><b>* Rp (Dalam Jutaan)</b></span>
                                <?php
                                    PivotTable::create(array(
                                        "dataStore"=>$report->dataStore('marketing_sales_table2'),
                                        'rowCollapseLevels' => array(0),
                                        'columnCollapseLevels' => array(0),
                                        'map' => array(
                                            'dataHeader' => function($dataField, $fieldInfo) {
                                                $v = $dataField;
                                                // if ($v === 'total_sales - sum')
                                                //     $v = 'Rp (Juta)';
                                                // else if ($v === 'total_unit - sum')
                                                //     $v = 'Unit';
                                                // else if ($v === 'total_sales_percent - sum')
                                                //     $v = 'Rp (%)';
                                                // else if ($v === 'total_unit_percent - sum')
                                                //     $v = 'Unit (%)';
                                                return $v;
                                            },
                                            'columnHeader' => function($colHeader, $headerInfo) {
                                                $v = $colHeader;
                                                $bulanStr = strtoupper(DateTime::createFromFormat('!m', $v)->format('M'));
                                                return $bulanStr;
                                            },
                                        ),
                                        // 'hideTotalColumn' => true,
                                        'hideTotalRow' => true,
                                        // 'showDataHeaders' => true,
                                        'totalName' => '<strong>TOTAL</strong>',
                                    ));
                                ?>
                                
                                <br />
                                <?php
                                    LegacyDrillDown::create(array(
                                        "name"=>"marketingSalesDrillDown2",
                                        "title"=>"<h5 style='text-align: right'><b>Sales (Juta)</b></h5>",
                                        "btnBack"=>array("text"=>"Back","class"=>"btn btn-primary"),
                                        "themeBase"=>"bs4",
                                        "dataSource"=>$report->dataStore('marketing_sales_chart2'),
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
                                                            // "prefix"=>'Rp. ',
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
                                                "title"=>"All Years"
                                            ),
                                        ),
                                    ));
                                ?>
                                
                                <br />
                                <span style="color: red;"><b>* Rp (Dalam Jutaan)</b></span>
                                <?php
                                    PivotTable::create(array(
                                        "dataStore"=>$report->dataStore('marketing_sales_table3'),
                                        'rowCollapseLevels' => array(0),
                                        'columnCollapseLevels' => array(0),
                                        'map' => array(
                                            'dataHeader' => function($dataField, $fieldInfo) {
                                                $v = $dataField;
                                                // if ($v === 'total_sales - sum')
                                                //     $v = 'Rp (Juta)';
                                                // else if ($v === 'total_unit - sum')
                                                //     $v = 'Unit';
                                                // else if ($v === 'total_sales_percent - sum')
                                                //     $v = 'Rp (%)';
                                                // else if ($v === 'total_unit_percent - sum')
                                                //     $v = 'Unit (%)';
                                                return $v;
                                            },
                                            'columnHeader' => function($colHeader, $headerInfo) {
                                                $v = $colHeader;
                                                return $v;
                                            },
                                        ),
                                        // 'hideTotalColumn' => true,
                                        'hideTotalRow' => true,
                                        // 'showDataHeaders' => true,
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
        var url = '<?php echo e(url("marketing_sales_report_excel/proyek/cut_off")); ?>';
        url = url.replace('proyek', proyek);
        url = url.replace('cut_off', cut_off);
        window.location.href = url;
    }

    function getPDF() {
        var proyek = <?php echo session('current_project') ?>;
        var cut_off = document.getElementById("txtCutOff").value;
        var url = '<?php echo e(url("marketing_sales_report_pdf/proyek/cut_off")); ?>';
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
            var url = '<?php echo e(url("marketing_sales_report/proyek/cut_off")); ?>';
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
<?php echo $__env->make('layouts.mainLayouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dashboard/public_html/metland_reporting/resources/views/Marketing/MarketingSales/marketing_sales.blade.php ENDPATH**/ ?>