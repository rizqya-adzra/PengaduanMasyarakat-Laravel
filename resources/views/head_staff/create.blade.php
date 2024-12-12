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
<div class="container">
    <h3 class="text-center mt-5">Buat Artikel</h3>
    <form action="{{ route('head_staff.store') }}" method="POST" class="form mt-3 p-4 shadow-lg" style="background-color: #F4F6FF; border-radius: 25px;" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label" for="description">Deskripsi</label>
                @error('description')
                <small class="text-danger">{{ $message }}</small>
                @enderror
                <textarea class="form-control" name="description" id="description" cols="20" rows="5"></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label" for="image">Unggah Foto</label>
                @error('image')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <input class="form-control" type="file" name="image" id="image">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="type">Jenis Artikel</label>
                @error('type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <select class="form-select" name="type" id="type">
                    <option value="" selected disabled hidden>Pilih Jenis</option>
                    <option value="KEJAHATAN">Kejahatan</option>
                    <option value="PEMBANGUNAN">Pembangunan</option>
                    <option value="SOSIAL">Sosial</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="province">Provinsi</label>
                @error('province')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <select class="form-select" name="province" id="province">
                    <option value="" disabled selected hidden>Pilih Provinsi</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="regency">Kota/Kabupaten</label>
                @error('regency')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <select class="form-select" name="regency" id="regency" disabled>
                    <option value="" disabled selected hidden>Pilih Kabupaten</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="subdistrict">Kecamatan</label>
                @error('subdistrict')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <select class="form-select" name="subdistrict" id="subdistrict" disabled>
                    <option value="" disabled selected hidden>Pilih Kecamatan</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="village">Desa</label>
                @error('village')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <select class="form-select" name="village" id="village" disabled>
                    <option value="" disabled selected hidden>Pilih Desa</option>
                </select>
            </div>
        </div>
        <div class="d-block text-center">
            <button type="submit" class="btn btn-warning mt-4">Kirim</button>
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
                    $('#province').append('<option value="' + province.id + '">' + province.name + '</option>');
                });
            },
            error: function() {
                alert("Gagal memuat data provinsi!");
            }
        });

        // Enable regency when province is selected
        $('#province').on('change', function() {
            let provinceId = $(this).val();
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
                            $('#regency').append('<option value="' + regency.id + '">' + regency.name + '</option>');
                        });
                    },
                    error: function() {
                        alert("Gagal memuat data kabupaten!");
                    }
                });
            }
        });

        // Enable subdistrict when regency is selected
        $('#regency').on('change', function() {
            let regencyId = $(this).val();
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
                            $('#subdistrict').append('<option value="' + subdistrict.id + '">' + subdistrict.name + '</option>');
                        });
                    },
                    error: function() {
                        alert("Gagal memuat data kecamatan!");
                    }
                });
            }
        });

        // Enable village when subdistrict is selected
        $('#subdistrict').on('change', function() {
            let subdistrictId = $(this).val();
            if (subdistrictId) {
                $('#village').prop('disabled', false).html('<option value="" disabled selected hidden>Loading...</option>');
                $.ajax({
                    method: "GET",
                    url: `https://www.emsifa.com/api-wilayah-indonesia/api/villages/${subdistrictId}.json`,
                    dataType: "json",
                    success: function(response) {
                        $('#village').html('<option value="" disabled selected hidden>Pilih Desa</option>');
                        response.forEach(function(village) {
                            $('#village').append('<option value="' + village.id + '">' + village.name + '</option>');
                        });
                    },
                    error: function() {
                        alert("Gagal memuat data desa!");
                    }
                });
            }
        });
    });
</script>
@endpush
