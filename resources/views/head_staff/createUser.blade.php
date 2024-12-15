{{-- @extends('templates.app', ['title' => 'Tambahkan data akun'])

@section('dynamic-contents')
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="card p-5">
                <h4>Akun Staff daerah Jawa Barat</h4>
                    @foreach ($users as $user)
                    <ul>
                        <li> {{ $user['email'] }}</li>
                        @foreach ($staffs as $staff)
                        <li> {{ $staff['province'] }}</li>
                        @endforeach
                        <li> {{ $user['role'] }}</li>
                    </ul>
                    @endforeach
                    </div>              
                </div>
            <div class="col-lg-6">
                <form class="card p-5 " action="{{route('head_staff.store') }}" method="POST">
                    @if (Session::get('failed'))
                        <div class="alert alert-danger"> {{ Session::get('failed') }} </div>
                    @endif
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="">Nama</label>
                        @error('name')
                                <small class="text-danger"> {{ $message }} </small>    
                        @enderror
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Email</label>
                        @error('email')
                                <small class="text-danger"> {{ $message }} </small>    
                        @enderror
                        <input type="text" class="form-control" name="email">
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">Password</label>
                        @error('password')
                                <small class="text-danger"> {{ $message }} </small>    
                        @enderror
                        <input type="text" class="form-control" name="password">
                    </div>
                    <button class="btn btn-success mt-4" type="submit">Tambah Akun</button>
                </form>
            </div>
        </div>
    </div>

@endsection --}}

@extends('templates.app', ['title' => 'Kelola Akun STAFF'])

@section('dynamic-contents')
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="card p-4">
                <h4>Akun STAFF daerah {{ $province }}</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Provinsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->staffProvince->province ?? '-' }}</td>
                            <td>
                                <form action="{{ route('head_staff.reset.password', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm">Reset Password</button>
                                </form>
                                <form action="{{ route('head_staff.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus akun ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if ($users->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center">Belum ada akun STAFF di provinsi ini</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card p-4">
                <h4>Tambah Akun STAFF {{ $province }}</h4>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('failed'))
                    <div class="alert alert-danger">{{ session('failed') }}</div>
                @endif
                <form action="{{ route('head_staff.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-success mt-3" type="submit">Tambah Akun</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
