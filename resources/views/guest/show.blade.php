@extends('templates.app', ['title' => 'Data | Pengaduan Masyarakat'])

@section('dynamic-contents')
<div class="mt-5 p-4 shadow-lg" style="background-color: #F4F6FF; border-radius: 25px;">
    <div class="d-flex align-items-center">
        <img src="{{ asset('storage/' . $reports['image']) }}" class="img-fluid rounded shadow-sm" alt="Gambar Artikel" style="width: 50%; max-width: 200px;">
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
                <button type="button" class="btn btn-secondary shadow-sm btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Bagikan di Sosial Media">
                    {{ $reports['type'] }}
                </button>
            </div>
            <button class="btn" name="voting" id="voting">
                <i class="fa fa-heart" aria-hidden="true" name="image"></i>
            </button>
            <button class="btn" name="viewers" id="viewers">
                <i class="fa fa-eye" aria-hidden="true"></i>
            </button>
            </div>
        </div>
    </div>
@endsection