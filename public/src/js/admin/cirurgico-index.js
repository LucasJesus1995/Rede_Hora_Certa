function agendasIndex(paciente, dataAgenda){
    $.get( "/admin/cirurgico/agendas/" + paciente + '/' + dataAgenda, function( data ) {
        
        $('#conteudoAgendas').html( data );
        $('#modalAgendas').modal()
        // $( ".result" ).html( data );
    });
}

function contatos(paciente, agenda){
    $.get( "/admin/cirurgico/contatos/" + paciente + '/' + agenda, function( data ) {
        
        $('#conteudoContatos').html( data );
        $('#modalContatos').modal()
        // $( ".result" ).html( data );
    });
}

function salvarContato(){
    paciente = $('#paciente_id').val();
    contato_id = $('#contato_id').val();
    agenda_id = $('#agenda_id').val();
    descricao = $('#descricao').val();

    if(descricao == ''){
        alert('Preencha a descrição!')
    } else {

        if($('#rd_s').is(":checked")){
            status = 's'
        } else {
            status = 'i'
        }

        token = $('#token').val();

        $.post( "/admin/cirurgico/contatos-salvar", { 
            _token: token, 
            paciente: paciente, 
            contato_id: contato_id, 
            agenda: agenda_id, 
            descricao: descricao, 
            status: status 
        })
        .done(function( data ) {
            // alert(data)
            contatos(paciente, agenda_id)
        });

    } 

}

function editarContato(id){    

    $.get( "/admin/cirurgico/edit-contato/" + id, function( data ) {
        console.log(data.agenda)
        $('#agenda_id').val(data.agenda)
        $('#paciente_id').val(data.paciente)
        $('#contato_id').val(data.id)
        $('#descricao').val(data.descricao)
        if(data.status == 's'){
            $('#rd_s').attr('checked', 'checked')
        } else {
            $('#rd_i').attr('checked', 'checked')
        }
    }, "json" );
}