<?php

namespace App\Http\Controllers;

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
        
        if($process){
            return redirect()->route('head_staff.create')->with('success', 'Artikel Berhasil ditambahkan!');
        } else {
            return redirect()->back()->with('failed', 'Artikel gagal ditambahkan! silahkan coba kembali');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reports = Report::find($id);
        return view('guest.show', compact('reports'));
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
