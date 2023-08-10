<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                <div class="sidebar-brand-text mx-3">Dstdelivery</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            
           
             <!-- Nav Item - swagger -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>@if(session()->get('locale') == "en") Dashboard @else @lang('messages.Dashboard') @endif</span></a>
            </li>

            

            {{-- <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.languages') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>@if(session()->get('locale') == "en") Languages @else @lang('messages.Languages') @endif</span></a>
            </li> --}}

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <form action="{{ route('product.submit') }}" method="POST" class="form-horizontal" role="form">
                    @csrf
                    <button type="submit" class="btn btn-primary">set products</button>
                </form>
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

                 
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                <section class="panel panel-default">
                    <div class="panel-heading"> 
                    <h3 class="panel-title">Update Product</h3> 
                    </div> 
                    <div class="panel-body">
                    
                    <form action="{{ route('admin.update.product') }}" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">
                        @csrf
                        <input type="hidden" name="id" value="{{$singleProduct->id}}">
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Title</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{$singleProduct->title}}" name="title" id="name" >
                            </div>
                        </div> <!-- form-group // -->
                        
                       
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{$singleProduct->description}}" name="description" id="name" >
                            </div>
                        </div> <!-- form-group // -->
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Price</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{$singleProduct->productPrice[0]['price']}}" name="price" id="name" >
                            </div>
                        </div> <!-- form-group // -->
                       
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Count</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{$singleProduct->count}}" name="count" id="name" >
                            </div>
                        </div> <!-- form-group // -->

                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Rate</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{$singleProduct->productRating[0]['rate']}}" name="rate" id="name" >
                            </div>
                        </div> <!-- form-group // -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div> <!-- form-group // -->
                    </form>
                    
                    </div><!-- panel-body // -->
                </section><!-- panel// -->
                </div>
                <!-- /.container-fluid -->

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