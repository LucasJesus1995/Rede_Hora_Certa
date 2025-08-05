@if (session('status'))
    <div class='panel bg-success pos-rlt'>
        <span class='arrow b-success '></span>
        <div class='panel-body'> {{ session('status') }}</div>
    </div>
@endif

@if (session('error'))
    <div class='panel bg-danger pos-rlt'>
        <span class='arrow b-danger '></span>
        <div class='panel-body'>{!! session('error') !!}</div>
    </div>
@endif