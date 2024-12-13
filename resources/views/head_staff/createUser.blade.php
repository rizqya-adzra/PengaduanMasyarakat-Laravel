@extends('templates.app', ['title' => 'Tambahkan data akun'])

@section('dynamic-contents')
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="card p-5">
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
                <form class="card p-5 " action="{{ route('head_staff.storeUser') }}" method="POST">
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
                        <label for="" class="form-label">Daerah</label>
                        @error('role')
                                <small class="text-danger"> {{ $message }} </small>    
                        @enderror
                        <input class="form-control" type="text" name="province" id="province">
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">Password</label>
                        @error('password')
                                <small class="text-danger"> {{ $message }} </small>    
                        @enderror
                        <input type="text" class="form-control" name="password">
                    </div>
                    <button class="btn btn-success mt-4" type="submit">Kirim</button>
                </form>
            </div>
        </div>
    </div>

@endsection