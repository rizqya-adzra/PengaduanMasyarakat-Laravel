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
            <!-- Bagian Pencarian -->
            <div class="col-lg-8 col-md-12 p-5">
                <div class="text-center mb-5">
                    <h2 class="mb-4" style="color: #495E57; font-weight: bold;">Pengaduan Anda</h2>
                    @foreach ($comments as $comment)
                        <div class="mt-5 p-4 shadow-lg " style=" border-radius: 25px;">
                            <div class="rounded" style="background-color: #495E57; color: white">
                                <p>ON PROCESS</p>
                            </div>
                            <p>dikirim pada <b>{{ $comment['created_at'] }}</b></p>
                            <div class="card">
                                <p>{{ $comment['comment'] }}</p>
                            </div>
                            <b><a href="{{ route('guest.showDashboard', $comment['id']) }}">Selengkapnya...</a></b>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-danger" onclick="showModal( '{{ $comment->id }}', '{{ $comment->comment }}')">Hapus</button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    <div class="position-fixed top-50 end-0 p-5 d-flex flex-column" style="transform: translateY(-50%);">
        <button class="btn btn-lg mb-3 shadow-sm" style="background-color: #FBD46D" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-info" aria-hidden="true"></i></button>
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

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" id="form-delete-comment" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">Hapus data Buku</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        apakah anda yakin akan menghapus data Buku <span id="nama-buku"
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
            let action = '{{ route('guest.delete', ':id') }}';
            action = action.replace(':id', id);
            $('#form-delete-comment').attr('action', action);
            $('#deleteModal').modal('show');
            console.log(comment);
            $('#nama-buku').text(comment);
        }
</script>
@endpush
