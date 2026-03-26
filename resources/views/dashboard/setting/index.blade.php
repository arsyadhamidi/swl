@extends('dashboard.layout.master')
@section('title', 'Setting | SWL Collection')

@section('content')
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    Biodata
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg text-center">
                            <div class="mb-4">
                                @if (Auth()->user()->foto_profile)
                                    <img src="{{ asset('storage/' . Auth()->user()->foto_profile) }}"
                                         class="img-fluid rounded-circle"
                                         style="object-fit: cover; width:150px; height:150px">
                                @else
                                    <img src="{{ asset('images/foto-profile.png') }}"
                                         class="img-fluid rounded-circle"
                                         width="150">
                                @endif
                            </div>
                            <div class="mb-3">
                                <h4>{{ Auth()->user()->name ?? '-' }}</h4>
                            </div>
                            <div class="mb-3">
                                <span class="text-muted">
                                    @if (Auth::user()->level_id == '1')
                                        Admin
                                    @else
                                        Pelanggan
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg">
                            <div class="mb-3">
                                <label><strong>Nama Lengkap</strong></label>
                                <p><i>{{ Auth()->user()->name ?? '-' }}</i></p>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label><strong>Alamat Email</strong></label>
                                <p><i>{{ Auth()->user()->email ?? '-' }}</i></p>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label><strong>Status</strong></label>
                                <p>
                                    <i>
                                        @if (Auth::user()->level_id == '1')
                                            Admin
                                        @else
                                            Pelanggan
                                        @endif
                                    </i>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label><strong>No. Telepon</strong></label>
                                <p><i>{{ $users->telp ?? '-' }}</i></p>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item"><a class="nav-link active"
                               href="#tab_1"
                               data-toggle="tab">Perbaharui Profil</a></li>
                        <li class="nav-item"><a class="nav-link"
                               href="#tab_2"
                               data-toggle="tab">Perbaharui Email</a></li>
                        <li class="nav-item"><a class="nav-link"
                               href="#tab_3"
                               data-toggle="tab">Perbaharui Kata Sandi</a></li>
                        <li class="nav-item"><a class="nav-link"
                               href="#tab_4"
                               data-toggle="tab">Perbaharui Gambar</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active"
                             id="tab_1">
                            @include('dashboard.setting.profil')
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane"
                             id="tab_2">
                            @include('dashboard.setting.email')
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane"
                             id="tab_3">
                            @include('dashboard.setting.password')
                        </div>
                        <!-- /.tab-pane -->

                        <!-- /.tab-pane -->
                        <div class="tab-pane"
                             id="tab_4">
                            @include('dashboard.setting.foto')
                        </div>
                        <!-- /.tab-pane -->
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
                                <form action="{{ route('setting.hapusgambar') }}"
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
        </div>
    </div>
@endsection
@push('custom-script')
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
