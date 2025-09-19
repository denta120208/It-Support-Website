<?php

namespace App\Http\Controllers;
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
use App\Http\Controllers\koolreportSupportDashboardController;
use App\Http\Controllers\LogActivity\LogActivityController;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        session()->forget('menuParentActive');
        session()->forget('menuSubActive');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->__construct();
        session(['menuParentActive' => "dashboard"]);

        $project_no = session('current_project');
        $email = session('email');
        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();

        $isAdmin=false;

        if($dataEmail){
            $isAdmin=true;
        }

        if($isAdmin == true){
            $datatable3 = \DB::select("SELECT b.PROJECT_NAME, b.PROJECT_NO_CHAR,
            (SELECT COUNT(*)FROM TRANS_TICKET WHERE TYPE = 1 AND PROJECT = a.PROJECT) AS KOMPLAIN,
            (SELECT COUNT(*)FROM TRANS_TICKET WHERE TYPE = 2 AND PROJECT = a.PROJECT) AS PERMINTAAN,
            (SELECT COUNT(*)FROM TRANS_TICKET WHERE TYPE = 3 AND PROJECT = a.PROJECT) AS INFORMASI,
            (SELECT COUNT(*)FROM TRANS_TICKET WHERE PROJECT = a.PROJECT ) AS AMOUNT
            FROM TRANS_TICKET AS a
            LEFT JOIN MD_PROJECT AS b ON b.PROJECT_NO_CHAR = a.PROJECT
            LEFT JOIN MD_TYPE_KELUHAN_TICKETING AS c ON c.MD_TYPE_KELUHAN_TICKETING_ID_INT = a.TYPE
            GROUP BY a.PROJECT, b.PROJECT_NAME
            ORDER BY AMOUNT DESC");

            $listTicketByPic = \DB::select("SELECT a.MD_USER_IT_ID_INT, a.NAMA,
            (SELECT COUNT(*) FROM TRANS_TICKET WHERE PIC = a.MD_USER_IT_ID_INT ) AS JUMLAH_TICKET,
            (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 0 AND PIC = a.MD_USER_IT_ID_INT ) AS REJECT,
            (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 2 AND PIC = a.MD_USER_IT_ID_INT ) AS OPEN,
            (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 3 AND PIC = a.MD_USER_IT_ID_INT ) AS HOLD,
            (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 4 AND PIC = a.MD_USER_IT_ID_INT ) AS PROGRESS,
            (SELECT COUNT(*) FROM TRANS_TICKET WHERE STATUS = 5 AND PIC = a.MD_USER_IT_ID_INT ) AS CLOSE
            FROM MD_USER_IT AS a");

            $totalTicket = \DB::select("SELECT COUNT(*) as TOTAL_TICKET
            FROM TRANS_TICKET ");
        }else{
            $datatable3 = null;
            $listTicketByPic = null;
            $totalTicket = null;
        }
        $report = new koolreportSupportDashboardController(array(
  
        ));
        $report->run();
        
        return view("home",compact('isAdmin'))
        ->with('report', $report)
        ->with('listTicketByPic', $listTicketByPic)
        ->with('datatable3', $datatable3)
        ->with('dataEmail', $dataEmail)
        ->with('totalTicket', $totalTicket)
        ->with('project_no', $project_no);
    }
    
    public function dashboardType(Request $request){
        $project_no = session('current_project');
        $email = session('email');
        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();

        $columns = array(
            0 =>'TRANS_TICKET_NOCHAR',
            1 =>'REQUEST_BY_USER',
            2 =>'PROJECT_NAME',
            3 =>'TRX_DATE',
            4 =>'JUDUL_TICKET',
            5 =>'created_by',
            6 =>'NAMA',
            7 =>'DESC_STATUS'
        );

        $totalData = \DB::table('TRANS_TICKET')->where('PROJECT', $request->project)->where('TYPE', $request->type)->count();

        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $reqProject = $request -> project;
        $reqType = $request ->type;

        if(empty($request->input('search.value'))) 
        {
            $posts = \DB::table('TRANS_TICKET AS a')
                ->selectRaw('a.TRANS_TICKET_NOCHAR, a.status, a.PIC, a.REQUEST_BY_USER, b.DESC_CHAR, a.TRX_DATE, a.JUDUL_TICKET, a.created_by, c.NAMA, d.DESC_CHAR AS DESC_STATUS, f.PROJECT_NAME')
                ->join('MD_TYPE_KELUHAN_TICKETING AS b', 'b.MD_TYPE_KELUHAN_TICKETING_ID_INT', '=', 'a.TYPE')
                ->join('MD_USER_IT AS c', 'c.MD_USER_IT_ID_INT', '=', 'a.PIC')
                ->join('TRANS_TICKET_STATUS AS d', 'd.TRANS_TICKET_STATUS_ID_INT', '=', 'a.status')
                ->join('MD_PROJECT AS f', 'f.PROJECT_NO_CHAR', '=', 'a.PROJECT')
                ->where('a.PROJECT', $request -> project)
                ->where('a.TYPE', $request -> type)
                ->offset($start)
                ->limit($limit)
                ->orderBy('a.TRX_DATE', 'DESC')
                ->orderBy('a.status', 'ASC')
                ->orderBy($order,$dir)
                ->get();

        }else{
            $search = $request->input('search.value');
            $posts =  \DB::table('TRANS_TICKET AS a')
                        ->selectRaw('a.TRANS_TICKET_NOCHAR, a.status, a.PIC, a.REQUEST_BY_USER, b.DESC_CHAR, a.TRX_DATE, a.JUDUL_TICKET, a.created_by, c.NAMA, d.DESC_CHAR AS DESC_STATUS, f.PROJECT_NAME')
                        ->join('MD_TYPE_KELUHAN_TICKETING AS b', 'b.MD_TYPE_KELUHAN_TICKETING_ID_INT', '=', 'a.TYPE')
                        ->join('MD_USER_IT AS c', 'c.MD_USER_IT_ID_INT', '=', 'a.PIC')
                        ->join('TRANS_TICKET_STATUS AS d', 'd.TRANS_TICKET_STATUS_ID_INT', '=', 'a.status')
                        ->join('MD_PROJECT AS f', 'f.PROJECT_NO_CHAR', '=', 'a.PROJECT')
                        ->where(function ($query) use ($project_no, $search, $reqProject, $reqType) {
                            $query->where('a.PROJECT', $reqProject)
                            ->where('a.TRANS_TICKET_NOCHAR', 'LIKE', "%{$search}%")
                            ->where('a.TYPE', $reqType);
                        })
                        ->orWhere(function ($query) use ($project_no, $search, $reqProject, $reqType) {
                            $query->where('a.PROJECT', $reqProject)
                            ->where('a.REQUEST_BY_USER','LIKE', "%{$search}%")
                            ->where('a.TYPE', $reqType);
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->orderBy('a.TRX_DATE', 'DESC')
                        ->orderBy('a.status', 'ASC')
                        ->get();

            $totalFiltered = \DB::table('TRANS_TICKET AS a')
                            ->selectRaw('a.TRANS_TICKET_NOCHAR, a.status, a.PIC, a.REQUEST_BY_USER, b.DESC_CHAR, a.TRX_DATE, a.JUDUL_TICKET, a.created_by, c.NAMA, d.DESC_CHAR AS DESC_STATUS, f.PROJECT_NAME')
                            ->join('MD_TYPE_KELUHAN_TICKETING AS b', 'b.MD_TYPE_KELUHAN_TICKETING_ID_INT', '=', 'a.TYPE')
                            ->join('MD_USER_IT AS c', 'c.MD_USER_IT_ID_INT', '=', 'a.PIC')
                            ->join('TRANS_TICKET_STATUS AS d', 'd.TRANS_TICKET_STATUS_ID_INT', '=', 'a.status')
                            ->join('MD_PROJECT AS f', 'f.PROJECT_NO_CHAR', '=', 'a.PROJECT')
                            ->where(function ($query) use ($project_no, $search, $reqProject, $reqType) {
                                $query->where('a.PROJECT', $reqProject)
                                ->where('a.TYPE', $reqType)
                                ->where('a.TRANS_TICKET_NOCHAR', 'LIKE', "%{$search}%");
                            })
                            ->orWhere(function ($query) use ($project_no, $search, $reqProject, $reqType) {
                                $query->where('a.PROJECT', $reqProject)
                                ->where('a.TYPE', $reqType)
                                ->where('a.REQUEST_BY_USER', 'LIKE', "%{$search}%");
                            })
                            ->count();
        }

        $data = array();

        if(!empty($posts))
        {
            foreach ($posts as $post)   
            {
                if($post -> status == 5){
                    $views = route('viewCloseTicketing', base64_encode($post->TRANS_TICKET_NOCHAR));
                }else{
                    $views = route('viewDataTicketing', base64_encode($post->TRANS_TICKET_NOCHAR));
                }
                if($dataEmail->MD_USER_IT_ID_INT == $post -> PIC){
                    $VIEW = $nestedData['VIEW'] = "<a href='{$views}' title='View' class='btn bg-gradient-primary btn-sm'>View</a>";
                }else{
                    $VIEW = $nestedData['VIEW'] = "<a href='javascript:void(0)' title='View' class='btn bg-gradient-default btn-sm'>View</a>";
                }
                
                $nestedData['TRANS_TICKET_NOCHAR'] = $post->TRANS_TICKET_NOCHAR;
                $nestedData['REQUEST_BY_USER'] = $post->REQUEST_BY_USER;
                $nestedData['PROJECT_NAME'] = $post->PROJECT_NAME;
                $nestedData['TRX_DATE'] = $post->TRX_DATE;
                $nestedData['JUDUL_TICKET'] = $post->JUDUL_TICKET;
                $nestedData['created_by'] = $post->created_by;
                $nestedData['NAMA'] = $post->NAMA;
                $nestedData['DESC_STATUS'] = $post->DESC_STATUS;
                $VIEW;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        return json_encode($json_data);
    }
   
    public function dashboardPic(Request $request){
        $project_no = session('current_project');
        $email = session('email');
        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();
        
        $columns = array(
            0 =>'TRANS_TICKET_NOCHAR',
            1 =>'REQUEST_BY_USER',
            2 =>'PROJECT_NAME',
            3 =>'TRX_DATE',
            4 =>'JUDUL_TICKET',
            5 =>'created_by'
        );

        $totalData = \DB::table('TRANS_TICKET')->where('PIC', $request -> pic)->where('status', $request->status)->count();

        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $reqPic = $request -> pic;
        $reqStatus = $request ->status;

        if(empty($request->input('search.value'))) 
        {
            $posts = \DB::table('TRANS_TICKET AS a')
            ->selectRaw('a.TRANS_TICKET_NOCHAR, a.status, a.PIC, a.REQUEST_BY_USER, b.DESC_CHAR, a.TRX_DATE, a.JUDUL_TICKET, a.created_by, c.PROJECT_NAME')
            ->join('MD_TYPE_KELUHAN_TICKETING AS b', 'b.MD_TYPE_KELUHAN_TICKETING_ID_INT', '=', 'a.TYPE')
            ->join('MD_PROJECT AS c', 'c.PROJECT_NO_CHAR', '=', 'a.PROJECT')
            ->where('a.PIC', $request -> pic)
            ->where('a.status', $request -> status)
            ->offset($start)
            ->limit($limit)
            ->orderBy('a.TRX_DATE', 'DESC')
            ->orderBy('a.status', 'ASC')
            ->orderBy($order,$dir)
            ->get();
        }else{
            $search = $request->input('search.value');
            $posts =  \DB::table('TRANS_TICKET AS a')
                        ->selectRaw('a.TRANS_TICKET_NOCHAR, a.status, a.PIC, a.REQUEST_BY_USER, b.DESC_CHAR, a.TRX_DATE, a.JUDUL_TICKET, a.created_by, c.PROJECT_NAME')
                        ->join('MD_TYPE_KELUHAN_TICKETING AS b', 'b.MD_TYPE_KELUHAN_TICKETING_ID_INT', '=', 'a.TYPE')
                        ->join('MD_PROJECT AS c', 'c.PROJECT_NO_CHAR', '=', 'a.PROJECT')
                        ->where(function ($query) use ($project_no, $search, $reqPic, $reqStatus) {
                            $query->where('a.PIC', $reqPic)
                            ->where('a.status', $reqStatus)
                            ->where('a.TRANS_TICKET_NOCHAR', 'LIKE', "%{$search}%");
                        })
                        ->orWhere(function ($query) use ($project_no, $search, $reqPic, $reqStatus) {
                            $query->where('a.PIC', $reqPic)
                            ->where('a.status', $reqStatus)
                            ->where('a.REQUEST_BY_USER', 'LIKE', "%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy('a.TRX_DATE', 'DESC')
                        ->orderBy('a.status', 'ASC')
                        ->orderBy($order,$dir)
                        ->get();

        $totalFiltered = \DB::table('TRANS_TICKET AS a')
                        ->selectRaw('a.TRANS_TICKET_NOCHAR, a.status, a.PIC, a.REQUEST_BY_USER, b.DESC_CHAR, a.TRX_DATE, a.JUDUL_TICKET, a.created_by, c.NAMA, d.DESC_CHAR AS DESC_STATUS, f.PROJECT_NAME')
                        ->join('MD_TYPE_KELUHAN_TICKETING AS b', 'b.MD_TYPE_KELUHAN_TICKETING_ID_INT', '=', 'a.TYPE')
                        ->join('MD_USER_IT AS c', 'c.MD_USER_IT_ID_INT', '=', 'a.PIC')
                        ->join('TRANS_TICKET_STATUS AS d', 'd.TRANS_TICKET_STATUS_ID_INT', '=', 'a.status')
                        ->join('MD_PROJECT AS f', 'f.PROJECT_NO_CHAR', '=', 'a.PROJECT')
                        ->where(function ($query) use ($project_no, $search, $reqPic, $reqStatus) {
                            $query->where('a.PIC', $reqPic)
                            ->where('a.status', $reqStatus)
                            ->where('a.TRANS_TICKET_NOCHAR', 'LIKE', "%{$search}%");
                        })
                        ->orWhere(function ($query) use ($project_no, $search, $reqPic, $reqStatus) {
                            $query->where('a.PIC', $reqPic)
                            ->where('a.status', $reqStatus)
                            ->where('a.REQUEST_BY_USER', 'LIKE', "%{$search}%");
                        })
                        ->count();
        }
        
        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {   
                if($post -> status == 5){
                    $views = route('viewCloseTicketing', base64_encode($post->TRANS_TICKET_NOCHAR));
                }else{
                    $views = route('viewDataTicketing', base64_encode($post->TRANS_TICKET_NOCHAR));
                }
                if($dataEmail->MD_USER_IT_ID_INT == $post -> PIC){
                    $VIEW = $nestedData['VIEW'] = "<a href='{$views}' title='View' class='btn bg-gradient-primary btn-sm'>View</a>";
                }else{
                    $VIEW = $nestedData['VIEW'] = "<a href='javascript:void(0)' title='View' class='btn bg-gradient-default btn-sm'>View</a>";
                }
                
                $nestedData['TRANS_TICKET_NOCHAR'] = $post->TRANS_TICKET_NOCHAR;
                $nestedData['REQUEST_BY_USER'] = $post->REQUEST_BY_USER;
                $nestedData['PROJECT_NAME'] = $post->PROJECT_NAME;
                $nestedData['TRX_DATE'] = $post->TRX_DATE;
                $nestedData['JUDUL_TICKET'] = $post->JUDUL_TICKET;
                $nestedData['created_by'] = $post->created_by;
                $VIEW;
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        return json_encode($json_data);
    }

    public function adminHome(){
        return view('adminHome');
    }
}
