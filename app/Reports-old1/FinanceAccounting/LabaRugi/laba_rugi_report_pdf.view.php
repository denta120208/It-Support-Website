<?php 
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\ColumnChart;
?>
<html>
    <style>
        .pivot-column-header, .pivot-data-header {
            font-weight: bold;
            text-align: center;
        }
        .table {
            text-align: center;
            min-width: 100%;
            width: 100%;
        }
        th {
            background-color: #081c5c;
            color: white;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 1%;
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
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
    </style>

    <body style="margin:0.5in 1in 0.5in 1in">
        <div class="page-header" style="text-align:right"><i>Finance Accounting Report</i></div>
        <div class="page-footer" style="text-align:right">{pageNum}</div>
        <div class="text-center">
            <h1>P & L</h1>
        </div>
        <hr/>

        <span style="color: red;"><b>* Angka Dalam Jutaan</b></span>
        <?php
            $dateParam = DateTime::createFromFormat('Ym', $this->cut_off);
            Table::create(array(
                "dataStore"=>$report->dataStore('finance_accounting_laba_rugi_table'),
                // "showFooter"=>"bottom",
                "themeBase"=>"bs4",
                "cssClass"=>array(
                    "table"=>"table table-striped table-bordered",
                    "td"=>function($row, $colName) {
                        if($colName == "PL_GROUP_DASHBOARD_NAME") {
                            return "text-left bg" . str_replace('#', '', $row['COLOR']);
                        }
                        else if($colName == "YTD_BACKWARD" || $colName == "YTD_CURRENT" || $colName == "T_YTD_CURRENT" || $colName == "T_CURRENT") {
                            return "text-right bg" . str_replace('#', '', $row['COLOR']);
                        }
                        else {
                            return "bg" . str_replace('#', '', $row['COLOR']);
                        }
                    }
                ),
                "columns" => array(
                    "PL_GROUP_DASHBOARD_NAME" => [
                        "label" => "KETERANGAN",
                        "formatValue" => function($value, $row) {
                            return $value;
                        }
                    ],
                    "YTD_BACKWARD" => [
                        "label" => "YTD " . $dateParam->format('F') . ' ' . ($dateParam->format('Y') - 1),
                        "formatValue" => function($value, $row) {
                            if($row['PL_GROUP_DASHBOARD_NAME'] == 'NET PROFIT MARGIN' || $row['PL_GROUP_DASHBOARD_NAME'] == '% GROSS MARGIN TOTAL') {
                                return $value == "0" ? "" : number_format($value,0,',','.') . "%";
                            }
                            else {
                                return $value == "0" ? "" : number_format($value / 1000000,0,',','.');
                            }
                        }
                    ],
                    "YTD_CURRENT" => [
                        "label" => "YTD " . $dateParam->format('F Y'),
                        "formatValue" => function($value, $row) {
                            if($row['PL_GROUP_DASHBOARD_NAME'] == 'NET PROFIT MARGIN' || $row['PL_GROUP_DASHBOARD_NAME'] == '% GROSS MARGIN TOTAL') {
                                return $value == "0" ? "" : number_format($value,0,',','.') . "%";
                            }
                            else {
                                return $value == "0" ? "" : number_format($value / 1000000,0,',','.');
                            }
                        }
                    ],
                    "T_YTD_CURRENT" => [
                        "label" => "T YTD " . $dateParam->format('F Y'),
                        "formatValue" => function($value, $row) {
                            if($row['PL_GROUP_DASHBOARD_NAME'] == 'NET PROFIT MARGIN' || $row['PL_GROUP_DASHBOARD_NAME'] == '% GROSS MARGIN TOTAL') {
                                return $value == "0" ? "" : number_format($value,0,',','.') . "%";
                            }
                            else {
                                return $value == "0" ? "" : number_format($value / 1000000,0,',','.');
                            }
                        }
                    ],
                    "ACHIEVEMENT" => [
                        "label" => "ACHIEVEMENT",
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
                    "T_CURRENT" => [
                        "label" => "T " . $dateParam->format('Y') . ' FY',
                        "formatValue" => function($value, $row) {
                            if($row['PL_GROUP_DASHBOARD_NAME'] == 'NET PROFIT MARGIN' || $row['PL_GROUP_DASHBOARD_NAME'] == '% GROSS MARGIN TOTAL') {
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
    </body>
</html>