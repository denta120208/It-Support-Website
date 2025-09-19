<?php

namespace App\Reports\FinanceAccounting\GOP;
use \koolreport\processes\Filter;
use \koolreport\processes\ColumnMeta;
use \koolreport\pivot\processes\Pivot;
use \koolreport\processes\Map;
use \koolreport\processes\Sort;
use \koolreport\processes\CalculatedColumn;
use \koolreport\processes\AggregatedColumn;
use \koolreport\datagrid\DataTables;
use \koolreport\widgets\koolphp\Table;
use DateTime;
use DB;

require_once dirname(__FILE__)."/../../../../vendor/koolreport/core/autoload.php";

class GOPReport extends \koolreport\KoolReport {
    use \koolreport\laravel\Friendship;
    use \koolreport\export\Exportable;
    use \koolreport\excel\ExcelExportable;

    public $cut_off = NULL;
    public $project = NULL;

    function settings()
    {
        $host = env('DB_HOST');
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        return array(
            "dataSources" => array(
                "sqlDataSources"=>array(
                    'host' => ''.$host.'',
                    'username' => ''.$username.'',
                    'password' => ''.$password.'',
                    'dbname' => ''.$database.'',
                    'class' => "\koolreport\datasources\SQLSRVDataSource"
                ), 
            )
        );
    }

    function setup()
    {
        $this->cut_off = $this->params["cut_off"];
        $this->project = $this->params["project"];
        $this->gop_table();
        $this->gop_excel();
    }

    function gop_table() {
        $node = $this->src('sqlDataSources');
        $node->query("
            EXEC sp_boc_gop @period = :cut_off, @project = :project
        ")
        ->params(array(
            ":cut_off"=>$this->params["cut_off"],
            ":project"=>$this->params["project"]
        ))
        ->pipe(new ColumnMeta(array(
            "GOP_GROUP_DASHBOARD_NAME"=>array(
                "label" => "KETERANGAN",
                "type" => "string"
            ),
            "ACTUAL_YTD_BACKWARD"=>array(
                "label" => "ACTUAL_YTD_BACKWARD",
                "type" => "number"
            ),
            "CURRENT_MONTH_RES"=>array(
                "label" => "CURRENT_MONTH_RES",
                "type" => "number"
            ),
            "CURRENT_MONTH_SPORT_CLUB"=>array(
                "label" => "CURRENT_MONTH_SPORT_CLUB",
                "type" => "number"
            ),
            "TOTAL_CURRENT_MONTH"=>array(
                "label" => "TOTAL_CURRENT_MONTH",
                "type" => "number"
            ),
            "ACTUAL_YTD_CURRENT"=>array(
                "label" => "ACTUAL_YTD_CURRENT",
                "type" => "number"
            ),
            "BUDGET_YTD_CURRENT"=>array(
                "label" => "BUDGET_YTD_CURRENT",
                "type" => "number"
            ),
            "ACHIEVEMENT"=>array(
                "label" => "ACHIEVEMENT UP TO",
                "type" => "number"
            ),
            "GROWTH"=>array(
                "label" => "GROWTH",
                "type" => "number"
            ),
            "BUDGET_FY_CURRENT"=>array(
                "label" => "BUDGET_FY_CURRENT",
                "type" => "number"
            ),
        )))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                // $row['TYPE_RETENSI'] = strtoupper($row['TYPE_RETENSI']);
                // $row['TITLE'] = "<p style='text-align: left;'>".$row['TITLE']."</p>";
                // if($row['TITLE'] == "TOTAL") {
                //     $row['TITLE'] = "<p><b>".$row['TITLE']."</b></p>";
                //     $row['COLLECTED_BACKWARD1'] = "<p><b>".number_format($row['COLLECTED_BACKWARD1'])."</b></p>";
                //     $row['COLLECTED_CURRENT'] = "<p><b>".number_format($row['COLLECTED_CURRENT'])."</b></p>";
                //     $row['TOTAL_AGING'] = "<p><b>".$row['TOTAL_AGING']."</b></p>";
                //     $row['TARGET_COLLECTED_CURRENT'] = "<p><b>".number_format($row['TARGET_COLLECTED_CURRENT'])."</b></p>";
                //     $row['COLLECTABILITY_CURRENT'] = "<p><b>".$row['COLLECTABILITY_CURRENT']."%</b></p>";
                // }
                // else {
                //     $row['TITLE'] = $row['TITLE'];
                //     $row['COLLECTED_BACKWARD1'] = number_format($row['COLLECTED_BACKWARD1']);
                //     $row['COLLECTED_CURRENT'] = number_format($row['COLLECTED_CURRENT']);
                //     $row['TOTAL_AGING'] = $row['TOTAL_AGING'];
                //     $row['TARGET_COLLECTED_CURRENT'] = number_format($row['TARGET_COLLECTED_CURRENT']);
                //     $row['COLLECTABILITY_CURRENT'] = $row['COLLECTABILITY_CURRENT']."%";
                // }
                // $row['HARGA_JUAL'] = $row['HARGA_JUAL'] / 1000000;
                return array($row);
            },
        )))
        ->pipe($this->dataStore('finance_accounting_gop_table'));
    }

    function gop_excel() {
        $node = $this->src('sqlDataSources');
        $node->query("
            EXEC sp_boc_gop @period = :cut_off, @project = :project
        ")
        ->params(array(
            ":cut_off"=>$this->params["cut_off"],
            ":project"=>$this->params["project"]
        ))
        ->pipe(new ColumnMeta(array(
            "GOP_GROUP_DASHBOARD_NAME"=>array(
                "label" => "KETERANGAN",
                "type" => "string"
            ),
            "ACTUAL_YTD_BACKWARD"=>array(
                "label" => "ACTUAL_YTD_BACKWARD",
                "type" => "number"
            ),
            "CURRENT_MONTH_RES"=>array(
                "label" => "CURRENT_MONTH_RES",
                "type" => "number"
            ),
            "CURRENT_MONTH_SPORT_CLUB"=>array(
                "label" => "CURRENT_MONTH_SPORT_CLUB",
                "type" => "number"
            ),
            "TOTAL_CURRENT_MONTH"=>array(
                "label" => "TOTAL_CURRENT_MONTH",
                "type" => "number"
            ),
            "ACTUAL_YTD_CURRENT"=>array(
                "label" => "ACTUAL_YTD_CURRENT",
                "type" => "number"
            ),
            "BUDGET_YTD_CURRENT"=>array(
                "label" => "BUDGET_YTD_CURRENT",
                "type" => "number"
            ),
            "ACHIEVEMENT"=>array(
                "label" => "ACHIEVEMENT UP TO",
                "type" => "number"
            ),
            "GROWTH"=>array(
                "label" => "GROWTH",
                "type" => "number"
            ),
            "BUDGET_FY_CURRENT"=>array(
                "label" => "BUDGET_FY_CURRENT",
                "type" => "number"
            ),
        )))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                if($row['GOP_GROUP_DASHBOARD_NAME'] == '%' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TOTAL' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% GA EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == '% MKT EXPENSE' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN TANAH' || $row['GOP_GROUP_DASHBOARD_NAME'] == 'GROSS MARGIN BANGUNAN') {
                    $row['ACTUAL_YTD_BACKWARD'] = $row['ACTUAL_YTD_BACKWARD'] == "0" ? "" : number_format($row['ACTUAL_YTD_BACKWARD'],0,',','.') . "%";
                    $row['CURRENT_MONTH_RES'] = $row['CURRENT_MONTH_RES'] == "0" ? "" : number_format($row['CURRENT_MONTH_RES'],0,',','.') . "%";
                    $row['CURRENT_MONTH_SPORT_CLUB'] = $row['CURRENT_MONTH_SPORT_CLUB'] == "0" ? "" : number_format($row['CURRENT_MONTH_SPORT_CLUB'],0,',','.') . "%";
                    $row['TOTAL_CURRENT_MONTH'] = $row['TOTAL_CURRENT_MONTH'] == "0" ? "" : number_format($row['TOTAL_CURRENT_MONTH'],0,',','.') . "%";
                    $row['ACTUAL_YTD_CURRENT'] = $row['ACTUAL_YTD_CURRENT'] == "0" ? "" : number_format($row['ACTUAL_YTD_CURRENT'],0,',','.') . "%";
                    $row['BUDGET_YTD_CURRENT'] = $row['BUDGET_YTD_CURRENT'] == "0" ? "" : number_format($row['BUDGET_YTD_CURRENT'],0,',','.') . "%";
                    $row['ACHIEVEMENT'] = $row['ACHIEVEMENT'] == "0" ? "" : number_format($row['ACHIEVEMENT'],0,',','.') . "%";
                    $row['GROWTH'] = $row['GROWTH'] == "0" ? "" : number_format($row['GROWTH'],0,',','.') . "%";
                    $row['BUDGET_FY_CURRENT'] = $row['BUDGET_FY_CURRENT'] == "0" ? "" : number_format($row['BUDGET_FY_CURRENT'],0,',','.') . "%";
                }
                else {
                    $row['ACTUAL_YTD_BACKWARD'] = $row['ACTUAL_YTD_BACKWARD'] == "0" ? "" : str_replace('.', '', number_format($row['ACTUAL_YTD_BACKWARD']/1000000,0,',','.'));
                    $row['CURRENT_MONTH_RES'] = $row['CURRENT_MONTH_RES'] == "0" ? "" : str_replace('.', '', number_format($row['CURRENT_MONTH_RES']/1000000,0,',','.'));
                    $row['CURRENT_MONTH_SPORT_CLUB'] = $row['CURRENT_MONTH_SPORT_CLUB'] == "0" ? "" : str_replace('.', '', number_format($row['CURRENT_MONTH_SPORT_CLUB']/1000000,0,',','.'));
                    $row['TOTAL_CURRENT_MONTH'] = $row['TOTAL_CURRENT_MONTH'] == "0" ? "" : str_replace('.', '', number_format($row['TOTAL_CURRENT_MONTH']/1000000,0,',','.'));
                    $row['ACTUAL_YTD_CURRENT'] = $row['ACTUAL_YTD_CURRENT'] == "0" ? "" : str_replace('.', '', number_format($row['ACTUAL_YTD_CURRENT']/1000000,0,',','.'));
                    $row['BUDGET_YTD_CURRENT'] = $row['BUDGET_YTD_CURRENT'] == "0" ? "" : str_replace('.', '', number_format($row['BUDGET_YTD_CURRENT']/1000000,0,',','.'));
                    $row['ACHIEVEMENT'] = $row['ACHIEVEMENT'] == "0" ? "" : number_format($row['ACHIEVEMENT'],0,',','.') . "%";
                    $row['GROWTH'] = $row['GROWTH'] == "0" ? "" : number_format($row['GROWTH'],0,',','.') . "%";
                    $row['BUDGET_FY_CURRENT'] = $row['BUDGET_FY_CURRENT'] == "0" ? "" : str_replace('.', '', number_format($row['BUDGET_FY_CURRENT']/1000000,0,',','.'));
                }

                // $row['TYPE_RETENSI'] = strtoupper($row['TYPE_RETENSI']);
                // $row['TITLE'] = "<p style='text-align: left;'>".$row['TITLE']."</p>";
                // if($row['TITLE'] == "TOTAL") {
                //     $row['TITLE'] = "<p><b>".$row['TITLE']."</b></p>";
                //     $row['COLLECTED_BACKWARD1'] = "<p><b>".number_format($row['COLLECTED_BACKWARD1'])."</b></p>";
                //     $row['COLLECTED_CURRENT'] = "<p><b>".number_format($row['COLLECTED_CURRENT'])."</b></p>";
                //     $row['TOTAL_AGING'] = "<p><b>".$row['TOTAL_AGING']."</b></p>";
                //     $row['TARGET_COLLECTED_CURRENT'] = "<p><b>".number_format($row['TARGET_COLLECTED_CURRENT'])."</b></p>";
                //     $row['COLLECTABILITY_CURRENT'] = "<p><b>".$row['COLLECTABILITY_CURRENT']."%</b></p>";
                // }
                // else {
                //     $row['TITLE'] = $row['TITLE'];
                //     $row['COLLECTED_BACKWARD1'] = number_format($row['COLLECTED_BACKWARD1']);
                //     $row['COLLECTED_CURRENT'] = number_format($row['COLLECTED_CURRENT']);
                //     $row['TOTAL_AGING'] = $row['TOTAL_AGING'];
                //     $row['TARGET_COLLECTED_CURRENT'] = number_format($row['TARGET_COLLECTED_CURRENT']);
                //     $row['COLLECTABILITY_CURRENT'] = $row['COLLECTABILITY_CURRENT']."%";
                // }
                // $row['HARGA_JUAL'] = $row['HARGA_JUAL'] / 1000000;
                return array($row);
            },
        )))
        ->pipe($this->dataStore('finance_accounting_gop_excel'));
    }
}
