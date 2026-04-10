<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('assets/img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ URL::asset('assets/img/favicon.png') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <title>Field Service</title>

    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />

    <!-- Nucleo Icons -->
    <link href="{{ URL::asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet" />

    <!-- CSS Files -->

    <link id="pagestyle" href="{{ URL::asset('assets/css/material-dashboard.css?v=3.0.6') }}" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">

    <aside
        class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-primary"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="#">
                <img src="{{ URL::asset('assets/img/logo-ct.png') }}" class="navbar-brand-img h-100" alt="main_logo" />
                <span class="ms-1 font-weight-bold text-white">Field Service</span>
            </a>
        </div>

        <hr class="horizontal light mt-0 mb-2" />

        <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                

                <li class="nav-item">
                    <a data-bs-toggle="nav-link text-white" href="#" class="nav-link text-white"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <img src="{{ URL::asset('assets/img/user.png') }}" class="avatar" />
                        <span class="nav-link-text ms-2 ps-1"> {{ Auth::user()->name }}</span>
                    </a>

                </li>
                <hr class="horizontal light mt-0" />
                <li class="nav-item">
                    <a data-bs-toggle="nav-link text-white" href="{{ route('home') }}" class="nav-link text-white"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">dashboard</i>

                        <span class="nav-link-text ms-2 ps-1">Dashboard</span>
                    </a>

                </li>


                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder text-white">
                       Users
                    </h6>
                </li>
                <li class="nav-item ">
                    <a  data-bs-toggle="nav-link text-white" href="{{route('admins.index')}}" class="nav-link text-white {{ (\Request::route()->getName() == 'admins.index') ? 'active' : '' }}"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i 
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">dashboard</i>

                        <span class="nav-link-text ms-2 ps-1">Admins</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="nav-link text-white" href="{{route('sub_admins.index')}}" class="nav-link text-white {{ (\Request::route()->getName() == 'sub_admins.index') ? 'active' : '' }}"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">dashboard</i>

                        <span class="nav-link-text ms-2 ps-1">Super Admins</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="nav-link text-white" href="{{route('customers.index')}}" class="nav-link text-white {{ (\Request::route()->getName() == 'customers.index') ? 'active' : '' }}"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">dashboard</i>

                        <span class="nav-link-text ms-2 ps-1">Customers</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder text-white">
                       Pages
                    </h6>
                </li>






                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#pagesProducts" class="nav-link text-white"
                        aria-controls="pagesProducts" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">card_travel</i>

                        <span class="nav-link-text ms-2 ps-1">Products</span>
                    </a>

                    <div class="collapse" id="pagesProducts">
                        <ul class="nav">

                            <li class="nav-item">
                                <a class="nav-link text-white {{ (\Request::route()->getName() == 'products.index') ? 'active' : '' }}" href="<?php echo url('products/index/all/0/0'); ?>">
                                    <span class="sidenav-mini-icon"> A </span>
                                    <span class="sidenav-normal ms-2 ps-1">
                                        All
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="<?php echo url('products/index/accepted/0/0'); ?>">
                                    <span class="sidenav-mini-icon"> A </span>

                                    <span class="sidenav-normal ms-2 ps-1">
                                        Active
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="<?php echo url('products/index/pending/0/0'); ?>">
                                    <span class="sidenav-mini-icon"> P </span>

                                    <span class="sidenav-normal ms-2 ps-1">
                                        In-Active/Pending
                                    </span>
                                </a>
                            </li>
                            




                        </ul>
                    </div>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder text-white">
                       Constants
                    </h6>
                </li>

               
                <li class="nav-item">
                    <a data-bs-toggle="nav-link text-white" href="{{route('job_types.index')}}" class="nav-link text-white"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">dashboard</i>

                        <span class="nav-link-text ms-2 ps-1">Job Types</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a data-bs-toggle="nav-link text-white" href="{{route('contact_by_persons.index')}}" class="nav-link text-white"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">dashboard</i>

                        <span class="nav-link-text ms-2 ps-1">Contact Persons</span>
                    </a>
                </li>

                
                <li class="nav-item">
                    <a data-bs-toggle="nav-link text-white" href="{{route('parent_brands.index')}}" class="nav-link text-white"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">dashboard</i>

                        <span class="nav-link-text ms-2 ps-1">Parent Brands</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="nav-link text-white" href="{{route('brands.index')}}" class="nav-link text-white"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">dashboard</i>

                        <span class="nav-link-text ms-2 ps-1"> Brands</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="nav-link text-white" href="{{route('uom_types.index')}}" class="nav-link text-white"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">dashboard</i>

                        <span class="nav-link-text ms-2 ps-1">UOM Types</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="nav-link text-white" href="{{route('uoms.index')}}" class="nav-link text-white"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">dashboard</i>

                        <span class="nav-link-text ms-2 ps-1">UOMs</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a data-bs-toggle="nav-link text-white" href="{{route('major_categories.index')}}" class="nav-link text-white"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">dashboard</i>

                        <span class="nav-link-text ms-2 ps-1">Major Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="nav-link text-white" href="{{route('sub_major_categories.index')}}" class="nav-link text-white"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">dashboard</i>

                        <span class="nav-link-text ms-2 ps-1">Sub Major Categories</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="nav-link text-white" href="{{route('sub_categories.index')}}" class="nav-link text-white"
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">dashboard</i>

                        <span class="nav-link-text ms-2 ps-1">Sub Categories</span>
                    </a>
                </li>
                

               

            

 



 

 

   
   
            </ul>
        </div>
    </aside>

    <main class="main-content border-radius-lg">
        <!-- Navbar -->

        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky"
            id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">


                <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none">
                    <a href="javascript:;" class="nav-link text-body p-0">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </div>

                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">

                    </div>

                    <ul class="navbar-nav justify-content-end">


                        <button type="button"
                            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"
                            class="btn btn-sm btn-alt-secondary ms-2">
                            <span class="d-none d-sm-inline-block ms-2">Logout</span>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </button>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- End Navbar -->
        @yield('content')


    </main>
 
    <!--   Core JS Files   -->
    <script src="{{ URL::asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>

    <!-- Kanban scripts -->
    <script src="{{ URL::asset('assets/js/plugins/dragula/dragula.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/jkanban/jkanban.js') }}"></script>

    <script>
        var win = navigator.platform.indexOf("Win") > -1;
        if (win && document.querySelector("#sidenav-scrollbar")) {
            var options = {
                damping: "0.5",
            };
            Scrollbar.init(document.querySelector("#sidenav-scrollbar"), options);
        }
    </script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ URL::asset('assets/js/material-dashboard.min.js?v=3.0.6') }}"></script>
</body>

</html>
