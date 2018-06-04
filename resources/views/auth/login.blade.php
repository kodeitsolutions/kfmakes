@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card ">
                <div class="card-header">
                    {{--<strong><span class="text-white">Ingresar</span></strong>--}}
                    <p class="text-white font-weight-bold"><b>Ingresar</b></p>
                </div>
                <div class="card-body justify-content-md-center">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group row  {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Contraseña</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-md-6 offset-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Recordarme
                                    </label>
                                </div>                                
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-default ">
                                    Ingresar
                                </button>

                                <a class="btn btn-default" href="{{ route('password.request') }}" role="button">
                                    ¿Olvidaste la contraseña?
                                </a>
                            </div>
                        </div>
                    </form>         
                </div> 
                <div class="card-footer">Version 1.2.1</div>
            </div>            
        </div>
    </div>
@endsection
