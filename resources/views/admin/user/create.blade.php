@extends('dashboard.layout.master')
@section('title', 'Data User | SWL Collection')
@section('menuUserRegistrasi', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-user.store') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Nama Lengkap</b></label>
                                        <input type="text"
                                               name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name') }}"
                                               placeholder="Masukan nama lengkap">
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Email</b></label>
                                        <input type="email"
                                               name="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}"
                                               placeholder="Masukan alamat email">
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Telepon</b></label>
                                        <input type="text"
                                               name="telp"
                                               class="form-control @error('telp') is-invalid @enderror"
                                               value="{{ old('telp') }}"
                                               placeholder="Masukan nomor telepon">
                                        @error('telp')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Level</b></label>
                                        <select name="level_id"
                                                class="form-control @error('level_id') is-invalid @enderror"
                                                id="selectedLevel"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Level</option>
                                            <option value="1"
                                                    {{ old('level_id') == '1' ? 'selected' : '' }}>Admin</option>
                                            <option value="2"
                                                    {{ old('level_id') == '2' ? 'selected' : '' }}>Pelanggan</option>
                                        </select>
                                        @error('level_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label><b>Alamat</b></label>
                                        <textarea name="alamat"
                                                  class="form-control @error('alamat') is-invalid @enderror"
                                                  rows="5"
                                                  placeholder="Masukan alamat"
                                                  required>{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit"
                                    class="btn btn-success">
                                <i class="fas fa-save"></i>
                                Simpan Data
                            </button>
                            <a href="{{ route('admin-user.index') }}"
                               class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('custom-script')
    <script>
        $('#selectedLevel').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush
