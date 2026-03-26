<form action="{{ route('pelanggan-barang.updateprofil') }}"
      method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-6">
            <div class="mb-3">
                <label><b>Nama Lengkap</b></label>
                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $users->name ?? '-') }}"
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
                <label><b>Nomor Telepon</b></label>
                <input type="text"
                       name="telp"
                       class="form-control @error('telp') is-invalid @enderror"
                       value="{{ old('telp', $users->telp ?? '-') }}"
                       placeholder="Masukan nomor telepon">
                @error('telp')
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
