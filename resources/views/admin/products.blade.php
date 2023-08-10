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

        
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <div class="container-fluid">

                <section class="panel panel-default">
                    <div class="panel-heading"> 
                    <h3 class="panel-title"></h3> 
                    </div> 
                    <div class="panel-body">
                    @if(isset($errors) && count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="list-unstyled">
                                @foreach($errors as $error)
                                <li> {{ $error[0] }} </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('product.submit') }}" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") name @else @lang('messages.name') @endif</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="name" id="name" >
                        </div>
                    </div> <!-- form-group // -->
                    
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") type @else @lang('messages.type') @endif</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="type" id="name" >
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") color @else @lang('messages.color') @endif</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="color" id="name" >
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") description @else @lang('messages.description') @endif</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="description" id="name" >
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") Choose a folder name for the photo @else @lang('messages.Choose a folder name for the photo') @endif</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="photoFileName" id="name" >
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="customFile">@if(session()->get('locale') == "en") image @else @lang('messages.image') @endif</label>
                        <div class="col-sm-9">
                        <input type="file" name="image[]" multiple="multiple" class="form-control" id="customFile" />
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") size @else @lang('messages.size') @endif</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="size" id="name" >
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") status @else @lang('messages.status') @endif</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="status" id="name" >
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") standardCost @else @lang('messages.standardCost') @endif</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="standardCost" id="name">
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") listprice @else @lang('messages.listprice') @endif</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="listprice" id="name" >
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") price @else @lang('messages.price') @endif</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="price" id="name" >
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") totalPrice @else @lang('messages.totalPrice') @endif</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="totalPrice" id="name" >
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") weight @else @lang('messages.weight') @endif</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="weight" id="name" >
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") total Qty @else @lang('messages.total Qty') @endif</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="totalQty" id="name" >
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") Stores @else @lang('messages.Stores') @endif </label>
                        <select name ="store_id" class="form-select form-select-lg">
                            @foreach($allStores as $store)
                                <option value="{{$store->id}}">{{$store->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") Categoryes @else @lang('messages.Categoryes') @endif </label>
                        <select name="category_id" class="form-select form-select-lg">
                            <option selected value="{{$singleCategory->id}}">with out category</option>
                            @foreach($allCategory as $category)
                                @if($category->status !== '001')
                                    <option value="{{$category->id}}">{{$category->title}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") Sub Categoryes @else @lang('messages.Sub Categoryes') @endif </label>
                        <select name="sub_category_id" class="form-select form-select-lg">
                            @foreach($allSubCategory as $subCategory)
                                @if($subCategory->status == '001')
                                    <option selected value="{{$subCategory->id}}">{{$subCategory->title}}</option>
                                @else
                                    <option value="{{$subCategory->id}}">{{$subCategory->title}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">@if(session()->get('locale') == "en") Child Sub Categories @else @lang('messages.Child Sub Categories') @endif </label>
                        <select name="child_sub_category_id" class="form-select form-select-lg">
                            @foreach($allChildSubCategory as $childSubCategory)
                                @if($childSubCategory->status == '001')
                                    <option selected value="{{$childSubCategory->id}}">{{$childSubCategory->title}}</option>
                                @else
                                    <option value="{{$childSubCategory->id}}">{{$childSubCategory->title}}</option>
                                @endif
                            @endforeach
                        </select>
                     </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-primary">@if(session()->get('locale') == "en") Send @else @lang('messages.Send') @endif</button>
                        </div>
                    </div> <!-- form-group // -->
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                        <a class="btn btn-primary" target="_blank" href="https://dstdelivery.sk-its.ru/api/documentation#/Product%20Section/18a3477fb98a13fe6f2eade0b8ee6afd">@if(session()->get('locale') == "en") Try in Swagger @else @lang('messages.Try in Swagger') @endif</a>
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