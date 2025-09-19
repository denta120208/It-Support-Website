<?php 

namespace App\Http\Controllers;
use \koolreport\processes\Filter;
use \koolreport\processes\ColumnMeta;
use \koolreport\pivot\processes\Pivot;
use \koolreport\processes\Map;
use \koolreport\processes\Sort;
use \koolreport\processes\CalculatedColumn;
use \koolreport\processes\AggregatedColumn;
use \koolreport\processes\Transpose;
use \koolreport\processes\Transpose2;
use \koolreport\processes\ColumnRename;
use \koolreport\processes\Group;
use \koolreport\processes\RemoveColumn;
use \koolreport\datagrid\DataTables;
use DateTime;
use DB;

require_once dirname(__FILE__)."/../../../vendor/koolreport/core/autoload.php";

class koolreportSupportDashboardController extends \koolreport\KoolReport {
    use \koolreport\laravel\Friendship;
    use \koolreport\export\Exportable;
    use \koolreport\excel\ExcelExportable;
    use \koolreport\cloudexport\Exportable;
    
    public $project = NULL;

    function settings()
    {
        $host = env('DB_HOST');
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        return array(
            "dataSources"=>array(
                "mySqlDataSource"=>array(
                    "connectionString"=>"mysql:host=$host;dbname=$database;",
                    "username"=>$username,
                    "password"=>$password,
                    "charset"=>"utf8"
                ),
            )
        );
    }

    function setup()
    {
        $this->collectionReportDashboardProjectDataTable();
    }

    function collectionReportDashboardProjectDataTable() {
        $node = $this->src('mySqlDataSource');
        $node->query("SELECT a.PROJECT, b.PROJECT_NAME,
        (SELECT COUNT(*)FROM TRANS_TICKET WHERE PROJECT = a.PROJECT ) AS AMOUNT,
        (SELECT COUNT(*)FROM TRANS_TICKET) AS TOTAL
        FROM TRANS_TICKET AS a  
        LEFT JOIN MD_PROJECT AS b ON b.PROJECT_NO_CHAR = a.PROJECT 
        GROUP BY a.PROJECT,b.PROJECT_NAME
        ORDER BY AMOUNT DESC")
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('collection_Report_Dashboard_Project_Datatable'));
    }
}