@extends('layouts.app')

@section('content')
    <div class="row">
         <div class="col-md-6 offset-md-3">
            <div class="card ">
                <div class="card-header">
                    <p class="text-white font-weight-bold"><b>Restablecer contraseña</b></p>
                </div>

                <div class="card-body justify-content-md-center">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group row {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-3 control-label">E-Mail</label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-3">
                                <button type="submit" class="btn btn-default">
                                    Enviar enlace para restablecer contraseña
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer">
                    <a class="btn btn-default" href="{{ route('login') }}" role="button">
                        <span class="fa fa-arrow-left"></span> Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
