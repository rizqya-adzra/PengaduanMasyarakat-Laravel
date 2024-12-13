<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'comment' => 'required',
        ]);

        $reportFormat = Report::find($id);

        $proses = Comment::create([
            'report_id' => $reportFormat->id,
            'comment' => $request->comment,
            'user_id' => Auth::user()->id,
        ]);

        if($proses) {
            return redirect()->back()->with('success', 'Pengaduan Anda Terkirim, dan Akan Ditindaklanjuti!');
        } else {
            return redirect()->back()->with('failed', 'Pengaduan Gagal Terkirim, Silahkan Coba Lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reports = Report::findOrFail($id);
        $comments = Comment::where('report_id', $id)->get();
        return view('guest.show', compact('reports', 'comments'));
    }


    public function showDashboard($id)
    {
        $comment = Comment::find($id);  

        if ($comment) {
            $reports = Report::find($comment->report_id);

            $comments = Comment::where('report_id', $comment->report_id)->get();
        } else {
            $reports = null;
            $comments = [];
        }

        return view('guest.showDashboard', compact('comments', 'reports'));
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
        $proses = Comment::where('id', $id)->delete();

        if ($proses) {
            return redirect()->back()->with('success', 'Data Berhasil Dihapus!');
        } else {
            return redirect()->back()->with('failed', 'Data Gagal Dihapus!'); 
        }
    }
}
