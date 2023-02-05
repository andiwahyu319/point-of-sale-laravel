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
            <h1>Edit Transaction</h1>
        </div>
    </div>
</div>
<div id="app" class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card mb-3">
                <form method="POST">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6 card-title"><b>Transaction</b></div>
                            <div class="col-6">
                                <div class="d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-outline-danger" @click="deleteAll">Delete</button>
                                    <button type="submit" class="btn btn-outline-warning">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="cashier_id" :value="datas.cashier">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Buyer Name</label>
                                    <input type="text" name="buyer" class="form-control" id="buyer"
                                        placeholder="Enter Buyer Name" :value="datas.buyer" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Total</label>
                                    <div id="total" class="form-control"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Via</label>
                                    <select name="payment_via" id="payment_via" class="form-control" v-model="datas.payment_via">
                                        <option value="cash">Cash</option>
                                        <option value="credit card">Credit Card</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" v-if="datas.payment_via == 'credit card'">
                                    <label>Credit Card</label>
                                    <input type="text" name="card" class="form-control" id="card"
                                        placeholder="Enter Credit Card" :value="datas.card" required>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="detail" :value="datas.card">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Product Cart</div>
                <div class="card-body">
                    <button type="button" class="btn btn-outline-info btn-block mb-3" @click="showAdd">+ Add Product</button>
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th style="width: 10px">Qty</th>
                                <th>Price x Qty</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form v-on:submit.prevent="submitProduct()">
                    <div class="alert alert-danger" v-if="alert != ''">@{{alert}}</div>
                    <div class="modal-body">
                        <input type="hidden" name="_method" value="PUT" v-if="edit">
                        <input type="hidden" name="transaction_id" id="transaction_id" :value="datas.id">
                        <div class="form-group">
                            <label>Product</label>
                            <div class="card mb-2" @click="showProduct()">
                                <div class="card-body p-2">
                                    <div class="row">
                                        <div class="col-4">
                                            <img class="img-fluid img-thumbnail"
                                                v-bind:src="productDataById(selected).img" alt="Product image">
                                        </div>
                                        <div class="col-8">
                                            <span
                                                class="card-title text-center text-bold">@{{productDataById(selected).name}}</span>
                                            <p class="card-text text-sm">
                                                Price: Rp @{{productDataById(selected).price}}<br>
                                                Qty: @{{productDataById(selected).qty}}<br>
                                                <span class="text-primary">Click For Select Other</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="product_id" :value="selected">
                        </div>
                        <div class="form-group">
                            <label>Qty</label><span class="text-muted"><small> (1 -
                                    @{{productDataById(selected).qty}})</small></span>
                            <input type="number" name="qty" id="qty" class="form-control" min="1"
                                :max="productDataById(selected).qty" :value="selected_cart.qty" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-info btn-sm">Add Product</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-product" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">All Products</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="Search Product" v-model="search">
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="products-list product-list-in-modal pl-2 pr-2 col-md-3" v-for="(product, index) in products">
                            <div class="item mb-3" @click="selectProduct(index)">
                                <div class="product-img">
                                    <img v-bind:src="product.img" alt="Product Image"
                                        class="img-size-50" loading="lazy">
                                </div>
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">
                                        @{{product.name}}
                                    </a>
                                    <span class="product-description">
                                        Price: Rp @{{product.price}}<br>
                                        Qty: @{{product.qty}}<br>
                                        <span class="text-warning">Click For Select</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
        {data: "name", orderable: true},
        {data: "price", render: function ( data, type, row ) {
            return "Rp. " + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ",-"
        }, orderable: true},
        {data: "qty", orderable: false},
        {data: "total", render: function ( data, type, row ) {
            return "Rp. " + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ",-"
        }, orderable: true},
        {render: function (index, row, data, meta) {
            return "<div class='btn-group-vertical'>"
            + "<button class='btn btn-outline-warning btn-xs' onclick='controller.editCart(" + meta.row + ")'>Edit</button>"
            + "<button class='btn btn-outline-danger btn-xs' onclick='controller.deleteCart(" + meta.row + ")'>Delete</button>"
            + "</div>"
        }, orderable: false}
    ];

    const {
        createApp
    } = Vue
    
    var controller = createApp({
        data() {
            return {
                datas: {},
                products: [],
                selected_cart: {},
                edit: false,
                search: "",
                payment_via: "cash",
                alert: "",
                selected: 0,
                actionUrl: "{{ url('transaction') }}",
                apiUrl: "{{ url('api/transaction'. '/' . $transaction->id) }}",
                editUrl:""
            }
        },
        mounted: function () {
            this.loadData();
        },
        methods: {
            loadData() {
                const _this = this;
                _this.table = $("#datatable").DataTable({
                    ajax: {
                        url: _this.apiUrl,
                        dataSrc: "cart",
                    },
                    columns: columns
                }).on("xhr", function () {
                    _this.datas = _this.table.ajax.json();
                    $("#total").text("Rp " + _this.datas.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ",-");
                });
                $.get("{{ url('/api/product') }}", function (data) {
                    _this.products = data;
                });
            },
            productDataById(id) {
                const _this = this;
                var data = {};
                for (const key in _this.products) {
                    if (Object.hasOwnProperty.call(_this.products, key)) {
                        const element = _this.products[key];
                        if (element.id == parseInt(id)) {
                            data = element;
                        }
                    }
                }
                return data;
            },
            showAdd() {
                this.selected_cart = {};
                this.edit = false;
                this.showProduct();
            },
            showProduct() {
                $("#modal-product").modal();
            },
            selectProduct(row) {
                this.alert = "";
                this.selected = this.products[row].id;
                $("#modal-product").modal("hide");
                $("#modal").modal();
            },
            submitProduct() {
                fd = new FormData(window.event.target);
                data = Object.fromEntries(fd);
                if (data.product_id == "0") {
                    this.alert = "Please select product";
                } else if (this.datas.cart.some(e => e.product_id === data.product_id)) {
                    this.alert = "Product already selected, Please select another product";
                } else if (this.productDataById(data.product_id).qty == 0) {
                    this.alert = "This stock product is empty, Please select another product";
                } else if (this.edit) {
                    actUrl = "{{ url('transaction_detail')}}" + "/" + this.selected_cart.id;
                    axios.post(actUrl, fd).then(response => {
                        this.table.ajax.reload();
                        $("#modal").modal("hide");
                    })
                } else {
                    axios.post("{{ url('transaction_detail')}}", fd).then(response => {
                        this.table.ajax.reload();
                        $("#modal").modal("hide");
                    })
                }
            },
            editCart(row) {
                this.edit = true;
                this.selected_cart = this.datas.cart[row];
                this.selected = this.datas.cart[row].product_id;
                this.alert = "";
                $("#modal").modal();
            },
            deleteCart(row) {
                actUrl = "{{ url('transaction_detail')}}" + "/" + this.datas.cart[row].id;
                if (confirm("are you sure ?")) {
                    axios.post(actUrl, {_method : "DELETE"}).then(response => {
                        this.table.ajax.reload();
                    });
                }
            },
            deleteAll() {
                actUrl = "{{ url('transaction')}}" + "/" + this.datas.id;
                if (confirm("are you sure ?")) {
                    axios.post(actUrl, {_method : "DELETE"}).then(response => {
                        window.location.href = "{{ url('transaction')}}";;
                    });
                }
            }
        }
    }).mount('#app')

</script>
@endsection