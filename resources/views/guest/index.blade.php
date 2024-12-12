@extends('templates.app', ['title' => 'Home | Pengaduan Masyarakat'])

@section('dynamic-contents')
    @if (Session::get('error'))
        <div class="toast align-items-center text-bg-danger border-0 position-fixed bottom-0 end-0 m-3 mb-4 p-2 shadow-lg"
            role="alert" aria-live="assertive" aria-atomic="true" id="toast">
            <div class="d-flex">
                <div class="toast-body">
                    Anda Sudah login!
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
                    Login Anda Berhasil!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-lg-8 col-md-12 p-5">
                <div class="text-center mb-5">
                    <h2 class="mb-4" style="color: #495E57; font-weight: bold;">Cari Berdasarkan Provinsi</h2>
                    <form class="d-flex justify-content-center align-items-center" role="search">
                        <select class="form-select me-2 shadow-sm" name="search" id="search"
                            style="border-radius: 15px; max-width: 300px;">
                            <option value="" disabled selected hidden>Pilih Provinsi</option>
                        </select>
                        <button class="btn shadow-sm" style="background-color: #495E57; color: white; border-radius: 15px;"
                            type="submit">Cari</button>
                    </form>
                </div>
                <!-- Bagian Artikel -->
                <!-- Daftar laporan berdasarkan provinsi -->
                <div id="reports-list">
                    <!-- Laporan yang sesuai dengan provinsi akan dimuat di sini -->
                </div>
            </div>
        </div>

        <div class="position-fixed top-50 end-0 p-5 d-flex flex-column" style="transform: translateY(-50%);">
            <button class="btn btn-lg mb-3 shadow-sm" style="background-color: #FBD46D" data-bs-toggle="modal"
                data-bs-target="#exampleModal"><i class="fa fa-info" aria-hidden="true"></i></button>
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

                $.ajax({
                    method: "GET",
                    url: "https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json",
                    dataType: "json",
                    success: function(response) {
                        response.forEach(function(province) {
                            $('#search').append('<option value="' + province.id + '">' + province
                                .name + '</option>');
                        });
                    },
                    error: function() {
                        alert("Gagal memuat data, coba lagi nanti!");
                    }
                });

                $('#search').on('change', function() {
                    var provinceId = $(this).val(); // Ambil ID provinsi yang dipilih

                    $.ajax({
                        url: "{{ route('guest.search') }}", // Panggil route untuk pencarian berdasarkan provinsi
                        type: "GET",
                        data: {
                            search: provinceId // Kirim ID provinsi ke server
                        },
                        success: function(response) {
                            // Clear hasil sebelumnya
                            $('#reports-list').empty();

                            // Loop melalui laporan yang diterima dari server dan tampilkan
                            response.forEach(function(report) {
                                // Buat objek tanggal dari created_at
                                const createdAt = new Date(report.created_at);

                                // Format tanggal dengan toLocaleString
                                const formattedDate = createdAt.toLocaleString('id-ID', {
                                    day: '2-digit',
                                    month: 'long',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                });
                                $('#reports-list').append(`
                        <div class="mt-5 p-4 shadow-lg" style="border-radius: 25px;">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage') }}/${report.image}" class="img-fluid rounded shadow-sm"
                                    alt="Gambar Artikel" style="width: 50%; max-width: 200px;">
                                <div class="ms-4">
                                    <h4 class="fw-bold"><a class="text-dark" href="/guest/show/${report.id}">
                                        ${report.description.substring(0, 50)}
                                    </a></h4>
                                    <p class="text-muted" style="font-size: 0.9rem;">
                                        ${report.description.substring(0, 150)}...
                                    </p>
                                    <div>
                                        <small>${formattedDate}</small>
                                    </div>
                                    <button class="btn voting-btn" data-id="${report.id}" name="voting" id="voting">
                                        <i class="fa fa-heart" aria-hidden="true"></i>
                                        <small class="d-block text-muted">${report.voting || 0} votes</small>
                                    </button>
                                    <button class="btn" name="viewers" id="viewers">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        <small class="d-block text-muted">${report.viewers || 0}  views</small>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `);
                            });
                        },
                        error: function() {
                            alert("Gagal memuat data laporan, coba lagi nanti!");
                        }
                    });
                });

                $('.voting-btn').each(function() {
                    // Periksa apakah pengguna sudah memberi vote saat halaman dimuat
                    var report_id = $(this).data('id');
                    var hasVoted = $(this).data('voted'); // Menyimpan status apakah pengguna sudah vote

                    // Jika pengguna sudah memberi vote, tambahkan kelas 'text-danger'
                    if (hasVoted) {
                        $(this).find('i').addClass('text-danger');
                    }
                });

                $('.voting-btn').on('click', function() {
                    var report_id = $(this).data('id');

                    $.ajax({
                        url: "{{ route('guest.vote', ':id') }}".replace(':id',
                            report_id), // Ganti placeholder dengan ID
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.message) {
                                // Jika vote berhasil, beri tanda pada tombol dengan menambahkan kelas 'text-danger'
                                $('.voting-btn[data-id="' + report_id + '"] i').addClass(
                                    'text-danger');

                                // Update jumlah vote di UI
                                $('.voting-btn[data-id="' + report_id + '"] .text-muted').text(
                                    response.count + ' votes');

                                // Set status bahwa pengguna sudah memberi vote
                                $('.voting-btn[data-id="' + report_id + '"]').data('voted', true);
                            } else {
                                alert(response.error);
                            }
                        },
                        error: function(error) {
                            alert('An error occurred while voting.');
                        }
                    });
                });





            });
        </script>
    @endpush
