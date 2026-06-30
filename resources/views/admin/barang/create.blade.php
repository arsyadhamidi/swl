@extends('dashboard.layout.master')
@section('title', 'Data Barang | SWL Collection')
@section('menuDataBarang', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-barang.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
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
                                               value="{{ old('nm_barang') }}"
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
                                        <label>Kategori Barang</label>
                                        <select name="kategori_id"
                                                class="form-control @error('kategori_id') is-invalid @enderror"
                                                id="selectedKategori"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Kategori</option>
                                            @foreach ($kategoris as $data)
                                                <option value="{{ $data->id ?? '' }}"
                                                        {{ old('kategori_id') == $data->id ? 'selected' : '' }}>{{ $data->nm_kategori ?? '-' }}</option>
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
                                                  placeholder="Masukan keterangan" id="ketBarangText">{{ old('ket_barang') }}</textarea>
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
                                <div class="col-lg-12">
                                    <hr>
                                    <h5>Variasi Barang</h5>

                                    <div id="variasi-container">
                                        <div class="row variasi-item mb-2">
                                            <div class="col-md-2">
                                                <input type="text"
                                                       name="ukuran[]"
                                                       class="form-control"
                                                       placeholder="Ukuran">
                                            </div>

                                            <div class="col-md-2">
                                                <input type="text"
                                                       name="warna[]"
                                                       class="form-control"
                                                       placeholder="Warna">
                                            </div>

                                            <div class="col-md-3">
                                                <input type="number"
                                                       name="harga[]"
                                                       class="form-control"
                                                       placeholder="Harga">
                                            </div>

                                            <div class="col-md-3">
                                                <input type="number"
                                                       name="stok[]"
                                                       class="form-control"
                                                       placeholder="Stok">
                                            </div>

                                            <div class="col-md-2">
                                                <button type="button"
                                                        class="btn btn-danger remove-variant">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button"
                                            id="add-variant"
                                            class="btn btn-info mt-2">
                                        <i class="fas fa-plus"></i>
                                        Tambah Variasi
                                    </button>
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
        $(document).ready(function() {

            CKEDITOR.replace('ketBarangText');

            $('#selectedKategori').select2({
                theme: 'bootstrap4'
            });

            $('#add-variant').click(function() {

                let html = `
        <div class="row variasi-item mb-2">

            <div class="col-md-2">
                <input type="text"
                    name="ukuran[]"
                    class="form-control"
                    placeholder="Ukuran">
            </div>

            <div class="col-md-2">
                <input type="text"
                    name="warna[]"
                    class="form-control"
                    placeholder="Warna">
            </div>

            <div class="col-md-3">
                <input type="number"
                    name="harga[]"
                    class="form-control"
                    placeholder="Harga">
            </div>

            <div class="col-md-3">
                <input type="number"
                    name="stok[]"
                    class="form-control"
                    placeholder="Stok">
            </div>

            <div class="col-md-2">
                <button type="button"
                        class="btn btn-danger remove-variant">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

        </div>
        `;

                $('#variasi-container').append(html);
            });

            $(document).on('click', '.remove-variant', function() {
                $(this).closest('.variasi-item').remove();
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            @if (Session::has('success'))
                toastr.success("{{ Session::get('success') }}");
            @endif

            @if (Session::has('error'))
                toastr.error("{{ Session::get('error') }}");
            @endif
        });
    </script>
@endpush
