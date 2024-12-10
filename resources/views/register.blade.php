@extends('templates.app', ['title' => 'Register | Pengaduan Masyarakat'])

@section('dynamic-contents')
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 shadow-lg rounded-3 overflow-hidden" style="max-width: 500px;">
        <!-- Bagian Form Register -->
        <div class="col-12 bg-white p-5 d-flex flex-column justify-content-center">
            <form class="form p-2" action="{{ route('register') }}" method="POST" id="registerForm">
                @csrf
                <div class="mb-4 text-center">
                    <h1 class="fw-bold" style="color: #495E57; font-size: 1.8rem;">DAFTAR</h1>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold" style="color: #495E57;">Username</label>
                    <input type="text" name="name" class="form-control form-control" id="name" placeholder="Masukan username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold" style="color: #495E57;">Alamat Email</label>
                    <input type="email" name="email" class="form-control form-control" id="email" placeholder="Masukan email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-se mibold" style="color: #495E57;">Password</label>
                    <input type="password" name="password" class="form-control form-control" id="password" placeholder="Masukan password" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold" style="color: #495E57;">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control form-control" id="password_confirmation" placeholder="Masukan password lagi" required>
                    <small id="passwordError" class="text-danger" style="display: none;">Password tidak cocok!</small>
                </div>
                <div class="mb-3 text-center">
                    <p class="mb-1" style="font-size: 0.9rem;">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #495E57;">Masuk di sini</a></p>
                </div>
                <button type="submit" class="btn btn w-100" style="background-color: #FBD46D; color: #495E57; font-weight: bold;">Daftar</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        if (password !== passwordConfirmation) {
            e.preventDefault(); // Mencegah pengiriman formulir
            const errorText = document.getElementById('passwordError');
            errorText.style.display = 'block'; // Tampilkan pesan error
        }
    });
</script>
@endpush
