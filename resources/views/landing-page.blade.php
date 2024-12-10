@extends('templates.app', ['title' => 'Landing Page | Pengaduan Masyarakat'])

@section('dynamic-contents')
<div class="container-fluid" style="height: 100vh;">
        @if (Session::get('success'))
                <div class="toast align-items-center text-bg-danger border-0 position-fixed bottom-0 start-0 m-3 mb-4 p-2 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" id="toast" >
                    <div class="d-flex">
                      <div class="toast-body">
                        Anda telah Logout!
                      </div>
                      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
        @endif
        @if (Session::get('canAccess'))
                <div class="toast align-items-center text-bg-danger border-0 position-fixed bottom-0 start-0 m-3 mb-4 p-2 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" id="toast" >
                    <div class="d-flex">
                      <div class="toast-body">
                        Silahkan Login terlebih dahulu!
                      </div>
                      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
        @endif
        <div class="row h-100">
            <div class="col-lg-7 p-5 d-flex flex-column justify-content-center">
                <div class="p-5" style="border-left: 6px solid #FBD46D;">
                    <h1 class="mb-3" style="color: #495E57">PENGADUAN MASYARAKAT</h1>
                    <p class="mb-4">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus, eligendi eveniet quod optio ipsam
                        ad doloremque. Soluta perspiciatis velit numquam cum alias nihil laboriosam quos ad, corporis minus adipisci
                        distinctio possimus est at accusamus id quo magnam, atque debitis enim aliquam dicta optio. Consequatur
                        dolore quasi omnis quibusdam.
                    </p>
                    <a class="btn" style="background-color: #FBD46D" href="{{ route('login') }}">Bergabung!</a>
                </div>
            </div>
            <div class="col-lg-5 position-relative" style="background-color: #495E57;">
                <div style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: url('{{ asset('assets/landing_img.jpg') }}') center/cover no-repeat;
                opacity: 0.3;">
                </div>
            </div>
        </div>
    </div>
    
    <div class="position-fixed top-50 end-0 p-5 d-flex flex-column" style="transform: translateY(-50%);">
        <a class="btn btn-lg mb-3" style="background-color: #FBD46D" href="{{ route('login') }}"><i class="fa fa-user-circle" aria-hidden="true"></i></a>
        <button class="btn btn-lg mb-3" style="background-color: #FBD46D" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-info" aria-hidden="true"></i></button>
        <button class="btn btn-lg mb-3" style="background-color: #FBD46D"><i class="fa fa-pencil" aria-hidden="true"></i></button>
    </div>    

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex text-center align-content-center justify-content-center" style="gap: 20px">
                            <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i>
                            <h4 class="text-center">Informasi Pembuatan Pengaduan</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="card-body">
                            <ul class="card-text">
                                <li>Pengaduan bisa dibuat hanya jika Anda telah membuat akun sebelumnya.</li>
                                <li>Keseluruhan data pada pengaduan bernilai BENAR dan DAPAT DIPERTANGGUNGJAWABKAN.</li>
                                <li>Semua bagian data perlu diisi.</li>
                                <li>Anda dapat mengakses pengaduan Anda di Dashboard setelah login.</li>
                            </ul>
                        </div>
                    </div>
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

