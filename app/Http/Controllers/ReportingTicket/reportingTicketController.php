<?php namespace App\Http\Controllers\ReportingTicket\reportingTicketController;
namespace App\Http\Controllers\ReportingTicket;

use App\Model\ProjectModel;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Report\Support\CRUDReportSupportClass;
use App\Model\BillingCicilanModel;
use App\Model\BillingDetailModel;
use App\Http\Util\utilArray;
use Carbon\Carbon;
use App\Model;
use Session;
use View;
use App\Http\Controllers\ReportingTicket\koolreportSupportDetailController;
use App\Http\Controllers\ReportingTicket\koolreportSupportSummaryController;
use App\Http\Controllers\LogActivity\LogActivityController;
use Illuminate\Http\Request;

class reportingTicketController extends Controller
{

    public function viewListReportSummary(){
        session(['menuParentActive' => "Reporting"]);
        session(['menuSubActive' => "viewListReportSummary"]);
        $project_no = session('current_project');
        $email = session('email');
        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();
        $isAdmin=false;

        $dataReporting = 0;
        return view('ReportTicketing.viewListReportSummary')
        ->with('dataReporting', $dataReporting)
        ->with('project_no', $project_no);
            
    }

    public function filterViewListReportSummary(Request $request){
        session(['menuParentActive' => "Reporting"]);
        session(['menuSubActive' => "viewListReportSummary"]);
        $project_no = session('current_project');
        $email = session('email');
        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();
        $project_no = session('current_project');
        $project_name = session('current_project_char');
        
        $cutoff = $request->cutOff;
        $pic = $dataEmail->MD_USER_IT_ID_INT;

        $dataReporting = 1;
        $report = new koolreportSupportSummaryController(array(
            "project"=>$project_no,
            "cut_off_param"=>$cutoff,
            "pic"=>$pic
        ));
        $report->run();

        return view('ReportTicketing.viewListReportSummary')
        ->with('report', $report)
        ->with('project_name', $project_name)
        ->with('dataReporting', $dataReporting)
        ->with('cut_off_param', $cutoff)
        ->with('pic', $pic)
        ->with('project_no', $project_no);
    }

    public function excelReportSummary($cutoff, $pic) {
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
        $project_no = session('current_project');
        $isLogged = (bool) Session::get('isLogin');
        if($isLogged == FALSE) {
            return redirect()->route('login');
        }

        $report = new koolreportSupportSummaryController(array(
            "project"=>$project_no,
            "pic"=>$pic,
            "cut_off_param"=>$cutoff
        ));

        $report->run();
        $report->exportToExcel("viewListReportSummaryExcel")->toBrowser("Reporting Data Histori Summary Ticket.xlsx");
    }
    
    public function printReportSummary($cutoff, $pic){
        session(['menuParentActive' => "Reporting"]);
        session(['menuSubActive' => "viewListReportSummary"]);
        $project_no = session('current_project');
        $email = session('email');
        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();
        $project_no = session('current_project');
        $project_name = session('current_project_char');
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
        $userName = trim(session('first_name') . ' ' . session('last_name'));

        $viewDataTicket = \DB::select("SELECT a.MD_USER_IT_ID_INT, a.NAMA,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= '".$cutoff."' ) AS JUMLAH_TICKET,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 0 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= '".$cutoff."' ) AS REJECT,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 2 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= '".$cutoff."' ) AS OPEN,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 3 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= '".$cutoff."' ) AS HOLD,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 4 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= '".$cutoff."' ) AS PROGRESS ,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 5 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= '".$cutoff."' ) AS CLOSE
        FROM MD_USER_IT AS a
        WHERE a.MD_USER_IT_ID_INT = '".$pic."'");

        $viewDataTicketGrouping = \DB::select("SELECT a.MD_USER_IT_ID_INT, a.NAMA,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= '".$cutoff."' ) AS JUMLAH_TICKET,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 0 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= '".$cutoff."' ) AS REJECT,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 2 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= '".$cutoff."' ) AS OPEN,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 3 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= '".$cutoff."' ) AS HOLD,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 4 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= '".$cutoff."' ) AS PROGRESS ,
        (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 5 AND PIC = a.MD_USER_IT_ID_INT AND TRX_DATE <= '".$cutoff."' ) AS CLOSE
        FROM MD_USER_IT AS a
        WHERE a.PARENT_USER_ID = '".$pic."'");

        return view('ReportTicketing.viewListReportSummaryPrint')
        ->with('project_name', $project_name)
        ->with('cut_off_param', $cutoff)
        ->with('pic', $pic)
        ->with('viewDataTicket', $viewDataTicket)
        ->with('viewDataTicketGrouping', $viewDataTicketGrouping)
        ->with('userName', $userName)
        ->with('dateNow', $dateNow)
        ->with('project_no', $project_no);   
    }

    public function viewListReportDetail(){
        session(['menuParentActive' => "Reporting"]);
        session(['menuSubActive' => "viewListReportDetail"]);
        
        $project_no = session('current_project');
        
        $dataReporting = 0;
        
        return view('ReportTicketing.viewListReportDetail')
        ->with('project_no', $project_no)
        ->with('dataReporting', $dataReporting);
        
    }

    public function viewReportingDetail(Request $param){
        session(['menuParentActive' => "Reporting"]);
        session(['menuSubActive' => "viewListReportDetail"]);
        $email = session('email');
        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();
        $pic = $dataEmail->MD_USER_IT_ID_INT;
        $project_no = session('current_project');
        $project_name = session('current_project_char');
        
        $cut_off_param = $param->CutOff;
     
        $dataReporting = 1;
        $report = new koolreportSupportDetailController(array(
            "project"=>$project_no,
            "project_name"=>$project_name,
            "cut_off_param"=>$cut_off_param,
            "pic"=>$pic
        ));
        $report->run();
      
        return view('ReportTicketing.viewListReportDetail')
        ->with('project_no', $project_no)
        ->with('project_name', $project_name)
        ->with('report', $report)
        ->with('pic', $pic)
        ->with('dataReporting', $dataReporting)
        ->with('cut_off_param', $cut_off_param);
    }
    
    public function printReportDetail($cut_off_param, $project_no, $pic){
        session(['menuParentActive' => "Reporting"]);
        session(['menuSubActive' => "listReportingDetail"]);
        $email = session('email');
        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();
        $project_name = session('current_project_char');
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
        $userName = trim(session('first_name') . ' ' . session('last_name'));
        
        $viewDataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET,a.TRX_DATE, a.REQUEST_BY_USER,
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
        WHERE a.status NOT IN (1,5) AND a.TRX_DATE <= '".$cut_off_param."' AND a.PIC = $pic
        ORDER BY a.created_at DESC ");

        $viewDataTicketGrouping = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET,a.TRX_DATE, a.REQUEST_BY_USER,
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
        WHERE a.status NOT IN (1,5) AND a.TRX_DATE <= '".$cut_off_param."' AND c.PARENT_USER_ID = $pic
        ORDER BY a.created_at DESC ");

        return view('ReportTicketing.viewListReportDetailPrint')
        ->with('project_no', $project_no)
        ->with('project_name', $project_name)
        ->with('dateNow', $dateNow)
        ->with('userName', $userName)
        ->with('viewDataTicketGrouping', $viewDataTicketGrouping)
        ->with('viewDataTicket', $viewDataTicket)
        ->with('cut_off_param', $cut_off_param);

    }
    
    public function excelReportDetail($cut_off_param, $project_no, $pic) {
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
        $project_no = session('current_project');

        $report = new koolreportSupportDetailController(array(
            "project"=>$project_no,
            "pic"=>$pic,
            "cut_off_param"=>$cut_off_param
        ));
    
        $report->run();
        $report->exportToExcel("viewListReportDetailExcel")->toBrowser("Reporting Data Histori Detail Ticket.xlsx");
    }
}