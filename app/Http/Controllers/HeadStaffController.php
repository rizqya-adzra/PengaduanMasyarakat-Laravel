<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\StaffProvince;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HeadStaffController extends Controller
{

    public function getReportsByProvince()
    {
        $data = Report::join('responses', 'reports.id', '=', 'responses.report_id')
            ->selectRaw('json_extract(reports.province, "$.name") as province_name, 
                              COUNT(reports.id) as total_reports, 
                              COUNT(CASE WHEN responses.response_status = "DONE" THEN 1 END) as done_count,
                              COUNT(CASE WHEN responses.response_status = "ON_PROCESS" THEN 1 END) as on_process_count,
                              COUNT(CASE WHEN responses.response_status = "REJECT" THEN 1 END) as reject_count')
            ->groupBy('province_name')
            ->get();

        return response()->json($data);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('head_staff.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $province = Auth::user()->StaffProvince->province ?? null;

        if (!$province) {
            return redirect()->route('login')->with('failed', 'Provinsi tidak ditemukan untuk akun Anda.');
        }
    
        $users = User::where('role', 'STAFF')
                     ->whereHas('staffProvince', function ($query) use ($province) {
                         $query->where('province', $province);
                     })
                     ->get();

        return view('head_staff.createUser', compact('users', 'province'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $province = auth()->user()->staffProvince->province ?? null;

        if (!$province) {
            return redirect()->back()->with('failed', 'Provinsi tidak ditemukan untuk akun Anda.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'STAFF',
        ]);

        StaffProvince::create([
            'user_id' => $user->id,
            'province' => $province,
        ]);

        return redirect()->back()->with('success', 'Akun STAFF berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('head_staff.create')->with('success', 'Akun STAFF berhasil dihapus');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $newPassword = substr($user->email, 0, 4); // 4 huruf pertama email
        $user->update(['password' => Hash::make($newPassword)]);

        return redirect()->route('head_staff.create')->with('success', 'Password berhasil direset menjadi: ' . $newPassword);
    }
}
