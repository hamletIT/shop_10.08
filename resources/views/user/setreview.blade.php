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

            @if(Auth::user()->two_factor_secret == null)
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('user.dashboard') }}">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-laugh-wink"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">Test project</div>
                </a>
            @else
              <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-laugh-wink"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">Test project</div>
                </a>
            @endif
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('user.cart') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">My cart</div>
            </a>
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('show.orders') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">My orders</div>
            </a>
            <hr class="sidebar-divider my-0">
        </ul>
        <div class="container-fluid">
            <!-- Page Heading -->
            <br>
            <h1 class="h3 mb-2 text-gray-800">Set Review</h1>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ route('set.review.prod') }}" method="POST" class="form-horizontal" role="form">
                                @csrf
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Text</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" value='' name="text" id="product_id" >
                                    </div>
                                </div> <!-- form-group // -->
                                <input class="product_id" name="product_id" value='{{$id}}' type="hidden">
                                <button type="submit" class="btn btn-danger">Save Review</button>
                        </form>
                    </div>
                </div>
            </div>
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

  
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>
    <script type="text/javascript">
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
    </script>
</body>

</html>