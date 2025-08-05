@extends('login')

@section('content')

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="p-lg panel md-whiteframe-z1 text-color m">
                <div class="alert alert-success justifyfull ">
                    <strong>{!! Auth::user()->name !!}</strong>, você possui um perfil de digitador(a), sendo assim é necessário selecionar em qual <strong> sala</strong> vai trabalhar e com qual <strong>profissional</strong>.
                </div>

                @include('elements.layout.form-error')
                <form action="/auth/perfil" method="POST">
                    {!! csrf_field() !!}

                    {!!Form::selectField('arena', \App\Arenas::Combo(), "Unidade", null, array('class' => 'form-control combo-arena chosen','id'=>'arena'))!!}
                    {!!Form::selectField('linha_cuidado', [], "Especialidade", null, array('class' => 'form-control linha_cuidado combo-linha-cuidado chosen','id' => 'linha_cuidado'))!!}
                    {!!Form::selectField('doctor', [], Lang::get('app.medico'), null, array('class' => 'form-control medico chosen'))!!}

                    <button type="submit" class="md-btn md-raised green btn-block p-h-md">{{Lang::get('app.salvar')}}</button>
                </form>
            </div>

            <div class="" style="text-align: center">
                <img src="/src/image/logo/cies.png" class="img-responsive"  style="margin: 0 auto;" />
            </div>

        </div>
        <div class="col-md-4"></div>
    </div>


@endsection