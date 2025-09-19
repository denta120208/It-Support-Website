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
            <h1>GA Expense</h1>
        </div>
        <hr/>

        <span style="color: red;"><b>* Angka Dalam Jutaan</b></span>
        <?php
            $dateParam = DateTime::createFromFormat('Ym', $this->cut_off);
            Table::create(array(
                "dataStore"=>$report->dataStore('finance_accounting_ga_expense_table'),
                // "showFooter"=>"bottom",
                "themeBase"=>"bs4",
                "cssClass"=>array(
                    "table"=>"table table-striped table-bordered",
                    "td"=>function($row, $colName) {
                        if($colName == "GA_EXPENSE_GROUP_DASHBOARD_NAME") {
                            return "text-left bg" . str_replace('#', '', $row['COLOR']);
                        }
                        else if($colName == "ACTUAL_YTD_BACKWARD" || $colName == "ACTUAL_YTD_CURRENT" || $colName == "BUDGET_YTD_CURRENT" || $colName == "BUDGET_FY_CURRENT") {
                            return "text-right bg" . str_replace('#', '', $row['COLOR']);
                        }
                        else {
                            return "bg" . str_replace('#', '', $row['COLOR']);
                        }
                    }
                ),
                "columns" => array(
                    "GA_EXPENSE_GROUP_DASHBOARD_NAME" => [
                        "label" => "KETERANGAN",
                        "formatValue" => function($value, $row) {
                            return $value;
                        }
                    ],
                    "ACTUAL_YTD_BACKWARD" => [
                        "label" => "ACTUAL YTD " . $dateParam->format('F') . ' ' . ($dateParam->format('Y') - 1),
                        "formatValue" => function($value, $row) {
                            if($row['GA_EXPENSE_GROUP_DASHBOARD_NAME'] == '% TERHADAP NET SALES' || $row['GA_EXPENSE_GROUP_DASHBOARD_NAME'] == '% TERHADAP GROSS SALES') {
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
                            if($row['GA_EXPENSE_GROUP_DASHBOARD_NAME'] == '% TERHADAP NET SALES' || $row['GA_EXPENSE_GROUP_DASHBOARD_NAME'] == '% TERHADAP GROSS SALES') {
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
                            if($row['GA_EXPENSE_GROUP_DASHBOARD_NAME'] == '% TERHADAP NET SALES' || $row['GA_EXPENSE_GROUP_DASHBOARD_NAME'] == '% TERHADAP GROSS SALES') {
                                return $value == "0" ? "" : number_format($value,0,',','.') . "%";
                            }
                            else {
                                return $value == "0" ? "" : number_format($value / 1000000,0,',','.');
                            }
                        }
                    ],
                    "PERCENT_TO_NET_SALES" => [
                        "label" => "% TO NET SALES",
                        "formatValue" => function($value, $row) {
                            return $value == "0" ? "" : number_format($value,0,',','.') . '%';
                        }
                    ],
                    "PERCENT_TO_GROSS_SALES" => [
                        "label" => "% TO GROSS SALES",
                        "formatValue" => function($value, $row) {
                            return $value == "0" ? "" : number_format($value,0,',','.') . '%';
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
                    "BUDGET_FY_CURRENT" => [
                        "label" => "BUDGET FY " . $dateParam->format('Y'),
                        "formatValue" => function($value, $row) {
                            if($row['GA_EXPENSE_GROUP_DASHBOARD_NAME'] == '% TERHADAP NET SALES' || $row['GA_EXPENSE_GROUP_DASHBOARD_NAME'] == '% TERHADAP GROSS SALES') {
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