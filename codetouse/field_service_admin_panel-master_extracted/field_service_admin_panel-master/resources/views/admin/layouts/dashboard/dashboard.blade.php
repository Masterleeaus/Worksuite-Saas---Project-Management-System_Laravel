<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

</head>
@extends('admin.layouts.admin-dash-layout')
@section('title', 'Dashboard')
@section('content')

    <div class="container-fluid py-4">

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card  mb-2">
                    <a data-bs-toggle="nav-link text-white" target="_blank" href="{{ route('buyers.index') }}">


                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person_add</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Total Customers</p>
                                <h4 class="mb-0">{!! $totalCustomers !!}</h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0"><span class="text-success text-sm font-weight-bolder">{!! $customersThanLastMonth !!}
                                    %
                                </span>than last month
                            </p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mt-sm-0 mt-4">
                <a data-bs-toggle="nav-link text-white" target="_blank" href="<?php echo url('suppliers/index/all/0/0'); ?>">

                    <div class="card  mb-2">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-primary shadow-primary shadow text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">store</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Total Suppliers</p>
                                <h4 class="mb-0">{!! $totalSuppliers !!}</h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0"><span class="text-success text-sm font-weight-bolder">{!! $suppliersThanLastMonth !!}
                                    %
                                </span>than last month
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mt-lg-0 mt-4">
                <a data-bs-toggle="nav-link text-white" target="_blank" href="<?php echo url('products/index/all/0/0'); ?>">

                    <div class="card  mb-2">
                        <div class="card-header p-3 pt-2 bg-transparent">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">card_travel</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize ">Total Products</p>
                                <h4 class="mb-0 ">{!! $totalProducts !!}</h4>
                            </div>
                        </div>
                        <hr class="horizontal my-0 dark">
                        <div class="card-footer p-3">
                            <p class="mb-0 "><span class="text-success text-sm font-weight-bolder">{!! $productsThanLastMonth !!}
                                    % </span>than last month
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mt-lg-0 mt-4">
                <a data-bs-toggle="nav-link text-white" target="_blank" href="{{ url('invoices/list/0/0') }}">

                    <div class="card ">
                        <div class="card-header p-3 pt-2 bg-transparent">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">add_shopping_cart</i>

                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize ">Total Orders</p>
                                <h4 class="mb-0 ">{!! $totalOrders !!}</h4>
                            </div>
                        </div>
                        <hr class="horizontal my-0 dark">
                        <div class="card-footer p-3">
                            <p class="mb-0 ">-</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-7 text-start">
                                <p class="text-sm mb-1 text-capitalize font-weight-bold">Total Affiliate</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {!! $totalAffiliateAmount !!} OMR </h5>
                                <span
                                    class="text-sm text-end text-success font-weight-bolder mt-auto mb-0">{!! $totalAffiliateAmountThanLastMonth !!}%
                                    <span class="font-weight-normal text-secondary">since last month</span></span>
                            </div>
                            <div class="col-5">
                                <div class="dropdown text-end">
                                    <a href="javascript:;" class="cursor-pointer text-secondary" id="dropdownUsers1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="text-xs text-secondary">2022-2023</span>
                                    </a>
                                    {{-- <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="dropdownUsers1">
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 7 days</a>
                                        </li>
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Last week</a></li>
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 30 days</a>
                                        </li>
                                    </ul> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 mt-sm-0 mt-4">
                <div class="card">
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-7 text-start">
                                <p class="text-sm mb-1 text-capitalize font-weight-bold">Recieved Affiliate</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {!! $recievedAffiliateAmount !!} OMR </h5>
                                <span class="text-sm text-end text-success font-weight-bolder mt-auto mb-0"> <span
                                        class="font-weight-normal text-secondary">- </span> </span>
                            </div>
                            <div class="col-5">
                                <div class="dropdown text-end">
                                    <a href="javascript:;" class="cursor-pointer text-secondary" id="dropdownUsers2"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="text-xs text-secondary">2022-2023</span>
                                    </a>
                                    {{-- <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="dropdownUsers2">
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 7 days</a>
                                        </li>
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Last week</a></li>
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 30 days</a>
                                        </li>
                                    </ul> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 mt-sm-0 mt-4">
                <div class="card">
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-7 text-start">
                                <p class="text-sm mb-1 text-capitalize font-weight-bold">Pending Affiliate</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {!! $pendingAffiliateAmount !!} OMR </h5>
                                <span class="font-weight-normal text-secondary text-sm"><span
                                        class="font-weight-bolder text-success"> -</span> </span>
                            </div>
                            <div class="col-5">
                                <div class="dropdown text-end">
                                    <a href="javascript:;" class="cursor-pointer text-secondary" id="dropdownUsers3"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="text-xs text-secondary">2022-2023</span>
                                    </a>
                                    {{-- <ul class="dropdown-menu dropdown-menu-end px-2 py-3"
                                        aria-labelledby="dropdownUsers3">
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 7 days</a>
                                        </li>
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Last week</a>
                                        </li>
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 30 days</a>
                                        </li>
                                    </ul> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row mt-4">
            <div class="col-lg-4 col-sm-6">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0">Channels</h6>
                            <button type="button"
                                class="btn btn-icon-only btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title=""
                                data-bs-original-title="See traffic channels">
                                <i class="material-icons text-sm">priority_high</i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body pb-0 p-3 mt-4">
                        <div class="row">
                            <div class="col-7 text-start">
                                <div class="chart">
                                    <canvas id="chart-pie" class="chart-canvas" height="200"></canvas>
                                </div>
                            </div>
                            <div class="col-5 my-auto">
                                <span class="badge badge-md badge-dot me-4 d-block text-start">
                                    <i class="bg-info"></i>
                                    <span class="text-dark text-xs">Facebook</span>
                                </span>
                                <span class="badge badge-md badge-dot me-4 d-block text-start">
                                    <i class="bg-primary"></i>
                                    <span class="text-dark text-xs">Direct</span>
                                </span>
                                <span class="badge badge-md badge-dot me-4 d-block text-start">
                                    <i class="bg-dark"></i>
                                    <span class="text-dark text-xs">Organic</span>
                                </span>
                                <span class="badge badge-md badge-dot me-4 d-block text-start">
                                    <i class="bg-secondary"></i>
                                    <span class="text-dark text-xs">Referral</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pt-0 pb-0 p-3 d-flex align-items-center">
                        <div class="w-60">
                            <p class="text-sm">
                                More than <b>1,200,000</b> sales are made using referral marketing, and <b>700,000</b> are
                                from social media.
                            </p>
                        </div>
                        <div class="w-40 text-end">
                            <a class="btn bg-light mb-0 text-end" href="javascript:;">Read more</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-sm-6 mt-sm-0 mt-4">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0">Revenue</h6>
                            <button type="button"
                                class="btn btn-icon-only btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center"
                                data-bs-toggle="tooltip" data-bs-placement="left"
                                data-bs-original-title="See which ads perform better">
                                <i class="material-icons text-sm">priority_high</i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-md badge-dot me-4">
                                <i class="bg-primary"></i>
                                <span class="text-dark text-xs">Facebook Ads</span>
                            </span>
                            <span class="badge badge-md badge-dot me-4">
                                <i class="bg-dark"></i>
                                <span class="text-dark text-xs">Google Ads</span>
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Top Selling Products</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">

                            <table class="table align-items-center mb-0 top-selling">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Product</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                           Price</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Item Sold</th>
                                        {{-- <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Affiliate</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <script>
                                        var products = <?php echo json_encode($topSellingProducts); ?>;
                                        products.forEach((element) => {


                                            document.write(" <tr>")
                                            document.write(`<td>
                                            <div class="d-flex px-3 py-1">
                                                <div>
                                                    <img src="` + element.image + `"
                                                        class="avatar me-3" alt="image">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">` + element.name_en + ` - ` + element
                                                .name_ar + `</h6>
                                                    <p class="text-sm font-weight-normal text-secondary mb-0"><span
                                                            class="text-success">10</span> Orders</p>
                                                </div>
                                            </div>
                                        </td>`)

                                            document.write(`<td>
                                            <p class="text-sm font-weight-normal mb-0">` + (element.price )
                                                .toFixed(3) + ` OMR</p>
                                        </td>`)
                                            document.write(`<td class="align-middle text-center text-sm">
                                            <p class="text-sm font-weight-normal mb-0">` +(element.count??0) + ` items</p>
                                        </td>`)
                                        //     document.write(`<td class="align-middle text-end">
                                        //     <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                        //         <p class="text-sm font-weight-normal mb-0">` + ((element.price * 10) *
                                        //         (
                                        //             0.05)).toFixed(3) + ` OMR</p>
                                        //      </div>
                                        // </td>`)





                                            document.write(" </tr> ")

                                        });
                                    </script>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer py-4  ">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>,
                            made by
                            <a href="$" class="font-weight-bold" target="_blank">Akaash
                            </a>
                            for Smart Energy .
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                            <li class="nav-item">
                                <a href="#" class="nav-link text-muted" target="_blank">Developer Contact</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link text-muted" target="_blank">About Field Service</a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link pe-0 text-muted" target="_blank">License</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>

@endsection
<script>
    var ctx1 = document.getElementById("chart-line").getContext("2d");
    var ctx2 = document.getElementById("chart-pie").getContext("2d");
    var ctx3 = document.getElementById("chart-bar").getContext("2d");

    // Line chart
    new Chart(ctx1, {
        type: "line",
        data: {
            labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                    label: "Facebook Ads",
                    tension: 0,
                    pointRadius: 5,
                    pointBackgroundColor: "#e91e63",
                    pointBorderColor: "transparent",
                    borderColor: "#e91e63",
                    borderWidth: 4,
                    backgroundColor: "transparent",
                    fill: true,
                    data: [50, 100, 200, 190, 400, 350, 500, 450, 700],
                    maxBarThickness: 6
                },
                {
                    label: "Google Ads",
                    tension: 0,
                    borderWidth: 0,
                    pointRadius: 5,
                    pointBackgroundColor: "#3A416F",
                    pointBorderColor: "transparent",
                    borderColor: "#3A416F",
                    borderWidth: 4,
                    backgroundColor: "transparent",
                    fill: true,
                    data: [10, 30, 40, 120, 150, 220, 280, 250, 280],
                    maxBarThickness: 6
                }
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5],
                        color: '#c1c4ce5c'
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#9ca2b7',
                        font: {
                            size: 14,
                            weight: 300,
                            family: "Roboto",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: true,
                        borderDash: [5, 5],
                        color: '#c1c4ce5c'
                    },
                    ticks: {
                        display: true,
                        color: '#9ca2b7',
                        padding: 10,
                        font: {
                            size: 14,
                            weight: 300,
                            family: "Roboto",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
            },
        },
    });


    new Chart(ctx2, {
        type: "pie",
        data: {
            labels: ['Facebook', 'Direct', 'Organic', 'Referral'],
            datasets: [{
                label: "Projects",
                weight: 9,
                cutout: 0,
                tension: 0.9,
                pointRadius: 2,
                borderWidth: 1,
                backgroundColor: ['#17c1e8', '#e91e63', '#3A416F', '#a8b8d8'],
                data: [15, 20, 12, 60],
                fill: false
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        color: '#c1c4ce5c'
                    },
                    ticks: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        color: '#c1c4ce5c'
                    },
                    ticks: {
                        display: false,
                    }
                },
            },
        },
    });

    // Bar chart
    new Chart(ctx3, {
        type: "bar",
        data: {
            labels: ['16-20', '21-25', '26-30', '31-36', '36-42', '42-50', '50+'],
            datasets: [{
                label: "Sales by age",
                weight: 5,
                borderWidth: 0,
                borderRadius: 4,
                backgroundColor: '#3A416F',
                data: [15, 20, 12, 60, 20, 15, 25],
                fill: false
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5],
                        color: '#c1c4ce5c'
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#c1c4ce5c',
                        font: {
                            size: 14,
                            weight: 300,
                            family: "Roboto",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: true,
                        drawTicks: true,
                        color: '#9ca2b7'
                    },
                    ticks: {
                        display: true,
                        color: '#9ca2b7',
                        padding: 10,
                        font: {
                            size: 14,
                            weight: 300,
                            family: "Roboto",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
            },
        },
    });
</script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
