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
    public function index(Request $request)
    {
        $sortOrder = $request->get('sort', 'desc');
        $startDate = $request->get('start_date'); 
        $endDate = $request->get('end_date');
        $province = Auth::user()->StaffProvince->province ?? null;
        $reports = Report::with('user')->orderBy('voting', $sortOrder)->get();
        if ($startDate && $endDate) {
            $reports->whereBetween('created_at', [$startDate, $endDate]);
        }
        $responses = Response::all();
        return view('staff.index', compact('reports', 'responses', 'sortOrder', 'sortOrder', 'startDate', 'endDate', 'province'));
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
            $existingResponse->update([
                'response_status' => $request->response_status,
                'staff_id' => Auth::user()->id,
            ]);
            $message = 'Response berhasil diperbarui!';
            $success = true;
        } else {
            $proses = Response::create([
                'report_id' => $reportFormat->id,
                'response_status' => $request->response_status,
                'staff_id' => Auth::user()->id,
            ]);
            $message = $proses ? 'Response berhasil ditambahkan!' : 'Gagal menambahkan response';
            $success = $proses ? true : false;
        }

        if ($success) {
            return redirect()->route('staff.show', ['id' => $id])->with('success', $message);
        } else {
            return redirect()->back()->with('failed', $message);
        }
    }


    public function show(string $id)
    {
        $report = Report::with('user')->find($id);

        if ($report) {
            $responses = Response::where('report_id', $report->id)->get();

            $response = $responses->first();


            if ($response) {
                $response_progresses = ResponseProgress::where('response_id', $response->id)->get();
                $reports = Report::where('id', $report->id)->get();
            } else {
                $response_progresses = [];
            }
        } else {
            $reports = [];
            $responses = [];
            $response_progresses = [];
        }

        // Kirim data ke view
        return view('staff.show', compact('reports', 'responses', 'response_progresses'));
    }


    public function storeProgress(Request $request, $id)
    {
        $request->validate([
            'histories' => 'required|string', 
        ]);

        $responseFormat = Response::findOrFail($id);

        $proses = ResponseProgress::create([
            'response_id' => $responseFormat->id,
            'histories' => json_encode(['note' => $request->histories]), 
        ]);

        if ($proses) {
            return response()->json([
                'success' => true,
                'message' => 'Tanggapan terkirim!',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tanggapan gagal terkirim, Silahkan Coba Lagi.',
            ]);
        }
    }


    public function updateStatus(Request $request, $id)
    {
        try {
            $response = Response::findOrFail($id);
            $response->response_status = $request->input('response_status'); // 'DONE'
            $response->save();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah menjadi DONE.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status.'
            ], 500);
        }
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
