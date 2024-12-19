<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Comment;
use App\Models\Report;
use App\Models\Response;
use App\Models\ResponseProgress;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reports = Report::where('user_id', Auth::id())->get();
        return view('guest.index', compact('reports'));
    }

    public function dashboard()
    {
        $reports = Report::with('response.response_progress')->where('user_id', Auth::id())->latest()->get();
        $responses = Response::all();
        $response_progress = ResponseProgress::all();
        return view('guest.dashboard', compact('reports', 'responses', 'response_progress'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('guest.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'type' => 'required',
            'province' => 'required',
            'regency' => 'required',
            'subdistrict' => 'required',
            'village' => 'required',
            'image' => 'required',
            'statement' => 'nullable',
        ]);

        $file = $request->file('image');
        $filePath = $file->storeAs('uploads', time() . '_' . $file->getClientOriginalName(), 'public');

        $statement = $request->has('statement') && $request->statement === 'on';

        $process = Report::create([
            'user_id' => Auth::user()->id,
            'description' => $request->description,
            'type' => $request->type,
            'province' => $request->province,
            'regency' => $request->regency,
            'subdistrict' => $request->subdistrict,
            'village' => $request->village,
            'image' => $filePath,
            'statement' => $statement,
        ]);

        if ($process) {
            return redirect()->route('guest.dashboard')->with('success', 'Pengaduan Berhasil ditambahkan!');
        } else {
            return redirect()->back()->with('failed', 'Pengaduan gagal ditambahkan! silahkan coba kembali');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $report = Report::find($id);

        if ($report) {
            $comments = Comment::where('report_id', $report->id)->get();

            $reports = Report::where('id', $report->id)->get();
        } else {
            $reports = [];
            $comments = [];
        }

        return view('guest.show', compact('reports', 'comments'));
    }

    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $proses = Report::where('id', $id)->delete();

        if ($proses) {
            return redirect()->back()->with('success', 'Data Berhasil Dihapus!');
        } else {
            return redirect()->back()->with('failed', 'Data Gagal Dihapus!');
        }
    }

    public function vote($id, Request $request)
    {
        $userId = auth()->id();

        $report = Report::findOrFail($id);

        if (in_array($userId, $report->voting)) {
            return response()->json(['error' => 'You have already voted for this report.'], 403);
        }
        $report->voting = array_merge($report->voting, [$userId]);
        $voteCount = count($report->voting);

        $report->save();

        return response()->json([
            'message' => 'Vote submitted successfully!',
            'count' => $voteCount,
        ]);
    }

    public function views($id)
    {
        try {
            $report = Report::findOrFail($id);
            $report->increment('viewers'); 
            return response()->json([
                'success' => true,
                'views' => $report->viewers,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }


    public function searchByProvince(Request $request)
    {
        $provinceId = $request->input('search');

        if (!$provinceId) {
            return response()->json(['error' => 'Provinsi tidak dipilih'], 400);
        }

        $reports = Report::whereRaw('JSON_UNQUOTE(JSON_EXTRACT(province, "$.id")) = ?', [$provinceId])->get();

        if ($reports->isEmpty()) {
            return response()->json(['error' => 'Tidak ada laporan ditemukan untuk provinsi ini'], 404);
        }

        return response()->json($reports);
    }

    public function exportExcel(Request $request)
    {
        // Menyimpan filter tanggal
        $dateFilter = $request->get('date_filter');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $file_name = 'data_pengaduan' . '.xlsx';

        if ($dateFilter === 'custom' && $startDate && $endDate) {
            return Excel::download(new ReportExport($startDate, $endDate), $file_name);
        }

        return Excel::download(new ReportExport(), $file_name);
    }
}
