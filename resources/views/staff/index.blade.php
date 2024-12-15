@extends('templates.app', ['title' => 'Monitoring Pengaduan | Pengaduan Masyarakat'])

@section('dynamic-contents')
    <div class="container bg-white mt-5 p-5">
        <div class="d-flex justify-content-end">
            <div>
                <button class="btn btn-secondary">Export Excel</button>
            </div>
        </div>
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Gambar dan Pengirim</th>
                        <th>Lokasi dan Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Vote</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td class="gap-0"><img src="{{ asset('storage/' . $report['image']) }}" alt="Gambar Artikel"
                                    class="rounded-circle shadow-sm img-fluid mt-3"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                                {{ $report->user_id }}</td>
                            <td>{{ json_decode($report->province)->name }},
                                {{ json_decode($report->regency)->name }},
                                {{ json_decode($report->subdistrict)->name }},
                                {{ json_decode($report->village)->name }}</td>
                            <td>{{ $report->description }}</td>
                            <td>{{ count($report->voting) }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li onclick="addModal('{{ $report->id }}', '{{ $report->response_status }}')">Tindak Lanjut
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
    <!-- Modal Tambah Data -->
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
        function addModal(id, response) {
            $('#response-id').val(id); // Set the ID of the report
            $('#response_status').val(response); // Set the selected response status
            $('#addModal').modal('show');
        }

        $('#form-add-response').on('submit', function(e) {
    e.preventDefault(); // Prevent default form submission

    let id = $('#response-id').val(); // Ambil ID report yang ingin ditindaklanjuti
    let responseStatus = $('#response_status').val(); // Ambil status response yang dipilih
    let actionUrl = "{{ url('/staff/pengaduan/store') }}/" + id; // URL untuk menambah data

    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}', // Token CSRF
            response_status: responseStatus, // Kirim status response
        },
        success: function(response) {
            $('#addModal').modal('hide');
            location.reload(); // Reload halaman setelah berhasil
            alert('Response berhasil ditambahkan!');
        },
        error: function(err) {
            alert('Gagal menambahkan response');
        }
    });
});

    </script>
@endpush
