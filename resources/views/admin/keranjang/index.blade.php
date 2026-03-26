@extends('dashboard.layout.master')
@section('title', 'Data Keranjang | SWL Collection')
@section('menuDataKeranjang', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <div class="card">
                    <div class="card-header">
                        <b>Filter Data Keranjang</b>
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
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Tanggal</th>
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
         id="modalDetail"
         tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Detail Keranjang</h5>
                    <button type="button"
                            class="btn btn-outline-primary"
                            data-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="modal-body">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>

                        <tbody id="detailKeranjang">
                        </tbody>

                    </table>

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
                    url: "{{ route('admin-keranjang.index') }}",
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
                            `{{ route('admin-keranjang.generatepdf') }}` + "?start_date=" + startDate + "&end_date=" + endDate
                        );
                        $('#generateexcel').attr(
                            'href',
                            `{{ route('admin-keranjang.generateexcel') }}` + "?start_date=" + startDate + "&end_date=" + endDate
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
                        data: "telp",
                        name: "telp",
                        defaultContent: "-",
                    },
                    {
                        data: "tanggal",
                        name: "tanggal",
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

            $(document).on("click", ".btn-detail", function() {

                let id = $(this).data("id");

                $.ajax({
                    url: "/admin-keranjang/keranjangdetail/" + id,
                    type: "GET",
                    success: function(res) {

                        let html = "";
                        let no = 1;

                        res.forEach(function(item) {

                            html += `
                    <tr>
                        <td>${no++}</td>
                        <td>${item.nm_barang}</td>
                        <td>${item.jumlah}</td>
                        <td>Rp ${Number(item.harga).toLocaleString('id-ID')}</td>
                        <td>Rp ${Number(item.subtotal).toLocaleString('id-ID')}</td>
                    </tr>
                `;
                        });

                        $("#detailKeranjang").html(html);
                        $("#modalDetail").modal("show");
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
