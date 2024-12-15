@extends('templates.app', ['title' => 'Dashboard | Pengaduan Masyarakat'])

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
    <h3 class="text-center">Monitor Pengaduan</h3>
    <div class="accordion" id="reportAccordion">
        @foreach ($reports as $report)
            <div class="accordion-item" style="background-color: {{ $loop->odd ? '#495E57' : '#fff' }}; color: {{ $loop->odd ? '#fff' : '#000' }}">
                <h2 class="accordion-header" id="heading-{{ $report->id }}">
                    <button 
                        class="accordion-button collapsed" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#collapse-{{ $report->id }}" 
                        aria-expanded="false" 
                        aria-controls="collapse-{{ $report->id }}"
                        style="background-color: {{ $loop->odd ? '#495E57' : '#fff' }}; color: {{ $loop->odd ? '#fff' : '#00' }}">
                        Pengaduan pada {{ $report->created_at->format('d M Y H:i') }}
                    </button>
                </h2>
                <div 
                    id="collapse-{{ $report->id }}" 
                    class="accordion-collapse collapse" 
                    aria-labelledby="heading-{{ $report->id }}" 
                    data-bs-parent="#reportAccordion">
                    <div class="accordion-body">
                        <ul class="nav nav-tabs justify-content-around" id="myTab-{{ $report->id }}" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active fw-bold text-uppercase border-0 bg-transparent" 
                                   id="data-tab-{{ $report->id }}" 
                                   data-bs-toggle="tab" 
                                   href="#data-{{ $report->id }}" 
                                   role="tab" 
                                   aria-controls="data-{{ $report->id }}" 
                                   aria-selected="true"
                                   style="color: {{ $loop->odd ? '#fff' : '#000' }}">
                                   Data
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link fw-bold text-uppercase border-0 bg-transparent" 
                                   id="image-tab-{{ $report->id }}" 
                                   data-bs-toggle="tab" 
                                   href="#image-{{ $report->id }}" 
                                   role="tab" 
                                   aria-controls="image-{{ $report->id }}" 
                                   aria-selected="false"
                                   style="color: {{ $loop->odd ? '#fff' : '#000' }}">
                                   Gambar
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link fw-bold text-uppercase border-0 bg-transparent" 
                                   id="status-tab-{{ $report->id }}" 
                                   data-bs-toggle="tab" 
                                   href="#status-{{ $report->id }}" 
                                   role="tab" 
                                   aria-controls="status-{{ $report->id }}" 
                                   aria-selected="false"
                                   style="color: {{ $loop->odd ? '#fff' : '#000' }}">
                                   Status
                                </a>
                            </li>
                        </ul>
                           
                        <div class="tab-content p-3 rounded-bottom" id="myTabContent-{{ $report->id }}">
                            <!-- Tab Data -->
                            <div 
                                class="tab-pane fade show active" 
                                id="data-{{ $report->id }}" 
                                role="tabpanel" 
                                aria-labelledby="data-tab-{{ $report->id }}">
                                <ul class="list-unstyled">
                                    <li><strong>Lokasi:</strong> {{ json_decode($report->province)->name }}, 
                                        {{ json_decode($report->regency)->name }}, 
                                        {{ json_decode($report->subdistrict)->name }}, 
                                        {{ json_decode($report->village)->name }}</li>
                                    <li><strong>Tipe:</strong> {{ $report['type'] }}</li>
                                    <li><strong>Deskripsi:</strong> {{ $report->description }}</li>
                                </ul>
                            </div>
    
                            <!-- Tab Gambar -->
                            <div 
                                class="tab-pane fade" 
                                id="image-{{ $report->id }}" 
                                role="tabpanel" 
                                aria-labelledby="image-tab-{{ $report->id }}">
                                <div class="text-center">
                                    <img 
                                        src="{{ asset('storage/' . $report['image']) }}" 
                                        alt="Gambar Artikel" 
                                        class="rounded shadow-sm img-fluid mt-3"
                                        style="max-width: 300px; object-fit: contain;">
                                </div>
                            </div>
    
                            <!-- Tab Status -->
                            <div 
                                class="tab-pane fade" 
                                id="status-{{ $report->id }}" 
                                role="tabpanel" 
                                aria-labelledby="status-tab-{{ $report->id }}">
                                <ul class="list-unstyled">
                                    <li class="text-danger fw-bold">
                                        Pengaduan belum di respon petugas, ingin menghapus pengaduan?
                                    </li>
                                    <li class="mt-2">
                                        <button 
                                            class="btn btn-danger btn-sm fw-semibold"
                                            onclick="deleteReport('{{ $report->id }}', '{{ $report->description }}')">
                                            <i class="bi bi-trash me-1"></i>Hapus
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


    

    <div class="position-fixed top-50 end-0 p-5 d-flex flex-column" style="transform: translateY(-50%);">
        <button class="btn btn-lg mb-3 shadow-sm" style="background-color: #FBD46D" data-bs-toggle="modal"
            data-bs-target="#deleteModal"><i class="fa fa-info" aria-hidden="true"></i></button>
        <a class="btn btn-lg mb-3" href="{{ route('guest.create') }}" style="background-color: #FBD46D"><i
                class="fa fa-pencil" aria-hidden="true"></i></a>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
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

    <div class="modal fade" id="modalDeleteReport" tabindex="-1" aria-labelledby="modalDeleteReportLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="" id="form-delete-report" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDeleteReportLabel">Hapus Pengaduan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        apakah anda yakin akan menghapus Pengaduan <span id="report"
                            style="font-weight: bolder"></span> ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Tetap Hapus</button>
                    </div>
                </div>
            </form>
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

        function deleteReport(id, description) {
            let action = '{{ route('guest.delete', ':id') }}';
            action = action.replace(':id', id);
            $('#form-delete-report').attr('action', action);
            $('#modalDeleteReport').modal('show');
            console.log(description);
            $('#report').text(description);
        }
    </script>
@endpush
