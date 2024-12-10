@extends('templates.app', ['title' => 'Home | Pengaduan Masyarakat'])

@section('dynamic-contents')
    @if (Session::get('error'))
        <div class="toast align-items-center text-bg-danger border-0 position-fixed bottom-0 end-0 m-3 mb-4 p-2 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" id="toast">
            <div class="d-flex">
              <div class="toast-body">
                Anda Sudah login!
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
    @if (Session::get('success'))
        <div class="toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-3 mb-4 p-2 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" id="toast">
            <div class="d-flex">
              <div class="toast-body">
                Login Anda Berhasil!
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="container">
        <div class="d-flex justify-content-center">
            <!-- Bagian Pencarian -->
            <div class="col-lg-8 col-md-12 p-5">
                <div class="text-center mb-5">
                    <h2 class="mb-4" style="color: #495E57; font-weight: bold;">Cari Berdasarkan Provinsi</h2>
                    <form class="d-flex justify-content-center align-items-center" role="search">
                        <select class="form-select me-2 shadow-sm" name="search" id="search" style="border-radius: 15px; max-width: 300px;">
                            <option value="" disabled selected hidden>Pilih Provinsi</option>
                        </select>
                        <button class="btn shadow-sm" style="background-color: #495E57; color: white; border-radius: 15px;" type="submit">Cari</button>
                    </form>
                </div>
                <!-- Bagian Artikel -->
                @foreach ($reports as $report)
                <div class="mt-5 p-4 shadow-lg" style="background-color: #F4F6FF; border-radius: 25px;">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/' . $report['image']) }}" class="img-fluid rounded shadow-sm" alt="Gambar Artikel" style="width: 50%; max-width: 200px;">
                        <div class="ms-4">
                            <h4 class="fw-bold"><a class="text-dark" href=" {{ route('head_staff.show', $report['id']) }} ">{{ \Illuminate\Support\Str::words($report['description'], 8) }}
                            </a></h4>
                            <p class="text-muted" style="font-size: 0.9rem;">
                                {{ $report['description'] }}
                            </p>
                            <div>
                                <small> {{ $report['created_at'] }} </small>
                                <small> {{ $report['province'] }} </small>
                                <small> {{ $report['regency'] }} </small>
                            </div>
                            <div class="mt-3 mb-2">
                                <button type="button" class="btn btn-secondary shadow-sm btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Bagikan di Sosial Media">
                                    {{ $report['type'] }}
                                </button>
                            </div>
                            <button class="btn" name="voting" id="voting">
                                <i class="fa fa-heart" aria-hidden="true" name="image"></i>
                            </button>
                            <button class="btn" name="viewers" id="viewers">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    <div class="position-fixed top-50 end-0 p-5 d-flex flex-column" style="transform: translateY(-50%);">
        <button class="btn btn-lg mb-3 shadow-sm" style="background-color: #FBD46D" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-info" aria-hidden="true"></i></button>
    </div>  

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex text-center align-content-center justify-content-center" style="gap: 20px">
                            <i class="fa fa-info-circle fa-2x" aria-hidden="true" ></i>
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
        // Toast Notification
        if ($("#toast").length) {
            var toast = new bootstrap.Toast(document.getElementById('toast'));
            toast.show();
        }

        // Fetch Provinces
        $.ajax({
            method: "GET",
            url: "https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json",
            dataType: "json",
            success: function(response) {
                response.forEach(function(province) {
                    $('#search').append('<option value="' + province.id + '">' + province.name + '</option>');
                });
            },
            error: function() {
                alert("Gagal memuat data, coba lagi nanti!");
            }
        });
    });
</script>
@endpush

