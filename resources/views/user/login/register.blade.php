<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
 
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet"/>
   
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back</h1>
                                         
                                        @if(isset($errors) && count($errors) > 0)
                                            <div class="alert alert-danger alert-dismissible fade show">
                                                <ul class="list-unstyled">
                                                    @foreach($errors as $error)
                                                    <li> {{ $error }} </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <form class="user" action="{{ route('user.register') }}" method="POST">
                                    @csrf
                                        <div class="form-group">
                                            <input type="text" name="phone" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Phone">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="name" class="form-control form-control-user"
                                                id="exampleInputName" aria-describedby="emailHelp"
                                                placeholder="Enter Name...">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password">
                                        </div>
                                        
                                        <div class="col-lg-7">
						                    <button class="btn btn-primary btn-user btn-block" type="submit">{{ __("Submit") }}</button>
						                </div>
                                       
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a href="{{ route('facebook.login') }}" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook fa-fw"></i> Login with Facebook
                                        </a>
                                        <a href="{{ route('google.login') }}" class="btn btn-google btn-user btn-block">
                                                <i class="fab fa-google fa-fw"></i> Login with google
                                        </a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('user.login.show') }}">I all ready have an Account!</a>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>

</body>

</html>