@extends('layouts.hasLogin')

@section('content')
<div id="app">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">New Transaction</h1>
    </div>

    <!-- Content Row -->
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow mb-4">
                <form action="{{ url('transaction') }}" method="POST">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">New Transaction</h6>
                        <button type="submit" class="btn btn-outline-warning btn-sm">Submit</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Barcode Scanner</label>
                                <br>
                                <div id="barcode" style="width: 100%"></div>
                            </div>
                            <div class="col-md-6">
                                <label>Total Price</label>
                                <br>
                                <span id="total" class="h2">Rp. 0,-</span>
                                <hr>
                                @csrf
                                <input type="hidden" name="cashier_id" value="{{ Auth::user()->id }}">
                                <div class="form-group">
                                    <label>Payment Via</label>
                                    <select name="payment_via" id="payment_via" class="form-control"
                                        v-model="payment_via">
                                        <option value="cash">Cash</option>
                                        <option value="credit card">Credit Card</option>
                                    </select>
                                </div>
                                <input type="hidden" name="cart" :value="datasValue()">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Product Cart</h6>
                    <button type="button" class="btn btn-outline-info btn-sm" @click="showProduct">+ Add Product manually</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in datas">
                                    <th>@{{index + 1}}</th>
                                    <td><img v-bind:src="productDataById(product.product_id).img" alt="Product Image" class="img-fluid img-thumbnail" 
                                        width="15px" height="15px"> @{{productDataById(product.product_id).name}}</td>
                                    <td>Rp @{{productDataById(product.product_id).price}}</td>
                                    <td>@{{product.qty}}</td>
                                    <td>Rp @{{productDataById(product.product_id).price * product.qty}}</td>
                                    <td><button @click="removeProduct(index)" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                <form v-on:submit.prevent="addProduct()">
                    <div class="alert alert-danger" v-if="alert != ''">@{{alert}}</div>
                    <div class="modal-body">
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
                                :max="productDataById(selected).qty" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
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
<script src="{{ asset('assets/vendor/vue/vue.global.min.js') }}"></script>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    // https://github.com/mebjas/html5-qrcode
    function onScanSuccess(decodedText, decodedResult) {
        scanned = decodedResult.decodedText;
        console.log(scanned);
        products = controller.products;
        for (let index = 0; index < products.length; index++) {
            const product = products[index];
            if (product.barcode == scanned) {
                controller.alert = "";
                controller.selected = product.id;
                $("#qty").val(1);
                $("#modal").modal();
                scanner.pause(true);
                setTimeout(() => {
                    scanner.resume();
                }, 5000);
            }
        }
    }
    let config = { 
        fps: 10,
        qrbox : { width:250, height: 50 },
        aspectRatio: 1.777778,
        supportedScanTypes: [
            Html5QrcodeScanType.SCAN_TYPE_CAMERA
        ],
     }
    var scanner = new Html5QrcodeScanner("barcode", config);
    scanner.render(onScanSuccess);
</script>
<script>
    const {
        createApp
    } = Vue

    var controller = createApp({
        data() {
            return {
                datas: [],
                products: [],
                product: {},
                search: "",
                payment_via: "cash",
                alert: "",
                selected: 0,
                productUrl: "{{ url('api/product') }}"
            }
        },
        mounted: function () {
            this.loadProducts();
        },
        methods: {
            loadProducts() {
                const _this = this;
                $.get(_this.productUrl, function (data) {
                    _this.products = data;
                });
            },
            updateTotal() {
                const _this = this;
                if (_this.datas != []) {
                    var total = 0;
                    for (const key in _this.datas) {
                        if (Object.hasOwnProperty.call(_this.datas, key)) {
                            const element = _this.datas[key];
                            total += _this.productDataById(element.product_id).price * element.qty;
                        }
                    }
                    $("#total").text("Rp. " + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ",-");
                } else {
                    $("#total").text("Rp. 0,-");
                }
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
            showProduct() {
                $("#modal-product").modal();
            },
            selectProduct(row) {
                this.alert = "";
                this.selected = this.productsSearch[row].id;
                $("#modal-product").modal("hide");
                $("#qty").val(1);
                $("#modal").modal();
            },
            addProduct() {
                data = Object.fromEntries(new FormData(window.event.target));
                if (data.product_id == "0") {
                    this.alert = "Please select product";
                } else if (this.datas.some(e => e.product_id === data.product_id)) {
                    this.alert = "Product already selected, Please select another product";
                } else if (this.productDataById(data.product_id).qty == 0) {
                    this.alert = "This stock product is empty, Please select another product";
                } else {
                    $("#modal").modal("hide");
                    this.datas.push(data);
                    this.updateTotal();
                }
            },
            removeProduct(index) {
                this.datas.splice(index, 1);
                this.updateTotal();
            },
            datasValue() {
                return JSON.stringify(this.datas);
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
