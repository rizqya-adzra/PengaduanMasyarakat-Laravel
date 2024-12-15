@extends('templates.app', ['title' => 'Monitoring Pengaduan | Pengaduan Masyarakat'])

@section('dynamic-contents')
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
                <button class="btn btn-secondary">Kembali</button>
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
                    <ul>
                        <li> {{ $responses->first()->response_status }} </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button class="btn btn-success">Nyatakan Selesai</button>
            <button class="btn btn-light">Tambah Progress</button>
        </div>
    </div>
@endsection
