@extends('dashboard.layout.master')
@section('title', 'Data Barang | SWL Collection')
@section('menuDataBarang', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-barang.update', $barangs->id ?? '') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Nama Barang</label>
                                        <input type="text"
                                               name="nm_barang"
                                               class="form-control @error('nm_barang') is-invalid @enderror"
                                               value="{{ old('nm_barang', $barangs->nm_barang ?? '0') }}"
                                               placeholder="Masukan nama barang">
                                        @error('nm_barang')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Harga Barang</label>
                                        <input type="number"
                                               name="harga"
                                               class="form-control @error('harga') is-invalid @enderror"
                                               value="{{ old('harga', $barangs->harga ?? '0') }}"
                                               placeholder="Masukan harga barang">
                                        @error('harga')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Stok Barang</label>
                                        <input type="number"
                                               name="stok"
                                               class="form-control @error('stok') is-invalid @enderror"
                                               value="{{ old('stok', $barangs->stok ?? '0') }}"
                                               placeholder="Masukan stok barang">
                                        @error('stok')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Kategori Barang</label>
                                        <select name="kategori_id"
                                                class="form-control @error('kategori_id') is-invalid @enderror"
                                                id="selectedKategori"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Kategori</option>
                                            @foreach ($kategoris as $data)
                                                <option value="{{ $data->id ?? '' }}"
                                                        {{ old('kategori_id', $barangs->kategori_id) == $data->id ? 'selected' : '' }}>{{ $data->nm_kategori ?? '-' }}</option>
                                            @endforeach
                                        </select>
                                        @error('kategori_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Deskripsi</label>
                                        <textarea name="ket_barang"
                                                  class="form-control @error('ket_barang') is-invalid @enderror"
                                                  rows="5"
                                                  placeholder="Masukan keterangan">{{ old('ket_barang', $barangs->ket_barang ?? '-') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Foto Barang</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file"
                                                       name="foto_barang"
                                                       class="custom-file-input"
                                                       id="exampleInputFile">
                                                <label class="custom-file-label"
                                                       for="exampleInputFile">Choose file</label>
                                            </div>
                                        </div>
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
                            <a href="{{ route('admin-barang.index') }}"
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
        $('#selectedKategori').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush
