@extends('dashboard.layout.master')
@section('title', 'Data User | SWL Collection')
@section('menuUserRegistrasi', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <div class="card">
                    <div class="card-header">
                        Filter Data User Registrasi
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <select name="level_id"
                                        class="form-control"
                                        id="selectedLevel"
                                        style="width: 100%">
                                    <option value=""
                                            selected>Pilih Role</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Pelanggan</option>
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
                        <a href="{{ route('admin-user.create') }}"
                           class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Tambah
                        </a>
                        <a href="#"
                           class="btn btn-danger" target="_blank" id="generatepdf">
                            <i class="fas fa-download"></i>
                            Download PDF
                        </a>
                        <a href="#"
                           class="btn btn-success" target="_blank" id="generateexcel">
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
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Telepon</th>
                                    <th>Level</th>
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

            // select2 opsional
            $("#selectedLevel").select2({
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
                    url: "{{ route('admin-user.index') }}",
                    data: function(data) {
                        data.page = Math.ceil(data.start / data.length) + 1;
                        data.search = $("#myTable_filter input").val();
                        data.level_id = $("#selectedLevel").val();
                        $('#generatepdf').attr('href',
                            `{{ route('admin-user.generatepdf') }}?&level_id=${data.level_id}`
                        );
                        $('#generateexcel').attr('href',
                            `{{ route('admin-user.generateexcel') }}?&level_id=${data.level_id}`
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
                        data: "name",
                        name: "name",
                        defaultContent: "-",
                    },
                    {
                        data: "email",
                        name: "email",
                        defaultContent: "-",
                    },
                    {
                        data: "duplicate",
                        name: "duplicate",
                        defaultContent: "-",
                    },
                    {
                        data: "telp",
                        name: "telp",
                        defaultContent: "-",
                    },
                    {
                        data: "level_id",
                        name: "level_id",
                        defaultContent: "-",
                        render: function(data, type, row, meta) {
                            if (data == 1) {
                                return 'Admin';
                            } else {
                                return 'Pelanggan';
                            }
                        }
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

            $("#selectedLevel").on("change", function() {
                myTable.ajax.reload();
            });

            // === Event Listener untuk Tombol Hapus User ===
            $("#myTable").on("click", ".btn-delete", function() {
                const resultid = $(this).data("resultid");

                if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    return;
                }

                $.ajax({
                    url: "/admin-user/destroy/" + resultid, // route('admin-users.destroy', id)
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
