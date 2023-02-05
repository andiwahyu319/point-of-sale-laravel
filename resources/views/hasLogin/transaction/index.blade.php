@extends('layouts.hasLogin')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col">
            <h1>Transaction</h1>
        </div>
    </div>
</div>
<div id="app" class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header row text-bold">
                    <div class="col">Data Transaction</div>
                    <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{url('transaction/create')}}" class="btn btn-outline-primary">Create new</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Buyer</th>
                                <th>Total Price</th>
                                <th>Payment Via</th>
                                <th>Action</th>
                                <th>Created At</th>
                                <th>Cashier</th>
                            </tr>
                        </thead>
                    </table>
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
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Buyer</b> <a class="float-right">@{{data.buyer}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Total</b> <a class="float-right">Rp @{{data.total}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Payment Via</b> <a class="float-right">@{{data.payment_via}}</a>
                            </li>
                            <li class="list-group-item" v-if="data.payment_via == 'credit card'">
                                <b>Card</b> <a class="float-right">@{{data.card}}</a>
                            </li>
                        </ul>
                        <b>Product Buy</b>
                        <div class="row">
                            <div class="col-md-6" v-for="product in data.cart">
                                <div class="card">
                                    <div class="card-header p-1">
                                        <a class="card-title text-center">@{{product.name}}</a>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="row">
                                            <div class="col-4">
                                                <img v-bind:src="product.img" alt="Product image" class="img-fluid img-thumbnail">
                                            </div>
                                            <div class="col-8">
                                                <p class="card-text text-sm">
                                                    Price: Rp @{{product.price}}<br>
                                                    Qty: @{{product.qty}}<br>
                                                    Total: Rp @{{product.total}}<br>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script>
    var columns = [
        {render: function (index, row, data, meta) { 
            return  meta.row + 1
        }, orderable: true},
        {data: "buyer", orderable: true},
        {data: "total", render: function ( data, type, row ) {
            return "Rp. " + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ",-"
        }, orderable: true},
        {data: "payment_via", orderable: false},
        {render: function (index, row, data, meta) {
            return "<button class='btn btn-outline-info btn-xs' onclick='controller.showData(" + meta.row + ")'>Show</button>"
        }, orderable: false},
        {data: "created_at", render: function ( data, type, row ) {
            return new Date(data).toLocaleString("en-US", { hour12: false })
        }, orderable: true},
        {data: "cashier", orderable: false}
    ];

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
                    columns: columns
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
