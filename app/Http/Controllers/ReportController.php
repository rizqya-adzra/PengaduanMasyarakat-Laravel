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

    public function vote($id)
    {
        // Ensure the user is logged in
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in to vote.'], 401);
        }

        // Find the report by ID
        $report = Report::findOrFail($id);

        // Decode the voting JSON field into an array
        $votes = json_decode($report->voting, true);

        // Check if the user has already voted
        if (in_array(Auth::id(), $votes)) {
            return response()->json(['error' => 'You have already voted for this report.'], 400);
        }

        // Add the user's ID to the votes array
        $votes[] = Auth::id();

        // Update the voting field in the report
        $report->voting = json_encode($votes);
        $report->save();

        // Return the new vote count
        return response()->json([
            'message' => 'Vote successful!',
            'count' => count($votes)  // Return the updated vote count
        ]);
    }
    
    public function searchByProvince(Request $request)
    {
        // Get the selected province from the request
        $province = $request->input('search');

        // Validate if province is provided
        if (!$province) {
            return response()->json(['error' => 'Provinsi tidak dipilih'], 400);
        }

        // Query the reports based on the selected province
        $reports = Report::where('province', $province)->get();


        // Return reports if found
        if ($reports->isEmpty()) {
            return response()->json(['error' => 'Tidak ada laporan ditemukan untuk provinsi ini'], 404);
        }

        // Return reports as JSON response
        return response()->json($reports);
    }
}
