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

class koolreportSupportDetailController extends \koolreport\KoolReport {
    use \koolreport\laravel\Friendship;
    use \koolreport\export\Exportable;
    use \koolreport\excel\ExcelExportable;
    use \koolreport\cloudexport\Exportable;

    public $cut_off_param = NULL;
    public $project_param = NULL;    
    public $pic = NULL;    

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
        $this->project_param = $this->params["project"];
        $this->pic = $this->params["pic"];
        $this->collectionReportDetailDataTable();
        $this->collectionReportDetailGroupingDataTable();
        $this->collectionvspointdetailexcel();
        $this->collectionvspointdetailgroupingexcel();

    }

    function collectionReportDetailDataTable() {
        $node = $this->src('mySqlDataSource');
        
        $node->query("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET,a.TRX_DATE, a.REQUEST_BY_USER,
        b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
        FROM TRANS_TICKET AS a 
        LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
        LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
        LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
        INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
        WHERE a.status NOT IN (1,5) AND c.PARENT_USER_ID = :pic  AND TRX_DATE <= :cut_off_param
        ORDER BY a.created_at DESC ")
        ->params(array(
            ":cut_off_param"=>$this->params["cut_off_param"],
            ":pic"=>$this->params["pic"]
        ))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('collection_Report_Detail_Datatable'));
    }

    function collectionReportDetailGroupingDataTable() {
        $node = $this->src('mySqlDataSource');
        
        $node->query("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET,a.TRX_DATE, a.REQUEST_BY_USER,
        b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
        FROM TRANS_TICKET AS a 
        LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
        LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
        LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
        INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
        WHERE a.status NOT IN (1,5) AND c.PARENT_USER_ID = :pic AND TRX_DATE <= :cut_off_param
        ORDER BY a.created_at DESC ")
        ->params(array(
            ":cut_off_param"=>$this->params["cut_off_param"],
            ":pic"=>$this->params["pic"]
        ))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('collection_Report_Detail_Grouping_Datatable'));
    }

    // EXPORT EXCEL
    function collectionvspointdetailexcel() {
        $node = $this->src('mySqlDataSource');
        $node->query("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET,a.TRX_DATE, a.REQUEST_BY_USER,
        b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME,
        CURDATE() AS curent_dates, f.close_tiket, DATEDIFF(CURDATE(), f.close_tiket) AS DIFF_DATES
        FROM TRANS_TICKET AS a 
        LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
        LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
        LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
        INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
        INNER JOIN 
            (
                SELECT TRANS_TICKET_NOCHAR,MAX(created_at) AS close_tiket
                FROM TRANS_TICKET_HISTORY
                WHERE STATUS = 5
                GROUP BY TRANS_TICKET_NOCHAR
            ) AS f ON a.TRANS_TICKET_NOCHAR = f.TRANS_TICKET_NOCHAR 
        WHERE a.status NOT IN (1,5) AND c.PARENT_USER_ID = :pic AND a.TRX_DATE <= :cut_off_param
        ORDER BY a.created_at DESC  ")
        ->params(array(
            ":cut_off_param"=>$this->params["cut_off_param"],
            ":pic"=>$this->params["pic"]
        ))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('report_ticket_detail_excel'));
    }
    function collectionvspointdetailgroupingexcel() {
        $node = $this->src('mySqlDataSource');
        $node->query("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET,a.TRX_DATE, a.REQUEST_BY_USER,
        b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME,
        CURDATE() AS curent_dates, f.close_tiket, DATEDIFF(CURDATE(), f.close_tiket) AS DIFF_DATES
        FROM TRANS_TICKET AS a 
        LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
        LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
        LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
        INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
        INNER JOIN 
            (
                SELECT TRANS_TICKET_NOCHAR,MAX(created_at) AS close_tiket
                FROM TRANS_TICKET_HISTORY
                WHERE STATUS = 5
                GROUP BY TRANS_TICKET_NOCHAR
            ) AS f ON a.TRANS_TICKET_NOCHAR = f.TRANS_TICKET_NOCHAR 
        WHERE a.status NOT IN (1,5) AND c.PARENT_USER_ID = :pic AND a.TRX_DATE <= :cut_off_param
        ORDER BY a.created_at DESC  ")
        ->params(array(
            ":cut_off_param"=>$this->params["cut_off_param"],
            ":pic"=>$this->params["pic"]
        ))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('report_ticket_detail_grouping_excel'));
    }
}