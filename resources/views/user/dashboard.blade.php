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

            <!-- Nav Item - Dashboard -->
           
           
             <!-- Nav Item - swagger -->
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>sdfvsd</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>sdgfsd</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>sEF</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            
           

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>sdfgdf</span>
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
                    {{-- <form action="{{ route('page.filter') }}" method="POST" 
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
                    </form> --}}
                    
                   
                   
                
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">seGFSEg</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                           
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{--@foreach($alloptions as $option)
                                            
                                            <tr>
                                                <td>{{$option->name}}</td>
                                                <td>{{$option->name_info}}</td>
                                                <td>{{$option->price}}</td>
                                                <td>
                                                    @if($option->status == 0)
                                                        @if(session()->get('locale') == "en") Deactive @else @lang('messages.Deactive') @endif
                                                    @elseif($user->status == 1)
                                                        @if(session()->get('locale') == "en") Active @else @lang('messages.Active') @endif
                                                    @endif
                                                </td>
                                               
                                                <td>
                                                <?php
                                                    $product = App\Models\Products::where('id',$option->product_id)->first();
                                                    // dd($product);
                                                ?>
                                                    {{$product['name']}}
                                                </td>
                                             
                                            </tr>
                                            <tr>
                                                @foreach($option->optionImages as $value)
                                                    <td>
                                                        <p class="del_photo_option">
                                                            x
                                                            <input class="option_id" value='{{$option->id}}' type="hidden">
                                                            <input class="option_poto_name" value='{{$value->name}}' type="hidden">
                                                        </p>
                                                        <img width="70px" height="50px" src="{{ asset('Option_Images'.'/'.$option->photoFileName.'/'.$value->name) }}" alt="">
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>
                                                <form action="{{ route('add.option_photos') }}" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">
                                                @csrf
                                                    <input type="file" name="image[]" multiple="multiple" class="form-control" id="customFile_ajax" />
                                                    <input type="hidden" value="{{$option->id}}" name="option_id" multiple="multiple" class="form-control" id="customFile_ajax" />
                                                    <button type="submit" class="btn btn-primary">@if(session()->get('locale') == "en") Send @else @lang('messages.Send') @endif</button>
                                                </form>
                                                </td>
                                            </tr>
                                           
                                        @endforeach --}}
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
</body>

</html>