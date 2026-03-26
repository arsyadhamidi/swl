<form action="{{ route('setting.updategambar') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="foto_profile"
                                   class="custom-file-input"
                                   id="exampleInputFile">
                            <label class="custom-file-label"
                                   for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
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
