@extends('layouts.hasLogin')

@section('css')
<link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('content')
<div id="app">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Transaction</h1>
    </div>

    <!-- Content Row -->
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow mb-4">
                <form method="POST">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Transaction</h6>
                        <div>
                            <button type="button" class="btn btn-outline-danger btn-sm mx-1" @click="deleteAll">Delete</button>
                            <button type="submit" class="btn btn-outline-warning btn-sm mx-1">Submit</button>
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="cashier_id" :value="datas.cashier">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Total</label>
                                    <div id="total" class="h2"></div>
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
                        </div>
                        <input type="hidden" name="detail" :value="datas.card">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Product Cart</h6>
                    <button type="button" class="btn btn-outline-info btn-sm" @click="showAdd">+ Add Product</button>
                </div>
                <div class="card-body">
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
                        <div class="col col-md-11">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(product, index) in productsSearch">
                                            <th scope="row">@{{index + 1}}</th>
                                            <td><img v-bind:src="product.img" alt="Product Image"
                                                class="img-fluid img-thumbnail" height="25px" loading="lazy"> @{{product.name}}</td>
                                            <td>Rp @{{product.price}}</td>
                                            <td>@{{product.qty}}</td>
                                            <td><button @click="selectProduct(index)" class="btn btn-info btn-circle btn-sm"><i class="fas fa-plus"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
<script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/vue/vue.global.min.js') }}"></script>
<script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
<script>
    $("[data-toggle='popover']").popover();
</script>
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
            return " <button onclick='controller.editCart("+meta.row+")' class='btn btn-info btn-circle btn-sm' data-container='body' data-toggle='popover' data-trigger='hover' data-placement='bottom' data-content='Edit'><i class='fas fa-edit'></i></button>"
            + " <button onclick='controller.deleteCart("+meta.row+")' class='btn btn-danger btn-circle btn-sm' data-container='body' data-toggle='popover' data-trigger='hover' data-placement='bottom' data-content='Delete'><i class='fas fa-trash'></i></button>"
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
        },
        computed: {
            productsSearch() {
                return this.products.filter(product => {
                    return product.name.toLowerCase().includes(this.search.toLowerCase());
                });
            }
        }
    }).mount('#app')

</script>
@endsection