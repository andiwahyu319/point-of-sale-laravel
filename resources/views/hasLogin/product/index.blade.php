@extends('layouts.hasLogin')

@section("css")
<link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('content')
<div id="app">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Product</h1>
        <button @click="addData()" class="btn btn-secondary">New</button>
    </div>

    <!-- Content Row -->
    <div class="row justify-content-center">
        <div class="col col-md-10 mb-4">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Barcode</th>
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
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
                    <h4 class="modal-title">Product Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body card p-0">
                    <div class="card-body">
                        <img class="rounded mx-auto d-block" v-bind:src="data.img" v-bind:alt="'image ' + data.name">
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">
                                <b>Name</b> <a class="float-right">@{{data.name}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Barcode</b> <a class="float-right">@{{data.barcode}}</a>
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
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                            <label>Image</label><span class="text-xs text-gray-500" v-if="edit == true"> (Optional)</span>
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
                            <label>Barcode Text</label><span class="text-xs text-gray-500"> (Recomended use 'Code 128' standard)</span>
                            <input type="text" name="barcode" class="form-control" id="barcode" :value="data.barcode"
                                placeholder="Enter Barcode Text" required>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
<script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/vue/vue.global.min.js') }}"></script>
<script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
<script>
    $("[data-toggle='popover']").popover();
</script>
<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
<script>
    var columns = [
        {render: function (index, row, data, meta) { 
            return  meta.row + 1;
        }, orderable: true},
        {data: "barcode", orderable: false},
        {data: "name", render: function ( data, type, row ) {
            return "<img src='"+row.img+"' alt='"+data+"' width='25px' height='25px'> " + data 
        }, orderable: true},
        {data: "qty", orderable: true},
        {data: "price", render: function ( data, type, row ) {
            return "Rp. " + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ",-"
        }, orderable: true},
        {render: function (index, row, data, meta) { 
            return "<button onclick='controller.showData("+meta.row+")' class='btn btn-primary btn-circle btn-sm' data-container='body' data-toggle='popover' data-trigger='hover' data-placement='bottom' data-content='Show'><i class='fas fa-eye'></i></button>"
            + " <button onclick='controller.editData("+meta.row+")' class='btn btn-info btn-circle btn-sm' data-container='body' data-toggle='popover' data-trigger='hover' data-placement='bottom' data-content='Edit'><i class='fas fa-edit'></i></button>"
            + " <button onclick='controller.deleteData("+meta.row+")' class='btn btn-danger btn-circle btn-sm' data-container='body' data-toggle='popover' data-trigger='hover' data-placement='bottom' data-content='Delete'><i class='fas fa-trash'></i></button>"
        }, orderable: false},
    ];
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
            number_format(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            },
            showData(index) {
                this.data = this.datas[index];
                $("#modal-show").modal();
            },
            addData() {
                this.data = {};
                this.edit = false;
                $("#modal").modal();
            },
            editData(index) {
                this.data = this.datas[index];
                this.edit = true;
                $('#modal').css('overflow-y', 'auto');
                $("#modal").modal();
            },
            deleteData(index) {
                this.data = this.datas[index];
                actUrl = this.actionUrl + "/" + this.data.id;
                if (confirm("are you sure ?")) {
                    axios.post(actUrl, {
                        _method: "DELETE"
                    }).then(response => {
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
        }
    }).mount('#app')

</script>
@endsection
