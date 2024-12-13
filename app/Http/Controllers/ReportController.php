<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reports = Report::all();
        return view('guest.index', compact('reports'));
    }

    public function dashboard()
    {
        $reports = Report::all();
        $comments = Comment::all();
        return view('guest.dashboard', compact('reports', 'comments'));
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
        ]);

        $file = $request->file('image');
        $filePath = $file->storeAs('uploads', time() . '_' . $file->getClientOriginalName(), 'public');

        $process = Report::create([
            'user_id' => Auth::user()->id,
            'description' => $request->description,
            'type' => $request->type,
            'province' => $request->province,
            'regency' => $request->regency,
            'subdistrict' => $request->subdistrict,
            'village' => $request->village,
            'image' => $filePath,
            'statement' => 1,
        ]);

        if ($process) {
            return redirect()->route('guest.create')->with('success', 'Pengaduan Berhasil ditambahkan!');
        } else {
            return redirect()->back()->with('failed', 'Pengaduan gagal ditambahkan! silahkan coba kembali');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    // Cari report berdasarkan ID
    $report = Report::find($id);

    if ($report) {
        // Ambil komentar-komentar yang terkait dengan report_id yang sama dengan ID report
        $comments = Comment::where('report_id', $report->id)->get();

        // Ambil laporan berdasarkan ID (hanya laporan dengan ID tersebut)
        $reports = Report::where('id', $report->id)->get();
    } else {
        $reports = [];
        $comments = [];
    }

    // Kirim data ke view
    return view('guest.show', compact('reports', 'comments'));
}



    /**
     * Show the form for editing the specified resource.
     */
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
        $userId = auth()->id(); // Ambil ID pengguna yang login
        
        $report = Report::findOrFail($id);

        // Cek apakah pengguna sudah memberikan suara
        if (in_array($userId, $report->voting)) {
            return response()->json(['error' => 'You have already voted for this report.'], 403);
        }

        // Tambahkan ID pengguna ke array voting
        $report->voting = array_merge($report->voting, [$userId]);

        // Update jumlah voting (jika diperlukan)
        $voteCount = count($report->voting);

        $report->save();

        return response()->json([
            'message' => 'Vote submitted successfully!',
            'count' => $voteCount,
        ]);
    }

    

    public function searchByProvince(Request $request)
    {
        // Get the selected province ID from the request
        $provinceId = $request->input('search'); // Assuming 'search' is the field for the province ID
    
        // Validate if province ID is provided
        if (!$provinceId) {
            return response()->json(['error' => 'Provinsi tidak dipilih'], 400);
        }
    
        // Query the reports based on the selected province ID, checking the 'id' field within the JSON object
        $reports = Report::whereRaw('JSON_UNQUOTE(JSON_EXTRACT(province, "$.id")) = ?', [$provinceId])->get();
    
        // Return reports if found
        if ($reports->isEmpty()) {
            return response()->json(['error' => 'Tidak ada laporan ditemukan untuk provinsi ini'], 404);
        }
    
        // Return reports as JSON response
        return response()->json($reports);
    }
    

}
