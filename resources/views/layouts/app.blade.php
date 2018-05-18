<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KFMakes') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="{{ URL::asset('js/custom.js') }}"></script>    
</head>
<body>
    <div class="container">   
        <nav class="navbar navbar-expand-lg sticky-top navbar-light bg-light">
            
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'KFMakes') }}
                </a>
            </div>
                
            <div class="collapse navbar-collapse " id="app-navbar-collapse">                    
                <ul class="navbar-nav ml-auto">
                    @if (!Auth::guest())    
                         <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="fa fa-cog"></span></a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item mr-auto" href="/user">Usuarios</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">                                
                                <a href="{{ route('logout') }}" id="logout" class="dropdown-item" role="button"
                                    onclick="event.preventDefault(); 
                                        document.getElementById('logout-form').submit();">
                                    Salir
                                </a>                                        

                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </li>                                               
                    @endif
                </ul>
            </div>
        </nav>

        @yield('navigation')        
        
        <div class="container" id="content">
            @yield('modal-add')
            @yield('modal-delete')
            @yield('modal-edit')
            @yield('modal-search')
            @yield('modal-reset')
            <div class="col-md-12">
                @if(Session::has('flash_message'))
                    <div class="alert alert-success"><span class="glyphicon glyphicon-ok-sign"></span><em> {{ session('flash_message') }}</em></div>
                @endif
                @if(Session::has('flash_message_not'))
                    <div class="alert alert-danger"><span class="glyphicon glyphicon-remove-sign"></span><em> {{ session('flash_message_not') }}</em></div>
                @endif
                @if(Session::has('flash_message_info'))
                    <div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span><em> {{ session('flash_message_info') }}</em></div>
                @endif
            </div>
            @yield('content')         
        </div>
        @yield('script') 
    </div>
</body>
</html>
