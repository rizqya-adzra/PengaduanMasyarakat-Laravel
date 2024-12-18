@extends('templates.app', ['title' => 'Login | Pengaduan Masyarakat'])

@section('dynamic-contents')
@if (Session::get('failed'))
        <div class="toast align-items-center text-bg-danger border-0 position-fixed bottom-0 end-0 m-3 mb-4 p-2 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" id="toast">
            <div class="d-flex">
              <div class="toast-body">
                {{ Session::get('failed') }}
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 shadow-lg rounded-3 overflow-hidden" style="max-width: 1000px;">
        <div class="col-lg-5 col-md-12 bg-white p-5 d-flex flex-column justify-content-center">
            <form class="form p-4" action="{{ route('login.auth') }}" method="POST">
                @csrf
                <div class="mb-4 text-center">
                    <h1 class="fw-bold" style="color: #00bf63; font-size: 1.8rem;">LOGIN</h1>
                </div>
                {{-- <div class="mb-3">
                    <label for="name" class="form-label fw-semibold" style="color: #00bf63;">Username</label>
                    <input type="name" name="name" class="form-control form-control" id="name" placeholder="Masukan username">
                </div> --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold" style="color: #00bf63;">Alamat Email</label>
                    <input type="text" name="email" class="form-control form-control" id="email" placeholder="Masukan email">
                </div>
                <div class="mb-5">
                    <label for="password" class="form-label fw-semibold" style="color: #00bf63;">Password</label>
                    <input type="password" name="password" class="form-control form-control" id="password" placeholder="Masukan password">
                </div>
                <div class="text-center">
                    <button type="submit" name="isCreatingAccount" value="true" class="btn btn w-100 mb-2" style="background-color: #00bf63 ; color: white; font-weight: bold;">Buat Akun</button>
                    <button type="submit" name="isCreatingAccount" value="false" class="btn btn w-100" style="background-color: #8c52ff; color: white; font-weight: bold;">Login</button>
                </div>                
            </form>
        </div>
        
        <div class="col-lg-7 d-none d-lg-flex p-5 position-relative" style="background-color: #495E57 ; overflow: hidden;">
            <div style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: url('{{ asset('assets/login_img.jpg') }}') center/cover no-repeat;\z-index: 1;
                opacity: 0.1;">
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center text-center" style="z-index: 2; position: relative;">
                <h2 class="fw-bold mb-3" style=" font-size: 2rem; color: white;">WELCOME!</h2>
                <p class="lh-lg" style=" max-width: 500px; font-size: 0.9rem; color: white;">
                    Selamat datang di layanan Pengaduan Masyarakat! Kami hadir untuk membantu Anda
                    menyampaikan aspirasi, keluhan, dan saran dengan lebih mudah dan cepat. Mari bersama 
                    menciptakan perubahan yang lebih baik untuk masyarakat kita.
                </p>
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