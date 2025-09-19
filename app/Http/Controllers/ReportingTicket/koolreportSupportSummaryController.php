<?php 

namespace App\Http\Controllers\ReportingTicket;
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

require_once dirname(__FILE__)."/../../../../vendor/koolreport/core/autoload.php";

class koolreportSupportSummaryController extends \koolreport\KoolReport {
    use \koolreport\laravel\Friendship;
    use \koolreport\export\Exportable;
    use \koolreport\excel\ExcelExportable;
    use \koolreport\cloudexport\Exportable;

    public $cut_off_param = NULL;  
    public $pic = NULL;  
    public $project_param = NULL;
    
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


        // return array(
        //     "dataSources" => array(
        //         "sqlDataSources"=>array(
        //             'host' => ''.$host.'',
        //             'username' => ''.$username.'',
        //             'password' => ''.$password.'',
        //             'dbname' => ''.$database.'',
        //             'class' => "\koolreport\datasources\SQLSRVDataSource"
        //         ),
        //     )
        // );
    }

    function setup()
    {
        $this->cut_off_param = $this->params["cut_off_param"];
        $this->collectionReportSummaryDataTable();     
        $this->collectionReportSummaryGroupingDataTable();        
        $this->collectionvspointsummaryexcel();        
        $this->collectionvspointsummarygroupingexcel();        
        $this->pic = $this->params["pic"];
        $this->project_param = $this->params["project"];
    }

    function collectionReportSummaryDataTable() {
        $node = $this->src('mySqlDataSource');
        $node->query("SELECT a.MD_USER_IT_ID_INT, a.NAMA,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS JUMLAH_TICKET,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 0 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS REJECT,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 2 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS OPEN,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 3 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS HOLD,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 4 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS PROGRESS ,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 5 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS CLOSE
        FROM MD_USER_IT AS a
        WHERE a.MD_USER_IT_ID_INT = :pic OR a.PARENT_USER_ID = :pic
        ORDER BY a.MD_USER_IT_ID_INT DESC")
        ->params(array(
            ":cut_off"=>$this->params["cut_off_param"],
            ":pic"=>$this->params["pic"],
        ))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('collection_Report_Summary_Datatable'));
    }
    function collectionReportSummaryGroupingDataTable() {
        $node = $this->src('mySqlDataSource');
        $node->query("SELECT a.MD_USER_IT_ID_INT, a.NAMA,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS JUMLAH_TICKET,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 0 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS REJECT,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 2 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS OPEN,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 3 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS HOLD,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 4 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS PROGRESS ,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 5 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS CLOSE
        FROM MD_USER_IT AS a
        WHERE  a.PARENT_USER_ID = :pic
        ORDER BY a.MD_USER_IT_ID_INT DESC")
        ->params(array(
            ":cut_off"=>$this->params["cut_off_param"],
            ":pic"=>$this->params["pic"],
        ))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('collection_Report_Summary_Grouping_Datatable'));
    }

    // EXPORT EXCEL
    function collectionvspointsummaryexcel() {
        $node = $this->src('mySqlDataSource');
        $node->query("SELECT a.MD_USER_IT_ID_INT, a.NAMA,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS JUMLAH_TICKET,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 0 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS REJECT,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 2 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS OPEN,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 3 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS HOLD,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 4 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS PROGRESS,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 5 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS CLOSE
        FROM MD_USER_IT AS a
        WHERE a.MD_USER_IT_ID_INT = :pic ")
        ->params(array(
            ":project"=>$this->params["project"],
            ":cut_off"=>$this->params["cut_off_param"],
            ":pic"=>$this->params["pic"],
        ))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('report_ticket_summary_excel'));
    }

    function collectionvspointsummarygroupingexcel() {
        $node = $this->src('mySqlDataSource');
        $node->query("SELECT a.MD_USER_IT_ID_INT, a.NAMA,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS JUMLAH_TICKET,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 0 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS REJECT,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 2 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS OPEN,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 3 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS HOLD,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 4 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS PROGRESS,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 5 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= :cut_off ) AS CLOSE
        FROM MD_USER_IT AS a
        WHERE a.PARENT_USER_ID = :pic ")
        ->params(array(
            ":project"=>$this->params["project"],
            ":cut_off"=>$this->params["cut_off_param"],
            ":pic"=>$this->params["pic"],
        ))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('report_ticket_summary_grouping_excel'));
    }
}