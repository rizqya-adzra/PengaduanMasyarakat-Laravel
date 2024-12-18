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
    <div class="container bg-white mt-5 p-5">
        <div class="d-flex justify-content-between">
            <div>
                <h3><span style="color: {{ $reports->first()->user ? 'black' : 'red' }};">
                    {{ $reports->first()->user->email ?? 'User Tidak Ditemukan' }}
                </span></h3>
                <p>Pada tanggal {{ \Carbon\Carbon::parse($reports->first()->created_at)->translatedFormat('d F Y') }} <b>Status Tanggapan:</b> </p>
                <p
                    class="btn 
                    @if ($responses->first()->response_status === 'DONE') btn-success 
                    @elseif ($responses->first()->response_status === 'REJECT') btn-danger 
                    @elseif ($responses->first()->response_status === 'ON_PROCESS') btn-warning @endif">
                    {{ $responses->first()->response_status }}
                </p>
            </div>
            <div>
                <a class="btn btn-secondary" href="{{ route('staff.index') }}">Kembali</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <b>
                    {{ json_decode($reports->first()->province)->name }} ,
                    {{ json_decode($reports->first()->regency)->name }},
                    {{ json_decode($reports->first()->subdistrict)->name }},
                    {{ json_decode($reports->first()->village)->name }}
                </b>
                <p>{{ $reports->first()->description }}</p>
                <img src="{{ asset('storage/' . $reports->first()->image) }}" class="img-fluid rounded shadow-sm"
                    alt="Gambar Artikel" style="width: 50%; max-width: 200px;">
            </div>
            <div class="col-lg-6">
                <div>
                    @foreach ($response_progresses as $response_progress)
                        <ul class="d-flex justify-content-between" >
                            <li> {{ json_decode($response_progress->histories)->note }} </li>
                            <p style="color: #00bf63">{{ $response_progress->created_at->diffForHumans() }} </p>
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            @if ($responses->first()->response_status === 'REJECT')
            <p class="text-danger">Pengaduan telah Anda tolak!</p>
            @else
            <button class="btn btn-success" onclick="showConfirmationModal()">Nyatakan Selesai</button>
            <button onclick="addModal('{{ $responses->first()->id }}', '{{ $responses->first()->histories }}')"
                class="btn btn-light">Tambah Progress</button>
            @endif
        </div>
    </div>

    <!-- Modal Tambah Progress -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addResponseTable" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-add-progress" method="POST">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addResponseTable">Tambah tanggapan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body mt-2">
                        <input type="hidden" name="id" id="histories-id">
                        <div class="form-group">
                            <label for="histories" class="form-label">Tanggapan</label>
                            <input type="text" name="histories" id="histories" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Aksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyelesaikan pengaduan ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" id="confirmYes" class="btn btn-primary">Yes</button>
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

        function addModal(id, histories) {
            $('#histories-id').val(id);
            $('#histories').val(histories || '');
            $('#addModal').modal('show');
        }

        $('#form-add-progress').on('submit', function(e) {
            e.preventDefault();

            let id = $('#histories-id').val();
            let progress = $('#histories').val();
            let actionUrl = "{{ url('/staff/pengaduan/store_progress') }}/" + id;

            $.ajax({
                url: actionUrl,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    _token: '{{ csrf_token() }}',
                    histories: progress,
                }),
                success: function(response) {
                    if (response.success) {
                        $('#addModal').modal('hide');
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(err) {
                    alert('Gagal menambahkan response');
                }
            });
        });

        function showConfirmationModal() {
            $('#confirmationModal').modal('show');
            $('#confirmYes').off('click').on('click', function() {
                updateStatusToDone();
            });
        }

        function updateStatusToDone() {
            let id = "{{ $responses->first()->id }}";
            let actionUrl = "{{ url('/staff/pengaduan/update_status') }}/" + id;

            $.ajax({
                url: actionUrl,
                type: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify({
                    _token: '{{ csrf_token() }}',
                    response_status: 'DONE'
                }),
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(err) {
                    alert('Gagal mengubah status.');
                }
            });
        }
    </script>
@endpush
