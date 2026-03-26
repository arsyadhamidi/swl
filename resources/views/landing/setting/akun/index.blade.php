@extends('landing.setting.index')
@section('menuPelangganSetting', 'active')

@section('content-pelanggan')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <ul class="nav nav-pills ml-auto p-2">
                            <li class="nav-item"><a class="nav-link active"
                                   href="#tab_1"
                                   data-bs-toggle="tab">Perbaharui Profil</a></li>
                            <li class="nav-item"><a class="nav-link"
                                   href="#tab_2"
                                   data-bs-toggle="tab">Perbaharui Email</a></li>
                            <li class="nav-item"><a class="nav-link"
                                   href="#tab_3"
                                   data-bs-toggle="tab">Perbaharui Kata Sandi</a></li>
                            <li class="nav-item"><a class="nav-link"
                                   href="#tab_4"
                                   data-bs-toggle="tab">Perbaharui Gambar</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <div class="tab-content">
                            <div class="tab-pane active"
                                 id="tab_1">
                                @include('landing.setting.akun.profil')
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane"
                                 id="tab_2">
                                @include('landing.setting.akun.email')
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane"
                                 id="tab_3">
                                @include('landing.setting.akun.password')
                            </div>
                            <!-- /.tab-pane -->

                            <!-- /.tab-pane -->
                            <div class="tab-pane"
                                 id="tab_4">
                                @include('landing.setting.akun.foto')
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (Auth()->user()->foto_profile)
        <div class="card mt-4">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg">
                        <h5>Peringatan Hapus Foto Profile ?</h5>
                        <p class="mb-3">
                            Apakah Anda yakin ingin menghapus gambar foto profil Anda? Tindakan ini tidak dapat
                            dibatalkan dan akan menghapus gambar yang saat ini ditetapkan sebagai foto profil Anda.
                        </p>
                        <form action="{{ route('pelanggan-barang.hapusgambar') }}"
                              method="POST"
                              enctype="multipart/form-data"
                              id="dataForm">
                            @csrf
                            <button type="submit"
                                    class="btn btn-danger"
                                    onclick="return confirm('Apakah anda yakin untuk menghapus data ini ?')">
                                <i class="fas fa-times"></i>
                                Hapus Gambar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
