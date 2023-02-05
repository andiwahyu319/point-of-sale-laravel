@extends('layouts.hasLogin')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col">
            <h1>Dashboard</h1>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $income }}</h3>
                    <p>Income This Month</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $transaction_total }}</h3>
                    <p>Total Transaction This Month</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-lightblue">
                <div class="inner">
                    <h3>{{ $product_total}}</h3>
                    <p>Total Products</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
    <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title">Product Qty Data</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
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
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title">Need Restock</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @foreach($product_qty_min as $key => $product)
                        <li class="item">
                            <div class="product-img">
                                <img src="{{url('/product_images') . '/' . $product->img}}" alt="Product Image" class="img-size-50">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">{{$product->name}}</a>
                                <span class="product-description">
                                    Qty: {{$product->qty}} <span class="badge badge-warning">Qty < 5</span>
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section("js")
<!-- ChartJS -->
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
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