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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($request->isCreatingAccount == 'true') {
            if ($user) {
                return redirect()->back()->with('failed', 'Akun sudah ada. Silahkan login.');
            }

            $newUser = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            Auth::login($newUser);
            return redirect()->route('guest.index')->with('success', 'Membuat akun dan login berhasil.');
        }

        if ($user && Auth::attempt($request->only('email', 'password'))) {
            $routes = [
                'STAFF' => 'staff.index',
                'GUEST' => 'guest.index',
                'HEAD_STAFF' => 'head_staff.index',
            ];
        
            $role = Auth::user()->role;
            $route = $routes[$role] ?? 'home';
        
            return redirect()->route($route)->with('success', "Login berhasil sebagai $role.");
        }
        

        return redirect()->back()->with('failed', 'Login gagal. Silahkan cek kembali data Anda.');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('landing')->with('success', 'Anda telah Logout!');
    }
}
