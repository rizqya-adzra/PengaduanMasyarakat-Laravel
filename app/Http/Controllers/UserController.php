<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StaffProvince;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function regis()
    // {
    //     $request->validate([
    //         'email' => 'required',
    //         'password' => 'required',
    //     ])

    //     $proses = User::create([
    //         'email' => ''
    //     ])
    // }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $staffs = StaffProvince::all();
        // Ambil semua user yang memiliki role 'STAFF'
        $users = User::where('role', 'STAFF')->get();
    
        // Kirim data ke view
        return view('head_staff.createUser', compact('users', 'staffs'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $role = 'STAFF'; 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            return redirect()->back()->with('success', 'User berhasil ditambahkan!');
        } else {
            return redirect()->back()->with('failed', 'User gagal ditambahkan!');
        }
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
    public function destroy(string $id)
    {
        //
    }

    public function loginAuth(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ],
        );

        $proses = $request->only(['name', 'email', 'password']);
        if (Auth::attempt($proses)) {
            return redirect()->route('guest.index')->with('success', 'Login berhasil');
        } else {
            return redirect()->back()->with('failed', 'Login gagal, silahkan coba lagi');
        }

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Anda harus login untuk memberikan voting.']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('landing')->with('success', 'Anda telah Logout!');
    }
}
