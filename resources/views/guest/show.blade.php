@extends('templates.app', ['title' => 'Data | Pengaduan Masyarakat'])

@section('dynamic-contents')
@if (Session::get('failed'))
        <div class="toast align-items-center text-bg-danger border-0 position-fixed bottom-0 end-0 m-3 mb-4 p-2 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" id="toast">
            <div class="d-flex">
              <div class="toast-body">
                {{ Session::get('failed') }}
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
    @if (Session::get('success'))
        <div class="toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-3 mb-4 p-2 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" id="toast">
            <div class="d-flex">
              <div class="toast-body">
                {{ Session::get('success') }}
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="container d-flex justify-content-center">
        <div class="col-lg-8 col-md-12 p-5">
            <div class="mt-2 p-4 shadow-lg" style=" border-radius: 25px;">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('storage/' . $reports['image']) }}" class="img-fluid rounded shadow-sm"
                        alt="Gambar Artikel" style="width: 50%; max-width: 200px;">
                    <div class="ms-4">
                        <h4 class="fw-bold"><a class="text-dark" href="">{{ $reports['description'] }}
                            </a></h4>
                        <p class="text-muted" style="font-size: 0.9rem;">
                            {{ $reports['description'] }}
                        </p>
                        <div>
                            <small> {{ $reports['created_at'] }} </small>
                            <small> {{ $reports['province'] }} </small>
                            <small> {{ $reports['regency'] }} </small>
                        </div>
                        <div class="mt-3 mb-2">
                            <button type="button" class="btn btn-secondary shadow-sm btn-sm" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Jenis Artikel">
                                {{ $reports['type'] }}
                            </button>
                        </div>
                        <button class="btn voting-btn" data-id="{{ $reports['id'] }}" name="voting" id="voting">
                            <i class="fa fa-heart" aria-hidden="true" name="image"></i>
                            <small class="d-block text-muted">
                                {{ count($reports['voting'] ?? []) }} votes
                            </small>
                        </button>
                        
                        <button class="btn" name="viewers" id="viewers">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            <small class="d-block text-muted">{{ $reports['viewers'] ?? 0 }} views</small>
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <div>
                        <p>Komentar</p>
                        @if($comments->isEmpty())
                            <p>Belum ada komentar.</p>
                        @else
                            <ul>
                                @foreach ($comments as $comment)
                                    <li>
                                        <p>{{ $comment->comment }}</p>
                                        <p><small>Dibuat pada: {{ $comment->created_at->format('d M Y H:i') }}</small></p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                

                <div class="mt-4">
                    <form action="{{ route('guest.store', $reports['id']) }}" class="form d-flex flex-column" method="POST">
                        @csrf
                        <label class="form-label">Tambahkan Komentar:</label>
                        <textarea class="form-control" name="comment" id="comment" cols="20" rows="5"></textarea>
                        <button type="submit" class="btn btn-success mt-3">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed top-50 end-0 p-5 d-flex flex-column" style="transform: translateY(-50%);">
        <button class="btn btn-lg mb-3 shadow-sm" style="background-color: #FBD46D" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-info" aria-hidden="true"></i></button>
    </div>
    
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
@endsection

@push('script')
<script>
    $(document).ready(function() {
        if ($("#toast").length) {
            var toast = new bootstrap.Toast(document.getElementById('toast'));
            toast.show();
        }
    });
</script>
@endpush