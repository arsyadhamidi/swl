<form action="{{ route('pelanggan-barang.updatepassword') }}"
      method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <label><b>Kata Sandi Terbaru</b></label>
                <input type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       value="{{ old('password') }}"
                       placeholder="Masukan kata sandi terbaru">
                @error('password')
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
