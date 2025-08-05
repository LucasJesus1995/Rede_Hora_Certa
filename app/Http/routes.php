<?php

$api = app('Dingo\Api\Routing\Router');

Route::get('/', 'Auth\AuthController@getLogin');
Route::get('login', 'Auth\AuthController@getLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::controller('auth/perfil', 'Auth\PerfilController');

Route::get('/bi', 'BIController@getIndex');
Route::get('/nossos-numeros', 'ServiceController@nossosNumeros');

Route::get('/unauthorized', 'UnauthorizedController@getIndex');
Route::get('/labs', 'LabsController@getIndex');
Route::get('/services/cns/{cns}', 'ServiceController@getCNS');
Route::get('/services/cep/{cep}', 'ServiceController@getCEP');
Route::get('/services/siga/pacientes-pesquisar-cns/{cns}', 'ServiceController@getSigaPacientePesquisarByCNS');
Route::post('/services/condutas-tipo-atendimento', 'ServiceController@getCondutasTipoAtendimento');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::group(['prefix' => 'cron'], function () {
    Route::controller('/tempo', 'Cron\TempoController');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'acl:manager', 'perfil']], function () {
    Route::get('/', 'PainelController@getIndex');

    Route::get('/dashboard', 'PainelController@getDashboard');

    Route::get('/agendas/prontuario/{paciente}', 'AgendaController@prontuario');
    Route::get('/cirurgico/list/{clear?}/{excel?}', 'CirurgicoController@index');
    Route::get('/cirurgico/agendas/{paciente}/{dataAgenda}', 'CirurgicoController@agendas');
    Route::post('/cirurgico/save', 'CirurgicoController@save');
    Route::post('/cirurgico/filtro', 'CirurgicoController@filtro');
    Route::get('/cirurgico/filtro', 'CirurgicoController@filtro');
    Route::get('/cirurgico/contatos/{paciente}/{agenda}', 'CirurgicoController@getContatoPaciente');
    Route::post('/cirurgico/contatos-salvar/', 'CirurgicoController@contatoSalvar');
    Route::get('/cirurgico/edit-contato/{id}', 'CirurgicoController@editContato');

    Route::controller('/painel', 'PainelController');
    Route::controller('/tipos', 'TipoController');
    Route::controller('/unidades', 'UnidadesController');
    Route::controller('/insumos', 'InsumosController');
    Route::controller('/arenas', 'ArenasController');
    Route::controller('/arena-equipamentos', 'Admin\ArenaEquipamentosController');
    Route::controller('/empresas', 'EmpresasController');
    Route::controller('/pais', 'PaisController');
    Route::controller('/linha-cuidado', 'LinhaCuidadoController');
    Route::controller('/procedimentos', 'ProcedimentosController');
    Route::controller('/procedimentos-medicos', 'ProcedimentosMedicosController');
    Route::controller('/cbo', 'CboController');
    Route::controller('/profissionais', 'ProfissionaisController');
    Route::controller('/pacientes', 'PacientesController');
    Route::controller('/cidades', 'CidadesController');
    Route::controller('/agendas', 'AgendaController');
    Route::controller('/usuarios', 'UsuariosController');
    Route::controller('/anamnese-perguntas', 'AnamnesePerguntasController');
    Route::controller('/atendimento', 'AtendimentoController');
    Route::controller('/impressao', 'ImpressaoController');
    Route::controller('/medicamentos', 'MedicamentosController');
    Route::controller('/laudo-medico', 'LaudoMedicoController');
    Route::controller('/relatorio', 'RelatorioController');
    Route::controller('/labs', 'LabsController');
    Route::controller('/estabelecimento', 'EstabelecimentoController');
    Route::controller('/perfil', 'PerfilController');
    Route::controller('/importacao', 'ImportacaoController');
    Route::controller('/faturamento', 'Admin\FaturamentoController');
    Route::controller('/faturamento-procedimento', 'Admin\FaturamentoProcedimentoController');
    Route::controller('/lotes', 'Admin\LoteController');
    Route::controller('/cid', 'Admin\CidController');
    Route::controller('/exportacao', 'Admin\ExportacaoController');
    Route::controller('/contratos', 'Admin\ContratoController');
    Route::controller('/programas', 'Admin\ProgramasController');
    Route::controller('/tipo-atendimento', 'Admin\TiposAtendimentoController');
    Route::controller('/condutas', 'Admin\CondutasController');
    Route::controller('/guias', 'Admin\GuiasController');
    Route::controller('/agendamento-tipo', 'Admin\AgendamentoTipoController');
    Route::controller('/atestado', 'AtestadoController');
    Route::controller('/grupos', 'GruposController');
    Route::controller('/sub-grupos', 'SubGruposController');
    Route::controller('/organizacao', 'OrganizacaoController');
    Route::controller('/exames', 'Admin\ExamesController');
    Route::controller('/ofertas', 'Admin\OfertasController');

    Route::controller('/monitoramento/crons', 'Monitoramento\CronController');

    Route::controller('/cirugia-linha-cuidado', 'Admin\Cirugia\LinhaCuidadoController');

    Route::controller('/relatorios/receita-arena', 'Admin\Relatorios\ReceitaArenaController');
    Route::controller('/relatorios/gordura-detalhado', 'Admin\Relatorios\GorduraDetalhadoController');
    Route::controller('/relatorios/pacientes-atendimento', 'Admin\Relatorios\PacientesAtendimentoController');
    Route::controller('/relatorios/importacao-agendas', 'Admin\Relatorios\ImportacaoAgendasController');
    Route::controller('/relatorios/importacao-agendas-mensal', 'Admin\Relatorios\ImportacaoAgendasMensalController');
    Route::controller('/relatorios/pacientes-faltas', 'Admin\Relatorios\PacientesFaltasController');

    Route::controller('/listagem/atendimento-pacientes', 'Admin\Listagem\AtendimentoPacienteController');
    Route::controller('/listagem/pacientes-importados', 'Admin\Listagem\PacientesImportadosController');


    //Estoque

    

    Route::group(['prefix' => 'estoque'], function () {
        Route::get('adicionar/{msg?}', 'EstoqueController@adicionar')->name('estoque.adicionar');
        Route::post('store', 'EstoqueController@store')->name('estoque.store');
        Route::get('transferir/{arena?}/{mensagem?}', 'EstoqueController@transferir')->name('estoque.transferir');
        Route::get('carregar-lotes/{produto}/{origem}', 'EstoqueController@carregarLotesQuantidade');
        Route::post('transferir-store', 'EstoqueController@transferirStore')->name('estoque.transferir-store');
        Route::get('receber/{status?}', 'EstoqueController@receber')->name('estoque.receber');
        Route::post('receber-confirma', 'EstoqueController@receberConfirma')->name('estoque.receber-confirma');
        Route::get('receber-store/{id}', 'EstoqueController@receberStore')->name('estoque.receber-store');
        Route::get('{produto}/ver-estoques', 'EstoqueController@verEstoques');
        Route::get('arenas', 'EstoqueController@arenas')->name('estoque.arenas');
        Route::get('/{arena}/arenas-estoque/{excel?}', 'EstoqueController@arenasEstoque');
        Route::get('baixar/{status?}', 'EstoqueController@baixar')->name('estoque.baixar');
        Route::get('/{arena}/arena-produtos', 'EstoqueController@arenaProdutos');
        Route::post('baixar-store', 'EstoqueController@baixarStore')->name('estoque.baixar-store');
        Route::get('transferencias', 'EstoqueController@transferencias')->name('estoque.transferencias');
        Route::get('ver-transferencias/{arena}/{data_transferencia}/{pdf?}', 'EstoqueController@verTransferencias');
        Route::get('ver-transferencias-receber/{arena}/{data_transferencia}/{pdf?}', 'EstoqueController@verTransferenciasReceber');

        Route::group(['prefix' => 'produtos'], function () {
            Route::get('/', 'ProdutosController@index')->name('produtos.index');
            Route::get('create', 'ProdutosController@create')->name('produtos.create');
            Route::post('store', 'ProdutosController@store')->name('produtos.store');
            Route::get('/{id}/edit', 'ProdutosController@edit')->name('produtos.edit');
            Route::put('/update', 'ProdutosController@update')->name('produtos.update');
        });
    
        Route::group(['prefix' => 'produtos-categorias'], function () {
            Route::get('/', 'ProdutosCategoriasController@index')->name('produtos_categorias.index');
            Route::get('create', 'ProdutosCategoriasController@create')->name('produtos_categorias.create');
            Route::post('store', 'ProdutosCategoriasController@store')->name('produtos_categorias.store');
            Route::get('/{id}/edit', 'ProdutosCategoriasController@edit')->name('produtos_categorias.edit');
            Route::put('/update', 'ProdutosCategoriasController@update')->name('produtos_categorias.update');
        });
    
        Route::group(['prefix' => 'relatorios'], function () {
            Route::get('baixa', 'EstoqueRelatoriosController@baixa')->name('estoque.relatorios.baixa');
            Route::post('baixa_excel', 'EstoqueRelatoriosController@baixaExcel')->name('estoque.relatorios.baixa_excel');
            Route::get('transferencias', 'EstoqueRelatoriosController@transferencias')->name('estoque.relatorios.transferencias');
            Route::post('transferencias_excel', 'EstoqueRelatoriosController@transferenciasExcel')->name('estoque.relatorios.transferencias_excel');
            Route::get('produtos-vencimentos', 'EstoqueRelatoriosController@produtosVencimentos')->name('estoque.relatorios.produtos_vencimentos');
            Route::post('vencimentos_excel', 'EstoqueRelatoriosController@produtosVencimentosExcel')->name('estoque.relatorios.vencimentos_excel');
            Route::get('entrada', 'EstoqueRelatoriosController@produtosEntrada')->name('estoque.relatorios.entrada');
            Route::post('entrada_excel', 'EstoqueRelatoriosController@entradaExcel')->name('estoque.relatorios.entrada_excel');
            Route::get('produtos', 'EstoqueRelatoriosController@produtos')->name('estoque.relatorios.produtos');
            Route::get('fornecedores_excel', 'EstoqueRelatoriosController@fornecedoresExcel')->name('estoque.relatorios.fornecedores');
        });

        Route::group(['prefix' => 'solicitacoes'], function () {
            Route::get('/', 'ProdutosSolicitacoesController@index')->name('estoque.solicitacoes.index');
            Route::get('create/{arena?}', 'ProdutosSolicitacoesController@create')->name('estoque.solicitacoes.create');
            Route::post('store', 'ProdutosSolicitacoesController@store')->name('estoque.solicitacoes.store');
            Route::get('{id}/edit', 'ProdutosSolicitacoesController@edit')->name('estoque.solicitacoes.edit');
            Route::put('update', 'ProdutosSolicitacoesController@update')->name('estoque.solicitacoes.update');
            Route::get('{id}/verificar', 'ProdutosSolicitacoesController@verificar')->name('estoque.solicitacoes.verificar');
            Route::post('confirmar', 'ProdutosSolicitacoesController@confirmar')->name('estoque.solicitacoes.confirmar');

        });

        Route::group(['prefix' => 'fornecedores'], function () {
            Route::get('/', 'ProdutosFornecedoresController@index')->name('estoque.fornecedores.index');
            Route::get('/create', 'ProdutosFornecedoresController@create')->name('estoque.fornecedores.create');
            Route::post('/store', 'ProdutosFornecedoresController@store')->name('estoque.fornecedores.store');
            Route::get('/{id}/edit', 'ProdutosFornecedoresController@edit')->name('estoque.fornecedores.edit');
            Route::put('/update', 'ProdutosFornecedoresController@update')->name('estoque.fornecedores.update');
        });

        Route::group(['prefix' => 'fabricantes'], function () {
            Route::get('/', 'ProdutosFabricantesController@index')->name('estoque.fabricantes.index');
            Route::get('/create', 'ProdutosFabricantesController@create')->name('estoque.fabricantes.create');
            Route::post('/store', 'ProdutosFabricantesController@store')->name('estoque.fabricantes.store');
            Route::get('/{id}/edit', 'ProdutosFabricantesController@edit')->name('estoque.fabricantes.edit');
            Route::put('/update', 'ProdutosFabricantesController@update')->name('estoque.fabricantes.update');
        });



    
    });

});

$api->version('v1', ['middleware' => ['security-services']], function ($api) {
    $api->post('/', 'App\Http\Controllers\API\LoginController@postLogin');
    $api->get('/atendimentos', 'App\Http\Controllers\API\AtendimentosController@getAtendimentos');
    $api->get('/atendimentos/{id}', 'App\Http\Controllers\API\AtendimentosController@getAtendimentosDetalhes');
});