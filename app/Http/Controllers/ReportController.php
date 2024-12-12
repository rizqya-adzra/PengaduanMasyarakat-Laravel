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
        return view('head_staff.create');
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
            return redirect()->route('head_staff.create')->with('success', 'Artikel Berhasil ditambahkan!');
        } else {
            return redirect()->back()->with('failed', 'Artikel gagal ditambahkan! silahkan coba kembali');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

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
        //
    }

    public function vote(Request $request, $id)
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in to vote.'], 401);
        }

        // Temukan report berdasarkan ID
        $report = Report::findOrFail($id);

        // Ambil daftar ID pengguna yang sudah memberikan vote (voting disimpan dalam JSON)
        $votes = json_decode($report->voting, true);

        // Cek apakah pengguna sudah memberi vote
        $hasVoted = in_array(Auth::id(), $votes);

        // Jika belum memberi vote, tambahkan ID pengguna ke dalam array votes
        if (!$hasVoted) {
            $votes[] = Auth::id();
            $report->voting = json_encode($votes);
            $report->save();
        }

        // Kembalikan respons dengan informasi vote yang baru
        return response()->json([
            'message' => $hasVoted ? 'You have already voted!' : 'Vote successful!',
            'count' => count($votes),  // Mengembalikan jumlah vote yang baru
            'hasVoted' => $hasVoted   // Menambahkan flag apakah pengguna sudah memberi vote
        ]);
    }

    public function searchByProvince(Request $request)
    {
        $provinceId = $request->input('search');

    // Validate if provinceId exists
        if (!$provinceId) {
            return response()->json(['error' => 'Provinsi tidak dipilih'], 400);
        }

        // Query the reports based on the selected province
        $reports = Report::where('province_id', $provinceId)->get();  // Assuming 'province_id' exists

        // Return reports if found
        if ($reports->isEmpty()) {
            return response()->json(['error' => 'Tidak ada laporan ditemukan untuk provinsi ini'], 404);
        }

        // Return reports as JSON response
        return response()->json($reports);
    }
}
