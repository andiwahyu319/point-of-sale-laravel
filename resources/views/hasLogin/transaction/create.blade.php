@extends('layouts.hasLogin')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col">
            <h1>New Transaction</h1>
        </div>
    </div>
</div>
<div id="app" class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <form action="{{ url('transaction') }}" method="POST">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                @csrf
                                <input type="hidden" name="cashier_id" value="{{ Auth::user()->id }}">
                                <div class="form-group">
                                    <label>Buyer's Name</label>
                                    <input type="text" name="buyer" class="form-control" id="buyer"
                                        placeholder="Enter Buyer's Name" required>
                                </div>
                                <div class="form-group">
                                    <label>Payment Via</label>
                                    <select name="payment_via" id="payment_via" class="form-control"
                                        v-model="payment_via">
                                        <option value="cash">Cash</option>
                                        <option value="credit card">Credit Card</option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="payment_via == 'credit card'">
                                    <label>Credit Card</label>
                                    <input type="text" name="card" class="form-control" id="card"
                                        placeholder="Enter Credit Card">
                                </div>
                                <button type="button" class="btn btn-outline-info btn-block" @click="showProduct">+ Add
                                    Product</button>
                                <input type="hidden" name="cart" :value="datasValue()">
                            </div>
                            <div class="col-md-8">
                                <label>Total Price</label>
                                <div class="callout callout-success">
                                    <span id="total" class="h2">Rp. 0,-</span>
                                </div>
                                <label>Product Cart</label>
                                <div class="border border-info" style="min-height: 2em;">
                                    <ul class="products-list product-list-in-card pl-2 pr-2">
                                        <li class="item" v-for="(product, index) in datas">
                                            <div class="product-img">
                                                <img v-bind:src="productDataById(product.product_id).img" alt="Product Image"
                                                    class="img-size-50">
                                            </div>
                                            <div class="product-info">
                                                <a href="javascript:void(0)" class="product-title">
                                                    @{{productDataById(product.product_id).name}}
                                                    <span class="badge badge-warning float-right">
                                                        Rp @{{productDataById(product.product_id).price * product.qty}}
                                                    </span>
                                                    <span class="badge badge-success float-right">
                                                        Qty: @{{product.qty}}
                                                    </span>
                                                </a>
                                                <span class="product-description">
                                                    Price: Rp @{{productDataById(product.product_id).price}} x 
                                                    @{{product.qty}} = Rp @{{productDataById(product.product_id).price * product.qty}}
                                                    <a class="float-right btn btn-danger btn-xs" @click="removeProduct(index)">Cancel</a>
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-outline-warning float-right">Submit</button>
                    </div>
                </form>
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
                        <div class="products-list product-list-in-modal pl-2 pr-2 col-md-3" v-for="(product, index) in productsSearch">
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
