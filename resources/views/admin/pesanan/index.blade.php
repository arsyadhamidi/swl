@extends('dashboard.layout.master')
@section('title', 'Data Pesanan | SWL Collection')
@section('menuDataPesanan', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <div class="card">
                    <div class="card-header">
                        <b>Filter Data Pesanan</b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <input type="text"
                                           class="form-control"
                                           id="searchByDate">
                                </div>
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
                                    <th>Name</th>
                                    <th>Tanggal</th>
                                    <th>Pengantaran</th>
                                    <th>Tot.Harga</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Status</th>
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

    <div class="modal fade"
         id="modalStatus"
         tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formUpdateStatus">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Status Pesanan</h5>
                        <button type="button"
                                class="btn btn-outline-primary"
                                data-dismiss="modal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden"
                               id="pesanan_id"
                               name="id">

                        <div class="mb-3">
                            <label>Status Pesanan</label>
                            <select class="form-control select2"
                                    name="status"
                                    id="status"
                                    style="width:100%">
                                <option value="">-- Pilih Status --</option>
                                <option value="Pending">Pending</option>
                                <option value="Diproses">Proses</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Dibatalkan">Dibatalkan</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">Batal</button>

                        <button type="submit"
                                class="btn btn-success">
                            Update Status
                        </button>
                    </div>

                </form>
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

            var startDate = moment().startOf('month');
            var endOfDate = moment().endOf('month');

            $('#searchByDate').daterangepicker({
                startDate: startDate,
                endDate: endOfDate,
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: 'Terapkan',
                    cancelLabel: 'Batal',
                    customRangeLabel: 'Pilih Rentang Tanggal',
                    daysOfWeek: [
                        'Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'
                    ],
                    monthNames: [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ],
                    firstDay: 1
                },
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start_date, end_date) {
                myTable.draw();
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
                    url: "{{ route('admin-pesanan.index') }}",
                    data: function(data) {
                        data.page = Math.ceil(data.start / data.length) + 1;
                        data.search = $("#myTable_filter input").val();
                        var startDate = $('#searchByDate').data('daterangepicker').startDate.format(
                            'YYYY-MM-DD');
                        var endDate = $('#searchByDate').data('daterangepicker').endDate.format(
                            'YYYY-MM-DD');

                        data.start_date = startDate;
                        data.end_date = endDate;
                        $('#generatepdf').attr(
                            'href',
                            `{{ route('admin-pesanan.generatepdf') }}` + "?start_date=" + startDate + "&end_date=" + endDate
                        );
                        $('#generateexcel').attr(
                            'href',
                            `{{ route('admin-pesanan.generateexcel') }}` + "?start_date=" + startDate + "&end_date=" + endDate
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
                        data: "tgl_pesanan",
                        name: "tgl_pesanan",
                        defaultContent: "-",
                    },
                    {
                        data: "kota",
                        name: "kota",
                        defaultContent: "-",
                    },
                    {
                        data: "tot_harga",
                        name: "tot_harga",
                        defaultContent: "-",
                        render: function(data, type, row) {

                            if (!data) return "-";

                            return "Rp " + parseInt(data).toLocaleString("id-ID");

                        }
                    },
                    {
                        data: "alamat_pengiriman",
                        name: "alamat_pengiriman",
                        defaultContent: "-",
                    },
                    {
                        data: "telp",
                        name: "telp",
                        defaultContent: "-",
                    },
                    {
                        data: "status",
                        name: "status",
                        defaultContent: "-",
                        render: function(data, type, row) {

                            if (!data) return "-";

                            let badge = "";

                            if (data === "Pending") {
                                badge = '<span class="badge bg-warning">Pending</span>';
                            } else if (data === "Diproses") {
                                badge = '<span class="badge bg-primary">Proses</span>';
                            } else if (data === "Selesai") {
                                badge = '<span class="badge bg-success">Selesai</span>';
                            } else if (data === "Dibatalkan") {
                                badge = '<span class="badge bg-danger">Dibatalkan</span>';
                            } else {
                                badge = '<span class="badge bg-secondary">' + data + '</span>';
                            }

                            return badge;
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

            // aktifkan select2
            $('.select2').select2({
                dropdownParent: $('#modalStatus'),
                theme: "bootstrap4",
            });


            // buka modal edit
            $(document).on('click', '.btn-detail', function() {

                let id = $(this).data('id');

                $('#pesanan_id').val(id);

                $('#modalStatus').modal('show');

            });

            // submit update status
            $("#formUpdateStatus").submit(function(e) {

                e.preventDefault();

                let id = $("#pesanan_id").val();
                let status = $("#status").val();

                $.ajax({
                    url: "/admin-pesanan/update/" + id,
                    type: "POST",
                    data: {
                        status: status,
                    },
                    success: function(response) {

                        $('#modalStatus').modal('hide');

                        toastr.success("Status berhasil diupdate");

                        $('#myTable').DataTable().ajax.reload(null, false);

                    },
                    error: function() {

                        toastr.error("Gagal update status");

                    }
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
