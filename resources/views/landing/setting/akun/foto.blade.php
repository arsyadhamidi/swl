<form action="{{ route('pelanggan-barang.updategambar') }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <input type="file"
                           name="foto_profile"
                           class="form-control @error('foto_profile') is-invalid @enderror">
                    @error('foto_profile')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <button type="submit"
                    class="btn btn-success"
                    onclick="return confirm('Apakah anda yakin data ini sudah benar ?')">
                <i class="fas fa-save"></i>
                Simpan Data
            </button>
        </div>
    </div>
</form>
