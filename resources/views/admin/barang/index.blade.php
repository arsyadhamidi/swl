@extends('dashboard.layout.master')
@section('title', 'Data Barang | SWL Collection')
@section('menuDataBarang', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <div class="card">
                    <div class="card-header">
                        Filter Data Barang
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <select name="kategori_id"
                                        class="form-control"
                                        id="selectedKategori"
                                        style="width: 100%">
                                    <option value=""
                                            selected>Pilih Kategori</option>
                                    @foreach ($kategoris as $data)
                                        <option value="{{ $data->id ?? '' }}">{{ $data->nm_kategori ?? '-' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('admin-barang.create') }}"
                           class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Tambah
                        </a>
                        <a href="#"
                           class="btn btn-danger"
                           target="_blank"
                           id="generatepdf">
                            <i class="fas fa-download"></i>
                            Download PDF
                        </a>
                        <a href="#"
                           class="btn btn-success"
                           target="_blank"
                           id="generateexcel">
                            <i class="fas fa-download"></i>
                            Download Excel
                        </a>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped"
                               id="myTable"
                               style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 4%">No.</th>
                                    <th>Barang</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Keterangan</th>
                                    <th>Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-script')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $("#selectedKategori").select2({
                theme: "bootstrap4"
            });

            // Tampilkan Data
            let myTable = $("#myTable").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, 250],
                    [10, 25, 50, 100, 250],
                ],
                language: {
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya",
                    },
                },
                ajax: {
                    url: "{{ route('admin-barang.index') }}",
                    data: function(data) {
                        data.page = Math.ceil(data.start / data.length) + 1;
                        data.search = $("#myTable_filter input").val();
                        data.kategori_id = $("#selectedKategori").val();
                        $('#generatepdf').attr('href',
                            `{{ route('admin-barang.generatepdf') }}?&kategori_id=${data.kategori_id}`
                        );
                        $('#generateexcel').attr('href',
                            `{{ route('admin-barang.generateexcel') }}?&kategori_id=${data.kategori_id}`
                        );
                    },
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                    },
                    {
                        data: "nm_barang",
                        name: "nm_barang",
                        defaultContent: "-",
                    },
                    {
                        data: "harga",
                        name: "harga",
                        defaultContent: "-",
                        render: function(data, type, row) {
                            if (data == null) return "-";

                            return "Rp " + new Intl.NumberFormat("id-ID").format(data);
                        }
                    },
                    {
                        data: "stok",
                        name: "stok",
                        defaultContent: "-",
                    },
                    {
                        data: "ket_barang",
                        name: "ket_barang",
                        defaultContent: "-",
                    },
                    {
                        data: "nm_kategori",
                        name: "nm_kategori",
                        defaultContent: "-",
                    },
                    {
                        data: "aksi",
                        name: "aksi",
                        orderable: false,
                        searchable: false,
                        defaultContent: "-",
                    },
                ],

                order: [
                    [1, "desc"]
                ],
            });

            $("#selectedKategori").on("change", function() {
                myTable.ajax.reload();
            });

            // === Event Listener untuk Tombol Hapus User ===
            $("#myTable").on("click", ".btn-delete", function() {
                const resultid = $(this).data("resultid");

                if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    return;
                }

                $.ajax({
                    url: "/admin-barang/destroy/" + resultid, // route('admin-users.destroy', id)
                    type: "POST", // Laravel destroy biasanya pakai method DELETE
                    data: {
                        _token: "{{ csrf_token() }}", // wajib untuk keamanan Laravel
                    },
                    success: function(res) {
                        if (res.status === "success") {
                            toastr.success(res.message || "Data berhasil dihapus!");
                            myTable.ajax.reload(null, false); // reload tabel tanpa reset pagination
                        } else {
                            toastr.warning(res.message || "Gagal menghapus data.");
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Terjadi kesalahan: " + xhr.responseText);
                    },
                });
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
