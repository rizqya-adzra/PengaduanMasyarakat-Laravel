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
                <h3><b>{{ $reports->first()->user_id }}</b></h3>
                <p>Pada tanggal {{ $reports->first()->created_at }} <b>Status Tanggapan:</b>
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
                    {{ json_decode($reports->first()->province)->name }},
                    {{ json_decode($reports->first()->regency)->name }},
                    {{ json_decode($reports->first()->subdistrict)->name }},
                    {{ json_decode($reports->first()->village)->name }}
                </b>
                <p>
                    {{ $reports->first()->description }}
                </p>
                <img src="{{ asset('storage/' . $reports->first()->image) }}" class="img-fluid rounded shadow-sm"
                    alt="Gambar Artikel" style="width: 50%; max-width: 200px;">
            </div>
            <div class="col-lg-6">
                <div>
                    @foreach ($response_progresses as $response_progress)
                    <ul>
                        <li> {{ json_decode($response_progress->histories)->note }} </li>
                    </ul>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button class="btn btn-success">Nyatakan Selesai</button>
            <button onclick="addModal('{{ $responses->first()->id }}', '{{ $responses->first()->histories }}')"
                class="btn btn-light">Tambah Progress</button>
        </div>
    </div>

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
    $('#histories-id').val(id); // Set ID untuk input hidden
    $('#histories').val(histories || ''); // Set string histories jika ada, kosongkan jika tidak ada
    $('#addModal').modal('show'); // Tampilkan modal
}

$('#form-add-progress').on('submit', function(e) {
    e.preventDefault(); // Prevent form default submit

    let id = $('#histories-id').val(); // Ambil ID
    let progress = $('#histories').val(); // Ambil input histories (string)
    let actionUrl = "{{ url('/staff/pengaduan/store_progress') }}/" + id; // URL target

    $.ajax({
        url: actionUrl,
        type: 'POST',
        contentType: 'application/json', // Format JSON
        data: JSON.stringify({
            _token: '{{ csrf_token() }}', // Token CSRF
            histories: progress, // Kirim string histories langsung
        }),
        success: function(response) {
            if (response.success) {
                $('#addModal').modal('hide');
                alert(response.message);
                location.reload(); // Reload jika sukses
            } else {
                alert(response.message);
            }
        },
        error: function(err) {
            alert('Gagal menambahkan response');
        }
    });
});

    </script>
@endpush
