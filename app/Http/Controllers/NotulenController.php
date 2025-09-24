<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotulenController extends Controller
{
    public function viewInputNotulen()
    {
        session(['menuParentActive' => "Notulen"]);
        session(['menuSubActive' => "viewInputNotulen"]);

        $projectNo = session('current_project');

        $rows = DB::table('NOTULEN_TRANS')
            ->where('PROJECT_NO_CHAR', $projectNo)
            ->where('IS_DELETE', 0)
            ->orderBy('NOTULEN_TRANS_CREATED_AT', 'desc')
            ->limit(50)
            ->get();

        return view('Notulen.viewInputNotulen')
            ->with('project_no', $projectNo)
            ->with('rows', $rows);
    }

    public function listNotulen()
    {
        session(['menuParentActive' => "Notulen"]);
        session(['menuSubActive' => "listNotulen"]);
        $projectNo = session('current_project');

        $rows = DB::table('NOTULEN_TRANS')
            ->where('PROJECT_NO_CHAR', $projectNo)
            ->where('IS_DELETE', 0)
            ->orderBy('NOTULEN_TRANS_CREATED_AT', 'desc')
            ->get();

        return view('Notulen.viewListNotulen')
            ->with('rows', $rows);
    }

    public function editNotulen($id)
    {
        session(['menuParentActive' => "Notulen"]);
        session(['menuSubActive' => "listNotulen"]);

        $header = DB::table('NOTULEN_TRANS')
            ->where('NOTULEN_TRANS_ID', $id)
            ->where('IS_DELETE', 0)
            ->first();
        if (!$header) {
            abort(404);
        }
        $attendance = DB::table('NOTULEN_ATTENDANCE')->where('NOTULEN_TRANS_ID', $id)->get();
        $details = DB::table('NOTULEN_DETAIL')->where('NOTULEN_TRANS_ID', $id)->get();

        return view('Notulen.viewEditNotulen')
            ->with('header', $header)
            ->with('attendance', $attendance)
            ->with('details', $details);
    }

    public function showNotulen($id)
    {
        session(['menuParentActive' => "Notulen"]);
        session(['menuSubActive' => "listNotulen"]);

        $header = DB::table('NOTULEN_TRANS')
            ->where('NOTULEN_TRANS_ID', $id)
            ->where('IS_DELETE', 0)
            ->first();
        if (!$header) {
            abort(404);
        }
        $attendance = DB::table('NOTULEN_ATTENDANCE')->where('NOTULEN_TRANS_ID', $id)->get();
        $details = DB::table('NOTULEN_DETAIL')->where('NOTULEN_TRANS_ID', $id)->get();

        return view('Notulen.viewShowNotulen')
            ->with('header', $header)
            ->with('attendance', $attendance)
            ->with('details', $details);
    }

    public function updateNotulen($id, Request $request)
    {
        $projectNo = session('current_project');
        $userName = trim(session('first_name') . ' ' . session('last_name'));
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));

        $request->validate([
            'tema_rapat' => 'required|string|max:200',
            'tanggal_rapat' => 'required|date',
            'attendance' => 'required|array|min:1',
            'attendance.*.name' => 'required|string|max:50',
            'attendance.*.job_level' => 'nullable|string|max:50',
            'attendance.*.email' => 'nullable|email|max:50',
            'points' => 'required|array|min:1',
            'points.*.title' => 'required|string|max:200',
            'points.*.desc' => 'required|string',
            'points.*.attendance_index' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            DB::table('NOTULEN_TRANS')->where('NOTULEN_TRANS_ID', $id)->update([
                'NOTULEN_TRANS_NAME' => $request->input('tema_rapat'),
                'NOTULEN_TRANS_DATETIME' => $request->input('tanggal_rapat'),
                'updated_at' => $dateNow,
            ]);

            DB::table('NOTULEN_ATTENDANCE')->where('NOTULEN_TRANS_ID', $id)->delete();
            DB::table('NOTULEN_DETAIL')->where('NOTULEN_TRANS_ID', $id)->delete();

            $attendanceInput = $request->input('attendance');
            $attendanceIdMap = [];
            foreach ($attendanceInput as $index => $att) {
                $attId = DB::table('NOTULEN_ATTENDANCE')->insertGetId([
                    'NOTULEN_TRANS_ID' => $id,
                    'NOTULEN_ATTENDANCE_NAME' => $att['name'],
                    'NOTULEN_ATTENDANCE_LEVEL' => $att['job_level'] ?? null,
                    'NOTULEN_ATTENDANCE_EMAIL' => $att['email'] ?? null,
                    'NOTULEN_ATTENDANCE_CREATED_BY' => $userName,
                    'NOTULEN_ATTENDANCE_CREATED_AT' => $dateNow,
                    'PROJECT_NO_CHAR' => $projectNo,
                    'created_at' => $dateNow,
                    'updated_at' => $dateNow,
                ], 'NOTULEN_ATTENDANCE_ID');
                $attendanceIdMap[$index] = $attId;
            }

            foreach ($request->input('points') as $p) {
                DB::table('NOTULEN_DETAIL')->insert([
                    'NOTULEN_TRANS_ID' => $id,
                    'NOTULEN_DETAIL_TITLE' => $p['title'],
                    'NOTULEN_DETAIL_DESC' => $p['desc'],
                    'NOTULEN_ATTENDANCE_ID' => $attendanceIdMap[$p['attendance_index']],
                    'NOTULEN_DETAIL_CREATED_BY' => $userName,
                    'NOTULEN_DETAIL_CREATED_AT' => $dateNow,
                    'PROJECT_NO_CHAR' => $projectNo,
                    'created_at' => $dateNow,
                    'updated_at' => $dateNow,
                ]);
            }

            DB::commit();
            session()->flash('success', 'Notulen berhasil diupdate.');
            if ($request->input('action') === 'stay') {
                return redirect()->route('editNotulen', ['id' => $id]);
            } else {
                return redirect()->route('viewInputNotulen');
            }
        } catch (\Throwable $ex) {
            DB::rollBack();
            session()->flash('error', 'Gagal update notulen: ' . $ex->getMessage());
            return redirect()->route('editNotulen', ['id' => $id]);
        }
    }

    public function deleteNotulen($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));
            $userName = trim(session('first_name') . ' ' . session('last_name'));
            
            // Soft delete NOTULEN_TRANS
            DB::table('NOTULEN_TRANS')
                ->where('NOTULEN_TRANS_ID', $id)
                ->update([
                    'IS_DELETE' => 1,
                    'NOTULEN_TRANS_UPDATED_BY' => $userName,
                    'NOTULEN_TRANS_UPDATED_AT' => $dateNow,
                    'updated_at' => $dateNow,
                ]);
            
            DB::commit();
            session()->flash('success', 'Notulen berhasil dihapus.');
        } catch (\Throwable $ex) {
            DB::rollBack();
            session()->flash('error', 'Gagal hapus notulen: ' . $ex->getMessage());
        }
        return redirect()->route('viewInputNotulen');
    }

    public function saveNotulen(Request $request)
    {
        $projectNo = session('current_project');
        $userName = trim(session('first_name') . ' ' . session('last_name'));
        $dateNow = Carbon::parse(Carbon::now(new \DateTimeZone('Asia/Jakarta')));

        $request->validate([
            'tema_rapat' => 'required|string|max:200',
            'tanggal_rapat' => 'required|date',
            'attendance' => 'required|array|min:1',
            'attendance.*.name' => 'required|string|max:50',
            'attendance.*.job_level' => 'nullable|string|max:50',
            'attendance.*.email' => 'nullable|email|max:50',
            'points' => 'required|array|min:1',
            'points.*.title' => 'required|string|max:200',
            'points.*.desc' => 'required|string',
            'points.*.attendance_index' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Ambil project & counter untuk buat nomor notulen
            $project = DB::table('MD_PROJECT')->where('PROJECT_NO_CHAR', $projectNo)->first();
            $counter = DB::table('counter_table')->where('PROJECT_NO_CHAR', $projectNo)->lockForUpdate()->first();
            $seq = $counter ? intval($counter->TRANS_NOTULEN_NOCHAR) : 1;
            $seqStr = sprintf("%05d", $seq);
            $projectCode = $project ? $project->PROJECT_CODE : 'PRJ';
            $month = date('m', strtotime($request->input('tanggal_rapat')));
            $year = date('y', strtotime($request->input('tanggal_rapat')));
            $notulenNoChar = $seqStr . '/' . $projectCode . '/IT/' . $month . '/' . $year;

            $notulenTransId = DB::table('NOTULEN_TRANS')->insertGetId([
                'NOTULEN_TRANS_NAME' => $request->input('tema_rapat'),
                'NOTULEN_TRANS_DATETIME' => $request->input('tanggal_rapat'),
                'NOTULEN_TRANS_NOCHAR' => $notulenNoChar,
                'NOTULEN_TRANS_CREATED_BY' => $userName,
                'NOTULEN_TRANS_CREATED_AT' => $dateNow,
                'PROJECT_NO_CHAR' => $projectNo,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ], 'NOTULEN_TRANS_ID');

            $attendanceInput = $request->input('attendance');
            $attendanceIdMap = [];
            foreach ($attendanceInput as $index => $att) {
                $id = DB::table('NOTULEN_ATTENDANCE')->insertGetId([
                    'NOTULEN_TRANS_ID' => $notulenTransId,
                    'NOTULEN_ATTENDANCE_NAME' => $att['name'],
                    'NOTULEN_ATTENDANCE_LEVEL' => $att['job_level'] ?? null,
                    'NOTULEN_ATTENDANCE_EMAIL' => $att['email'] ?? null,
                    'NOTULEN_ATTENDANCE_CREATED_BY' => $userName,
                    'NOTULEN_ATTENDANCE_CREATED_AT' => $dateNow,
                    'PROJECT_NO_CHAR' => $projectNo,
                    'created_at' => $dateNow,
                    'updated_at' => $dateNow,
                ], 'NOTULEN_ATTENDANCE_ID');
                $attendanceIdMap[$index] = $id;
            }

            $points = $request->input('points');
            foreach ($points as $p) {
                $attIndex = $p['attendance_index'];
                $attId = $attendanceIdMap[$attIndex] ?? null;

                DB::table('NOTULEN_DETAIL')->insert([
                    'NOTULEN_TRANS_ID' => $notulenTransId,
                    'NOTULEN_DETAIL_TITLE' => $p['title'],
                    'NOTULEN_DETAIL_DESC' => $p['desc'],
                    'NOTULEN_ATTENDANCE_ID' => $attId,
                    'NOTULEN_DETAIL_CREATED_BY' => $userName,
                    'NOTULEN_DETAIL_CREATED_AT' => $dateNow,
                    'PROJECT_NO_CHAR' => $projectNo,
                    'created_at' => $dateNow,
                    'updated_at' => $dateNow,
                ]);
            }

            // Naikkan counter notulen
            if ($counter) {
                DB::table('counter_table')->where('PROJECT_NO_CHAR', $projectNo)->update([
                    'TRANS_NOTULEN_NOCHAR' => $seq + 1,
                    'updated_at' => $dateNow,
                ]);
            }

            DB::commit();

            session()->flash('success', 'Notulen berhasil disimpan.');
            return redirect()->route('viewInputNotulen');
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error('Gagal simpan notulen', [
                'error' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString(),
                'input' => $request->all(),
                'user' => $userName,
                'project' => $projectNo
            ]);
            session()->flash('error', 'Gagal simpan notulen: ' . $ex->getMessage() . '. Silakan cek data dan coba lagi.');
            return redirect()->route('viewInputNotulen');
        }
    }

    /**
     * Print Preview Notulen
     */
    public function printPreview($id)
    {
        $header = DB::table('NOTULEN_TRANS')
            ->where('NOTULEN_TRANS_ID', $id)
            ->where('IS_DELETE', 0)
            ->first();
        if (!$header) {
            abort(404);
        }
        $attendance = DB::table('NOTULEN_ATTENDANCE')->where('NOTULEN_TRANS_ID', $id)->get();
        $details = DB::table('NOTULEN_DETAIL')->where('NOTULEN_TRANS_ID', $id)->get();

        return view('Notulen.viewPrintNotulen')
            ->with('header', $header)
            ->with('attendance', $attendance)
            ->with('details', $details);
    }
}