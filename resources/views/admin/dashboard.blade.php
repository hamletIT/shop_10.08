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
                <div class="sidebar-brand-text mx-3">Test project</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.show.all.users') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Users</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.show.all.orders') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Orders</span></a>
            </li>
           
            
            <!-- Divider -->
            <hr class="sidebar-divider">
            <?php $prods = App\Models\Products::get(); ?>
            @if(!isset($prods))
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
            @endif

        </ul>
            <div class="container-fluid">
                <!-- Page Heading -->
                <h1 class="h3 mb-2 text-gray-800">Product Table</h1>

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Price</th>
                                        <th>Count</th>
                                        <th>Rate</th>
                                        <th>Action Edit</th>
                                        <th>Action delete</th>
                                        <th>Action show</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($allProducts))
                                    @foreach($allProducts as $prod)
                                        <tr>
                                            <td>{{$prod->title}}</td>
                                            <td>{{$prod->productPrice[0]->price}}</td>
                                            <td>{{$prod->count}}</td>
                                            <td>{{$prod->productRating[0]->rate}}</td>
                                            <td>
                                                <a href="{{ route('admin.update.show',['productID' => $prod->id]) }}">
                                                    <button type="button" class="btn btn-danger">Edit</button>
                                                </a>
                                            </td>
                                            <td>
                                                <form action="{{ route('delete.product') }}" method="POST" class="form-horizontal" role="form">
                                                    @csrf
                                                    <input class="product_id" name="product_id" value='{{$prod->id}}' type="hidden">
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                            <td>
                                                <button data-form="{{$prod->id}}" type="button" class="btn btn-danger show_photo_action_blok">Show</button>
                                            </td>
                                        </tr>
                                        
                                        <tr class="photo_action_blok" style="display:none;"data-form="{{$prod->id}}">
                                            <td class="d-flex flex-row">
                                                <img width="70px" height="50px" src="{{$prod->image}}" alt="">
                                            </td>
                                            <td>
                                                {{$prod->description}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
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
    
    $('.show_photo_action_blok').click(function(){
        var dataFormValue = $(this).data("form");
        console.log(dataFormValue); 
        var photo_blok = $($(this).parent().parent().parent()).children();
        for (let i = 0; i < photo_blok.length; i++) {
            if ($(photo_blok[i]).data("form") == dataFormValue) {
                if($(photo_blok[i]).css('display') == 'none')
                {
                    $(photo_blok[i]).css('display','block');
                } else {
                    $(photo_blok[i]).css('display','none');
                }
                
            }
        }

        
    })

    </script>
</body>
</html>