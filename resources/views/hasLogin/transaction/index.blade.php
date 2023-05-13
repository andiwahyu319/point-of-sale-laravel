@extends('layouts.hasLogin')

@section("css")
<link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div id="app">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transaction</h1>
        <a href="{{url('transaction/create')}}" class="btn btn-secondary">Create new</a>
    </div>

    <!-- Content Row -->
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Cashier</th>
                                    <th>Total Price</th>
                                    <th>Payment Via</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-show" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Transaction Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body card p-0">
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">
                                <b>Cashier</b> <a class="float-right">@{{data.cashier}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Total</b> <a class="float-right">Rp @{{data.total}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Payment Via</b> <a class="float-right">@{{data.payment_via}}</a>
                            </li>
                        </ul>
                        <b>Product Buy</b>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in data.cart">
                                        <td><img v-bind:src="product.img" class="img img-fluid img-thumbnail" alt="Product image" width="25px" height="25px"> @{{product.name}}</td>
                                        <td>Rp @{{product.price}}</td>
                                        <td>@{{product.qty}}</td>
                                        <td>Rp @{{product.total}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <a :href="editUrl" class="btn btn-outline-warning">Edit / Delete</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
@endsection

@section("js")
<script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/vue/vue.global.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script>
    var columns = [
        {render: function (index, row, data, meta) { 
            return  meta.row + 1
        }, orderable: true},
        {data: "cashier", orderable: false},
        {data: "total", render: function ( data, type, row ) {
            return "Rp. " + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ",-"
        }, orderable: true},
        {data: "payment_via", orderable: false},
        {data: "created_at", render: function ( data, type, row ) {
            return new Date(data).toLocaleString("en-US", { hour12: false })
        }, orderable: true},
        {render: function (index, row, data, meta) {
            return "<button onclick='controller.showData("+meta.row+")' class='btn btn-primary btn-circle btn-sm' data-container='body' data-toggle='popover' data-trigger='hover' data-placement='bottom' data-content='Show'><i class='fas fa-eye'></i></button>"
        }, orderable: false},
    ];
    var buttons = [
            {
                extend: "copyHtml5",
                text: "Copy",
                header: "Transaction Data",
                messageTop: "Transaction Data",
                title: "Transaction Data"
            },
            {
                extend: "excelHtml5",
                text: "Excel",
                filename: "Transaction Data",
                header: "Transaction Data",
                messageTop: "Transaction Data"
            },
            {
                extend: "pdfHtml5",
                text: "PDF",
                filename: "Transaction Data",
                orientation: "portrait",
                pageSize: "A4",
                title: "Transaction Data"
            },
        ]

    const {
        createApp
    } = Vue
    
    var controller = createApp({
        data() {
            return {
                datas: [],
                data: {},
                edit: false,
                actionUrl: "{{ url('transaction') }}",
                apiUrl: "{{ url('api/transaction') }}",
                editUrl:""
            }
        },
        mounted: function () {
            this.datatable();
        },
        methods: {
            datatable() {
                const _this = this;
                _this.table = $("#datatable").DataTable({
                    ajax: {
                        url: this.apiUrl,
                        dataSrc: "",
                    },
                    columns: columns,
                    dom: "Bfrtip",
                    buttons: buttons
                }).on("xhr", function () {
                    _this.datas = _this.table.ajax.json();
                });
            },
            showData(row) {
                const _this = this;
                _this.data = _this.datas[row];
                _this.editUrl = _this.actionUrl + "/" + _this.data.id + "/edit";
                data = _this.data;
                $("#modal-show").modal();
            }
        }
    }).mount('#app')

</script>
@endsection
