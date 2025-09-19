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
                session()->flash('error', 'Delete User Gagal, errmsg : ' . $ex);
                return redirect()->route('viewListUserIt');
            }
            session()->flash('success', "Delete User Berhasil!");
            return redirect()->route('viewListUserIt');
        }
        
        public function viewListAplikasi(){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listAplikasi"]);
            $project_no = session('current_project');
            $id = session('id');
            $email = session('email');

            $listDataAplikasi = \DB::select("SELECT a.DESC_CHAR,b.DESC_CHAR AS DESC_ROLE, b.MD_ROLE_IT_ID_INT, a.MD_APLIKASI_ID_INT
            FROM MD_APLIKASI as a
            LEFT JOIN MD_ROLE_IT as b ON b.MD_ROLE_IT_ID_INT = a.ID_ROLE
            WHERE a.STATUS = 1
            ORDER BY MD_APLIKASI_ID_INT ASC");

            $listRole = DB::table('MD_ROLE_IT')->where('status', 1 )->whereNotIn('MD_ROLE_IT_ID_INT',[1,3])->get();

            
            return view('MasterData.Aplikasi.viewListAplikasi')
            ->with('listDataAplikasi', $listDataAplikasi)
            ->with('listRole', $listRole)
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
                    'ID_ROLE' => $request ->role,
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

        public function viewDataAplikasi($id){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listAplikasi"]);
            $project_no = session('current_project');
            $email = session('email');

            $listAplikasi = DB::table('MD_APLIKASI')->where('status', 1)->where('MD_APLIKASI_ID_INT',$id)->first();

            $dataRole = \DB::select("SELECT a.DESC_CHAR,b.DESC_CHAR AS DESC_ROLE, b.MD_ROLE_IT_ID_INT
            FROM MD_APLIKASI as a
            LEFT JOIN MD_ROLE_IT as b ON b.MD_ROLE_IT_ID_INT = a.ID_ROLE
            WHERE MD_APLIKASI_ID_INT = '".$id."'
            ORDER BY MD_APLIKASI_ID_INT ASC");

            $listRole = DB::table('MD_ROLE_IT')->where('status', 1 )->whereNotIn('MD_ROLE_IT_ID_INT',[1,3])->get();

            return view('MasterData.Aplikasi.viewDataAplikasi')
            ->with('listAplikasi', $listAplikasi)
            ->with('dataRole', $dataRole)
            ->with('listRole', $listRole)
            ->with('project_no', $project_no);
        }

        public function saveEditAplikasi(Request $request){
            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            $project_no = session('current_project');
            $email = session('email');
            
            try {
                DB::beginTransaction();
                
                DB::table('MD_APLIKASI')->where('MD_APLIKASI_ID_INT',$request -> id)->update([
                    'DESC_CHAR' => $request -> aplikasi,
                    'ID_ROLE' => $request -> role,
                    'updated_by' => $userName,
                    'updated_at' => $dateNow
                ]);
                
                DB::commit();
            }    catch (QueryException $ex) {
                DB::rollback();
                session()->flash('error', 'Delete Aplikasi Gagal, errmsg : ' . $ex);
                return redirect()->route('viewListAplikasi');
            }
            session()->flash('success', "Delete Aplikasi Berhasil!");
            return redirect()->route('viewListAplikasi');
        }

        public function deleteDataAplikasi($id){
            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            $project_no = session('current_project');
            $email = session('email');
            
            try {
                DB::beginTransaction();
                
                DB::table('MD_APLIKASI')->where('MD_APLIKASI_ID_INT',$id)->update([
                    'status' => '0',
                    'updated_by' => $userName,
                    'updated_at' => $dateNow
                ]);
                
                DB::commit();
            }    catch (QueryException $ex) {
                DB::rollback();
                session()->flash('error', 'Delete Aplikasi Gagal, errmsg : ' . $ex);
                return redirect()->route('viewListAplikasi');
            }
            session()->flash('success', "Delete Aplikasi Berhasil!");
            return redirect()->route('viewListAplikasi');
        }
 
        public function viewListEmailSupport(){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listEmailSupport"]);
            $project_no = session('current_project');
            $email = session('email');

            $listEmailSupp = DB::table('MD_EMAIL_SUPPORT')->where('status', 1)->get();
            
            return view('MasterData.EmailSupport.viewListEmailSupport')
            ->with('listEmailSupp', $listEmailSupp)
            ->with('project_no', $project_no);
        }

        public function viewAddEmailSupport(){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listEmailSupport"]);
            $project_no = session('current_project');
            $email = session('email');
        
            return view('MasterData.EmailSupport.viewAddEmailSupport')
            ->with('project_no', $project_no);
        }

        public function saveAddEmailSupport(Request $request){
            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            $project_no = session('current_project');
            $email = session('email');
            
            try {
                DB::beginTransaction();
                
                DB::table('MD_EMAIL_SUPPORT')->insert([
                    'NAMA' => $request -> nama,
                    'EMAIL' => $request -> email,
                    'PASSWORD' => $request -> password,
                    'VERIF_TELP' => $request -> noTelp,
                    'SANDI_APLIKASI' => $request -> sandi,
                    'DRIVER' => $request -> driver,
                    'HOST' => $request -> host,
                    'PORT' => $request -> port,
                    'ENCRYPTION' => $request -> encryption,
                    'created_by' => $userName,
                    'created_date' => $dateNow
                ]);
    
                DB::commit();
            }    catch (QueryException $ex) {
                DB::rollback();
                session()->flash('error', 'Add Email Support Gagal, errmsg : ' . $ex);
                return redirect()->route('viewListEmailSupport');
            }
            session()->flash('success', "Add Email Support Berhasil!");
            return redirect()->route('viewListEmailSupport');
        }

        public function viewEditEmailSupport($id){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listUserIt"]);
            $project_no = session('current_project');
            $email = session('email');

            $listEmailSupp = DB::table('MD_EMAIL_SUPPORT')->where('MD_EMAIL_SUPPORT_ID_INT',$id)->where('status', 1)->get();
          
            return view('MasterData.EmailSupport.viewDataEmailSupport')
            ->with('listEmailSupp', $listEmailSupp)
            ->with('project_no', $project_no);
        }
        
        public function saveEditEmailSupport(Request $request){
            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            $project_no = session('current_project');
            $email = session('email');
            
            try {
                DB::beginTransaction();
  
                DB::table('MD_EMAIL_SUPPORT')->where('MD_EMAIL_SUPPORT_ID_INT', $request -> id)->update([
                    'NAMA' => $request -> nama,
                    'EMAIL' => $request -> email,
                    'PASSWORD' => $request -> password,
                    'VERIF_TELP' => $request -> noTelp,
                    'SANDI_APLIKASI' => $request -> sandi,
                    'DRIVER' => $request -> driver,
                    'HOST' => $request -> host,
                    'PORT' => $request -> port,
                    'ENCRYPTION' => $request -> encryption,
                    'updated_by' => $userName,
                    'updated_date' => $dateNow
                ]);
                
                DB::commit();
            }    catch (QueryException $ex) {
                DB::rollback();
                session()->flash('error', 'Edit Email Support Gagal, errmsg : ' . $ex);
                return redirect()->route('viewListEmailSupport');
            }
            session()->flash('success', "Edit Email Support Berhasil!");
            return redirect()->route('viewListEmailSupport');
        }

        public function deleteDataEmailSupport($id){
            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            $project_no = session('current_project');
            $email = session('email');
            
            try {
                DB::beginTransaction();
                
                DB::table('MD_EMAIL_SUPPORT')->where('MD_EMAIL_SUPPORT_ID_INT',$id)->update([
                    'status' => '0',
                    'updated_by' => $userName,
                    'updated_date' => $dateNow
                ]);
                
                DB::commit();
            }    catch (QueryException $ex) {
                DB::rollback();
                session()->flash('error', 'Delete Email Gagal, errmsg : ' . $ex);
                return redirect()->route('viewListEmailSupport');
            }
            session()->flash('success', "Delete Email Berhasil!");
            return redirect()->route('viewListEmailSupport');
        }
        
        public function viewlistRoleIt(){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listRoleIt"]);
            $project_no = session('current_project');
            $email = session('email');
            
            $listRoleIt = DB::table('MD_ROLE_IT')->where('status', 1)->get();
            
            return view('MasterData.RoleIt.viewListRoleIt')
            ->with('listRoleIt', $listRoleIt)
            ->with('project_no', $project_no);
        }
        
        public function saveAddRoleIt(Request $request){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listAplikasi"]);
            
            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            $project_no = session('current_project');
            $email = session('email');
            
            try {
                DB::beginTransaction();
                
                DB::table('MD_ROLE_IT')->insert([
                    'DESC_CHAR' => $request -> role,
                    'created_by' => $userName,
                    'created_at' => $dateNow
                ]);
                
                DB::commit();
            }    catch (QueryException $ex) {
                DB::rollback();
                session()->flash('error', 'Add Role It Gagal, errmsg : ' . $ex);
                return redirect()->route('viewlistRoleIt');
            }
            session()->flash('success', "Add Role It Berhasil!");
            return redirect()->route('viewlistRoleIt');
        }
        
        public function viewDataRoleIt($id){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listUserIt"]);
            $project_no = session('current_project');
            $email = session('email');
    
            $listRoleIt = DB::table('MD_ROLE_IT')->where('MD_ROLE_IT_ID_INT',$id)->where('status', 1)->first();
          
            return view('MasterData.RoleIt.viewDataRoleIt')
            ->with('listRoleIt', $listRoleIt)
            ->with('project_no', $project_no);
        }

        public function saveEditRoleIt(Request $request){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listUserIt"]);
            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            $project_no = session('current_project');
            $email = session('email');

            try {
                DB::beginTransaction();
                
                DB::table('MD_ROLE_IT')->where('MD_ROLE_IT_ID_INT',$request -> id)->update([
                    'DESC_CHAR' => $request -> role,
                    'updated_by' => $userName,
                    'updated_at' => $dateNow
                ]);
                DB::commit();
            }    catch (QueryException $ex) {
                DB::rollback();
                session()->flash('error', 'Edit Role It Gagal, errmsg : ' . $ex);
                return redirect()->route('viewlistRoleIt');
            }
            session()->flash('success', "Edit Role It Berhasil!");
            return redirect()->route('viewlistRoleIt');
        }
        public function deleteDataRoleIt($id){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listUserIt"]);
            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            $project_no = session('current_project');
            $email = session('email');

            try {
                DB::beginTransaction();
                
                DB::table('MD_ROLE_IT')->where('MD_ROLE_IT_ID_INT',$id)->update([
                    'status' => '0',
                    'updated_by' => $userName,
                    'updated_at' => $dateNow
                ]);
                DB::commit();
            }    catch (QueryException $ex) {
                DB::rollback();
                session()->flash('error', 'Delete Role It Gagal, errmsg : ' . $ex);
                return redirect()->route('viewlistRoleIt');
            }
            session()->flash('success', "Delete Role It Berhasil!");
            return redirect()->route('viewlistRoleIt');
        }

        public function viewlistTypeKeluhan(){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listTypeKeluhan"]);
            $project_no = session('current_project');
            $email = session('email');
            
            $listTypeKeluhan = DB::table('MD_TYPE_KELUHAN_TICKETING')->where('status', 1)->get();
            
            return view('MasterData.TypeKeluhan.viewListTypeKeluhan')
            ->with('listTypeKeluhan', $listTypeKeluhan)
            ->with('project_no', $project_no);
        }

        public function saveAddTypeKeluhan(Request $request){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listTypeKeluhan"]);
            
            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            $project_no = session('current_project');
            $email = session('email');
            
            try {
                DB::beginTransaction();
                
                DB::table('MD_TYPE_KELUHAN_TICKETING')->insert([
                    'DESC_CHAR' => $request -> type,
                    'created_by' => $userName,
                    'created_at' => $dateNow
                ]);
                
                DB::commit();
            }    catch (QueryException $ex) {
                DB::rollback();
                session()->flash('error', 'Add Type Keluhan Gagal, errmsg : ' . $ex);
                return redirect()->route('viewlistTypeKeluhan');
            }
            session()->flash('success', "Add Type Keluhan Berhasil!");
            return redirect()->route('viewlistTypeKeluhan');
        }

        
        public function viewDataTypeKeluhan($id){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listTypeKeluhan"]);
            $project_no = session('current_project');
            $email = session('email');
            
            $listTypeKeluhan = DB::table('MD_TYPE_KELUHAN_TICKETING')->where('MD_TYPE_KELUHAN_TICKETING_ID_INT',$id)->where('status', 1)->first();
            
            return view('MasterData.TypeKeluhan.viewDataTypeKeluhan')
            ->with('listTypeKeluhan', $listTypeKeluhan)
            ->with('project_no', $project_no);
        }
        
        public function saveEditTypeKeluhan(Request $request){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listTypeKeluhan"]);
            
            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            $project_no = session('current_project');
            $email = session('email');
            
            try {
                DB::beginTransaction();
                
                DB::table('MD_TYPE_KELUHAN_TICKETING')->where('MD_TYPE_KELUHAN_TICKETING_ID_INT', $request -> id)->update([
                    'DESC_CHAR' => $request -> type,
                    'created_by' => $userName,
                    'created_at' => $dateNow
                ]);
                
                DB::commit();
            }    catch (QueryException $ex) {
                DB::rollback();
                session()->flash('error', 'edit Type Keluhan Gagal, errmsg : ' . $ex);
                return redirect()->route('viewlistTypeKeluhan');
            }
            session()->flash('success', "edit Type Keluhan Berhasil!");
            return redirect()->route('viewlistTypeKeluhan');
        }

        public function deleteDataTypeKeluhan($id){
            session(['menuParentActive' => "masterData"]);
            session(['menuSubActive' => "listTypeKeluhan"]);
            
            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            $project_no = session('current_project');
            $email = session('email');
            
            try {
                DB::beginTransaction();
                
                DB::table('MD_TYPE_KELUHAN_TICKETING')->where('MD_TYPE_KELUHAN_TICKETING_ID_INT', $id)->update([
                    'STATUS' => '0',
                    'updated_by' => $userName,
                    'updated_at' => $dateNow
                ]);
                
                DB::commit();
            }    catch (QueryException $ex) {
                DB::rollback();
                session()->flash('error', 'Delete Type Keluhan Gagal, errmsg : ' . $ex);
                return redirect()->route('viewlistTypeKeluhan');
            }
            session()->flash('success', "Delete Type Keluhan Berhasil!");
            return redirect()->route('viewlistTypeKeluhan');
        }
    }