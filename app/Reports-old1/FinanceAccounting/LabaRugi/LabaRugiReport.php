<?php

namespace App\Reports\FinanceAccounting\LabaRugi;
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

class LabaRugiReport extends \koolreport\KoolReport {
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
        $this->laba_rugi_table();
        $this->laba_rugi_excel();
    }

    function laba_rugi_table() {
        $node = $this->src('sqlDataSources');
        $node->query("
            EXEC sp_boc_labarugi @period = :cut_off, @project = :project
        ")
        ->params(array(
            ":cut_off"=>$this->params["cut_off"],
            ":project"=>$this->params["project"]
        ))
        ->pipe(new ColumnMeta(array(
            "PL_GROUP_DASHBOARD_NAME"=>array(
                "label" => "KETERANGAN",
                "type" => "string"
            ),
            "YTD_BACKWARD"=>array(
                "label" => "YTD_BACKWARD",
                "type" => "number"
            ),
            "YTD_CURRENT"=>array(
                "label" => "YTD_CURRENT",
                "type" => "number"
            ),
            "T_YTD_CURRENT"=>array(
                "label" => "T_YTD_CURRENT",
                "type" => "number"
            ),
            "ACHIEVEMENT"=>array(
                "label" => "ACHIEVEMENT",
                "type" => "number"
            ),
            "GROWTH"=>array(
                "label" => "GROWTH",
                "type" => "number"
            ),
            "T_CURRENT"=>array(
                "label" => "T_CURRENT",
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
        ->pipe($this->dataStore('finance_accounting_laba_rugi_table'));
    }

    function laba_rugi_excel() {
        $node = $this->src('sqlDataSources');
        $node->query("
            EXEC sp_boc_labarugi @period = :cut_off, @project = :project
        ")
        ->params(array(
            ":cut_off"=>$this->params["cut_off"],
            ":project"=>$this->params["project"]
        ))
        ->pipe(new ColumnMeta(array(
            "PL_GROUP_DASHBOARD_NAME"=>array(
                "label" => "KETERANGAN",
                "type" => "string"
            ),
            "YTD_BACKWARD"=>array(
                "label" => "YTD_BACKWARD",
                "type" => "number"
            ),
            "YTD_CURRENT"=>array(
                "label" => "YTD_CURRENT",
                "type" => "number"
            ),
            "T_YTD_CURRENT"=>array(
                "label" => "T_YTD_CURRENT",
                "type" => "number"
            ),
            "ACHIEVEMENT"=>array(
                "label" => "ACHIEVEMENT",
                "type" => "number"
            ),
            "GROWTH"=>array(
                "label" => "GROWTH",
                "type" => "number"
            ),
            "T_CURRENT"=>array(
                "label" => "T_CURRENT",
                "type" => "number"
            ),
        )))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                if($row['PL_GROUP_DASHBOARD_NAME'] == 'NET PROFIT MARGIN' || $row['PL_GROUP_DASHBOARD_NAME'] == '% GROSS MARGIN TOTAL') {
                    $row['YTD_BACKWARD'] = $row['YTD_BACKWARD'] == "0" ? "" : number_format($row['YTD_BACKWARD'],0,',','.') . "%";
                    $row['YTD_CURRENT'] = $row['YTD_CURRENT'] == "0" ? "" : number_format($row['YTD_CURRENT'],0,',','.') . "%";
                    $row['T_YTD_CURRENT'] = $row['T_YTD_CURRENT'] == "0" ? "" : number_format($row['T_YTD_CURRENT'],0,',','.') . "%";
                    $row['ACHIEVEMENT'] = $row['ACHIEVEMENT'] == "0" ? "" : number_format($row['ACHIEVEMENT'],0,',','.') . "%";
                    $row['GROWTH'] = $row['GROWTH'] == "0" ? "" : number_format($row['GROWTH'],0,',','.') . "%";
                    $row['T_CURRENT'] = $row['T_CURRENT'] == "0" ? "" : number_format($row['T_CURRENT'],0,',','.') . "%";
                }
                else {
                    $row['YTD_BACKWARD'] = $row['YTD_BACKWARD'] == "0" ? "" : str_replace('.', '', number_format($row['YTD_BACKWARD']/1000000,0,',','.'));
                    $row['YTD_CURRENT'] = $row['YTD_CURRENT'] == "0" ? "" : str_replace('.', '', number_format($row['YTD_CURRENT']/1000000,0,',','.'));
                    $row['T_YTD_CURRENT'] = $row['T_YTD_CURRENT'] == "0" ? "" : str_replace('.', '', number_format($row['T_YTD_CURRENT']/1000000,0,',','.'));
                    $row['ACHIEVEMENT'] = $row['ACHIEVEMENT'] == "0" ? "" : number_format($row['ACHIEVEMENT'],0,',','.') . "%";
                    $row['GROWTH'] = $row['GROWTH'] == "0" ? "" : number_format($row['GROWTH'],0,',','.') . "%";
                    $row['T_CURRENT'] = $row['T_CURRENT'] == "0" ? "" : str_replace('.', '', number_format($row['T_CURRENT']/1000000,0,',','.'));
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
        ->pipe($this->dataStore('finance_accounting_laba_rugi_excel'));
    }
}
