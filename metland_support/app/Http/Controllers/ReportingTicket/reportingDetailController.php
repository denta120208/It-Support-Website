<?php namespace App\Http\Controllers\ReportingTicket\reportingDetailController;
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
use App\Http\Controllers\ReportingTicket\koolreportSupportController;
use App\Http\Controllers\LogActivity\LogActivityController;
use Illuminate\Http\Request;

class reportingDetailController extends Controller
{
    public function index(){
        session(['menuParentActive' => "Ticketing"]);
        session(['menuSubActive' => "viewListReportDetail"]);
        
        $project_no = session('current_project');
    
        $dataReporting = 0;

        return view('ReportTicketing.viewListReportDetail')
        ->with('project_no', $project_no)
        ->with('dataReporting', $dataReporting);
            
    }

    public function viewReportingDetail(Request $param){
        session(['menuParentActive' => "Ticketing"]);
        session(['menuSubActive' => "viewListReportDetail"]);
        $email = session('email');
        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();
        $project_no = session('current_project');
        $project_name = session('current_project_char');

        $param1 = $param->StartDate;
        $param2 = $param->EndDate;

        $dataReporting = 1;
        $report = new koolreportSupportController(array(
            "project"=>$project_no,
            "project_name"=>$project_name,
            "start_date_param"=>$param1,
            "end_date_param"=>$param2,
            "pic"=>$dataEmail->MD_USER_IT_ID_INT
        ));
        $report->run();
 
        $dataCollectionByPoint = 1;
        return view('ReportTicketing.viewListReportDetail')
        ->with('project_no', $project_no)
        ->with('project_name', $project_name)
        ->with('report', $report)
        ->with('dataReporting', $dataReporting)
        ->with('start_date_param', $param1)
        ->with('end_date_param', $param2);
    }

    public function printDataService($StartDate, $EndDate, $project_no){
        session(['menuParentActive' => "Reporting"]);
        session(['menuSubActive' => "listReportingDetail"]);
        $email = session('email');
        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();
        $project_name = session('current_project_char');
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
        $userName = trim(session('first_name') . ' ' . session('last_name'));

        $report = new koolreportSupportController(array(
            "project"=>$project_no,
            "project_name"=>$project_name,
            "start_date_param"=>$StartDate,
            "end_date_param"=>$EndDate,
            "pic"=>$dataEmail->MD_USER_IT_ID_INT
        ));

        $report->run();
        // dd($report->dataStore('collection_Report_Detail_Datatable')->data());
        $dataCollectionPoint = NULL;

        $dataTable = $report->dataStore('collection_Report_Detail_Datatable')->data();
        $dataReporting = 1;


        return view('ReportTicketing.printDataDetail')
        ->with('project_no', $project_no)
        ->with('project_name', $project_name)
        ->with('report', $report)
        ->with('dateNow', $dateNow)
        ->with('userName', $userName)
        ->with('start_date_param', $StartDate)
        ->with('end_date_param', $EndDate)
        ->with('dataTable', $dataTable)
        ->with('dataReporting', $dataReporting);
            
    }
  
}