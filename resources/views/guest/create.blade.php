@extends('templates.app', ['title' => 'Tambah Artikel | Pengaduan Masyarakat'])

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
<div class="container w-75 my-5">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-uppercase" style="color: #4e73df;">Buat Pengaduan</h3>
    </div>

    <form action="{{ route('guest.store') }}" method="POST" class="form p-4 shadow rounded-3" style="background-color: #f8f9fc;" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label fw-bold" for="description">Deskripsi</label>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <textarea class="form-control" name="description" id="description" rows="5" placeholder="Tulis deskripsi di sini..."></textarea>
            </div>
        </div>

        <div class="row mb-3">
            {{-- Unggah Foto --}}
            <div class="col-md-6">
                <label class="form-label fw-bold" for="image">Unggah Foto</label>
                <div class="input-group">
                    <span class="input-group-text bg-warning text-white"><i class="bi bi-upload"></i></span>
                    <input class="form-control" type="file" name="image" id="image">
                </div>
                @error('image')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Jenis Artikel --}}
            <div class="col-md-6">
                <label class="form-label fw-bold" for="type">Jenis Artikel</label>
                <select class="form-select" name="type" id="type">
                    <option value="" disabled selected>Pilih Jenis</option>
                    <option value="KEJAHATAN">Kejahatan</option>
                    <option value="PEMBANGUNAN">Pembangunan</option>
                    <option value="SOSIAL">Sosial</option>
                </select>
                @error('type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        {{-- Wilayah Dropdowns --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="province">Provinsi</label>
                <select class="form-select" name="province" id="province">
                    <option value="" disabled selected>Pilih Provinsi</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="regency">Kota/Kabupaten</label>
                <select class="form-select" name="regency" id="regency" disabled>
                    <option value="" disabled selected>Pilih Kabupaten</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="subdistrict">Kecamatan</label>
                <select class="form-select" name="subdistrict" id="subdistrict" disabled>
                    <option value="" disabled selected>Pilih Kecamatan</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="village">Desa</label>
                <select class="form-select" name="village" id="village" disabled>
                    <option value="" disabled selected>Pilih Desa</option>
                </select>
            </div>
        </div>

        {{-- Checkbox --}}
        <div class="mb-3">
            <input type="checkbox" class="form-check-input" name="statement" id="statement">
            <label class="form-check-label" for="statement">Laporan yang disampaikan sesuai dengan kebenaran.</label>
        </div>

        {{-- Submit Button --}}
        <div class="text-center">
            <button type="submit" class="btn btn-primary shadow-sm px-4 py-2">Kirim</button>
        </div>
    </form>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
    if ($("#toast").length) {
        var toast = new bootstrap.Toast(document.getElementById('toast'));
        toast.show();
    }

    $.ajax({
        method: "GET",
        url: "https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json",
        dataType: "json",
        success: function(response) {
            response.forEach(function(province) {
                $('#province').append('<option value="' + province.id + '" data-name="' + province.name + '">' + province.name + '</option>');
            });
        },
        error: function() {
            alert("Gagal memuat data provinsi!");
        }
    });

    $('#province').on('change', function() {
        let provinceId = $(this).val();
        let provinceName = $('#province option:selected').data('name');
        if (provinceId) {
            $('#regency').prop('disabled', false).html('<option value="" disabled selected hidden>Loading...</option>');
            $('#subdistrict, #village').prop('disabled', true).html('<option value="" disabled selected hidden>Pilih Kecamatan/Desa</option>');
            $.ajax({
                method: "GET",
                url: `https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`,
                dataType: "json",
                success: function(response) {
                    $('#regency').html('<option value="" disabled selected hidden>Pilih Kabupaten</option>');
                    response.forEach(function(regency) {
                        $('#regency').append('<option value="' + regency.id + '" data-name="' + regency.name + '">' + regency.name + '</option>');
                    });
                },
                error: function() {
                    alert("Gagal memuat data kabupaten!");
                }
            });
        }
    });

    $('#regency').on('change', function() {
        let regencyId = $(this).val();
        let regencyName = $('#regency option:selected').data('name');
        if (regencyId) {
            $('#subdistrict').prop('disabled', false).html('<option value="" disabled selected hidden>Loading...</option>');
            $('#village').prop('disabled', true).html('<option value="" disabled selected hidden>Pilih Desa</option>');
            $.ajax({
                method: "GET",
                url: `https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regencyId}.json`,
                dataType: "json",
                success: function(response) {
                    $('#subdistrict').html('<option value="" disabled selected hidden>Pilih Kecamatan</option>');
                    response.forEach(function(subdistrict) {
                        $('#subdistrict').append('<option value="' + subdistrict.id + '" data-name="' + subdistrict.name + '">' + subdistrict.name + '</option>');
                    });
                },
                error: function() {
                    alert("Gagal memuat data kecamatan!");
                }
            });
        }
    });

    $('#subdistrict').on('change', function() {
        let subdistrictId = $(this).val();
        let subdistrictName = $('#subdistrict option:selected').data('name');
        if (subdistrictId) {
            $('#village').prop('disabled', false).html('<option value="" disabled selected hidden>Loading...</option>');
            $.ajax({
                method: "GET",
                url: `https://www.emsifa.com/api-wilayah-indonesia/api/villages/${subdistrictId}.json`,
                dataType: "json",
                success: function(response) {
                    $('#village').html('<option value="" disabled selected hidden>Pilih Desa</option>');
                    response.forEach(function(village) {
                        $('#village').append('<option value="' + village.id + '" data-name="' + village.name + '">' + village.name + '</option>');
                    });
                },
                error: function() {
                    alert("Gagal memuat data desa!");
                }
            });
        }
    });

    $('form').on('submit', function(e) {
        e.preventDefault();

        let province = JSON.stringify({
            id: $('#province').val(),
            name: $('#province option:selected').data('name')
        });
        let regency = JSON.stringify({
            id: $('#regency').val(),
            name: $('#regency option:selected').data('name')
        });
        let subdistrict = JSON.stringify({
            id: $('#subdistrict').val(),
            name: $('#subdistrict option:selected').data('name')
        });
        let village = JSON.stringify({
            id: $('#village').val(),
            name: $('#village option:selected').data('name')
        });

        $('<input>').attr({
            type: 'hidden',
            name: 'province',
            value: province
        }).appendTo('form');
        $('<input>').attr({
            type: 'hidden',
            name: 'regency',
            value: regency
        }).appendTo('form');
        $('<input>').attr({
            type: 'hidden',
            name: 'subdistrict',
            value: subdistrict
        }).appendTo('form');
        $('<input>').attr({
            type: 'hidden',
            name: 'village',
            value: village
        }).appendTo('form');

        this.submit();
    });
});

</script>
@endpush