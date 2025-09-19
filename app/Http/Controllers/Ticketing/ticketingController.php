<?php
namespace App\Http\Controllers\Ticketing;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Carbon\Carbon;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use Swift_SmtpTransport;
use Response;
use DB;

class ticketingController extends Controller
{

    public function index(){
        session(['menuParentActive' => "Ticketing"]);
        session(['menuSubActive' => "listTicketing"]);
        $project_no = session('current_project');
        $id = session('id');
        $email = session('email');

        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();

        $isAdmin=false;

        if($dataEmail){
            $isAdmin=true;
        }

        if($isAdmin == true){
            if($dataEmail -> ROLE === 1){
                $listDataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET, a.TRX_DATE, a.REQUEST_BY_USER,
                b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
                FROM TRANS_TICKET AS a 
                LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
                LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
                INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
                WHERE a.status NOT IN (0,1,5) AND a.PIC = '".$dataEmail->MD_USER_IT_ID_INT."'
                ORDER BY a.created_at DESC");

                $listDataTicket3 = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.DESC_CHAR, a.JUDUL_TICKET,a.updated_at,a.REQUEST_BY_USER,
                a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, b.NAMA, c.DESC_CHAR AS DESC_CHAR_APLIKASI, e.DESC_CHAR AS DESC_STATUS,
                d.DESC_CHAR AS DESC_CHAR_MD_TYPE_KELUHAN_TICKETING,f.PROJECT_NAME, e.TRANS_TICKET_STATUS_ID_INT,CURDATE() AS curent_dates,g.close_tiket,
                DATEDIFF(CURDATE(), g.close_tiket) AS DIFF_DATES
                FROM TRANS_TICKET AS a
                LEFT JOIN MD_USER_IT as b ON a.PIC = b.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as c ON a.APLIKASI = c.MD_APLIKASI_ID_INT
                LEFT JOIN MD_TYPE_KELUHAN_TICKETING as d ON a.TYPE = d.MD_TYPE_KELUHAN_TICKETING_ID_INT
                INNER JOIN TRANS_TICKET_STATUS as e ON a.status = e.TRANS_TICKET_STATUS_ID_INT
                INNER JOIN MD_PROJECT as f ON a.PROJECT = f.PROJECT_NO_CHAR
                INNER JOIN 
                    (
                        SELECT TRANS_TICKET_NOCHAR,MAX(created_at) AS close_tiket
                        FROM TRANS_TICKET_HISTORY
                        WHERE STATUS = 5
                        GROUP BY TRANS_TICKET_NOCHAR
                    ) AS g ON a.TRANS_TICKET_NOCHAR = g.TRANS_TICKET_NOCHAR 
                WHERE a.status = 5 AND a.PIC = '".$dataEmail->MD_USER_IT_ID_INT."'
                ORDER BY a.created_at DESC");

                $listDataTicket4 = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET, a.REQUEST_BY_USER, a.TRX_DATE,
                b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
                FROM TRANS_TICKET AS a
                LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
                LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
                INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
                WHERE a.status NOT IN (0,1,5)
                ORDER BY a.created_at DESC");
            }else{
                $listDataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET,a.TRX_DATE, a.REQUEST_BY_USER,
                b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
                FROM TRANS_TICKET AS a 
                LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
                LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
                INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
                WHERE a.status NOT IN (0,1,5) AND a.PIC = '".$dataEmail->MD_USER_IT_ID_INT."'
                ORDER BY a.created_at DESC");

                $listDataTicket3 = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.DESC_CHAR, a.JUDUL_TICKET, a.updated_at, a.REQUEST_BY_USER,
                a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, b.NAMA, c.DESC_CHAR AS DESC_CHAR_APLIKASI, e.DESC_CHAR AS DESC_STATUS,
                d.DESC_CHAR AS DESC_CHAR_MD_TYPE_KELUHAN_TICKETING,f.PROJECT_NAME, e.TRANS_TICKET_STATUS_ID_INT,CURDATE() AS curent_dates,g.close_tiket,
                DATEDIFF(CURDATE(), g.close_tiket) AS DIFF_DATES
                FROM TRANS_TICKET AS a
                LEFT JOIN MD_USER_IT as b ON a.PIC = b.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as c ON a.APLIKASI = c.MD_APLIKASI_ID_INT
                LEFT JOIN MD_TYPE_KELUHAN_TICKETING as d ON a.TYPE = d.MD_TYPE_KELUHAN_TICKETING_ID_INT
                INNER JOIN TRANS_TICKET_STATUS as e ON a.status = e.TRANS_TICKET_STATUS_ID_INT
                INNER JOIN MD_PROJECT as f ON a.PROJECT = f.PROJECT_NO_CHAR
                INNER JOIN 
                    (
                        SELECT TRANS_TICKET_NOCHAR,MAX(created_at) AS close_tiket
                        FROM TRANS_TICKET_HISTORY
                        WHERE STATUS = 5
                        GROUP BY TRANS_TICKET_NOCHAR
                    ) AS g ON a.TRANS_TICKET_NOCHAR = g.TRANS_TICKET_NOCHAR 
                WHERE a.PIC = '".$dataEmail->MD_USER_IT_ID_INT."' OR b.PARENT_USER_ID = '".$dataEmail->MD_USER_IT_ID_INT."' AND a.status = 5
                ORDER BY a.created_at DESC");

                $listDataTicket4 = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET, a.REQUEST_BY_USER, a.TRX_DATE,
                b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
                FROM TRANS_TICKET AS a
                LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
                LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
                INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
                WHERE c.MD_USER_IT_ID_INT = '".$dataEmail->MD_USER_IT_ID_INT."' OR c.PARENT_USER_ID = '".$dataEmail->MD_USER_IT_ID_INT."' And a.status NOT IN (1,5)
                ORDER BY a.created_at DESC");
            }
            $listDataTicket2 = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET, a.REQUEST_BY_USER,
            b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
            FROM TRANS_TICKET AS a
            LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
            LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
            LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
            INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
            WHERE a.status = '1' 
            ORDER BY a.created_at DESC");
            
        }else{
            $listDataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET,a.TRX_DATE, a.REQUEST_BY_USER,
            b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
            FROM TRANS_TICKET AS a 
            LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
            LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
            LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
            INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
            WHERE a.status NOT IN (1,5) AND a.CREATED_BY_ID_SSO = '".$id."'
            ORDER BY a.created_at DESC");

            $listDataTicket2 = null;

            $listDataTicket3 = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.DESC_CHAR, a.JUDUL_TICKET,a.updated_at, a.REQUEST_BY_USER,
                a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, b.NAMA, c.DESC_CHAR AS DESC_CHAR_APLIKASI, e.DESC_CHAR AS DESC_STATUS,
                d.DESC_CHAR AS DESC_CHAR_MD_TYPE_KELUHAN_TICKETING,f.PROJECT_NAME, e.TRANS_TICKET_STATUS_ID_INT,CURDATE() AS curent_dates,g.close_tiket,
                DATEDIFF(CURDATE(), g.close_tiket) AS DIFF_DATES
                FROM TRANS_TICKET AS a
                LEFT JOIN MD_USER_IT as b ON a.PIC = b.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as c ON a.APLIKASI = c.MD_APLIKASI_ID_INT
                LEFT JOIN MD_TYPE_KELUHAN_TICKETING as d ON a.TYPE = d.MD_TYPE_KELUHAN_TICKETING_ID_INT
                INNER JOIN TRANS_TICKET_STATUS as e ON a.status = e.TRANS_TICKET_STATUS_ID_INT
                INNER JOIN MD_PROJECT as f ON a.PROJECT = f.PROJECT_NO_CHAR
                INNER JOIN 
                    (
                        SELECT TRANS_TICKET_NOCHAR,MAX(created_at) AS close_tiket
                        FROM TRANS_TICKET_HISTORY   
                        WHERE STATUS = 5
                        GROUP BY TRANS_TICKET_NOCHAR
                    ) AS g ON a.TRANS_TICKET_NOCHAR = g.TRANS_TICKET_NOCHAR 
                WHERE a.CREATED_BY_ID_SSO = '".$id."' AND a.status = 5
                ORDER BY a.created_at ASC");

                $listDataTicket4 = null;
        }
  
        $dataStatus = DB::table('TRANS_TICKET_STATUS')->whereNotIn('TRANS_TICKET_STATUS_ID_INT',[1])->get();
        
        return view('Ticketing.listViewTicketing',compact('isAdmin'))
        ->with('listDataTicket', $listDataTicket)
        ->with('listDataTicket2', $listDataTicket2)
        ->with('listDataTicket3', $listDataTicket3)
        ->with('listDataTicket4', $listDataTicket4)
        ->with('dataStatus', $dataStatus)
        ->with('project_no', $project_no);
            
    }

    public function viewInputTicketing(){
        session(['menuParentActive' => "Reporting"]);
        session(['menuSubActive' => "listReportingDriverCar"]);

        $project_no = session('current_project');
        $email = session('email');
        $userName = trim(session('first_name') . ' ' . session('last_name'));
        $dataType = DB::table('MD_TYPE_KELUHAN_TICKETING')->where('status', 1)->get();
        $dataAplikasi = DB::table('MD_APLIKASI')->where('status', 1)->get();
        $dataTicketing = DB::table('TRANS_TICKET')->get();
        $dataPic = DB::table('MD_USER_IT')->where('status', 1)->wherenotin('ROLE',[1])->get();
        $dataRole = DB::table('MD_ROLE_IT')->wherenotin('MD_ROLE_IT_ID_INT',[1,3])->where('ID_DIVISION',1)->where('status', 1)->get();

        $dataApRole = \DB::select("SELECT a.MD_ROLE_IT_ID_INT, a.DESC_CHAR, b.MD_APLIKASI_ID_INT, b.ID_ROLE, b.DESC_CHAR AS DESC_APLIKASI
        FROM MD_ROLE_IT AS a
        LEFT JOIN MD_APLIKASI as b ON a.MD_ROLE_IT_ID_INT = b.ID_ROLE
        WHERE MD_ROLE_IT_ID_INT NOT IN (1,3)");

        return view('Ticketing.viewInputTicketing')
        ->with('dataAplikasi', $dataAplikasi)
        ->with('dataType', $dataType)
        ->with('dataPic', $dataPic)
        ->with('dataApRole', $dataApRole)
        ->with('email', $email)
        ->with('userName', $userName)
        ->with('dataTicketing', $dataTicketing)
        ->with('dataRole', $dataRole)
        ->with('project_no', $project_no);
            
    }

    public function getAplikasi($id) {
        $aplikasi =  DB::table('MD_APLIKASI')->where('ID_ROLE',$id)->get();
        return response()->json([
            'aplikasi' => $aplikasi
        ]);
    }

    public function saveDataTicket(Request $request) {
    
        $project_no = session('current_project');
        $id = session('id');
        $email = session('email');
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
        $userName = trim(session('first_name') . ' ' . session('last_name'));
        $dataProject = DB::table('MD_PROJECT')->where('PROJECT_NO_CHAR', $project_no)->first();
        $dataCounter = DB::table('counter_table')->where('PROJECT_NO_CHAR', $project_no)->first();
        $dataNomor = $dataProject->PROJECT_CODE.date('y').sprintf("%04d",$dataCounter->TRANS_TICKET_NOCHAR);
        $reqPic = $request -> PIC;  
        $dataPic = DB::table('MD_USER_IT')->where('MD_USER_IT_ID_INT', $reqPic)->first();
        $reqType = $request -> TYPE;  
        $dataType = DB::table('MD_TYPE_KELUHAN_TICKETING')->where('MD_TYPE_KELUHAN_TICKETING_ID_INT', $reqType)->first();
        $reqAplikasi = $request -> APLIKASI;  
        $dataAplikasi = DB::table('MD_APLIKASI')->where('MD_APLIKASI_ID_INT', $reqAplikasi)->first();
        $status = 2;
        $dataStatus = DB::table('TRANS_TICKET_STATUS')->where('TRANS_TICKET_STATUS_ID_INT', $status)->first();
        $reqCounterIt = $request -> CATEGORY; 
        $counterIt = DB::table('MD_USER_IT')->where('MD_USER_IT_ID_INT', $reqCounterIt)->first();
        $uniq = uniqid();
        $reqEmail = $request->email;
        $reqUserName = $request->userName;

        if($request->hasFile('ATTACH')) {
            foreach($request->file('ATTACH') as $att ){

            //get filename with extension
            $filenamewithextension = $att->getClientOriginalName();
      
            //filename to store
            $filenametostore = $uniq.'_'.$filenamewithextension;
    
            //Upload File to external server
            Storage::disk('ftp')->put($filenametostore, fopen($att, 'r+'));

            //Store $filenametostore in the database
        }
        } else {
            $filenametostore = "";
        }

        if($request->hasFile('ATTACH2')) {
            foreach($request->file('ATTACH2') as $att ){

            //get filename with extension
            $filenamewithextension = $att->getClientOriginalName();
      
            //filename to store
            $filenametostore2 = $uniq.'_'.$filenamewithextension;
    
            //Upload File to external server
            Storage::disk('ftp')->put($filenametostore2, fopen($att, 'r+'));

            //Store $filenametostore in the database
        }
        } else {
            $filenametostore2 = "";
        }

        if($request->hasFile('ATTACH3')) {
            foreach($request->file('ATTACH3') as $att ){

            //get filename with extension
            $filenamewithextension = $att->getClientOriginalName();
      
            //filename to store
            $filenametostore3 = $uniq.'_'.$filenamewithextension;
    
            //Upload File to external server
            Storage::disk('ftp')->put($filenametostore3, fopen($att, 'r+'));

            //Store $filenametostore in the database
        }
        } else {
            $filenametostore3 = "";
        }

        $dataPicCounter = \DB::select("SELECT * FROM (
            SELECT *, COUNTER % 2 AS COUNTER_MOD FROM MD_USER_IT
            WHERE STATUS = 1 AND ROLE = '".$reqCounterIt."'
            ) AS a
            ORDER BY a.COUNTER_MOD DESC, a.COUNTER ASC");

        try {
            DB::beginTransaction();
            if($request -> CATEGORY == "" ){
                $status = 1;
            }else{
                $status = 2;

                 // Kirim Email //
                $dataEmail = \DB::select("SELECT COUNTER,NAMA, EMAIL, HOST, PORT, ENCRYPTION, SANDI_APLIKASI
                    FROM MD_EMAIL_SUPPORT
                    WHERE STATUS = '1'
                    ORDER BY COUNTER ASC");
                $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));

                // Backup original mailer
                $backup = Mail::getSwiftMailer();

                // Setup your gmail mailer
                $transport = new Swift_SmtpTransport($dataEmail[0]->HOST, $dataEmail[0]->PORT, $dataEmail[0]->ENCRYPTION);
                $transport->setUsername($dataEmail[0]->EMAIL);
                $transport->setPassword($dataEmail[0]->SANDI_APLIKASI);

                $gmail = new Swift_Mailer($transport);

                // Set the mailer as gmail
                Mail::setSwiftMailer($gmail);

                // Send your message
                $subject = $request->judul. " - " . $dataNomor;
                $to = $dataPicCounter[0]->EMAIL;

                $textHtml = "Dear".' '.$dataPicCounter[0] -> NAMA.',<br><br>'.
                "Berikut Informasi Mengenai Ticket Anda :".' '.
                "<p>User Request : ".'<b>'. $userName.'</b>'.'</p>'.
                "<p>Nomor Ticket : ".''. $dataNomor.''.'</p>'.
                "<p>Judul Ticket : ".''. $request->judul.''.'</p>'.
                "<p>Type : ".''. $dataType -> DESC_CHAR.''.'</p>'.
                "<p>Aplikasi : ".''. $dataAplikasi -> DESC_CHAR .''.'</p>'.
                "<p>Tanggal Request : ".''.  date('d/m/Y',strtotime($request->TRX_DATE)).''.'</p>'.
                "<p>Deskripsi : ".''. $request->DESC_CHAR.''.'</p>'.
                "<p>Attach :  Terlampir Dalam Ticket </p>".
                "<p>Status : ".'<b>'. $dataStatus -> DESC_CHAR .'</b>'.'</p>'.
                "<p>Url : ".'https://support.metropolitanland.com/viewDataTicketing/'.base64_encode($dataNomor).'</p>'.
                "<p></p><p></p>"."Demikian Informasi Ini disampaikan, Terima Kasih.<p>
                Salam, <br>System Support<p>
                *Email Dikirim Otomatis, Dimohon Tidak di Reply Email Ini";

                Mail::send([], [], function ($message) use ($subject, $to, $textHtml, $dataEmail) {
                    
                    $message->from($dataEmail[0]->EMAIL, $dataEmail[0]->NAMA);
                    $message->to($to)->subject($subject);
                    $message->setBody($textHtml, 'text/html');
                });

            //USER
            $subject2 = $request->judul. " - " . $dataNomor;
            $to2 = $request->email;

            $textHtml2 = "Dear".' '.$request -> userName .',<br><br>'.
            "Berikut Update Informasi Mengenai Ticket Anda :".
            "<p>User Request : ".'<b>'. $userName.'</b>'.'</p>'.
            "<p>Nomor Ticket : ".''. $dataNomor.''.'</p>'.
            "<p>Judul Ticket : ".''. $request->judul.''.'</p>'.
            "<p>Type : ".''. $dataType -> DESC_CHAR.''.'</p>'.
            "<p>Aplikasi : ".''. $dataAplikasi -> DESC_CHAR .''.'</p>'.
            "<p>Tanggal Request : ".''.  date('d/m/Y',strtotime($request->TRX_DATE)).''.'</p>'.
            "<p>Deskripsi : ".''. $request->DESC_CHAR.''.'</p>'.
            "<p>PIC : ".''.'<b>'.$dataPicCounter[0] -> NAMA.'</b>'.'</p>'.
            "<p>Attach :  Terlampir Dalam Ticket </p>".
            "<p>Status : ".'<b>'.  $dataStatus -> DESC_CHAR .'</b>'.'</p>'.
            "<p>Url : ".'https://support.metropolitanland.com/viewDataTicketing/'.base64_encode($dataNomor).'</p>'.
            "<p></p><p></p>"."Demikian Informasi Ini disampaikan, Terima Kasih.<p>
            Salam, <br>System Support<p>
            *Email Dikirim Otomatis, Dimohon Tidak di Reply Email Ini"; 
            Mail::send([], [], function ($message) use ($subject2, $to2, $textHtml2, $dataEmail,$reqEmail,$reqUserName) {
                $message->from($reqEmail,$reqUserName);
                $message->to($to2)->subject($subject2);
                $message->setBody($textHtml2, 'text/html');
            });

                // Restore your original mailer
                Mail::setSwiftMailer($backup);

                // End Kirim Email

            DB::table('MD_EMAIL_SUPPORT')->where('EMAIL',$dataEmail[0]-> EMAIL)->update([
                'COUNTER' => $dataEmail[0] -> COUNTER +1 
            ]);
            DB::table('MD_EMAIL_SUPPORT')->where('EMAIL',$dataEmail[1]-> EMAIL)->update([
                'COUNTER' => $dataEmail[1] -> COUNTER +1 
            ]);

        }

        if($request -> CATEGORY){
            DB::table('TRANS_TICKET')->insert([
                'JUDUL_TICKET' => $request->judul,
                'TYPE' => $request->TYPE,
                'APLIKASI' => $request->APLIKASI,
                'PIC' => $dataPicCounter[0]->MD_USER_IT_ID_INT,
                'DESC_CHAR' => $request->DESC_CHAR,
                'TRX_DATE' => $request->TRX_DATE,
                'REQUEST_BY_EMAIL' => $request->email,
                'REQUEST_BY_USER' => $request->userName,
                'TRANS_TICKET_NOCHAR' => $dataNomor,    
                'created_by' => $userName,
                'created_at' => $dateNow,
                'PROJECT' => $project_no,
                'status' => $status,
                'CREATED_BY_ID_SSO' => $id
            ]);
        }else{
            DB::table('TRANS_TICKET')->insert([
                'JUDUL_TICKET' => $request->judul,
                'TYPE' => $request->TYPE,
                'APLIKASI' => $request->APLIKASI,
                'PIC' => null,
                'DESC_CHAR' => $request->DESC_CHAR,
                'TRX_DATE' => $request->TRX_DATE,
                'REQUEST_BY_EMAIL' => $request->email,
                'REQUEST_BY_USER' => $request->userName,
                'TRANS_TICKET_NOCHAR' => $dataNomor,
                'created_by' => $userName,
                'created_at' => $dateNow,
                'PROJECT' => $project_no,
                'status' => $status,
                'CREATED_BY_ID_SSO' => $id
                
            ]);
        }

            DB::table('counter_table')->where('PROJECT_NO_CHAR',$project_no)->update([
                'TRANS_TICKET_NOCHAR' => $dataCounter->TRANS_TICKET_NOCHAR + 1 
            ]);
            
            $idHistory = DB::table('TRANS_TICKET_HISTORY')->insertGetId([
                'TRANS_TICKET_NOCHAR' => $dataNomor,
                'status' => $status,
                'HISTORY_DESC' => $request -> DESC_CHAR,
                'OWNER' => $dataPicCounter[0]->MD_USER_IT_ID_INT,
                'created_by' => $userName,
                'created_at' => $dateNow
        ]);
        if($request->hasFile('ATTACH')) {
            DB::table('TRANS_TICKET_ATTACHMENT')->insert([
                'ATTACHMENT_NAME' => $filenametostore,
                'ATTACHMENT_NAME2' => $filenametostore2,
                'ATTACHMENT_NAME3' => $filenametostore3,
                'TICKET_HISTORY_ID' => $idHistory,
                'TRANS_TICKET_NOCHAR' => $dataNomor,
                'created_by' => $userName,
                'created_at' => $dateNow
            ]);
        }
        if($request -> CATEGORY){
            DB::table('MD_USER_IT')->where('MD_USER_IT_ID_INT',$dataPicCounter[0] -> MD_USER_IT_ID_INT)->update([
                'COUNTER' => $dataPicCounter[0] ->COUNTER + 1 
            ]);
        }

            DB::commit();
        }    catch (QueryException $ex) {
            DB::rollback();
            session()->flash('error', 'Input Ticket Gagal, errmsg : ' . $ex);
            return redirect()->route('viewListTicketing');
        }

       

        session()->flash('success', "Input Ticket Berhasil!");
        return redirect()->route('viewListTicketing');
    }
    
    public function viewDataTicketing($id) {
            
        session(['menuParentActive' => "Ticketing"]);
        session(['menuSubActive' => "listTicketing"]);
        $id = base64_decode($id,true);
        $project_no = session('current_project');
        $email = session('email');

        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();
        $isAdmin=false;
        if($dataEmail){
            $isAdmin=true;
        }
        
        $dataFile = DB::table('TRANS_TICKET_ATTACHMENT')->where('TRANS_TICKET_NOCHAR',$id)->first();
        $isFile = false;
        if($dataFile <> null){
            $isFile = true;
        }

        $dataStatus = DB::table('TRANS_TICKET')->where('TRANS_TICKET_NOCHAR',$id)->where('status', 1)->first();
        $isStatus=false;
        if($dataStatus){
            $isStatus=true;
        }

        $viewDataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.DESC_CHAR, a.JUDUL_TICKET, a.JUDUL_TICKET,a.TRX_DATE,
        a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, b.NAMA, c.DESC_CHAR AS DESC_CHAR_APLIKASI, e.DESC_CHAR AS DESC_STATUS,
        d.DESC_CHAR AS DESC_CHAR_MD_TYPE_KELUHAN_TICKETING,e.TRANS_TICKET_STATUS_ID_INT, b.MD_USER_IT_ID_INT, f.created_at AS TimeATT,
        (SELECT HISTORY_DESC FROM TRANS_TICKET_HISTORY WHERE TRANS_TICKET_NOCHAR = a.TRANS_TICKET_NOCHAR ORDER BY created_at DESC LIMIT 1)
        AS HISTORY_DESC
        FROM TRANS_TICKET AS a
        LEFT JOIN MD_USER_IT as b ON a.PIC = b.MD_USER_IT_ID_INT
        LEFT JOIN MD_APLIKASI as c ON a.APLIKASI = c.MD_APLIKASI_ID_INT
        LEFT JOIN MD_TYPE_KELUHAN_TICKETING as d ON a.TYPE = d.MD_TYPE_KELUHAN_TICKETING_ID_INT
        INNER JOIN TRANS_TICKET_STATUS as e ON a.status = e.TRANS_TICKET_STATUS_ID_INT
        LEFT JOIN TRANS_TICKET_HISTORY as f ON a.TRANS_TICKET_NOCHAR = f.TRANS_TICKET_NOCHAR
        WHERE a.TRANS_TICKET_NOCHAR = '".$id."'
        ORDER BY a.created_at DESC LIMIT 1");

        $dataStatus = \DB::select("SELECT TRANS_TICKET_STATUS_ID_INT, DESC_CHAR
        FROM TRANS_TICKET_STATUS
        WHERE TRANS_TICKET_STATUS_ID_INT NOT IN ('".$viewDataTicket[0]->status."')
        ORDER BY created_at");

        $dataPic = \DB::select("SELECT NAMA, MD_USER_IT_ID_INT
        FROM MD_USER_IT 
        WHERE MD_USER_IT_ID_INT NOT IN ('".$viewDataTicket[0]->PIC."')
        ORDER BY created_at");

        $dataChat = DB::table('TRANS_TICKET_HISTORY')->where('TRANS_TICKET_NOCHAR',$id)->first();

        $dataAtt =DB::table('TRANS_TICKET_ATTACHMENT')->where('TRANS_TICKET_NOCHAR',$id)->get();

        $dataHistory = \DB::select("SELECT a.TICKET_HISTORY_ID, c.ID, a.created_by, a.HISTORY_DESC, c.ATTACHMENT_NAME, c.ATTACHMENT_NAME2, c.ATTACHMENT_NAME3, b.DESC_CHAR, a.created_at
        FROM TRANS_TICKET_HISTORY as a
        INNER JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
        LEFT JOIN TRANS_TICKET_ATTACHMENT AS c ON c.TICKET_HISTORY_ID = a.TICKET_HISTORY_ID
        WHERE a.TRANS_TICKET_NOCHAR ='".$id."'
        ORDER BY a.created_at DESC");


        return view('Ticketing.viewDataTicket',compact('isAdmin','isStatus','isFile'))
            ->with('project_no', $project_no)
            ->with('dataStatus', $dataStatus)
            ->with('dataPic', $dataPic)
            ->with('dataChat', $dataChat)
            ->with('dataHistory', $dataHistory)
            ->with('dataAtt', $dataAtt)
            ->with('viewDataTicket', $viewDataTicket);
    }

    public function downloadFile($attachment_Name) {

        $project_no = session('current_project');
     
         $fileName = basename($attachment_Name);
        
         $filecontent = Storage::disk('ftp')->get($fileName);
     
         return Response::make($filecontent, '200', array(
             'Content-Type' => 'application/octet-stream',
             'Content-Disposition' => 'attachment; filename="'.$fileName.'"'
         ));
         //END NGAMBIL FILE
    }

    public function viewNotAssignTicket($id) {
            
        session(['menuParentActive' => "Ticketing"]);
        session(['menuSubActive' => "listTicketing"]);
        $id = base64_decode($id,true);
        $project_no = session('current_project');
        $email = session('email');

        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();
        $isAdmin=false;
        if($dataEmail -> ROLE == 1){
            $isAdmin=true;
        }

        $dataStatus = DB::table('TRANS_TICKET')->where('TRANS_TICKET_NOCHAR',$id)->where('status', 1)->first();
        $isStatus=false;
        if($dataStatus){
            $isStatus=true;
        }

        $viewDataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.DESC_CHAR, a.JUDUL_TICKET, a.JUDUL_TICKET,a.TRX_DATE,
        a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, b.NAMA, c.DESC_CHAR AS DESC_CHAR_APLIKASI, e.DESC_CHAR AS DESC_STATUS,
        d.DESC_CHAR AS DESC_CHAR_MD_TYPE_KELUHAN_TICKETING,e.TRANS_TICKET_STATUS_ID_INT, b.MD_USER_IT_ID_INT
        FROM TRANS_TICKET AS a
        LEFT JOIN MD_USER_IT as b ON a.PIC = b.MD_USER_IT_ID_INT
        LEFT JOIN MD_APLIKASI as c ON a.APLIKASI = c.MD_APLIKASI_ID_INT
        LEFT JOIN MD_TYPE_KELUHAN_TICKETING as d ON a.TYPE = d.MD_TYPE_KELUHAN_TICKETING_ID_INT
        INNER JOIN TRANS_TICKET_STATUS as e ON a.status = e.TRANS_TICKET_STATUS_ID_INT
        WHERE a.TRANS_TICKET_NOCHAR = '".$id."'
        ORDER BY a.created_at DESC");

        $viewAttachment = \DB::select("SELECT ATTACHMENT_NAME, created_by, created_at, ID
        FROM TRANS_TICKET_ATTACHMENT
        WHERE TRANS_TICKET_NOCHAR ='".$id."'
        ORDER BY created_at DESC");

        $dataStatus = DB::table('TRANS_TICKET_STATUS')->wherenotin('TRANS_TICKET_STATUS_ID_INT',[1])->get();
        // dd($role[] -> PARENT_USER_ID);
        if($dataEmail->PARENT_USER_ID == null){
            $dataPic = DB::table('MD_USER_IT')->where('status', 1)->get();
        }else{
            $dataPic = \DB::select("SELECT * FROM MD_USER_IT
            WHERE MD_USER_IT_ID_INT = '".$dataEmail->MD_USER_IT_ID_INT."' OR PARENT_USER_ID = '".$dataEmail->MD_USER_IT_ID_INT."'");
        }

        return view('Ticketing.viewNotAssignTicket',compact('isAdmin','isStatus'))
            ->with('project_no', $project_no)
            ->with('dataStatus', $dataStatus)
            ->with('dataPic', $dataPic)
            ->with('viewAttachment', $viewAttachment)
            ->with('viewDataTicket', $viewDataTicket);
    }

    public function editDataTicketNotAssign(Request $request) {
    
        $project_no = session('current_project');
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
        $userName = trim(session('first_name') . ' ' . session('last_name'));

        $email = session('email');
        $pic = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();
        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();

        $history = $request -> PIC;
        $history1 = DB::table('MD_USER_IT')->where('MD_USER_IT_ID_INT',$history)->where('STATUS', 1)->first();

        try {
            DB::beginTransaction();
            DB::table('TRANS_TICKET_HISTORY')->insert([
                'TRANS_TICKET_NOCHAR' => $request -> TRANS_TICKET_NOCHAR,
                'status' => 2,
                'OWNER' => $request -> PIC,
                'created_by' => $userName,
                'created_at' => $dateNow
            ]);

            DB::table('TRANS_TICKET')->where('TRANS_TICKET_NOCHAR',$request->TRANS_TICKET_NOCHAR)->update([
                'PIC' => $request -> PIC,
                'status' => 2,
                'updated_by' => $userName,
                'updated_at' => $dateNow
            ]);

            DB::table('MD_USER_IT')->where('MD_USER_IT_ID_INT', $history1 -> MD_USER_IT_ID_INT)->update([
                'COUNTER' =>  $history1 -> COUNTER + 1 
            ]);

            DB::commit();
        }    catch (QueryException $ex) {
            DB::rollback();
            session()->flash('error', 'Edit Ticket Gagal, errmsg : ' . $ex);
            return redirect()->route('viewListTicketing');
        }

         // Kirim Email //
         $dataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET, a.TRX_DATE, a.DESC_CHAR, 
         b.DESC_CHAR AS DESC_STATUS, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI,
         e.PROJECT_NAME, f.DESC_CHAR AS DESC_KELUHAN, g.HISTORY_DESC, a.REQUEST_BY_EMAIL 
         FROM TRANS_TICKET AS a 
         LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
         LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
         LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
         INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
         LEFT JOIN MD_TYPE_KELUHAN_TICKETING as f ON a.TYPE = f.MD_TYPE_KELUHAN_TICKETING_ID_INT
         LEFT JOIN TRANS_TICKET_HISTORY as g ON a.TRANS_TICKET_NOCHAR = g.TRANS_TICKET_NOCHAR
         WHERE a.TRANS_TICKET_NOCHAR = '".$request -> TRANS_TICKET_NOCHAR."'
         ORDER BY a.created_at DESC");
 
         $dataEmail = \DB::select("SELECT COUNTER,NAMA, EMAIL, HOST, PORT, ENCRYPTION, SANDI_APLIKASI
         FROM MD_EMAIL_SUPPORT
         WHERE STATUS = '1'
         ORDER BY COUNTER ASC");
 
         // Backup original mailer
         $backup = Mail::getSwiftMailer();
 
         // Setup your gmail mailer
         $transport = new Swift_SmtpTransport($dataEmail[0]->HOST, $dataEmail[0]->PORT, $dataEmail[0]->ENCRYPTION);
         $transport->setUsername($dataEmail[0]->EMAIL);
         $transport->setPassword($dataEmail[0]->SANDI_APLIKASI);
 
         $gmail = new Swift_Mailer($transport);
 
         // Set the mailer as gmail
         Mail::setSwiftMailer($gmail);
 
         // Send your message
         $subject = $dataTicket[0]->JUDUL_TICKET. " - " . $dataTicket[0] ->TRANS_TICKET_NOCHAR;
         $to = $dataTicket[0]->REQUEST_BY_EMAIL;

         $textHtml = "Dear".' '.$dataTicket[0] -> created_by.',<br><br>'.
         "Berikut Update Informasi Mengenai Ticket Anda :".
         "<p>User Request : ".'<b>'. $dataTicket[0] -> created_by.'</b></p>'.
         "<p>Nomor Ticket : ".''. $dataTicket[0] ->TRANS_TICKET_NOCHAR.'</p>'.
         "<p>Judul Ticket : ".''. $dataTicket[0]->JUDUL_TICKET.'</p>'.
         "<p>Type : ".''.  $dataTicket[0] -> DESC_KELUHAN.''.'</p>'.
         "<p>Aplikasi : ".''.  $dataTicket[0] -> DESC_CHAR_APLIKASI .''.'</p>'.
         "<p>Tanggal Request : ".''. date('d/m/Y',strtotime($dataTicket[0] -> TRX_DATE)).''.'</p>'.
         "<p>Deskripsi : ".''.  $dataTicket[0] -> DESC_CHAR.'</p>'.
         "<p>PIC : ".''.'<b>'.$dataTicket[0] -> NAMA.'</b>'.'</p>'.
         "<p>Attach :  Terlampir Dalam Ticket </p>".
         "<p>Status : ".'<b>'.  $dataTicket[0] -> DESC_STATUS .'</b>'.'</p>'.
         "<p>Url : ".'https://support.metropolitanland.com/viewDataTicketing/'.base64_encode($dataTicket[0] -> TRANS_TICKET_NOCHAR).'</p>'.
         "<p></p><p></p>"."Demikian Informasi Ini disampaikan, Terima Kasih.<p>
         Salam, <br>System Support<p>
         *Email Dikirim Otomatis, Dimohon Tidak di Reply Email Ini";
 
         Mail::send([], [], function ($message) use ($subject, $to, $textHtml, $dataEmail) {
         
         $message->from($dataEmail[0]->EMAIL, $dataEmail[0]->NAMA);
         $message->to($to)->subject($subject);
         $message->setBody($textHtml, 'text/html');
         });
          
         DB::table('MD_EMAIL_SUPPORT')->where('EMAIL',$dataEmail[0]-> EMAIL)->update([
            'COUNTER' => $dataEmail[0] -> COUNTER +1 
        ]);
         // Restore your original mailer
         Mail::setSwiftMailer($backup);


         // End Kirim Email

        session()->flash('success', "Edit Ticket Berhasil!");
        return redirect()->route('viewListTicketing');
    }

    public function editDataTicketHistory(Request $request) {
    
        $project_no = session('current_project');
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
        $userName = trim(session('first_name') . ' ' . session('last_name'));
        $reqStatus = $request -> status;  
        $dataStatus = DB::table('TRANS_TICKET_STATUS')->where('TRANS_TICKET_STATUS_ID_INT', $reqStatus)->first();
        $reqPic = $request -> PIC;  
        $dataPic = DB::table('MD_USER_IT')->where('MD_USER_IT_ID_INT', $reqPic)->first();
        $email = session('email');

        $dataPic2 = DB::table('TRANS_TICKET')->where('TRANS_TICKET_NOCHAR', $request -> TRANS_TICKET_NOCHAR)->first();
        $dataPic3 = DB::table('MD_USER_IT')->where('status', 1)->where('MD_USER_IT_ID_INT', $dataPic2 -> PIC)->first();

        $uniq = uniqid();
        
        $reqStatus = $request -> status;
        
        // Kirim Email //
        $dataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET, a.TRX_DATE, a.DESC_CHAR, 
         b.DESC_CHAR AS DESC_STATUS, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, c.COUNTER AS COUNTER_USER,
         d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME, f.DESC_CHAR AS DESC_KELUHAN, g.HISTORY_DESC, a.REQUEST_BY_EMAIL, a.REQUEST_BY_USER
         FROM TRANS_TICKET AS a 
         LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
         LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
         LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
         INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
         LEFT JOIN MD_TYPE_KELUHAN_TICKETING as f ON a.TYPE = f.MD_TYPE_KELUHAN_TICKETING_ID_INT
         LEFT JOIN TRANS_TICKET_HISTORY as g ON a.TRANS_TICKET_NOCHAR = g.TRANS_TICKET_NOCHAR
         WHERE a.TRANS_TICKET_NOCHAR = '".$request -> TRANS_TICKET_NOCHAR."'
         ORDER BY a.created_at DESC");
        
         $dataEmail = \DB::select("SELECT COUNTER, NAMA, EMAIL, HOST, PORT, ENCRYPTION, SANDI_APLIKASI
         FROM MD_EMAIL_SUPPORT
         WHERE STATUS = '1'
         ORDER BY COUNTER ASC");

        $dataPicEmail = DB::table('MD_USER_IT')->where('status', 1)->where('MD_USER_IT_ID_INT', $dataTicket[0]->PIC)->first();

         // Backup original mailer
         $backup = Mail::getSwiftMailer();
 
         // Setup your gmail mailer
         $transport = new Swift_SmtpTransport($dataEmail[0]->HOST, $dataEmail[0]->PORT, $dataEmail[0]->ENCRYPTION);
         $transport->setUsername($dataEmail[0]->EMAIL);
         $transport->setPassword($dataEmail[0]->SANDI_APLIKASI);
 
         $gmail = new Swift_Mailer($transport);
 
         // Set the mailer as gmail
         Mail::setSwiftMailer($gmail);

        if($dataTicket[0] -> PIC <> $reqPic ){
            // CHANGE PIC
            // Send your message to user
            $subject = $dataTicket[0]->JUDUL_TICKET. " - " . $dataTicket[0] ->TRANS_TICKET_NOCHAR;
            $to = $dataTicket[0]->REQUEST_BY_EMAIL;

            $textHtml = "Dear".' '.$dataTicket[0]->REQUEST_BY_USER.',<br><br>'.
                "Berikut Update Informasi Mengenai Ticket Anda :".' '.
                "<p>User Request : ".'<b>'. $dataTicket[0]->REQUEST_BY_USER.'</b>'.'</p>'.
                "<p>Nomor Ticket : ".''. $dataTicket[0] ->TRANS_TICKET_NOCHAR.''.'</p>'.
                "<p>Judul Ticket : ".''. $dataTicket[0]->JUDUL_TICKET.''.'</p>'.
                "<p>Type : ".''.  $dataTicket[0] -> DESC_KELUHAN.''.'</p>'.
                "<p>Aplikasi : ".''.  $dataTicket[0] -> DESC_CHAR_APLIKASI .''.'</p>'.
                "<p>Tanggal Request : ".''. date('d/m/Y',strtotime($dataTicket[0] -> TRX_DATE)).''.'</p>'.
                "<p>Deskripsi : ".''.  $dataTicket[0] -> DESC_CHAR.''.'</p>'.
                "<p>Attach :  Terlampir Dalam Ticket </p>".
                "<p>Status : ".'<b>'. $dataStatus -> DESC_CHAR .'</b>'.'</p>'.
                "<p>Url : ".'https://support.metropolitanland.com/viewDataTicketing/'.base64_encode($dataTicket[0] -> TRANS_TICKET_NOCHAR).'</p>'.
                "<p>PIC Awal : ".''.'<b>'.$dataTicket[0] -> NAMA.'</b>'.'</p>'.
                "<p>PIC Sekarang : ".''.'<b>'.$dataPic -> NAMA.'</b>'.'</p>'.
                "<p></p><p></p>"."Demikian Informasi Ini disampaikan, Terima Kasih.<p>
                Salam, <br>System Support<p>
                *Email Dikirim Otomatis, Dimohon Tidak di Reply Email Ini";

            DB::table('MD_EMAIL_SUPPORT')->where('EMAIL',$dataEmail[0]-> EMAIL)->update([
                'COUNTER' => $dataEmail[0] -> COUNTER +1 
            ]);

            // Send your message PIC
            $subject2 = $dataTicket[0]->JUDUL_TICKET. " - " . $dataTicket[0] ->TRANS_TICKET_NOCHAR;
            $to2 = $dataPic -> EMAIL;

            $textHtml2 = "Dear".' '.$dataPic -> NAMA.',<br><br>'.
                "Berikut Informasi Mengenai Pergantian Assign PIC Ticket ini :".' '.
                "<p>User Request : ".'<b>'. $dataTicket[0]->REQUEST_BY_USER.'</b>'.'</p>'.
                "<p>Nomor Ticket : ".''. $dataTicket[0] ->TRANS_TICKET_NOCHAR.''.'</p>'.
                "<p>Judul Ticket : ".''. $dataTicket[0]->JUDUL_TICKET.''.'</p>'.
                "<p>Type : ".''.  $dataTicket[0] -> DESC_KELUHAN.''.'</p>'.
                "<p>Aplikasi : ".''.  $dataTicket[0] -> DESC_CHAR_APLIKASI .''.'</p>'.
                "<p>Tanggal Request : ".''. date('d/m/Y',strtotime($dataTicket[0] -> TRX_DATE)) .''.'</p>'.
                "<p>Deskripsi : ".''.  $dataTicket[0] -> DESC_CHAR.''.'</p>'.
                "<p>Attach :  Terlampir Dalam Ticket </p>".
                "<p>Status : ".'<b>'. $dataStatus -> DESC_CHAR .'</b>'.'</p>'.
                "<p>Url : ".'https://support.metropolitanland.com/viewDataTicketing/'.base64_encode($dataTicket[0] -> TRANS_TICKET_NOCHAR).'</p>'.
                "<p>PIC Awal : ".''.'<b>'.$dataTicket[0] -> NAMA.'</b>'.'</p>'.
                "<p>PIC Sekarang : ".''.'<b>'.$dataPic -> NAMA.'</b>'.'</p>'.
                "<p></p><p></p>"."Demikian Informasi Ini disampaikan, Terima Kasih.<p>
                Salam, <br>System Support<p>
                *Email Dikirim Otomatis, Dimohon Tidak di Reply Email Ini";
            
            DB::table('MD_EMAIL_SUPPORT')->where('EMAIL',$dataEmail[1]-> EMAIL)->update([
                'COUNTER' => $dataEmail[1] -> COUNTER +1 
            ]);
            
            Mail::send([], [], function ($message) use ($subject2, $to2, $textHtml2, $dataEmail) {
            
                $message->from($dataEmail[1]->EMAIL, $dataEmail[1]->NAMA);
                $message->to($to2)->subject($subject2);
                $message->setBody($textHtml2, 'text/html');
                });
        }else{
            //Change Status or else
            if($dataTicket[0] -> REQUEST_BY_EMAIL == $email ){
                //From User to pic
                $subject = $dataTicket[0]->JUDUL_TICKET. " - " . $dataTicket[0] ->TRANS_TICKET_NOCHAR;
                $to = $dataPicEmail->EMAIL;

                $textHtml = "Dear".' '.$dataPicEmail -> NAMA.',<br><br>'.
                    "Berikut Update Informasi Mengenai Ticket Anda :".' '.
                    "<p>User Request : ".'<b>'. $dataTicket[0]->REQUEST_BY_USER.'</b>'.'</p>'.
                    "<p>Nomor Ticket : ".''. $dataTicket[0] ->TRANS_TICKET_NOCHAR.''.'</p>'.
                    "<p>Judul Ticket : ".''. $dataTicket[0]->JUDUL_TICKET.''.'</p>'.
                    "<p>Type : ".''.  $dataTicket[0] -> DESC_KELUHAN.''.'</p>'.
                    "<p>Aplikasi : ".''.  $dataTicket[0] -> DESC_CHAR_APLIKASI .''.'</p>'.
                    "<p>Tanggal Request : ".''. date('d/m/Y',strtotime($dataTicket[0] -> TRX_DATE)) .''.'</p>'.
                    "<p>Deskripsi : ".''.  $dataTicket[0] -> DESC_CHAR.''.'</p>'.
                    "<p>Attach :  Terlampir Dalam Ticket </p>".
                    "<p>PIC : ".''.'<b>'.$dataTicket[0] -> NAMA.'</b>'.'</p>'.
                    "<p>Attach :  Terlampir Dalam Ticket </p>".
                    "<p>Status : ".'<b>'. $dataStatus -> DESC_CHAR .'</b>'.'</p>'.
                    "<p>Respond : ".''.  $request -> HISTORY_DESC.''.'</p>'.
                    "<p>Url : ".'https://support.metropolitanland.com/viewDataTicketing/'.base64_encode($dataTicket[0] -> TRANS_TICKET_NOCHAR).'</p>'.
                    "<p></p><p></p>"."Demikian Informasi Ini disampaikan, Terima Kasih.<p>
                    Salam, <br>System Support<p>
                    *Email Dikirim Otomatis, Dimohon Tidak di Reply Email Ini";
            }else{
                //From PIC to user
                $subject = $dataTicket[0]->JUDUL_TICKET. " - " . $dataTicket[0] ->TRANS_TICKET_NOCHAR;
                $to = $dataTicket[0]->REQUEST_BY_EMAIL;

                $textHtml = "Dear".' '.$dataTicket[0]->REQUEST_BY_USER.',<br><br>'.
                    "Berikut Update Informasi Mengenai Ticket Anda :".' '.
                    "<p>User Request : ".'<b>'. $dataTicket[0]->REQUEST_BY_USER.'</b>'.'</p>'.
                    "<p>Nomor Ticket : ".''. $dataTicket[0] ->TRANS_TICKET_NOCHAR.''.'</p>'.
                    "<p>Judul Ticket : ".''. $dataTicket[0]->JUDUL_TICKET.''.'</p>'.
                    "<p>Type : ".''.  $dataTicket[0] -> DESC_KELUHAN.''.'</p>'.
                    "<p>Aplikasi : ".''.  $dataTicket[0] -> DESC_CHAR_APLIKASI .''.'</p>'.
                    "<p>Tanggal Request : ".''. date('d/m/Y',strtotime($dataTicket[0] -> TRX_DATE)) .''.'</p>'.
                    "<p>Deskripsi : ".''.  $dataTicket[0] -> DESC_CHAR.''.'</p>'.
                    "<p>Attach :  Terlampir Dalam Ticket </p>".
                    "<p>PIC : ".''.'<b>'.$dataTicket[0] -> NAMA.'</b>'.'</p>'.
                    "<p>Attach :  Terlampir Dalam Ticket </p>".
                    "<p>Status : ".'<b>'. $dataStatus -> DESC_CHAR .'</b>'.'</p>'.
                    "<p>Respond : ".''.  $request -> HISTORY_DESC.''.'</p>'.
                    "<p>Url : ".'https://support.metropolitanland.com/viewDataTicketing/'.base64_encode($dataTicket[0] -> TRANS_TICKET_NOCHAR).'</p>'.
                    "<p></p><p></p>"."Demikian Informasi Ini disampaikan, Terima Kasih.<p>
                    Salam, <br>System Support<p>
                    *Email Dikirim Otomatis, Dimohon Tidak di Reply Email Ini";
            }

            DB::table('MD_EMAIL_SUPPORT')->where('EMAIL',$dataEmail[0]-> EMAIL)->update([
                'COUNTER' => $dataEmail[0] -> COUNTER +1 
            ]);
        }

        sleep(3);

         Mail::send([], [], function ($message) use ($subject, $to, $textHtml, $dataEmail) {
         
         $message->from($dataEmail[0]->EMAIL, $dataEmail[0]->NAMA);
         $message->to($to)->subject($subject);
         $message->setBody($textHtml, 'text/html');
         });

         // Restore your original mailer
         Mail::setSwiftMailer($backup);

        if($request->hasFile('ATTACH')) {  
            foreach($request->file('ATTACH') as $att ){
        
                //get filename with extension
                $filenamewithextension = $att->getClientOriginalName();
        
                //filename to store
                $filenametostore = $uniq.'_'.$filenamewithextension;
        
                //Upload File to external server
                Storage::disk('ftp')->put($filenametostore, fopen($att, 'r+'));

                //Store $filenametostore in the database
            }
        } else {
            $filenametostore = "";
        }

        if($request->hasFile('ATTACH2')) {
            foreach($request->file('ATTACH2') as $att ){
   
            //get filename with extension
            $filenamewithextension = $att->getClientOriginalName();
      
            //filename to store
            $filenametostore2 = $uniq.'_'.$filenamewithextension;
          
            //Upload File to external server
            Storage::disk('ftp')->put($filenametostore2, fopen($att, 'r+'));

            //Store $filenametostore in the database
            }
        } else {
            $filenametostore2 = "";
        }

        if($request->hasFile('ATTACH3')) {
            foreach($request->file('ATTACH3') as $att ){

            //get filename with extension
            $filenamewithextension = $att->getClientOriginalName();
      
            //filename to store
            $filenametostore3 = $uniq.'_'.$filenamewithextension;
           
            //Upload File to external server
            Storage::disk('ftp')->put($filenametostore3, fopen($att, 'r+'));

            //Store $filenametostore in the database
        }
        } else {
            $filenametostore3 = "";
        }

        try {
            DB::beginTransaction();

            if($request -> PIC <> $dataTicket[0] -> PIC){
              
                DB::table('MD_USER_IT')->where('MD_USER_IT_ID_INT', $dataTicket[0]->PIC)->update([
                    'COUNTER' =>  $dataPic3  -> COUNTER - 1
                ]);
                DB::table('MD_USER_IT')->where('MD_USER_IT_ID_INT', $reqPic)->update([
                    'COUNTER' =>  $dataPic -> COUNTER + 1
                ]);
            }

            $idHistory = DB::table('TRANS_TICKET_HISTORY')->insertGetId([
                'TRANS_TICKET_NOCHAR' => $request -> TRANS_TICKET_NOCHAR,
                'status' => $request -> status,
                'HISTORY_DESC' => $request -> HISTORY_DESC,
                'OWNER' => $request-> PIC,
                'created_by' => $userName,
                'created_at' => $dateNow
            ]);

            DB::table('TRANS_TICKET')->where('TRANS_TICKET_NOCHAR',$request->TRANS_TICKET_NOCHAR)->update([
                'PIC' => $request-> PIC,
                'status' => $request -> status,
                'updated_by' => $userName,
                'updated_at' => $dateNow
            ]);
            
            if($request->hasFile('ATTACH')) {
                DB::table('TRANS_TICKET_ATTACHMENT')->insert([
                    'ATTACHMENT_NAME' => $filenametostore,
                    'ATTACHMENT_NAME2' => $filenametostore2,
                    'ATTACHMENT_NAME3' => $filenametostore3,
                    'TRANS_TICKET_NOCHAR' => $request -> TRANS_TICKET_NOCHAR,
                    'TICKET_HISTORY_ID' => $idHistory,
                    'created_by' => $userName,
                    'created_at' => $dateNow
                ]);
            }

            DB::commit();
        }    catch (QueryException $ex) {
            DB::rollback();
            session()->flash('error', 'Edit Ticket Gagal, errmsg : ' . $ex);
            return redirect()->route('viewListTicketing');
        }
 
         // End Kirim Email
        session()->flash('success', "Edit Ticket Berhasil!");
        return redirect()->route('viewListTicketing');
    }


    

    public function filterDataTicket(Request $request){
        session(['menuParentActive' => "Ticketing"]);
        session(['menuSubActive' => "listTicketing"]);
        
        $project_no = session('current_project');
        $email = session('email');
        $id = session('id');

        $startDate = '';
        $endDate = '';
        $status= $request->status;
        $noTicket= '';
        $queryDate = '';
        $queryStatus= '';
        $queryTicket= '';


        if($startDate != null & $endDate != null){
            $queryDate = " AND a.TRXDATE >= '".$request->startDate."' AND a.TRXDATE <= '".$request->endDate."'";
        }else{
            $queryDate = " ";
        }

        if($status <> 'all'){
            $queryStatus = " AND a.status = '".$request->status."'";
        }elseif($status == 'all'){
            $queryStatus = " AND a.status NOT IN (1,5)";
        }else{
            $queryStatus = " ";
        }

        if($noTicket != null){
            $queryTicket = " AND a.TRANS_TICKET_NOCHAR = '".$request->noTicket."'";
        }else{
            $queryTicket = " ";
        }

        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();
        $isAdmin=false;
        if($dataEmail){
            $isAdmin=true;
        }

        if($isAdmin == true){
            if($dataEmail -> ROLE == 1){
                $listDataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET,a.TRX_DATE, a.REQUEST_BY_USER, 
                b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
                FROM TRANS_TICKET AS a 
                LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
                LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
                INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
                WHERE a.status NOT IN (1,5) AND a.PIC = '".$dataEmail->MD_USER_IT_ID_INT."' ".$queryDate.$queryStatus.$queryTicket." 
                ORDER BY a.created_at DESC");

                $listDataTicket3 = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.DESC_CHAR, a.JUDUL_TICKET, a.REQUEST_BY_USER, a.updated_at,
                a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, b.NAMA, c.DESC_CHAR AS DESC_CHAR_APLIKASI, e.DESC_CHAR AS DESC_STATUS,
                d.DESC_CHAR AS DESC_CHAR_MD_TYPE_KELUHAN_TICKETING,f.PROJECT_NAME, e.TRANS_TICKET_STATUS_ID_INT,CURDATE() AS curent_dates,g.close_tiket,
                DATEDIFF(CURDATE(), g.close_tiket) AS DIFF_DATES
                FROM TRANS_TICKET AS a
                LEFT JOIN MD_USER_IT as b ON a.PIC = b.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as c ON a.APLIKASI = c.MD_APLIKASI_ID_INT
                LEFT JOIN MD_TYPE_KELUHAN_TICKETING as d ON a.TYPE = d.MD_TYPE_KELUHAN_TICKETING_ID_INT
                INNER JOIN TRANS_TICKET_STATUS as e ON a.status = e.TRANS_TICKET_STATUS_ID_INT
                INNER JOIN MD_PROJECT as f ON a.PROJECT = f.PROJECT_NO_CHAR
                INNER JOIN 
                    (
                        SELECT TRANS_TICKET_NOCHAR,MAX(created_at) AS close_tiket
                        FROM TRANS_TICKET_HISTORY
                        WHERE STATUS = 5
                        GROUP BY TRANS_TICKET_NOCHAR
                    ) AS g ON a.TRANS_TICKET_NOCHAR = g.TRANS_TICKET_NOCHAR 
                WHERE  a.status = 5 AND a.PIC = '".$dataEmail->MD_USER_IT_ID_INT."'
                ORDER BY a.created_at DESC");

                $listDataTicket4 = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET, a.REQUEST_BY_USER,
                b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
                FROM TRANS_TICKET AS a
                LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
                LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
                INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
                WHERE a.status NOT IN (1,5)
                ORDER BY a.created_at DESC");

            }else{
                $listDataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET,a.TRX_DATE, a.REQUEST_BY_USER,
                b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
                FROM TRANS_TICKET AS a 
                LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
                LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
                INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
                WHERE a.status NOT IN (1,5) AND a.PIC = '".$dataEmail->MD_USER_IT_ID_INT."' ".$queryDate.$queryStatus.$queryTicket."
                ORDER BY a.created_at DESC");

                $listDataTicket3 = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.DESC_CHAR, a.JUDUL_TICKET, a.REQUEST_BY_USER, a.updated_at,
                a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, b.NAMA, c.DESC_CHAR AS DESC_CHAR_APLIKASI, e.DESC_CHAR AS DESC_STATUS,
                d.DESC_CHAR AS DESC_CHAR_MD_TYPE_KELUHAN_TICKETING,f.PROJECT_NAME, e.TRANS_TICKET_STATUS_ID_INT,CURDATE() AS curent_dates,g.close_tiket,
                DATEDIFF(CURDATE(), g.close_tiket) AS DIFF_DATES
                FROM TRANS_TICKET AS a
                LEFT JOIN MD_USER_IT as b ON a.PIC = b.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as c ON a.APLIKASI = c.MD_APLIKASI_ID_INT
                LEFT JOIN MD_TYPE_KELUHAN_TICKETING as d ON a.TYPE = d.MD_TYPE_KELUHAN_TICKETING_ID_INT
                INNER JOIN TRANS_TICKET_STATUS as e ON a.status = e.TRANS_TICKET_STATUS_ID_INT
                INNER JOIN MD_PROJECT as f ON a.PROJECT = f.PROJECT_NO_CHAR
                INNER JOIN 
                    (
                        SELECT TRANS_TICKET_NOCHAR,MAX(created_at) AS close_tiket
                        FROM TRANS_TICKET_HISTORY
                        WHERE STATUS = 5
                        GROUP BY TRANS_TICKET_NOCHAR
                    ) AS g ON a.TRANS_TICKET_NOCHAR = g.TRANS_TICKET_NOCHAR 
                WHERE a.PIC = '".$dataEmail->MD_USER_IT_ID_INT."'  AND a.status = 5
                ORDER BY a.created_at DESC");

                $listDataTicket4 = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET, a.REQUEST_BY_USER,
                b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
                FROM TRANS_TICKET AS a
                LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
                LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
                INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
                WHERE c.MD_USER_IT_ID_INT = '".$dataEmail->MD_USER_IT_ID_INT."' OR c.PARENT_USER_ID = '".$dataEmail->MD_USER_IT_ID_INT."' And a.status NOT IN (1,5)
                ORDER BY a.created_at DESC");
            }
            $listDataTicket2 = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET, a.REQUEST_BY_USER,
            b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
            FROM TRANS_TICKET AS a
            LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
            LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
            LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
            INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
            WHERE a.status = '1'
            ORDER BY a.created_at DESC");
        }else{
            $listDataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET,a.TRX_DATE, a.REQUEST_BY_USER,
            b.DESC_CHAR, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, e.PROJECT_NAME
            FROM TRANS_TICKET AS a 
            LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
            LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
            LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
            INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
            WHERE a.status NOT IN (1,5) AND a.CREATED_BY_ID_SSO = '".$id."' ".$queryDate.$queryStatus.$queryTicket."
            ORDER BY a.created_at DESC");

            $listDataTicket2 = null;

            $listDataTicket3 = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.DESC_CHAR, a.JUDUL_TICKET, a.REQUEST_BY_USER, a.updated_at,
                a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, b.NAMA, c.DESC_CHAR AS DESC_CHAR_APLIKASI, e.DESC_CHAR AS DESC_STATUS,
                d.DESC_CHAR AS DESC_CHAR_MD_TYPE_KELUHAN_TICKETING,f.PROJECT_NAME, e.TRANS_TICKET_STATUS_ID_INT,CURDATE() AS curent_dates,g.close_tiket,
                DATEDIFF(CURDATE(), g.close_tiket) AS DIFF_DATES
                FROM TRANS_TICKET AS a
                LEFT JOIN MD_USER_IT as b ON a.PIC = b.MD_USER_IT_ID_INT
                LEFT JOIN MD_APLIKASI as c ON a.APLIKASI = c.MD_APLIKASI_ID_INT
                LEFT JOIN MD_TYPE_KELUHAN_TICKETING as d ON a.TYPE = d.MD_TYPE_KELUHAN_TICKETING_ID_INT
                INNER JOIN TRANS_TICKET_STATUS as e ON a.status = e.TRANS_TICKET_STATUS_ID_INT
                INNER JOIN MD_PROJECT as f ON a.PROJECT = f.PROJECT_NO_CHAR
                INNER JOIN 
                    (
                        SELECT TRANS_TICKET_NOCHAR,MAX(created_at) AS close_tiket
                        FROM TRANS_TICKET_HISTORY
                        WHERE STATUS = 5
                        GROUP BY TRANS_TICKET_NOCHAR
                    ) AS g ON a.TRANS_TICKET_NOCHAR = g.TRANS_TICKET_NOCHAR 
                WHERE a.CREATED_BY_ID_SSO = '".$id."'  AND a.status = 5
                ORDER BY a.created_at DESC");

                $listDataTicket4 = null;

        }

        $dataStatus = DB::table('TRANS_TICKET_STATUS')->whereNotIn('TRANS_TICKET_STATUS_ID_INT',[1])->get();
    
        return view('Ticketing.listViewTicketing',compact('isAdmin'))
        ->with('listDataTicket', $listDataTicket)
        ->with('listDataTicket2', $listDataTicket2)
        ->with('listDataTicket3', $listDataTicket3)
        ->with('listDataTicket4', $listDataTicket4)
        ->with('dataStatus', $dataStatus)
        ->with('project_no', $project_no);
            
    }

    public function viewCloseTicketing($id) {
        session(['menuParentActive' => "Ticketing"]);
        session(['menuSubActive' => "listTicketing"]);
        $id = base64_decode($id,true);
        $project_no = session('current_project');
     
        $email = session('email');

        $dataEmail = DB::table('MD_USER_IT')->where('EMAIL',$email)->where('STATUS', 1)->first();

        $isAdmin=false;

        if($dataEmail){
            $isAdmin = true;
        }
        
        $viewDataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.DESC_CHAR, a.JUDUL_TICKET,a.TRX_DATE,
        a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, b.NAMA, c.DESC_CHAR AS DESC_CHAR_APLIKASI, e.DESC_CHAR AS DESC_STATUS,
        d.DESC_CHAR AS DESC_CHAR_MD_TYPE_KELUHAN_TICKETING,f.PROJECT_NAME, e.TRANS_TICKET_STATUS_ID_INT,CURDATE() AS curent_dates,g.close_tiket, h.TICKET_HISTORY_ID,
        DATEDIFF(CURDATE(), g.close_tiket) AS DIFF_DATES,
        (SELECT HISTORY_DESC FROM TRANS_TICKET_HISTORY WHERE TRANS_TICKET_NOCHAR = a.TRANS_TICKET_NOCHAR ORDER BY created_at DESC LIMIT 1)
        AS HISTORY_DESC
        FROM TRANS_TICKET AS a
        LEFT JOIN MD_USER_IT as b ON a.PIC = b.MD_USER_IT_ID_INT
        LEFT JOIN MD_APLIKASI as c ON a.APLIKASI = c.MD_APLIKASI_ID_INT
        LEFT JOIN MD_TYPE_KELUHAN_TICKETING as d ON a.TYPE = d.MD_TYPE_KELUHAN_TICKETING_ID_INT
        INNER JOIN TRANS_TICKET_STATUS as e ON a.status = e.TRANS_TICKET_STATUS_ID_INT
        INNER JOIN MD_PROJECT as f ON a.PROJECT = f.PROJECT_NO_CHAR
        INNER JOIN 
            (
                SELECT TRANS_TICKET_NOCHAR,MAX(created_at) AS close_tiket
                FROM TRANS_TICKET_HISTORY
                WHERE STATUS = 5
                GROUP BY TRANS_TICKET_NOCHAR
            ) AS g ON a.TRANS_TICKET_NOCHAR = g.TRANS_TICKET_NOCHAR 
        LEFT JOIN TRANS_TICKET_HISTORY as h ON a.TRANS_TICKET_NOCHAR = h.TRANS_TICKET_NOCHAR
        WHERE a.status = 5 AND a.TRANS_TICKET_NOCHAR = '".$id."'
        ORDER BY a.created_at DESC");

        $listTicketHistory = \DB::select("SELECT a.TRANS_TICKET_NOCHAR,b.JUDUL_TICKET,b.created_by, a.created_by AS created_history, 
        a.HISTORY_DESC, d.NAMA, c.DESC_CHAR, a.created_at, e.PROJECT_NAME, a.TICKET_HISTORY_ID, f.ID, f.ATTACHMENT_NAME, f.ATTACHMENT_NAME2, f.ATTACHMENT_NAME3
        FROM TRANS_TICKET_HISTORY AS a
        INNER JOIN TRANS_TICKET AS b ON a.TRANS_TICKET_NOCHAR = b.TRANS_TICKET_NOCHAR
        LEFT JOIN TRANS_TICKET_STATUS AS c ON a.status = c.TRANS_TICKET_STATUS_ID_INT
        LEFT JOIN MD_USER_IT AS d ON a.OWNER = d.MD_USER_IT_ID_INT
        LEFT JOIN MD_PROJECT AS e ON b.PROJECT =  e.PROJECT_NO_CHAR
        LEFT JOIN TRANS_TICKET_ATTACHMENT AS f ON a.TICKET_HISTORY_ID = f.TICKET_HISTORY_ID
        WHERE a.TRANS_TICKET_NOCHAR = '".$id."'
        ORDER BY a.created_at ASC");

        $dataStatus = DB::table('TRANS_TICKET_STATUS')->get();
        $dataPic = DB::table('MD_USER_IT')->where('status', 1)->wherenotin('ROLE',[1])->get();

        return view('Ticketing.viewClosedTicket',compact('isAdmin'))
            ->with('project_no', $project_no)
            ->with('dataStatus', $dataStatus)
            ->with('dataPic', $dataPic)
            ->with('listTicketHistory', $listTicketHistory)
            ->with('viewDataTicket', $viewDataTicket);
    }

    public function reopenDataTicketing(Request $request){
        session(['menuParentActive' => "Ticketing"]);
        session(['menuSubActive' => "listTicketing"]);
        $project_no = session('current_project');
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
        $userName = trim(session('first_name') . ' ' . session('last_name'));
        $id = base64_decode($request -> Url,true);
        $dataTicket1 = DB::table('TRANS_TICKET')->where('TRANS_TICKET_NOCHAR',$id)->first();

        try {
            DB::beginTransaction();
    
            DB::table('TRANS_TICKET_HISTORY')->insert([
                'TRANS_TICKET_NOCHAR' => $id,
                'HISTORY_DESC' => $request -> RESPOND,
                'status' => 2,
                'OWNER' => $dataTicket1-> PIC,
                'created_by' => $userName,
                'created_at' => $dateNow
            ]);

            DB::table('TRANS_TICKET')->where('TRANS_TICKET_NOCHAR',$id)->update([
                'status' => 2,
                'updated_by' => $userName,
                'updated_at' => $dateNow
            ]);

            DB::commit();
        }    catch (QueryException $ex) {
            DB::rollback();
            return response()->json(['error'=>'Reopen Ticket Gagal !','URL' => "/viewListTicketing"]);
        }
        // Kirim Email //

        $dataEmail = \DB::select("SELECT COUNTER,NAMA, EMAIL, HOST, PORT, ENCRYPTION, SANDI_APLIKASI
        FROM MD_EMAIL_SUPPORT
        WHERE STATUS = '1'
        ORDER BY COUNTER ASC");

        $dataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET, a.TRX_DATE, a.DESC_CHAR, c.EMAIL, 
        b.DESC_CHAR AS DESC_STATUS, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI,
        e.PROJECT_NAME, f.DESC_CHAR AS DESC_KELUHAN, g.HISTORY_DESC, a.REQUEST_BY_USER
        FROM TRANS_TICKET AS a 
        LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
        LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
        LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
        INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
        LEFT JOIN MD_TYPE_KELUHAN_TICKETING as f ON a.TYPE = f.MD_TYPE_KELUHAN_TICKETING_ID_INT
        LEFT JOIN TRANS_TICKET_HISTORY as g ON a.TRANS_TICKET_NOCHAR = g.TRANS_TICKET_NOCHAR
        WHERE a.TRANS_TICKET_NOCHAR = '".$id."' AND a.PROJECT = '".$project_no."'
        ORDER BY a.created_at DESC");

        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));

        // Backup original mailer
        $backup = Mail::getSwiftMailer();

        // Setup your gmail mailer
        $transport = new Swift_SmtpTransport($dataEmail[0]->HOST, $dataEmail[0]->PORT, $dataEmail[0]->ENCRYPTION);
        $transport->setUsername($dataEmail[0]->EMAIL);
        $transport->setPassword($dataEmail[0]->SANDI_APLIKASI);

        $gmail = new Swift_Mailer($transport);

        // Set the mailer as gmail
        Mail::setSwiftMailer($gmail);

        // Send your message
        $subject = $dataTicket[0] -> JUDUL_TICKET. " - " . $dataTicket[0] -> TRANS_TICKET_NOCHAR;
        $to = $dataTicket[0] -> EMAIL;

        $textHtml = "<p>Dear".' '. $dataTicket[0] -> NAMA.',</p><br>'.
        "Berikut Update Informasi Mengenai Ticket Anda :".' '.
        "<p>User Request : ".'<b>'. $dataTicket[0] -> REQUEST_BY_USER.'</b>'.'</p>'.
        "<p>Nomor Ticket : ".''. $dataTicket[0] -> TRANS_TICKET_NOCHAR.''.'</p>'.
        "<p>Judul Ticket : ".''.$dataTicket[0] -> JUDUL_TICKET.''.'</p>'.
        "<p>Type : ".''. $dataTicket[0] -> DESC_KELUHAN.''.'</p>'.
        "<p>Aplikasi : ".''. $dataTicket[0] -> DESC_CHAR_APLIKASI.''.'</p>'.
        "<p>Tanggal Request : ".''. date('d/m/Y',strtotime($dataTicket[0] -> TRX_DATE)) .''.'</p>'.
        "<p>Deskripsi : ".''. $dataTicket[0] ->DESC_CHAR.'</p>'.
        "<p>Status : ".'<b>Re-'. $dataTicket[0] -> DESC_STATUS.'</b>'.'</p>'.
        "<p>Respond : ".''. $request -> RESPOND.'</p>'.
        "<p>Url : ".'https://support.metropolitanland.com/viewDataTicketing/'.base64_encode($id).'</p>'.
        "<p><p><p>"."Demikian Informasi Ini disampaikan, Terima Kasih.<p>
        Salam, <br>System Support<p>
        *Email Dikirim Otomatis, Dimohon Tidak di Reply Email Ini.";

        Mail::send([], [], function ($message) use ($subject, $to, $textHtml, $dataEmail) {
                
            $message->from($dataEmail[0]->EMAIL, $dataEmail[0]->NAMA);
            $message->to($to)->subject($subject);
            $message->setBody($textHtml, 'text/html');
        });

        DB::table('MD_EMAIL_SUPPORT')->where('EMAIL',$dataEmail[0]-> EMAIL)->update([
            'COUNTER' => $dataEmail[0] -> COUNTER +1 
        ]);

        // Restore your original mailer
        Mail::setSwiftMailer($backup);

        // End Kirim Email

        return response()->json(['success'=>'Reopend Ticket Berhasil !','URL' => "/viewListTicketing"]);
    }

    public function respondCloseTicketing(Request $request){
        session(['menuParentActive' => "Ticketing"]);
        session(['menuSubActive' => "listTicketing"]);
        $project_no = session('current_project');
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
        $userName = trim(session('first_name') . ' ' . session('last_name'));
        $id = base64_decode($request -> Url,true);
        $dataTicket1 = DB::table('TRANS_TICKET')->where('TRANS_TICKET_NOCHAR',$id)->first();

        try {
            DB::beginTransaction();
    
            DB::table('TRANS_TICKET_HISTORY')->insert([
                'TRANS_TICKET_NOCHAR' => $id,
                'HISTORY_DESC' => $request -> RESPOND,
                'status' => 5,
                'OWNER' => $dataTicket1-> PIC,
                'created_by' => $userName,
                'created_at' => $dateNow
            ]);

            DB::table('TRANS_TICKET')->where('TRANS_TICKET_NOCHAR',$id)->update([
                'status' => 5,
                'updated_by' => $userName,
                'updated_at' => $dateNow
            ]);

            DB::commit();
        }    catch (QueryException $ex) {
            DB::rollback();
            return response()->json(['error'=>'Respond Ticket Gagal !','URL' => "/viewListTicketing"]);
        }
        // Kirim Email //

        $dataEmail = \DB::select("SELECT COUNTER,NAMA, EMAIL, HOST, PORT, ENCRYPTION, SANDI_APLIKASI
        FROM MD_EMAIL_SUPPORT
        WHERE STATUS = '1'
        ORDER BY COUNTER ASC");
        
        $dataTicket = \DB::select("SELECT a.TYPE, a.APLIKASI, a.PIC, a.status, a.JUDUL_TICKET, a.TRX_DATE, a.DESC_CHAR, c.EMAIL, 
        b.DESC_CHAR AS DESC_STATUS, a.created_by, a.created_at, a.TRANS_TICKET_NOCHAR, c.NAMA, d.DESC_CHAR AS DESC_CHAR_APLIKASI, 
        e.PROJECT_NAME, f.DESC_CHAR AS DESC_KELUHAN, g.HISTORY_DESC, a.REQUEST_BY_USER
        FROM TRANS_TICKET AS a 
        LEFT JOIN TRANS_TICKET_STATUS as b ON a.status = b.TRANS_TICKET_STATUS_ID_INT
        LEFT JOIN MD_USER_IT as c ON a.PIC = c.MD_USER_IT_ID_INT
        LEFT JOIN MD_APLIKASI as d ON a.APLIKASI = d.MD_APLIKASI_ID_INT
        INNER JOIN MD_PROJECT as e ON a.PROJECT = e.PROJECT_NO_CHAR
        LEFT JOIN MD_TYPE_KELUHAN_TICKETING as f ON a.TYPE = f.MD_TYPE_KELUHAN_TICKETING_ID_INT
        LEFT JOIN TRANS_TICKET_HISTORY as g ON a.TRANS_TICKET_NOCHAR = g.TRANS_TICKET_NOCHAR
        WHERE a.TRANS_TICKET_NOCHAR = '".$id."' AND a.PROJECT = '".$project_no."'
        ORDER BY a.created_at DESC");

        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));

        // Backup original mailer
        $backup = Mail::getSwiftMailer();

        // Setup your gmail mailer
        $transport = new Swift_SmtpTransport($dataEmail[0]->HOST, $dataEmail[0]->PORT, $dataEmail[0]->ENCRYPTION);
        $transport->setUsername($dataEmail[0]->EMAIL);
        $transport->setPassword($dataEmail[0]->SANDI_APLIKASI);

        $gmail = new Swift_Mailer($transport);

        // Set the mailer as gmail
        Mail::setSwiftMailer($gmail);

        // Send your message
        $subject = $dataTicket[0] -> JUDUL_TICKET. " - " . $dataTicket[0] -> TRANS_TICKET_NOCHAR;
        $to = $dataTicket[0] -> EMAIL;

        $textHtml = "<p>Dear".' '. $dataTicket[0] -> NAMA.',</p><br>'.
        "Berikut Update Informasi Mengenai Ticket Anda :".' '.
        "<p>User Request : ".'<b>'. $dataTicket[0] -> REQUEST_BY_USER.'</b>'.'</p>'.
        "<p>Nomor Ticket : ".''. $dataTicket[0] -> TRANS_TICKET_NOCHAR.''.'</p>'.
        "<p>Judul Ticket : ".''.$dataTicket[0] -> JUDUL_TICKET.''.'</p>'.
        "<p>Type : ".''. $dataTicket[0] -> DESC_KELUHAN.''.'</p>'.
        "<p>Aplikasi : ".''. $dataTicket[0] -> DESC_CHAR_APLIKASI.''.'</p>'.
        "<p>Tanggal Request : ".''. date('d/m/Y',strtotime($dataTicket[0] -> TRX_DATE)) .''.'</p>'.
        "<p>Deskripsi : ".''. $dataTicket[0] ->DESC_CHAR.'</p>'.
        "<p>Status : ".''. $dataTicket[0] -> DESC_STATUS.'</b>'.'</p>'.
        "<p>Respond : ".''. $request -> RESPOND.'</p>'.
        "<p>Url : ".'https://support.metropolitanland.com/viewDataTicketing/'.base64_encode($id).'</p>'.
        "<p><p><p>"."Demikian Informasi Ini disampaikan, Terima Kasih.<p>
        Salam, <br>System Support<p>
        *Email Dikirim Otomatis, Dimohon Tidak di Reply Email Ini.";

        Mail::send([], [], function ($message) use ($subject, $to, $textHtml, $dataEmail) {
                
            $message->from($dataEmail[0]->EMAIL, $dataEmail[0]->NAMA);
            $message->to($to)->subject($subject);
            $message->setBody($textHtml, 'text/html');
        });

        DB::table('MD_EMAIL_SUPPORT')->where('EMAIL',$dataEmail[0]-> EMAIL)->update([
            'COUNTER' => $dataEmail[0] -> COUNTER +1 
        ]);

        // Restore your original mailer
        Mail::setSwiftMailer($backup);

        // End Kirim Email
        return response()->json(['success'=>'Respond Ticket Berhasil!','URL' => "/viewListTicketing"]);
    }
}
