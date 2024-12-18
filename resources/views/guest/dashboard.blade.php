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
        <h2 class="text-center mb-4" style="color: #8c52ff; font-weight: bold;">Monitoring Pengaduan</h2>
        <div class="accordion shadow-sm" id="reportAccordion">
            @foreach ($reports as $report)
                <div class="accordion-item"
                    style="background-color: {{ $loop->odd ? '#00bf63' : '#fff' }}; color: {{ $loop->odd ? '#fff' : '#000' }}">
                    <h2 class="accordion-header" id="heading-{{ $report->id }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $report->id }}" aria-expanded="false"
                            aria-controls="collapse-{{ $report->id }}"
                            style="background-color: {{ $loop->odd ? '#00bf63' : '#fff' }}; color: {{ $loop->odd ? '#fff' : '#00' }}">
                            Pengaduan pada {{ $report->created_at->format('d M Y H:i') }}
                        </button>
                    </h2>
                    <div id="collapse-{{ $report->id }}" class="accordion-collapse collapse"
                        aria-labelledby="heading-{{ $report->id }}" data-bs-parent="#reportAccordion">
                        <div class="accordion-body">
                            <ul class="nav nav-tabs justify-content-around" id="myTab-{{ $report->id }}" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active fw-bold text-uppercase border-0 bg-transparent"
                                        id="data-tab-{{ $report->id }}" data-bs-toggle="tab"
                                        href="#data-{{ $report->id }}" role="tab"
                                        aria-controls="data-{{ $report->id }}" aria-selected="true"
                                        style="color: {{ $loop->odd ? '#fff' : '#000' }}">
                                        Data
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link fw-bold text-uppercase border-0 bg-transparent"
                                        id="image-tab-{{ $report->id }}" data-bs-toggle="tab"
                                        href="#image-{{ $report->id }}" role="tab"
                                        aria-controls="image-{{ $report->id }}" aria-selected="false"
                                        style="color: {{ $loop->odd ? '#fff' : '#000' }}">
                                        Gambar
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link fw-bold text-uppercase border-0 bg-transparent"
                                        id="status-tab-{{ $report->id }}" data-bs-toggle="tab"
                                        href="#status-{{ $report->id }}" role="tab"
                                        aria-controls="status-{{ $report->id }}" aria-selected="false"
                                        style="color: {{ $loop->odd ? '#fff' : '#000' }}">
                                        Status
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content p-3 rounded-bottom" id="myTabContent-{{ $report->id }}">
                                <!-- Tab Data -->
                                <div class="tab-pane fade show active" id="data-{{ $report->id }}" role="tabpanel"
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
                                <div class="tab-pane fade" id="image-{{ $report->id }}" role="tabpanel"
                                    aria-labelledby="image-tab-{{ $report->id }}">
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $report['image']) }}" alt="Gambar Artikel"
                                            class="rounded shadow-sm img-fluid mt-3"
                                            style="max-width: 300px; object-fit: contain;">
                                    </div>
                                </div>

                                <!-- Tab Status -->
                                @forelse ($responses->where('report_id', $report->id) as $response)
                                    @if ($responses->where('report_id', $report->id)->isEmpty())
                                        <div class="tab-pane fade" id="status-{{ $report->id }}" role="tabpanel"
                                            aria-labelledby="status-tab-{{ $report->id }}">
                                            <ul class="list-unstyled">
                                                <li class="d-flex flex-column">
                                                    <div class="fw-bold d-flex" style="gap: 10px">
                                                        <p>Belum di respon</p>
                                                    </div>
                                                </li>
                                                <li class="mt-4">
                                                    <button class="btn btn-danger btn-sm fw-semibold"
                                                        onclick="deleteReport('{{ $report->id }}', '{{ $report->description }}')">
                                                        <i class="fa fa-trash me-1"></i>
                                                        Hapus Pengaduan
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    @else
                                        @foreach ($response->response_progress as $response_progress)
                                            <div class="tab-pane fade" id="status-{{ $report->id }}" role="tabpanel"
                                                aria-labelledby="status-tab-{{ $report->id }}">
                                                <ul class="list-unstyled">
                                                    <li class="d-flex flex-column">
                                                        <div class="fw-bold d-flex" style="gap: 10px">
                                                            <p>
                                                                @if ($response->response_status === 'DONE')
                                                                    Pengaduan anda telah selesai!
                                                                @elseif ($response->response_status === 'ON_PROCESS')
                                                                    Pengaduan Anda ditolak!
                                                                @elseif ($response->response_status === 'REJECT')
                                                                    Pengaduan sedang diproses, dengan status
                                                                @endif
                                                            </p>
                                                            <p
                                                                class="btn
                                                                @if ($response->response_status === 'DONE') btn-success 
                                                                @elseif ($response->response_status === 'ON_PROCESS') btn-danger 
                                                                @elseif ($response->response_status === 'REJECT') btn-warning @endif">
                                                                {{ $response->response_status }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <ul>
                                                                @foreach ($response->response_progress as $response_progress)
                                                                    <li>{{ json_decode($response_progress->histories)->note }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    @if ($response->response_status !== 'DONE' && $response->response_status !== 'ON_PROCESS' && $response->response_status !== 'ON_PROCESS')
                                                        <li class="mt-4">
                                                            <button class="btn btn-danger btn-sm fw-semibold"
                                                                onclick="deleteReport('{{ $report->id }}', '{{ $report->description }}')">
                                                                <i class="fa fa-trash me-1"></i>
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endforeach
                                    @endif
                                @empty
                                    <div class="tab-pane fade" id="status-{{ $report->id }}" role="tabpanel"
                                        aria-labelledby="status-tab-{{ $report->id }}">
                                        <ul class="list-unstyled">
                                            <li class="d-flex flex-column">
                                                <div class="fw-bold d-flex" style="gap: 10px">
                                                    <p>Belum di respon, apakah ingin menghapus data?</p>
                                                </div>
                                            </li>
                                            <li class="mt-4">
                                                <button class="btn btn-danger btn-sm fw-semibold"
                                                    onclick="deleteReport('{{ $report->id }}', '{{ $report->description }}')">
                                                    <i class="fa fa-trash me-1"></i>
                                                    Hapus Pengaduan
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal Code Here -->
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
