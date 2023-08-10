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
                {{--<div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">@if(session()->get('locale') == "en") Application Table @else @lang('messages.Application Table') @endif</h1>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                            <?php
                                $countApp = count($application);
                            ?>
                            @if(session()->get('locale') == "en") Registered @else @lang('messages.Registered') @endif
                                {{ $countApp }}
                            @if(session()->get('locale') == "en") application @else @lang('messages.application') @endif
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>@if(session()->get('locale') == "en") name @else @lang('messages.name') @endif</th>
                                            <th>@if(session()->get('locale') == "en") phone @else @lang('messages.phone') @endif</th>
                                            <th>@if(session()->get('locale') == "en") password @else @lang('messages.password') @endif</th>
                                            <th>@if(session()->get('locale') == "en") text @else @lang('messages.text') @endif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($application as $app)
                                            <tr>
                                                <td>{{$app->name ?? 'empty'}}</td>
                                                <td>{{$app->phone ?? 'empty'}}</td>
                                                <td>{{$app->password ?? 'empty'}}</td>
                                                <td>{{$app->text ?? 'empty'}}</td>
                                                <td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">@if(session()->get('locale') == "en") User Table @else @lang('messages.User Table') @endif</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                            <?php
                                $countUser = count($allUsers);
                            ?>
                            @if(session()->get('locale') == "en") Registered @else @lang('messages.Registered') @endif
                                {{ $countUser }}
                            @if(session()->get('locale') == "en") users @else @lang('messages.users') @endif
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>@if(session()->get('locale') == "en") name @else @lang('messages.name') @endif</th>
                                            <th>@if(session()->get('locale') == "en") status @else @lang('messages.status') @endif</th>
                                            <th>@if(session()->get('locale') == "en") code @else @lang('messages.code') @endif</th>
                                            <th>@if(session()->get('locale') == "en") phone @else @lang('messages.phone') @endif</th>
                                            <th>@if(session()->get('locale') == "en") email @else @lang('messages.email') @endif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allUsers as $user)
                                            @if($user->two_factor_secret !== 'admin')
                                            <tr>
                                                <td>{{$user->name}}</td>
                                                <td>
                                                    @if($user->status == 0)
                                                        @if(session()->get('locale') == "en") Unregistered @else @lang('messages.Unregistered') @endif 
                                                    @elseif($user->status == 1)
                                                        @if(session()->get('locale') == "en") Registered @else @lang('messages.Registered') @endif 
                                                    @endif
                                                </td>
                                                <td>@if(session()->get('locale') == "en") Individual code @else @lang('messages.Individual code') @endif {{$user->code}}</td>
                                                <td>{{$user->phone}}</td>
                                                <td>{{$user->email}}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">@if(session()->get('locale') == "en") Big Store Table @else @lang('messages.Big Store Table') @endif</h1>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                            <?php
                                $countProd = count($allBigStores);
                            ?>
                            @if(session()->get('locale') == "en") Added Big Store @else @lang('messages.Added Big Store') @endif
                                {{$countProd}} 
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>@if(session()->get('locale') == "en") Name @else @lang('messages.Name') @endif</th>
                                            <th>@if(session()->get('locale') == "en") Info @else @lang('messages.Info') @endif</th>
                                            <th>@if(session()->get('locale') == "en") Status @else @lang('messages.Status') @endif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allBigStores as $val)
                                            @if($val->status === '001')
                                            <tr>
                                                <td>{{$val->name}}</td>
                                                <?php $categories = count($val->categories);?>
                                                <td>{{$categories}}</td>
                                            </tr>
                                            @else
                                                <tr>
                                                    <td>{{$val->name}}</td>
                                                    <?php $categories = count($val->categories);?>
                                                        <td>{{$categories}}</td>
                                                    <td>
                                                    <button type="button" class="btn btn-danger del_category">
                                                    @if(session()->get('locale') == "en") delete @else @lang('messages.delete') @endif
                                                        <input class="category_id" value='{{$val->id}}' type="hidden">
                                                    </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    @foreach($val->bigStoreImages as $item)
                                                        <td class="d-flex flex-row">
                                                            <div>
                                                            <button type="button" class="btn btn-danger del_photo_bigStore">X
                                                                <input class="big_store_id" value='{{$val->id}}' type="hidden">
                                                                <input class="cat_poto_name" value='{{$item->name}}' type="hidden">
                                                            </button>
                                                               <img width="70px" height="50px" src="{{ asset('Big_Store_images'.'/'.$val->photoFileName.'/'.$item->name) }}" alt=""> 
                                                            </div>
                                                            <div>
                                                                <form action="{{ route('set.bigStore.banner') }}" method="POST" class="form-horizontal" role="form">
                                                                @csrf
                                                                    @if($item->banner == 'on')
                                                                        <input type="checkbox" checked name="banner" class="form-control" />
                                                                    @else
                                                                        <input type="checkbox" name="banner" class="form-control" />
                                                                    @endif
                                                                    <input type="hidden" value="{{$item->id}}" name="photo_id" class="form-control"/>
                                                                    <input type="hidden" value="{{$val->id}}" name="big_store_id" class="form-control"/>
                                                                    <button type="submit" class="btn btn-primary">@if(session()->get('locale') == "en") Set as banner @else @lang('messages.Set as banner') @endif</button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <form action="{{ route('add.bigStore_photos') }}" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">
                                                        @csrf
                                                            <input type="file" name="image[]" multiple="multiple" class="form-control" id="customFile_ajax" />
                                                            <input type="hidden" value="{{$val->id}}" name="big_store_id" multiple="multiple" class="form-control" id="customFile_ajax" />
                                                            <button type="submit" class="btn btn-primary">@if(session()->get('locale') == "en") Send @else @lang('messages.Send') @endif</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">@if(session()->get('locale') == "en") Catagory Table @else @lang('messages.Catagory Table') @endif</h1>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                            <?php
                                $countProd = count($allCategory);
                            ?>
                            @if(session()->get('locale') == "en") Added Category @else @lang('messages.Added Category') @endif
                                {{$countProd}} 
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>@if(session()->get('locale') == "en") title @else @lang('messages.title') @endif</th>
                                            <th>@if(session()->get('locale') == "en") Number of available products @else @lang('messages.Number of available products') @endif</th>
                                            <th>@if(session()->get('locale') == "en") action @else @lang('messages.action') @endif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allCategory as $val)
                                            @if($val->status == '001')
                                            <tr>
                                                    <td>{{$val->title}}</td>
                                                    <?php $product = count($val->products);?>
                                                    <td>{{$product}}</td>
                                                    
                                            </tr>
                                            @else
                                                <tr>
                                                    <td>{{$val->title}}</td>
                                                    <?php $product = count($val->products);?>
                                                        <td>{{$product}}</td>
                                                    <td>
                                                    <button type="button" class="btn btn-danger del_category">
                                                    @if(session()->get('locale') == "en") delete @else @lang('messages.delete') @endif
                                                        <input class="category_id" value='{{$val->id}}' type="hidden">
                                                    </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    @foreach($val->categoryImages as $item)
                                                        <td class="d-flex flex-row">
                                                            <div>
                                                            <button type="button" class="btn btn-danger del_photo_category">X
                                                                <input class="category_id" value='{{$val->id}}' type="hidden">
                                                                <input class="cat_poto_name" value='{{$item->name}}' type="hidden">
                                                            </button>
                                                               <img width="70px" height="50px" src="{{ asset('Category_images'.'/'.$val->photoFileName.'/'.$item->name) }}" alt=""> 
                                                            </div>
                                                            <div>
                                                                <form action="{{ route('set.category.banner') }}" method="POST" class="form-horizontal" role="form">
                                                                @csrf
                                                                    @if($item->banner == 'on')
                                                                        <input type="checkbox" checked name="banner" class="form-control" />
                                                                    @else
                                                                        <input type="checkbox" name="banner" class="form-control" />
                                                                    @endif
                                                                    <input type="hidden" value="{{$item->id}}" name="photo_id" class="form-control"/>
                                                                    <input type="hidden" value="{{$val->id}}" name="category_id" class="form-control"/>
                                                                    <button type="submit" class="btn btn-primary">@if(session()->get('locale') == "en") Set as banner @else @lang('messages.Set as banner') @endif</button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <form action="{{ route('add.category_photos') }}" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">
                                                        @csrf
                                                            <input type="file" name="image[]" multiple="multiple" class="form-control" id="customFile_ajax" />
                                                            <input type="hidden" value="{{$val->id}}" name="category_id" multiple="multiple" class="form-control" id="customFile_ajax" />
                                                            <button type="submit" class="btn btn-primary">@if(session()->get('locale') == "en") Send @else @lang('messages.Send') @endif</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">@if(session()->get('locale') == "en") Sub Catagory Table @else @lang('messages.Sub Catagory Table') @endif</h1>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                            <?php
                                $countProd = count($allSubCategory);
                            ?>
                            @if(session()->get('locale') == "en") Added Sub Category @else @lang('messages.Added Sub Category') @endif
                                {{$countProd}} 
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>@if(session()->get('locale') == "en") title @else @lang('messages.title') @endif</th>
                                            <th>@if(session()->get('locale') == "en") Number of available products @else @lang('messages.Number of available products') @endif</th>
                                            <th>@if(session()->get('locale') == "en") action @else @lang('messages.action') @endif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allSubCategory as $val)
                                            @if($val->status == '001')
                                                <tr>
                                                        <td>{{$val->title}}</td>
                                                        <?php $product = count($val->products);?>
                                                        <td>{{$product}}</td>
                                                        
                                                </tr>
                                            @else
                                                <tr>
                                                    <td>{{$val->title}}</td>
                                                    <?php $product = count($val->products);?>
                                                        <td>{{$product}}</td>
                                                    <td>
                                                    <button type="button" class="btn btn-danger del_sub_category">
                                                    @if(session()->get('locale') == "en") delete @else @lang('messages.delete') @endif
                                                        <input class="sub_category_id" value='{{$val->id}}' type="hidden">
                                                    </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    @foreach($val->subCategoryImages as $item)
                                                        <td class="d-flex flex-row">
                                                            <div>
                                                            <button type="button" class="btn btn-danger del_photo_sub_category">X
                                                                <input class="sub_category_id" value='{{$val->id}}' type="hidden">
                                                                <input class="sub_cat_poto_name" value='{{$item->name}}' type="hidden">
                                                            </button>
                                                                <img width="70px" height="50px" src="{{ asset('Sub_category_images'.'/'.$val->photoFileName.'/'.$item->name) }}" alt="">
                                                            </div>
                                                       
                                                        <div>
                                                            <form action="{{ route('set.subCategory.banner') }}" method="POST" class="form-horizontal" role="form">
                                                            @csrf
                                                                @if($item->banner == 'on')
                                                                    <input type="checkbox" checked name="banner" class="form-control" />
                                                                @else
                                                                    <input type="checkbox" name="banner" class="form-control" />
                                                                @endif
                                                                <input type="hidden" value="{{$item->id}}" name="photo_id" class="form-control"/>
                                                                <input type="hidden" value="{{$val->id}}" name="sub_category_id" class="form-control"/>
                                                                <button type="submit" class="btn btn-primary">@if(session()->get('locale') == "en") Set as banner @else @lang('messages.Set as banner') @endif</button>
                                                            </form>
                                                        </div>
                                                        </td>
                                                    @endforeach
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <form action="{{ route('add.sub_category_photos') }}" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">
                                                        @csrf
                                                            <input type="file" name="image[]" multiple="multiple" class="form-control" id="customFile_ajax" />
                                                            <input type="hidden" value="{{$val->id}}" name="sub_category_id" multiple="multiple" class="form-control" id="customFile_ajax" />
                                                            <button type="submit" class="btn btn-primary">@if(session()->get('locale') == "en") Send @else @lang('messages.Send') @endif</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- -------------------- -->

                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">@if(session()->get('locale') == "en") Child Sub Catagory Table @else @lang('messages.Child Sub Catagory Table') @endif</h1>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                            <?php
                                $countProd = count($allChildSubCategory);
                            ?>
                            @if(session()->get('locale') == "en") Added Child Sub Category @else @lang('messages.Added Child Sub Category') @endif
                                {{$countProd}} 
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>@if(session()->get('locale') == "en") title @else @lang('messages.title') @endif</th>
                                            <th>@if(session()->get('locale') == "en") Number of available products @else @lang('messages.Number of available products') @endif</th>
                                            <th>@if(session()->get('locale') == "en") action @else @lang('messages.action') @endif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allChildSubCategory as $val)
                                            @if($val->status == '001')
                                                <tr>
                                                        <td>{{$val->title}}</td>
                                                        <?php $product = count($val->products);?>
                                                        <td>{{$product}}</td>
                                                        
                                                </tr>
                                            @else
                                                <tr>
                                                    <td>{{$val->title}}</td>
                                                    <?php $product = count($val->products);?>
                                                        <td>{{$product}}</td>
                                                    <td>
                                                    <button type="button" class="btn btn-danger del_sub_category">
                                                    @if(session()->get('locale') == "en") delete @else @lang('messages.delete') @endif
                                                        <input class="sub_category_id" value='{{$val->id}}' type="hidden">
                                                    </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    @foreach($val->ChildsubCategoryImages as $item)
                                                        <td class="d-flex flex-row">
                                                            <div>
                                                            <button type="button" class="btn btn-danger del_photo_child_sub_category">X
                                                                <input class="child_sub_category_id" value='{{$val->id}}' type="hidden">
                                                                <input class="sub_cat_poto_name" value='{{$item->name}}' type="hidden">
                                                            </button>
                                                                <img width="70px" height="50px" src="{{ asset('Child_sub_category_images'.'/'.$val->photoFileName.'/'.$item->name) }}" alt="">
                                                            </div>
                                                        <div>
                                                            <form action="{{ route('set.childSubCategory.banner') }}" method="POST" class="form-horizontal" role="form">
                                                            @csrf
                                                                @if($item->banner == 'on')
                                                                    <input type="checkbox" checked name="banner" class="form-control" />
                                                                @else
                                                                    <input type="checkbox" name="banner" class="form-control" />
                                                                @endif
                                                                <input type="hidden" value="{{$item->id}}" name="photo_id" class="form-control"/>
                                                                <input type="hidden" value="{{$val->id}}" name="child_sub_category_id" class="form-control"/>
                                                                <button type="submit" class="btn btn-primary">@if(session()->get('locale') == "en") Set as banner @else @lang('messages.Set as banner') @endif</button>
                                                            </form>
                                                        </div>
                                                        </td>
                                                    @endforeach
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <form action="{{ route('add.child_sub_category_photos') }}" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">
                                                        @csrf
                                                            <input type="file" name="image[]" multiple="multiple" class="form-control" id="customFile_ajax" />
                                                            <input type="hidden" value="{{$val->id}}" name="child_sub_category_id" multiple="multiple" class="form-control" id="customFile_ajax" />
                                                            <button type="submit" class="btn btn-primary">@if(session()->get('locale') == "en") Send @else @lang('messages.Send') @endif</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}

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

                {{-- <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">@if(session()->get('locale') == "en") Store Table @else @lang('messages.Store Table') @endif</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                            <?php
                                $countStore = count($allStores);
                            ?>
                            @if(session()->get('locale') == "en") Registered store @else @lang('messages.Registered store') @endif
                                {{$countStore}}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>@if(session()->get('locale') == "en") name @else @lang('messages.name') @endif</th>
                                            <th>@if(session()->get('locale') == "en") status @else @lang('messages.status') @endif</th>
                                            <th>@if(session()->get('locale') == "en") Information @else @lang('messages.Information') @endif</th>
                                            <th>@if(session()->get('locale') == "en") lng @else @lang('messages.lng') @endif</th>
                                            <th>@if(session()->get('locale') == "en") lat @else @lang('messages.lat') @endif</th>
                                            <th>@if(session()->get('locale') == "en") store owner @else @lang('messages.store owner') @endif</th>
                                        </tr>
                                       
                                    </thead>
                                    <tbody>
                                        @foreach($allStores as $store)
                                            <tr>
                                                <td>{{$store->name}}</td>
                                                <td>{{$store->info}}</td>
                                                <td>
                                                    @if($store->status == 0)
                                                        @if(session()->get('locale') == "en") Deactive @else @lang('messages.Deactive') @endif
                                                    @elseif($store->status == 1)
                                                        @if(session()->get('locale') == "en") Active @else @lang('messages.Active') @endif
                                                    @endif
                                                </td>
                                                <td>{{$store->lat}}</td>
                                                <td>{{$store->lng}}</td>
                                                <td>
                                                    <?php
                                                        $user = App\Models\User::where('id',$store->user_id)->first();
                                                    ?>
                                                    {{$user->name}}
                                                </td>
                                                </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">@if(session()->get('locale') == "en") Option Table @else @lang('messages.Option Table') @endif</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                            <?php
                                $countOption = count($alloptions);
                            ?>
                            @if(session()->get('locale') == "en") Registered option @else @lang('messages.Registered option') @endif
                                {{$countOption}}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>@if(session()->get('locale') == "en") name @else @lang('messages.name') @endif</th>
                                            <th>@if(session()->get('locale') == "en") price @else @lang('messages.price') @endif</th>
                                            <th>@if(session()->get('locale') == "en") Information @else @lang('messages.Information') @endif</th>
                                            <th>@if(session()->get('locale') == "en") status @else @lang('messages.status') @endif</th>
                                            <th>@if(session()->get('locale') == "en") Product @else @lang('messages.Product') @endif </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($alloptions as $option)
                                            
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
                                           
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
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
    <!-- <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script> -->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>
    <!-- <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script> -->
    <!-- <script src="{{asset('js/demo/datatables-demo.js')}}"></script> -->
    
    <script type="text/javascript">
  
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    // $(".del_photo").click(function(){
    //     var xbtn = $(this).children();
    //     var prod_id = $($(this).children([0])[0]).val();
    //     var prod_numb = $($(this).children([1])[1]).val();
    //     var photo_name = $($(this).children([2])[2]).val();
        
        
    //     $.ajax({
    //         type: "POST",
    //         url:"{{ route('delete.photo') }}",
    //         data:{prod_id:prod_id,prod_numb:prod_numb,photo_name:photo_name},
    //         success:function(data){
    //             // console.log(data);
    //             location.reload();
    //         }
    //     });
    // });
    // $(".del_photo_option").click(function(){
    //     var option_id = $($(this).children([0])[0]).val();
    //     var option_poto_name = $($(this).children([1])[1]).val();
        
    //     $.ajax({
    //         type: "POST",
    //         url:"{{ route('delete.option.photo') }}",
    //         data:{option_id:option_id,option_poto_name:option_poto_name},
    //         success:function(data){
    //             // console.log(data);
    //             location.reload();
    //         }
    //     });
    // });
    // $(".del_photo_category").click(function(){
    //     var xbtn = $(this).children();
    //     var category_id = $($(this).children([0])[0]).val();
    //     var cat_poto_name = $($(this).children([1])[1]).val();
        
    //     $.ajax({
    //         type: "POST",
    //         url:"{{ route('delete.category.photo') }}",
    //         data:{category_id:category_id,cat_poto_name:cat_poto_name},
    //         success:function(data){
    //             // console.log(data);
    //             location.reload();
    //         }
    //     });
    // });
    // $(".del_photo_bigStore").click(function(){
    //     var xbtn = $(this).children();
    //     var big_store_id = $($(this).children([0])[0]).val();
    //     var cat_poto_name = $($(this).children([1])[1]).val();
        
    //     $.ajax({
    //         type: "POST",
    //         url:"{{ route('delete.bigstore.photo') }}",
    //         data:{big_store_id:big_store_id,cat_poto_name:cat_poto_name},
    //         success:function(data){
    //             // console.log(data);
    //             location.reload();
    //         }
    //     });
    // });
    // $(".del_photo_sub_category").click(function(){
    //     var xbtn = $(this).children();
    //     var sub_category_id = $($(this).children([0])[0]).val();
    //     var sub_cat_poto_name = $($(this).children([1])[1]).val();
        
    //     $.ajax({
    //         type: "POST",
    //         url:"{{ route('delete.subCategory.photo') }}",
    //         data:{sub_category_id:sub_category_id,sub_cat_poto_name:sub_cat_poto_name},
    //         success:function(data){
    //             // console.log(data);
    //             location.reload();
    //         }
    //     });
    // });
    // $(".del_photo_child_sub_category").click(function(){
    //     var xbtn = $(this).children();
    //     var child_sub_category_id = $($(this).children([0])[0]).val();
    //     var sub_cat_poto_name = $($(this).children([1])[1]).val();
        
    //     $.ajax({
    //         type: "POST",
    //         url:"{{ route('delete.childSubCategory.photo') }}",
    //         data:{child_sub_category_id:child_sub_category_id,sub_cat_poto_name:sub_cat_poto_name},
    //         success:function(data){
    //             // console.log(data);
    //             location.reload();
    //         }
    //     });
    // });
    // $(".del_category").click(function(){
    //     var category_id = $($(this).children([0])[0]).val();
    //     // console.log(category_id);
    //     $.ajax({
    //         type: "POST",
    //         url:"{{ route('delete.category') }}",
    //         data:{category_id:category_id},
    //         success:function(data){
    //             // console.log(data);
    //             // if (data !== null) {
    //             //    $('.append_aparam').html('<p>Please delete these products first</p>')
    //             // }
    //             location.reload();
    //         }
    //     });
    // });
    // $(".del_sub_category").click(function(){
    //     var sub_category_id = $($(this).children([0])[0]).val();
    //     // console.log(category_id);
    //     $.ajax({
    //         type: "POST",
    //         url:"{{ route('delete.subCategory') }}",
    //         data:{sub_category_id:sub_category_id},
    //         success:function(data){
    //             // console.log(data);
    //             // if (data !== null) {
    //             //    $('.append_aparam').html('<p>Please delete these products first</p>')
    //             // }
    //             location.reload();
    //         }
    //     });
    // });
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