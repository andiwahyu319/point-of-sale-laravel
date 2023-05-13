@extends('layouts.hasLogin')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Income This Month</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $income }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col col-md-4 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Transaction This Month</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transaction_total }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col col-md-4 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Products</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $product_total }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Product Qty Data</h6>
            </div>
            <div class="card-body">
                <div class="chartjs-size-monitor">
                    <div class="chartjs-size-monitor-expand">
                        <div class=""></div>
                    </div>
                    <div class="chartjs-size-monitor-shrink">
                        <div class=""></div>
                    </div>
                </div>
                <canvas id="productChart"
                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 318px;"
                    width="318" height="250" class="chartjs-render-monitor"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">Need Restock</h6>
            </div>
            <div class="card-body">
                @foreach ($product_qty_min as $key => $product)
                <div class="row">
                    <div class="col col-2">
                        <img src="{{url('/product_images') . '/' . $product->img}}" alt="Product Image" width="50" height="50">
                    </div>
                    <div class="col col-10">
                        <p class="text-bold">{{$product->name}}</p>
                        <p>Qty: {{$product->qty}} <span class="badge badge-warning">Qty < 5</span></p>
                    </div>
                </div>
                <hr>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section("js")
<!-- ChartJS -->
<script src="{{ asset('assets/vendor/chart.js/Chart.min.js') }}"></script>
<script>
    function randColor() {
        return "#" + Math.floor(Math.random()*16777215).toString(16);
    }
    $.get("{{ url('/api/product') }}", function (data) {
        products = data;
        productLabel = [];
        productData = [];
        productBackround = [];
        for (const key in products) {
            if (Object.hasOwnProperty.call(products, key)) {
                const product = products[key];
                productLabel.push(product.name);
                productData.push(product.qty);
                productBackround.push(randColor())
            }
        }
        var productChart = $('#productChart').get(0).getContext('2d');
        new Chart(productChart, {
            type: 'pie',
            data: {
                labels: productLabel,
                datasets: [{
                    data: productData,
                    backgroundColor: productBackround,
                }]
            },
            
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                }
            }
        })
    });
</script>
@endsection