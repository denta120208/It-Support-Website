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

class koolreportSupportController extends \koolreport\KoolReport {
    use \koolreport\laravel\Friendship;
    use \koolreport\export\Exportable;
    use \koolreport\excel\ExcelExportable;
    use \koolreport\cloudexport\Exportable;

    public $start_date_param = NULL;
    public $end_date_param = NULL;
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
        $this->start_date_param = $this->params["start_date_param"];
        $this->end_date_param = $this->params["end_date_param"];        
        $this->project_param = $this->params["project"];
        $this->pic = $this->params["pic"];
        $this->collectionReportDetailDataTable();
        $this->collectionReportDetailPiechart();
        $this->collectionReportDetailColumnchart();
        
    }

    function collectionReportDetailDataTable() {
        $node = $this->src('mySqlDataSource');
        
        $node->query("SELECT b.DESC_CHAR, a.TRANS_TICKET_NOCHAR, a.JUDUL_TICKET, f.DESC_CHAR AS DESC_KELUHAN, d.DESC_CHAR AS DESC_APLIKASI,   
            a.created_by, a.created_at, a.updated_by, a.updated_at, c.NAMA, e.PROJECT_NAME, a.status, a.TRX_DATE
            FROM TRANS_TICKET AS a 
            LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
            LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
            LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
            INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
            LEFT JOIN MD_TYPE_KELUHAN_TICKETING AS f ON a.TYPE = f.MD_TYPE_KELUHAN_TICKETING_ID_INT
            WHERE a.status NOT IN (0,1) AND b.DESC_CHAR NOT IN ('Cancel','Not Assign') AND a.PIC = :pic  AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date 
            ORDER BY a.created_at DESC")
        ->params(array(
            ":project"=>$this->params["project"],
            ":start_date"=>$this->params["start_date_param"],
            ":end_date"=>$this->params["end_date_param"],
            ":pic"=>$this->params["pic"]

        ))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('collection_Report_Detail_Datatable'));
    }

    function collectionReportDetailPiechart() {
        $node = $this->src('mySqlDataSource');
        $node->query("SELECT a.status, b.DESC_CHAR,
            (SELECT COUNT(status) FROM TRANS_TICKET WHERE STATUS = '0' AND a.PIC = :pic  AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date) AS Cancel,
            (SELECT COUNT(status) FROM TRANS_TICKET WHERE STATUS = '1' AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date) AS NotAssign,
            (SELECT COUNT(status) FROM TRANS_TICKET WHERE STATUS = '2' AND a.PIC = :pic  AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date) AS Open,
            (SELECT COUNT(status) FROM TRANS_TICKET WHERE STATUS = '3' AND a.PIC = :pic  AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date) AS Hold,
            (SELECT COUNT(status) FROM TRANS_TICKET WHERE STATUS = '4' AND a.PIC = :pic  AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date) AS Proggres,
            (SELECT COUNT(status) FROM TRANS_TICKET WHERE STATUS = '5' AND a.PIC = :pic  AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date) AS Close
            FROM TRANS_TICKET AS a 
            LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
            WHERE a.PIC = :pic  AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date 
            ORDER BY a.created_at ASC")
        ->params(array(
            ":project"=>$this->params["project"],
            ":start_date"=>$this->params["start_date_param"],
            ":end_date"=>$this->params["end_date_param"],
            ":pic"=>$this->params["pic"]

        ))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('collection_Report_Detail_Piechart'));
    }

    function collectionReportDetailColumnchart() {
        $node = $this->src('mySqlDataSource');
        $node->query("SELECT  MONTHNAME(TRX_DATE) AS Date,
        (SELECT COUNT(status) FROM TRANS_TICKET WHERE STATUS = '0' AND a.PIC = :pic  AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date AND MONTHNAME(TRX_DATE) = MONTHNAME(a.TRX_DATE)) AS Cancel,
        (SELECT COUNT(status) FROM TRANS_TICKET WHERE STATUS = '1' AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date AND MONTHNAME(TRX_DATE) = MONTHNAME(a.TRX_DATE)) AS NotAssign ,
        (SELECT COUNT(status) FROM TRANS_TICKET WHERE STATUS = '2' AND a.PIC = :pic  AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date AND MONTHNAME(TRX_DATE) = MONTHNAME(a.TRX_DATE)) AS Open,
        (SELECT COUNT(status) FROM TRANS_TICKET WHERE STATUS = '3' AND a.PIC = :pic  AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date AND MONTHNAME(TRX_DATE) = MONTHNAME(a.TRX_DATE)) AS Hold,
        (SELECT COUNT(status) FROM TRANS_TICKET WHERE STATUS = '4' AND a.PIC = :pic  AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date AND MONTHNAME(TRX_DATE) = MONTHNAME(a.TRX_DATE)) AS Proggres,
        (SELECT COUNT(status) FROM TRANS_TICKET WHERE STATUS = '5' AND a.PIC = :pic  AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date AND MONTHNAME(TRX_DATE) = MONTHNAME(a.TRX_DATE)) AS Close
        FROM TRANS_TICKET AS a	
        WHERE a.PIC = :pic  AND a.PROJECT = :project AND a.TRX_DATE >= :start_date AND a.TRX_DATE <= :end_date 
        GROUP BY MONTHNAME(TRX_DATE)
        ORDER BY a.created_at DESC;")
        ->params(array(
            ":project"=>$this->params["project"],
            ":start_date"=>$this->params["start_date_param"],
            ":end_date"=>$this->params["end_date_param"],
            ":pic"=>$this->params["pic"]
        ))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('collection_Report_Detail_Columnchart'));
    }

    // EXPORT PDF
    function collectionvspointsummarypdf() {
        $node = $this->src('sqlDataSources');
        $node->query("
            SELECT a.KODE_STOK_UNIQUE_ID_CHAR, a.CUSTOMER_NAME_CHAR, SUM(a.PAID_BILL_AMOUNT_NUM) AS PAID_BILL_AMOUNT_NUM, SUM(a.POINT_MC_NUM) AS POINT_MC_NUM, SUM(a.POINT) AS POINT, :start_date AS START_DATE_PARAM, :end_date AS END_DATE_PARAM, :project AS PROJECT_PARAM
            FROM (
                SELECT FORMAT(a.TGL_PEMBAYARAN_DATE,'dd-MM-yyyy') as TGL_PEMBAYARAN_DATE,a.ACC_JOURNAL_NOCHAR,b.KODE_STOK_UNIQUE_ID_CHAR,b.CUSTOMER_NAME_CHAR,
                a.ACC_NO_CHAR+' - '+a.ACC_NAME_CHAR as COA,a.DESC_CHAR_PAYMENT as DESC_CHAR,
                (isnull(a.PAID_BILL_AMOUNT_NUM,0) + isnull(a.AJB_NUM,0) + isnull(a.BPHTB_NUM,0) + isnull(a.OTHERS_NUM,0) + isnull(a.DENDA_NUM,0) ) as  PAID_BILL_AMOUNT_NUM,
                a.POINT_MC_NUM,FLOOR(a.POINT_MC_NUM/50000) as POINT,c.MEMBER_MC_NOCHAR
                FROM SA_BILLINGPAYMENT as a LEFT JOIN SA_BOOKINGENTRY as b ON a.BOOKING_ENTRY_CODE_INT = b.BOOKING_ENTRY_CODE_INT
                INNER JOIN MD_CUSTOMER as c ON b.ID_CUSTOMER_CHAR = c.CUSTOMER_ID_INT
                WHERE a.APPROVE_INT = 1 
                AND b.PROJECT_NO_CHAR = :project
                AND a.TGL_PEMBAYARAN_DATE between :start_date AND :end_date
                AND a.POINT_MC_NUM > 0
            ) AS a GROUP BY a.KODE_STOK_UNIQUE_ID_CHAR, a.CUSTOMER_NAME_CHAR
        ")
        ->params(array(
            ":project"=>$this->params["project"],
            ":start_date"=>$this->params["start_date_param"],
            ":end_date"=>$this->params["end_date_param"]
        ))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('collection_vs_point_summary_pdf'));
    }

    // EXPORT EXCEL
    function collectionvspointsummaryexcel() {
        $node = $this->src('sqlDataSources');
        $node->query("
            SELECT a.KODE_STOK_UNIQUE_ID_CHAR, a.CUSTOMER_NAME_CHAR, SUM(a.PAID_BILL_AMOUNT_NUM) AS PAID_BILL_AMOUNT_NUM, SUM(a.POINT_MC_NUM) AS POINT_MC_NUM, SUM(a.POINT) AS POINT, :start_date AS START_DATE_PARAM, :end_date AS END_DATE_PARAM, :project AS PROJECT_PARAM
            FROM (
                SELECT FORMAT(a.TGL_PEMBAYARAN_DATE,'dd-MM-yyyy') as TGL_PEMBAYARAN_DATE,a.ACC_JOURNAL_NOCHAR,b.KODE_STOK_UNIQUE_ID_CHAR,b.CUSTOMER_NAME_CHAR,
                a.ACC_NO_CHAR+' - '+a.ACC_NAME_CHAR as COA,a.DESC_CHAR_PAYMENT as DESC_CHAR,
                (isnull(a.PAID_BILL_AMOUNT_NUM,0) + isnull(a.AJB_NUM,0) + isnull(a.BPHTB_NUM,0) + isnull(a.OTHERS_NUM,0) + isnull(a.DENDA_NUM,0) ) as  PAID_BILL_AMOUNT_NUM,
                a.POINT_MC_NUM,FLOOR(a.POINT_MC_NUM/50000) as POINT,c.MEMBER_MC_NOCHAR
                FROM SA_BILLINGPAYMENT as a LEFT JOIN SA_BOOKINGENTRY as b ON a.BOOKING_ENTRY_CODE_INT = b.BOOKING_ENTRY_CODE_INT
                INNER JOIN MD_CUSTOMER as c ON b.ID_CUSTOMER_CHAR = c.CUSTOMER_ID_INT
                WHERE a.APPROVE_INT = 1 
                AND b.PROJECT_NO_CHAR = :project
                AND a.TGL_PEMBAYARAN_DATE between :start_date AND :end_date
                AND a.POINT_MC_NUM > 0
            ) AS a GROUP BY a.KODE_STOK_UNIQUE_ID_CHAR, a.CUSTOMER_NAME_CHAR
        ")
        ->params(array(
            ":project"=>$this->params["project"],
            ":start_date"=>$this->params["start_date_param"],
            ":end_date"=>$this->params["end_date_param"]
        ))
        ->pipe(new Map(array(
            '{value}' => function($row, $metaData) {
                return array($row);
            },
        )))
        ->pipe($this->dataStore('collection_vs_point_summary_excel'));
    }
}