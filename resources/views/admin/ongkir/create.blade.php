@extends('dashboard.layout.master')
@section('title', 'Data Ongkir | SWL Collection')
@section('menuDataOngkir', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-ongkir.store') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Kota</b></label>
                                        <input type="text"
                                               name="kota"
                                               class="form-control @error('kota') is-invalid @enderror"
                                               value="{{ old('kota') }}"
                                               placeholder="Masukan kota">
                                        @error('kota')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Biaya</b></label>
                                        <input type="number"
                                               name="biaya"
                                               class="form-control @error('biaya') is-invalid @enderror"
                                               value="{{ old('biaya') }}"
                                               placeholder="Masukan biaya">
                                        @error('biaya')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
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
                            <a href="{{ route('admin-ongkir.index') }}"
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
