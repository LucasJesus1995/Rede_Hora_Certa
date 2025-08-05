@if(in_array($agenda->linha_cuidado,[9]))
    I83.1 - Varizes dos membros inferiores com inflamação
@endif

@if(in_array($sub_especialidade,[1]))
    H25 - Catarata senil
@endif

@if(in_array($sub_especialidade,[2]))
    H11.0 - Pterígio
@endif

@if(in_array($sub_especialidade,[4]))
    (&nbsp;&nbsp;&nbsp;&nbsp;) K42 - Hérnia umbilical<br />
    (&nbsp;&nbsp;&nbsp;&nbsp;) K43 - Hérnia ventral
@endif

@if(in_array($agenda->linha_cuidado,[47]))
    Z30.2 - Esterilização<br />
@endif