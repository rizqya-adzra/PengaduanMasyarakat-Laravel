@extends('templates.app', ['title' => 'Dashboard | Pengaduan Masyarakat'])

@section('dynamic-contents')
    @if (Session::get('failed'))
        <div class="toast align-items-center text-bg-danger border-0 position-fixed bottom-0 end-0 m-3 mb-4 p-2 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" id="toast">
            <div class="d-flex">
              <div class="toast-body">
                {{Session::get('failed')}}
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
    @if (Session::get('success'))
        <div class="toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-3 mb-4 p-2 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" id="toast">
            <div class="d-flex">
              <div class="toast-body">
                {{Session::get('success')}}
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-lg-6 p-5">
                <div class="text-center mb-5">
                    <h2 class="mb-3" style="color: #495E57; font-weight: bold;">Pengaduan Anda</h2>
                    @foreach ($reports as $report)
                        <div class="mt-3 p-4 shadow-lg " style=" border-radius: 25px;">
                            <div class="rounded" style="background-color: #495E57; color: white">
                                <p>ON PROCESS</p>
                            </div>
                            <p>dikirim pada <b>{{ $report['created_at'] }}</b></p>
                            <div class="card">
                                <img src="{{ asset('storage/' . $report['image']) }}" class=" d-flex align-content-center rounded shadow-sm"
                        alt="Gambar Artikel" style="width: 50%; max-width: 200px;">
                                <p>{{ \Illuminate\Support\Str::words($report['description'], 8) }}</p>
                                <p>{{ $report['type'] }}</p>
                                <div class="d-flex justify-content-center gap-10">
                                    <p>{{ $report['province'] }}</p>
                                    <p>{{ $report['regency'] }}</p>
                                    <p>{{ $report['subdistrict'] }}</p>
                                    <p>{{ $report['village'] }}</p>
                                </div>
                            </div>
                            <b><a href="{{ route('guest.show', $report['id']) }}">Selengkapnya...</a></b>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-danger" onclick="deleteReport( '{{ $report->id }}', '{{ $report->description }}')">Hapus</button>
                        </div>
                    @endforeach
                </div>
            </div>

    <div class="position-fixed top-50 end-0 p-5 d-flex flex-column" style="transform: translateY(-50%);">
        <button class="btn btn-lg mb-3 shadow-sm" style="background-color: #FBD46D" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-info" aria-hidden="true"></i></button>
        <a class="btn btn-lg mb-3" href="{{ route('guest.create') }}" style="background-color: #FBD46D"><i
            class="fa fa-pencil" aria-hidden="true"></i></a>
    </div>  

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
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

    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" id="form-delete-comment" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDeleteLabel">Hapus Komentar</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        apakah anda yakin akan menghapus Komentar <span id="comment"
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
    <div class="modal fade" id="modalDeleteReport" tabindex="-1" aria-labelledby="modalDeleteReportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" id="form-delete-report" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDeleteReportLabel">Hapus Pengaduan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
        
        function showModal(id, comment) {
            let action = '{{ route('guest.comments.delete', ':id') }}';
            action = action.replace(':id', id);
            $('#form-delete-comment').attr('action', action);
            $('#modalDelete').modal('show');
            console.log(comment);
            $('#comment').text(comment);
        }

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
