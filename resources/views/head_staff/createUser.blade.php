@extends('templates.app', ['title' => 'Kelola Akun STAFF'])

@section('dynamic-contents')
@if (Session::get('failed'))
<div class="toast align-items-center text-bg-danger border-0 position-fixed bottom-0 end-0 m-3 mb-4 p-2 shadow-lg"
    role="alert" aria-live="assertive" aria-atomic="true" id="toast">
    <div class="d-flex">
        <div class="toast-body">
            {{ Session::get('failed') }}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
            aria-label="Close"></button>
    </div>
</div>
@endif
@if (Session::get('success'))
<div class="toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-3 mb-4 p-2 shadow-lg"
    role="alert" aria-live="assertive" aria-atomic="true" id="toast">
    <div class="d-flex">
        <div class="toast-body">
            {{ Session::get('success') }}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
            aria-label="Close"></button>
    </div>
</div>
@endif
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="card p-4">
                <h4>Akun STAFF daerah <span style="color: #00bf63"> {{ $province }}</span></h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            {{-- <th>Nama</th> --}}
                            <th>Email</th>
                            <th>Provinsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            {{-- <td>{{ $user->name }}</td> --}}
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
                <h4>Tambah Akun STAFF <span style="color: #8c52ff"> {{ $province }}</span></h4>
                <form action="{{ route('head_staff.store') }}" method="POST">
                    @csrf
                    {{-- <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div> --}}
                    <div class="form-group mt-2 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
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

@push('script')
    <script>
        $(document).ready(function() {
            if ($("#toast").length) {
                var toast = new bootstrap.Toast(document.getElementById('toast'));
                toast.show();
            }
        });
    </script>
@endpush


