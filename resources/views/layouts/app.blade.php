<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KFMakes') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/css/gijgo.min.css" rel="stylesheet"/>
    
    <link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}">  

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/js/gijgo.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/js/messages/messages.es-es.js"></script>

    <script src="{{ URL::asset('js/custom.js') }}"></script>  

</head>
<body>
    <div class="container">   
        <nav class="navbar navbar-expand-lg navbar-light bg-light">           
            
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'KFMakes') }}</a>                      
            
            @if (!Auth::guest())
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-navbar-collapse" ><span class="navbar-toggler-icon"></span></button> 

                <div class="collapse navbar-collapse " id="app-navbar-collapse">                    
                    <ul class="navbar-nav ml-auto">
                        
                         <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="fa fa-cog"></span></a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item mr-auto" href="/user">Usuarios</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a href="/module" class="dropdown-item" role="button"> Cambiar módulo</a>

                                <div class="dropdown-divider"></div>

                                <a href="{{ url('/logout') }}" id="logout" class="dropdown-item" role="button"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Salir
                                </a> 

                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            @endif
        </nav>

        @yield('navigation')        
        
        <div class="container" id="content">
            @yield('modal-add')
            @yield('modal-delete')
            @yield('modal-edit')
            @yield('modal-search')
            @yield('modal-reset')
            @yield('modal-import')
            @yield('modal-export')
            @yield('modal-show')
            @yield('modal-move')
            <div class="col-md-12">
                @if(Session::has('flash_message'))
                    <div class="alert alert-success" role="alert"><span class="fa fa-check-circle"></span><em> {{ session('flash_message') }}</em></div>
                @endif
                @if(Session::has('flash_message_not'))
                    <div class="alert alert-danger" role="alert"><span class="fa fa-times-circle"></span><em> {{ session('flash_message_not') }}</em></div>
                @endif
                @if(Session::has('flash_message_info'))
                    <div class="alert alert-info role="alert"><span class="fa fa-info-circle"></span><em> {{ session('flash_message_info') }}</em></div>
                @endif
            </div>
            @yield('content')         
        </div>
        @yield('script') 
    </div>
</body>
</html>
