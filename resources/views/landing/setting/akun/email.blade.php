<form action="{{ route('pelanggan-barang.updateemail') }}"
      method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <label><b>Alamat Email</b></label>
                <input type="email"
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $users->email ?? '-') }}"
                       placeholder="Masukan alamat email">
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
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
