<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Tables</title>

    <!-- Custom fonts for this template -->
    <!-- <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css"> -->
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">

    <!-- Custom styles for this page -->
    <!-- <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"> -->

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Project Dislab</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" target="_blank" href="http://127.0.0.1:8000/api/documentation">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>@if(session()->get('locale') == "en") Swagger Api Documentation @else @lang('messages.Swagger Api Documentation') @endif</span></a>
            </li>
            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>@if(session()->get('locale') == "en") Dashboard @else @lang('messages.Dashboard') @endif</span></a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.statistic') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>@if(session()->get('locale') == "en") Statistics @else @lang('messages.Statistics') @endif</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.languages') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>@if(session()->get('locale') == "en") Languages @else @lang('messages.Languages') @endif</span></a>
            </li>
            
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                @if(session()->get('locale') == "en") Interface @else @lang('messages.Interface') @endif 
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.store') }}" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>@if(session()->get('locale') == "en") Add store @else @lang('messages.Add store') @endif</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.Bigstore') }}" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>@if(session()->get('locale') == "en") Add Big Store @else @lang('messages.Add Big Store') @endif</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li>

             <!-- Nav Item - Pages Collapse Menu -->
             <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.category') }}" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>@if(session()->get('locale') == "en") Add Category @else @lang('messages.Add Category') @endif</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li>

             <!-- Nav Item - Pages Collapse Menu -->
             <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.subCategory') }}" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>@if(session()->get('locale') == "en") Add Sub Category @else @lang('messages.Add Sub Category') @endif</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.childsubCategory') }}" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>@if(session()->get('locale') == "en") Add Child Sub Category @else @lang('messages.Add Child Sub Category') @endif</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.products') }}" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>@if(session()->get('locale') == "en") Add Product @else @lang('messages.Add Product') @endif</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.option') }}" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>@if(session()->get('locale') == "en") Add Option @else @lang('messages.Add Option') @endif</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Search -->
                    <form action="{{ route('page.filter') }}" method="POST" 
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        @csrf
                        <p>
                        </p>
                        <div class="d-flex">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") Select field @else @lang('messages.Select field') @endif</label>
                            <select name ="table_name" class="form-select form-select-lg">
                                @if(isset($productFilter))
                                    <option selected value="Product">@if(session()->get('locale') == "en") Product @else @lang('messages.Product') @endif</option>
                                @elseif(isset($optionFilter))
                                    <option selected value="Option">@if(session()->get('locale') == "en") Option @else @lang('messages.Option') @endif</option>
                                @elseif(isset($storeFilter))
                                    <option selected value="Store">@if(session()->get('locale') == "en") Store @else @lang('messages.Store') @endif</option>
                                @elseif(isset($categoryFilter))
                                    <option selected value="Category">@if(session()->get('locale') == "en") Category @else @lang('messages.Category') @endif</option>
                                @elseif(isset($subCategoryFilter))
                                    <option selected value="SubCategory">@if(session()->get('locale') == "en") Sub Category @else @lang('messages.Sub Category') @endif</option>
                                @elseif(isset($childSubCategoryFilter))
                                    <option selected value="ChildSubCategory">@if(session()->get('locale') == "en") Child Sub Category @else @lang('messages.Child Sub Category') @endif</option>
                                @else
                                    <option selected value="Product">@if(session()->get('locale') == "en") Product @else @lang('messages.Product') @endif</option>
                                    <option  value="Option">@if(session()->get('locale') == "en") Option @else @lang('messages.Option') @endif</option>
                                    <option  value="Store">@if(session()->get('locale') == "en") Store @else @lang('messages.Store') @endif</option>
                                    <option  value="Category">@if(session()->get('locale') == "en") Category @else @lang('messages.Category') @endif</option>
                                    <option  value="SubCategory">@if(session()->get('locale') == "en") Sub Category @else @lang('messages.Sub Category') @endif</option>
                                    <option  value="ChildSubCategory">@if(session()->get('locale') == "en") Child Sub Category @else @lang('messages.Child Sub Category') @endif</option>
                                @endif
                            </select>
                            @if(session()->get('locale') == "en")
                            <input type="text" name="name" class="form-control bg-light border-0 small" placeholder="Search for.."
                                aria-label="Search" aria-describedby="basic-addon2">
                            @else
                            <input type="text" name="name" class="form-control bg-light border-0 small" placeholder="Искать.."
                                aria-label="Search" aria-describedby="basic-addon2">
                            @endif
                            
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    @if(session()->get('locale') == "en") Search @else @lang('messages.Search') @endif
                                </button>
                            </div>
                        </div>
                    </form>
                 
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @if(isset($errors) && count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="list-unstyled">
                                @foreach($errors as $error)
                                <li> {{ $error[0] }} </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <section class="panel panel-default">
                        <div class="panel-heading"> 
                            <h3 class="panel-title">@if(session()->get('locale') == "en") Add Big Store @else @lang('messages.Add Big Store') @endif</h3> 
                        </div> 
                        <div class="panel-body">
                            <!-- form-group // -->
                            <form action="{{ route('bigStore.submit') }}" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">
                                @csrf
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") Name @else @lang('messages.Name') @endif</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" id="name">
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") Inforamtion @else @lang('messages.Inforamtion') @endif</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" name="info" id="name">
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") Photo File Name @else @lang('messages.Photo File Name') @endif</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" name="photoFileName" id="name">
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="customFile">@if(session()->get('locale') == "en") Big Store images @else @lang('messages.Big Store images') @endif</label>
                                    <div class="col-sm-9">
                                    <input type="file" name="image[]" multiple="multiple" class="form-control" id="customFile" />
                                    </div>
                                </div> <!-- form-group // -->
                            
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-primary">@if(session()->get('locale') == "en") Send @else @lang('messages.Send') @endif</button>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                    <a class="btn btn-primary" target="_blank" href="http://127.0.0.1:8000/api/documentation#/Create%20store/0f469761a63d8080fd2b7345f203478b">@if(session()->get('locale') == "en") Try in Swagger @else @lang('messages.Try in Swagger') @endif</a>
                                    </div>
                                </div> 
                            </form>
                            <!-- form-group // -->
                        </div>
                    </section>
                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <!-- <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script> -->
    <!-- <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script> -->
    <!-- <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script> -->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>
    <!-- <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script> -->
    <!-- <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script> -->
    <!-- <script src="{{asset('js/demo/datatables-demo.js')}}"></script> -->

</body>

</html>