@extends('templates.app', ['title' => 'Data Dashboard | Pengaduan Masyarakat'])

@section('dynamic-contents')
<div class="container mt-5">
    <h1>Monitor Pengaduan</h1>
    <p>Pengaduan {{ $reports->first()->created_at->format('d M Y H:i') }}</p>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal"
                type="button" role="tab" aria-controls="personal" aria-selected="true">
                Data</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="employment-tab" data-bs-toggle="tab" data-bs-target="#employment"
                type="button" role="tab" aria-controls="employment" aria-selected="false">
                Employment</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
            <ul>
                <li>Tipe: {{ $reports->first()->type }}</li>
                <li>Lokasi: {{ $reports->first()->province }} {{ $reports->first()->subdistrict }} {{ $reports->first()->regency }} {{ $reports->first()->village }}</li>
            </ul>
        </div>
        <div class="tab-pane fade" id="employment" role="tabpanel" aria-labelledby="employment-tab">
            This is Employment Information Tab
        </div>
    </div>
</div>
@endsection