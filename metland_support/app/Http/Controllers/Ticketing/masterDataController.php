<?php
namespace App\Http\Controllers\Ticketing;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Carbon\Carbon;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use Swift_SmtpTransport;
use Response;

class masterDataController extends Controller
{

    public function viewListUserIt(){
        session(['menuParentActive' => "masterData"]);
        session(['menuSubActive' => "listUserIt"]);

        $project_no = session('current_project');
        $id = session('id');
        $email = session('email');
        $listDataIt = \DB::select("SELECT *, c.DESC_CHAR FROM MD_USER_IT AS a
        LEFT JOIN MD_ROLE_IT AS c ON a.ROLE = c.MD_ROLE_IT_ID_INT
        WHERE a.STATUS = 1
        ORDER BY a.MD_USER_IT_ID_INT ASC");
           
        return view('MasterData.UserIt.viewListUserIt')
        ->with('listDataIt', $listDataIt)
        ->with('project_no', $project_no);
            
    }

    
    public function viewAddUserIt(){
        session(['menuParentActive' => "masterData"]);
        session(['menuSubActive' => "listUserIt"]);
        $project_no = session('current_project');
        $email = session('email');

        $listRoleIt = DB::table('MD_ROLE_IT')->where('status', 1)->get();
        $listParentIt = DB::table('MD_USER_IT')->where('status', 1)->get();
        
        return view('MasterData.UserIt.viewAddUserIt')
        ->with('listRoleIt', $listRoleIt)
        ->with('listParentIt', $listParentIt)
        ->with('project_no', $project_no);
    }

    public function saveAddUserIt(Request $request){
        session(['menuParentActive' => "masterData"]);
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
        $userName = trim(session('first_name') . ' ' . session('last_name'));
        $project_no = session('current_project');
        $email = session('email');
        
        try {
            DB::beginTransaction();
            
            DB::table('MD_USER_IT')->insert([
                'NAMA' => $request -> nama,
                'EMAIL' => $request -> Email,
                'ID_SSO' => $request -> idSso,
                'ROLE' => $request -> Role,
                'PARENT_USER_ID' => $request -> Parent,
                'created_by' => $userName,
                'created_at' => $dateNow
            ]);

            DB::commit();
        }    catch (QueryException $ex) {
            DB::rollback();
            session()->flash('error', 'Add User Gagal, errmsg : ' . $ex);
            return redirect()->route('viewListUserIt');
        }
        session()->flash('success', "Add User Berhasil!");
        return redirect()->route('viewListUserIt');
    }

    public function viewDataUserIt($id){
        session(['menuParentActive' => "masterData"]);
        session(['menuSubActive' => "listUserIt"]);
        $project_no = session('current_project');
        $email = session('email');
        
        $listDataIt = \DB::select("SELECT *, c.DESC_CHAR FROM MD_USER_IT AS a
        LEFT JOIN MD_ROLE_IT AS c ON a.ROLE = c.MD_ROLE_IT_ID_INT
        WHERE a.STATUS = 1 AND a.MD_USER_IT_ID_INT = '".$id."' ");

        $listRoleIt = DB::table('MD_ROLE_IT')->where('status', 1)->get();
        $listParentIt = DB::table('MD_USER_IT')->where('status', 1)->get();
        $listParentIt2 = DB::table('MD_USER_IT')->where('status', 1)->where('MD_USER_IT_ID_INT',$listDataIt[0]->PARENT_USER_ID)->first();

        return view('MasterData.UserIt.viewDataUserIt')
        ->with('listDataIt', $listDataIt)
        ->with('listRoleIt', $listRoleIt)
        ->with('listParentIt', $listParentIt)
        ->with('listParentIt2', $listParentIt2)
        ->with('project_no', $project_no);
        
    }
    
    public function saveEditUserIt(Request $request){
        session(['menuParentActive' => "masterData"]);
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
        $userName = trim(session('first_name') . ' ' . session('last_name'));
        $project_no = session('current_project');
        $email = session('email');
        
        try {
            DB::beginTransaction();
            
            DB::table('MD_USER_IT')->where('MD_USER_IT_ID_INT',$request->id)->update([
                'NAMA' => $request -> nama,
                'EMAIL' => $request -> Email,
                'ID_SSO' => $request -> idSso,
                'ROLE' => $request -> Role,
                'PARENT_USER_ID' => $request -> Parent,
                'updated_by' => $userName,
                'updated_at' => $dateNow
            ]);

            DB::commit();
        }    catch (QueryException $ex) {
            DB::rollback();
            session()->flash('error', 'Edit User Gagal, errmsg : ' . $ex);
            return redirect()->route('viewListUserIt');
        }
            session()->flash('success', "Edit User Berhasil!");
            return redirect()->route('viewListUserIt');
        }
        
        public function deleteDataUserIt($id){
            session(['menuParentActive' => "masterData"]);
            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            $project_no = session('current_project');
            $email = session('email');
            
            try {
                DB::beginTransaction();
                
                DB::table('MD_USER_IT')->where('MD_USER_IT_ID_INT',$id)->update([
                    'status' => '0',
                    'updated_by' => $userName,
                    'updated_at' => $dateNow
                ]);
                
                DB::commit();
            }    catch (QueryException $ex) {
                DB::rollback();
                session()->flash('error', 'Edit User Gagal, errmsg : ' . $ex);
                return redirect()->route('viewListUserIt');
            }
            session()->flash('success', "Edit User Berhasil!");
            return redirect()->route('viewListUserIt');
        }
        
        public function viewListAplikasi(){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listAplikasi"]);
            $project_no = session('current_project');
            $id = session('id');
            $email = session('email');

            $listDataAplikasi = \DB::select("SELECT * FROM MD_APLIKASI 
            WHERE STATUS = 1
            ORDER BY MD_APLIKASI_ID_INT ASC");
               
            return view('MasterData.Aplikasi.viewListAplikasi')
            ->with('listDataAplikasi', $listDataAplikasi)
            ->with('project_no', $project_no);
        }

        public function saveAddAplikasi(Request $request){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listAplikasi"]);

            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            $project_no = session('current_project');
            $email = session('email');
            
            try {
                DB::beginTransaction();
                
                DB::table('MD_APLIKASI')->insert([
                    'DESC_CHAR' => $request -> aplikasi,
                    'created_by' => $userName,
                    'created_at' => $dateNow
                ]);
    
                DB::commit();
            }    catch (QueryException $ex) {
                DB::rollback();
                session()->flash('error', 'Add Aplikasi Gagal, errmsg : ' . $ex);
                return redirect()->route('viewListAplikasi');
            }
            session()->flash('success', "Add Aplikasi Berhasil!");
            return redirect()->route('viewListAplikasi');
        }
        
        public function viewDataUserIt($id){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listUserIt"]);
            $project_no = session('current_project');
            $email = session('email');
            
            $listDataIt = \DB::select("SELECT *, c.DESC_CHAR FROM MD_USER_IT AS a
            LEFT JOIN MD_ROLE_IT AS c ON a.ROLE = c.MD_ROLE_IT_ID_INT
            WHERE a.STATUS = 1 AND a.MD_USER_IT_ID_INT = '".$id."' ");
    
            $listRoleIt = DB::table('MD_ROLE_IT')->where('status', 1)->get();
            $listParentIt = DB::table('MD_USER_IT')->where('status', 1)->get();
            $listParentIt2 = DB::table('MD_USER_IT')->where('status', 1)->where('MD_USER_IT_ID_INT',$listDataIt[0]->PARENT_USER_ID)->first();
    
            return view('MasterData.UserIt.viewDataUserIt')
            ->with('listDataIt', $listDataIt)
            ->with('listRoleIt', $listRoleIt)
            ->with('listParentIt', $listParentIt)
            ->with('listParentIt2', $listParentIt2)
            ->with('project_no', $project_no);
            
        }
}