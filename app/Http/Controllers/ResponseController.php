<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffProvince;
use App\Models\Response;
use App\Models\ResponseProgress;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $responses = Response::all();
        $reports = Report::all();
        return view('staff.index', compact('reports', 'responses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
{
    $request->validate([
        'response_status' => 'required',
    ]);

    $reportFormat = Report::findOrFail($id);

    $existingResponse = Response::where('report_id', $reportFormat->id)->first();

    if ($existingResponse) {
        // If a response exists, update it
        $existingResponse->update([
            'response_status' => $request->response_status,
            'staff_id' => Auth::user()->id,
        ]);
        $message = 'Response berhasil diperbarui!';
        $success = true;
    } else {
        // If no response exists, create a new one
        $proses = Response::create([
            'report_id' => $reportFormat->id,
            'response_status' => $request->response_status,
            'staff_id' => Auth::user()->id,
        ]);
        $message = $proses ? 'Response berhasil ditambahkan!' : 'Gagal menambahkan response';
        $success = $proses ? true : false;
    }

    // Redirect based on success or failure
    if ($success) {
        return redirect()->route('staff.show', ['id' => $id])->with('success', $message);
    } else {
        return redirect()->back()->with('failed', $message);
    }
}


    public function show(string $id)
    {
        $report = Report::find($id);

        if ($report) {
            $responses = Response::where('report_id', $report->id)->get();

            $reports = Report::where('id', $report->id)->get();
        } else {
            $reports = [];
            $responses = [];
        }

        // Kirim data ke view
        return view('staff.show', compact('reports', 'responses'));
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
        //
    }
}
