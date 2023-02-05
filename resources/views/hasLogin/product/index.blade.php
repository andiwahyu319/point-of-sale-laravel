@extends('layouts.hasLogin')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col">
            <h1>Product</h1>
        </div>
    </div>
</div>
<div id="app" class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search Product" v-model="search">
                        <div class="input-group-append">
                            <button class="btn btn-primary" @click="addData()">or Add New</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="mb-2 mt-2">
    <div class="row justify-content-center">
        <div class="col-md-3 col-sm-6 col-xs-12 mb-2" v-for="product in products">
            <div class="card h-100" @click="showData(product)">
                <img class="card-img-top" v-bind:src="product.img" v-bind:alt="'image ' + product.name" loading="lazy">
                <div class="card-body">
                    <h5 class="card-title">@{{product.name}}</h5>
                    <p class="card-text">Rp @{{number_format(product.price)}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-show" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Product Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body card p-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <img class="img-fluid img-thumbnail" v-bind:src="data.img"
                                    v-bind:alt="'image ' + data.name">
                            </div>
                            <div class="col-8">
                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b>Name</b> <a class="float-right">@{{data.name}}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Price</b> <a class="float-right">Rp @{{data.price}}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Qty</b> <a class="float-right">@{{data.qty}}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Description</b> <a class="float-right">@{{data.description}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <div class="d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-warning me-md-2" @click="editData()">Edit</button>
                        <button type="button" class="btn btn-danger" @click="deleteData()">Delete</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="POST" v-on:submit.prevent="submitForm()">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="_method" value="PUT" v-if="edit">
                        <div class="form-group">
                            <label>Image</label><span class="text-sm text-muted" v-if="edit == true">(Optional)</span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                                <label class="custom-file-label" for="image">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" id="name" :value="data.name"
                                placeholder="Enter Name" required>
                        </div>
                        <div class="form-group">
                            <label>Qty</label>
                            <input type="number" name="qty" class="form-control" id="qty" :value="data.qty"
                                placeholder="Enter Qty" required>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" class="form-control" id="price" :value="data.price"
                                placeholder="Enter Price" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" id="description" rows="2"
                                :value="data.description" placeholder="Enter Description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save">
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
@endsection

@section("js")
<!-- bs-custom-file-input -->
<script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>
<script>
    const {
        createApp
    } = Vue

    var controller = createApp({
        data() {
            return {
                datas: [],
                data: {},
                search: "",
                edit: false,
                actionUrl: "{{ url('product') }}",
                apiUrl: "{{ url('/api/product') }}"
            }
        },
        mounted: function () {
            this.getProducts();
        },
        methods: {
            getProducts() {
                const _this = this;
                $.get(_this.apiUrl, function (data) {
                    _this.datas = data;
                });
            },
            number_format(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            },
            showData(data) {
                this.data = data;
                $("#modal-show").modal();
            },
            addData() {
                this.data = {};
                this.edit = false;
                $("#modal").modal();
            },
            editData() {
                $("#modal-show").modal("hide");
                this.edit = true;
                $('#modal').css('overflow-y', 'auto');
                $("#modal").modal();
            },
            deleteData() {
                actUrl = this.actionUrl + "/" + this.data.id;
                if (confirm("are you sure ?")) {
                    axios.post(actUrl, {
                        _method: "DELETE"
                    }).then(response => {
                        $("#modal-show").modal("hide");
                        this.getProducts();
                    });
                }
            },
            submitForm() {
                const _this = this;
                actUrl = _this.edit ? _this.actionUrl + "/" + _this.data.id : _this.actionUrl;
                axios.post(actUrl, new FormData(window.event.target)).then(response => {
                    $("#modal").modal("hide");
                    _this.getProducts();
                })
            }
        },
        computed: {
            products() {
                return this.datas.filter(product => {
                    return product.name.toLowerCase().includes(this.search.toLowerCase());
                });
            }
        }
    }).mount('#app')

</script>
@endsection
