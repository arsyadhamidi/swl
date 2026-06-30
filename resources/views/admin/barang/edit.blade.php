@extends('dashboard.layout.master')
@section('title', 'Data Barang | SWL Collection')
@section('menuDataBarang', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-barang.update', $barang->id ?? '') }}"
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
                                               value="{{ old('nm_barang', $barang->nm_barang ?? '0') }}"
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
                                                        {{ old('kategori_id', $barang->kategori_id) == $data->id ? 'selected' : '' }}>{{ $data->nm_kategori ?? '-' }}</option>
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
                                                  placeholder="Masukan keterangan"
                                                  id="ketBarangText">{{ old('ket_barang', $barang->ket_barang ?? '-') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Foto Barang</label>

                                        @if ($barang->foto_barang)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $barang->foto_barang) }}"
                                                     width="150"
                                                     class="img-thumbnail">
                                            </div>
                                        @endif

                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file"
                                                       name="foto_barang"
                                                       class="custom-file-input"
                                                       id="exampleInputFile">

                                                <label class="custom-file-label">
                                                    Pilih Foto Baru
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">

                                    <hr>

                                    <div class="d-flex justify-content-between align-items-center mb-3">

                                        <h5 class="mb-0">
                                            Variasi Barang
                                        </h5>

                                        <button type="button"
                                                id="add-variasi"
                                                class="btn btn-success">
                                            <i class="fas fa-plus"></i>
                                            Tambah Variasi
                                        </button>

                                    </div>

                                    <div id="variasi-container">

                                        @foreach ($variasis as $index => $variasi)
                                            <div class="card border mb-3 variasi-item">

                                                <div class="card-header d-flex align-items-center">

                                                    <strong>
                                                        Variasi {{ $loop->iteration }}
                                                    </strong>

                                                    @if (!$loop->first)
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm remove-variasi ml-auto">
                                                            <i class="fas fa-trash"></i>
                                                            Hapus
                                                        </button>
                                                    @endif

                                                </div>

                                                <div class="card-body">

                                                    <input type="hidden"
                                                           name="variasi_id[]"
                                                           value="{{ $variasi->id }}">

                                                    <div class="row">

                                                        <div class="col-md-3 mb-3">

                                                            <label>Ukuran</label>

                                                            <input type="text"
                                                                   name="ukuran[]"
                                                                   class="form-control"
                                                                   value="{{ $variasi->ukuran }}">

                                                        </div>

                                                        <div class="col-md-3 mb-3">

                                                            <label>Lingkar Dada (cm)</label>

                                                            <input type="number"
                                                                   name="lingkar_dada[]"
                                                                   class="form-control"
                                                                   value="{{ $variasi->lingkar_dada }}">

                                                        </div>

                                                        <div class="col-md-3 mb-3">

                                                            <label>Panjang Baju (cm)</label>

                                                            <input type="number"
                                                                   name="panjang_baju[]"
                                                                   class="form-control"
                                                                   value="{{ $variasi->panjang_baju }}">

                                                        </div>

                                                        <div class="col-md-3 mb-3">

                                                            <label>Panjang Lengan (cm)</label>

                                                            <input type="number"
                                                                   name="panjang_lengan[]"
                                                                   class="form-control"
                                                                   value="{{ $variasi->panjang_lengan }}">

                                                        </div>

                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-4 mb-3">

                                                            <label>Warna</label>

                                                            <input type="text"
                                                                   name="warna[]"
                                                                   class="form-control"
                                                                   value="{{ $variasi->warna }}">

                                                        </div>

                                                        <div class="col-md-4 mb-3">

                                                            <label>Harga</label>

                                                            <input type="number"
                                                                   name="harga[]"
                                                                   class="form-control"
                                                                   value="{{ $variasi->harga }}">

                                                        </div>

                                                        <div class="col-md-4 mb-3">

                                                            <label>Stok</label>

                                                            <input type="number"
                                                                   name="stok[]"
                                                                   class="form-control"
                                                                   value="{{ $variasi->stok }}">

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>
                                        @endforeach

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
        CKEDITOR.replace('ketBarangText');

        $('#selectedKategori').select2({
            theme: 'bootstrap4'
        });

        $(document).on('click', '.remove-variasi', function() {

            if ($('.variasi-item').length == 1) {

                toastr.error('Minimal harus ada 1 variasi.');

                return;
            }

            $(this).closest('.variasi-item').remove();

            nomor = 0;

            $('.variasi-item').each(function() {

                nomor++;

                $(this).find('.card-header strong').text('Variasi ' + nomor);

            });

        });
    </script>
    <script>
        let nomor = {{ count($variasis) }};

        $('#add-variasi').click(function() {

            nomor++;

            let html = `
    <div class="card border mb-3 variasi-item">

        <div class="card-header d-flex align-items-center">

            <strong>Variasi ${nomor}</strong>

            <button type="button"
                    class="btn btn-danger btn-sm remove-variasi ml-auto">
                <i class="fas fa-trash"></i> Hapus
            </button>

        </div>

        <div class="card-body">

            <input type="hidden"
                   name="variasi_id[]"
                   value="">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Ukuran</label>
                    <input type="text"
                           name="ukuran[]"
                           class="form-control">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Lingkar Dada (cm)</label>
                    <input type="number"
                           name="lingkar_dada[]"
                           class="form-control">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Panjang Baju (cm)</label>
                    <input type="number"
                           name="panjang_baju[]"
                           class="form-control">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Panjang Lengan (cm)</label>
                    <input type="number"
                           name="panjang_lengan[]"
                           class="form-control">
                </div>

            </div>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label>Warna</label>
                    <input type="text"
                           name="warna[]"
                           class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Harga</label>
                    <input type="number"
                           name="harga[]"
                           class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Stok</label>
                    <input type="number"
                           name="stok[]"
                           class="form-control">
                </div>

            </div>

        </div>

    </div>`;

            $('#variasi-container').append(html);

        });
    </script>
@endpush
