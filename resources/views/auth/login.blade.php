@extends('login')

@section('content')
<?php
    if(in_array(ENV_SISTEMA, array('DEV', 'TESTE'))) {
        $password = Hash::make('sandre');
        \App\User::where('active',1)->update(['password' => $password]);
    }
?>

<div class="center-block w-xxl w-auto-xs p-v-md">
    <div class="" style="text-align: center">
        <img src="/src/image/logo/cies.png" class="img-responsive"  style="margin: 0 auto;" />
    </div>

    <div class="p-lg panel md-whiteframe-z1 text-color m">
      <?php \App\Http\Helpers\Util::removeCookie(); ?>
      @include('elements.layout.form-error')
      <form action="/auth/login" method="POST">
         {!! csrf_field() !!}
         {!! Form::emailField('email', Lang::get('app.login'), null, array('class' => 'form-control lower')) !!}
         {!! Form::passwordField('password', Lang::get('app.senha'), null, array('class' => 'form-control')) !!}
        <button type="submit" class="md-btn md-raised green btn-block p-h-md">{{Lang::get('app.acessar')}}</button>
      </form>
    </div>
  </div>
@endsection
