@extends('templates.app', ['title' => 'Home | Pengaduan Masyarakat'])

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
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-lg-8 col-md-12 p-5">
                <div class="text-center mb-5">
                    <h2 class="mb-4" style="color: #8c52ff; font-weight: bold;">Cari Berdasarkan Provinsi</h2>
                    <form class="d-flex justify-content-center align-items-center" role="search">
                        <select class="form-select me-2 shadow-sm" name="search" id="search">
                            <option value="" disabled selected hidden>Pilih Provinsi</option>
                        </select>
                    </form>
                </div>

                <div id="reports-list">
                </div>

            </div>
            <div class="col-lg-4 col-md-6 col-sm-10 mt-5 my-5" style="">
                <div class="card shadow-sm align-items-center position-sticky justify-content-center"
                    style="border-radius: 15px; top: 120px;">
                    <div class="card-header text-center bg-light p-4">
                        <div class="d-flex align-items-center justify-content-center ">
                            <i class="fa fa-info-circle fa-2x" aria-hidden="true" style="color: #00bf63;"></i>
                            <h4 class="mb-0" style="color: #00bf63;">Informasi Pembuatan Pengaduan</h4>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <ul class="card-text">
                            <li>Pengaduan bisa dibuat hanya jika Anda telah membuat akun sebelumnya.</li>
                            <li>Keseluruhan data pada pengaduan bernilai BENAR dan DAPAT DIPERTANGGUNGJAWABKAN.</li>
                            <li>Semua bagian data perlu diisi.</li>
                            <li>Anda dapat mengakses pengaduan Anda di Dashboard setelah login.</li>
                        </ul>
                    </div>
                    <div class="p-5 d-flex flex-column">
                        <p>Buat pengaduan Anda disini:</p>
                        <a class="btn btn-lg mb-3" href="{{ route('guest.create') }}" style="background-color: #00bf63; color:white"><i
                                class="fa fa-pencil" aria-hidden="true"></i></a>
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
                        $('#search').append('<option value="' + province.id + '" data-name="' +
                            province.name + '">' + province
                            .name + '</option>');
                    });
                },
                error: function() {
                    alert("Gagal memuat data, coba lagi nanti!");
                }
            });

            $('#search').on('change', function() {
                var provinceId = $(this).val();

                $.ajax({
                    url: "{{ route('guest.search') }}",
                    type: "GET",
                    data: {
                        search: provinceId
                    },
                    success: function(response) {
                        $('#reports-list').empty();

                        response.forEach(function(report) {
                            const provinceName = JSON.parse(report.province || '{}')
                                .name || 'Tidak diketahui';
                            const regencyName = JSON.parse(report.regency || '{}')
                                .name || 'Tidak diketahui';
                            const subdistrictName = JSON.parse(report.subdistrict ||
                                '{}').name || 'Tidak diketahui';
                            const villageName = JSON.parse(report.village || '{}')
                                .name || 'Tidak diketahui'

                            dayjs.locale('id');
                            dayjs.extend(dayjs_plugin_relativeTime);

                            const createdAt = new Date(report.created_at);

                            const formattedDate = dayjs(createdAt).fromNow();
                            $('#reports-list').append(`
                        <div class="mt-5 p-4 shadow-md" style="border-radius: 10px; background-color:#ffff">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage') }}/${report.image}" class="img-fluid rounded shadow-sm"
                                    alt="Gambar Artikel" style="width: 50%; max-width: 200px;">
                                <div class="ms-4">
                                    <h4 class="fw-bold report-title" data-id="${report.id}">
                                        <a class="text-dark" href="/guest/show/${report.id}">
                                            ${report.description.substring(0, 40)}...
                                        </a>
                                    </h4>
                                    <p class="text-muted" style="font-size: 0.9rem;">
                                        ${report.description.substring(0, 150)}...
                                    </p>
                                    <div class="d-flex gap-1">
                                        <small>${provinceName}</small>
                                        <small>${regencyName}</small>
                                        <small>${subdistrictName}</small>
                                        <small>${villageName}</small>
                                        </div>
                                    <small class="mb-3">${formattedDate}</small>
                                    <div class="mt-3 mb-2">
                                        <p class="btn 
                                            ${report.type === 'SOSIAL' ? 'btn-success' : 
                                            report.type === 'KEJAHATAN' ? 'btn-danger' : 
                                            report.type === 'PEMBANGUNAN' ? 'btn-warning' : ''}">
                                            ${report.type}
                                        </p>
                                    </div>
                                    <button class="btn voting-btn" 
                                        data-id="${report.id}" 
                                        data-voted="${report.voting && report.voting.includes('{{ auth()->id() }}') ? 'true' : 'false'}"
                                        name="voting" id="voting">
                                        <i class="fa fa-heart ${report.voting && report.voting.includes('{{ auth()->id() }}') ? 'text-danger' : ''}" aria-hidden="true"></i>
                                        <small id="vote-count" class="d-block text-muted">${report.voting ? report.voting.length : 0} votes</small>
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

            $(document).on('click', '.voting-btn', function() {
                var reportId = $(this).data('id');
                var voted = $(this).data('voted');

                if (voted === 'true') {
                    alert('Anda sudah menambahkan vote.');
                    return;
                }

                $.ajax({
                    url: "{{ route('guest.vote', ':id') }}".replace(':id', reportId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.message) {
                            $('.voting-btn[data-id="' + reportId + '"] i').addClass(
                                'text-danger');
                            $('.voting-btn[data-id="' + reportId + '"] .text-muted').text(
                                response.count + ' votes');
                            $('.voting-btn[data-id="' + reportId + '"]').data('voted', true);
                        } else {
                            alert(response.error);
                        }
                    },
                    error: function() {
                        alert('Anda sudah melakukan voting.');
                    }
                });
            });

            $(document).on('click', '.report-title', function() {
                var reportId = $(this).data('id'); 
                var viewersCount = $(this).siblings('.viewers-btn').find(
                '.viewers-count'); 

                $.ajax({
                    url: "{{ route('guest.views', ':id') }}".replace(':id', reportId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            viewersCount.text(response.views + ' views');
                        }
                    },
                    error: function() {
                        alert('Gagal menambahkan jumlah views.');
                    }
                });
            });

        });
    </script>
@endpush
