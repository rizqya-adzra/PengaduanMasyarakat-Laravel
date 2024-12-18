@extends('templates.app', ['title' => 'Monitoring Pengaduan | Pengaduan Masyarakat'])

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
    <div class="container bg-white mt-5 p-5 shadow-sm ">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h4>Data Pengaduan Daerah <span style="color: #8c52ff">{{$province}}</span> </h4>
            </div>
            <form action="{{ route('staff.download') }}" method="GET">
                <div class="dropdown">
                    <button class="btn mb-4 btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Export (.xlsx)
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li>
                            <a class="dropdown-item" href="#" id="export-all">Seluruh Data</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" id="export-date">Berdasarkan Tanggal</a>
                        </li>
                    </ul>
                </div>
            
                <div id="date-range" class="d-none mt-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" placeholder="Pilih Tanggal Mulai">
                    
                    <label for="end_date" class="form-label mt-2">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" placeholder="Pilih Tanggal Selesai">
                </div>
            
                <button type="submit" class="btn btn-primary mt-3" id="submit-btn" style="display:none">Export Excel</button>
            
                <input type="hidden" name="date_filter" id="date_filter" value="all">
            </form>
            
            <script>
                document.getElementById('export-date').addEventListener('click', function () {
                    document.getElementById('date-range').classList.remove('d-none');
                    document.getElementById('submit-btn').style.display = 'inline-block';
                    document.getElementById('date_filter').value = 'custom'; 
                });
            
                document.getElementById('export-all').addEventListener('click', function () {
                    document.getElementById('date-range').classList.add('d-none');
                    document.getElementById('submit-btn').style.display = 'inline-block';
                    document.getElementById('date_filter').value = 'all';  
                });
            </script>
            
            
        </div>
        <div>
            <table class="table p-5">
                <thead class="table-info">
                    <tr>
                        <th>Gambar dan Pengirim</th>
                        <th>Lokasi dan Tanggal</th>
                        <th>Deskripsi</th>
                        <th>
                            <a href="{{ route('staff.index', ['sort' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                Jumlah Vote 
                                @if ($sortOrder === 'asc')
                                    🔼
                                @else
                                    🔽
                                @endif
                            </a>
                        </th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td class="gap-0"><img src="{{ asset('storage/' . $report['image']) }}" alt="Gambar Artikel"
                                    class="rounded-circle shadow-sm img-fluid mt-3"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                                    <span style="color: {{ $report->user ? 'black' : 'red' }};">
                                        {{ $report->user->email ?? 'User Tidak Ditemukan' }}
                                    </span></td>
                            <td class="col-3">{{ json_decode($report->province)->name }},
                                {{ json_decode($report->regency)->name }},
                                {{ json_decode($report->subdistrict)->name }},
                                {{ json_decode($report->village)->name }} -
                                {{ \Carbon\Carbon::parse($report->created_at)->translatedFormat('d F Y') }}
                            </td>
                            <td class="col-5"><a class="text-dark"
                                    href="{{ route('staff.show', $report['id']) }}">{{ $report->description }}</a></td>
                            <td>{{ count($report->voting) }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li onclick="addModal('{{ $report->id }}', '{{ $report->response_status }}')">
                                            Tindak Lanjut
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addResponseTable" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-add-response" method="POST">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addResponseTable">Tambah Response</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="response-id">
                        <div class="form-group">
                            <label for="response_status" class="form-label">Nama Response</label>
                            <select name="response_status" id="response_status" class="form-control">
                                <option value="" selected hidden disabled>Pilih</option>
                                <option value="ON_PROCESS">On Process</option>
                                <option value="DONE">Done</option>
                                <option value="REJECT">Reject</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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

        function addModal(id, response) {
            $('#response-id').val(id);
            $('#response_status').val(response);
            $('#addModal').modal('show');
        }

        $('#form-add-response').on('submit', function(e) {
            e.preventDefault();

            let id = $('#response-id').val();
            let responseStatus = $('#response_status').val();
            let actionUrl = "{{ url('/staff/pengaduan/store') }}/" + id;

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', 
                    response_status: responseStatus, 
                },
                success: function(response) {
                    $('#addModal').modal('hide');
                    window.location.href = "{{ url('/staff/pengaduan/show') }}/" +
                    id;
                    alert('Response berhasil ditambahkan!');
                },
                error: function(err) {
                    alert('Gagal menambahkan response');
                }
            });
        });
    </script>
@endpush
