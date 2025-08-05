DELIMITER $

DROP PROCEDURE IF EXISTS bi $$

CREATE PROCEDURE bi()
BEGIN
    SET @data_processamento = "2019-01-01 00:00:00";

    DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_agendas;
    CREATE TABLE bi_sistema_ciesglobal_org.bi_agendas
    SELECT agendas.id            AS agenda,
           agendas.paciente,
           agendas.linha_cuidado AS especialidade,
           agendas.arena,
           agendas.data,
           agendas.status
    FROM sistema_ciesglobal_org.agendas AS agendas
    WHERE agendas.data >= @data_processamento;


    DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_atendimentos;
    CREATE TABLE bi_sistema_ciesglobal_org.bi_atendimentos
    SELECT atendimento.id AS atendimento,
           atendimento.medico,
           atendimento.agenda
    FROM sistema_ciesglobal_org.atendimento AS atendimento
           JOIN bi_sistema_ciesglobal_org.bi_agendas AS bi_agendas ON bi_agendas.agenda = atendimento.agenda;


    DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_atendimento_procedimentos;
    CREATE TABLE bi_sistema_ciesglobal_org.bi_atendimento_procedimentos
    SELECT atendimento_procedimentos.id                                                     AS atendimento_procedimento,
           atendimento_procedimentos.atendimento,
           atendimento_procedimentos.procedimento,
           atendimento_procedimentos.profissional,
           atendimento_procedimentos.faturado,
           atendimento_procedimentos.quantidade,
           atendimento_procedimentos.multiplicador,
           atendimento_procedimentos.multiplicador_medico,
           (atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) AS QTD_Total,
           atendimento_procedimentos.finalizacao
    FROM sistema_ciesglobal_org.atendimento_procedimentos AS atendimento_procedimentos
           JOIN bi_sistema_ciesglobal_org.bi_atendimentos AS bi_atendimentos ON bi_atendimentos.atendimento = atendimento_procedimentos.atendimento;

     DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_arenas;
     CREATE TABLE bi_sistema_ciesglobal_org.bi_arenas
     SELECT DISTINCT arenas.id AS arena,
                     arenas.nome,
                     arenas.alias
     FROM sistema_ciesglobal_org.arenas AS arenas
     WHERE arenas.ativo = 1;


     DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_especialidades;
     CREATE TABLE bi_sistema_ciesglobal_org.bi_especialidades
     SELECT DISTINCT especialidades.id            AS especialidade,
                     especialidades.abreviacao    AS alias,
                     especialidades.nome,
                     especialidades.especialidade AS tipo
     FROM sistema_ciesglobal_org.linha_cuidado AS especialidades;

     DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_pacientes;
     CREATE TABLE bi_sistema_ciesglobal_org.bi_pacientes
     SELECT DISTINCT pacientes.id AS paciente,
                     pacientes.nome,
                     pacientes.nascimento,
                     pacientes.sexo,
                     pacientes.estado_civil
     FROM sistema_ciesglobal_org.pacientes AS pacientes
            JOIN bi_sistema_ciesglobal_org.bi_agendas AS bi_agendas ON bi_agendas.paciente = pacientes.id
     WHERE pacientes.nascimento != ''
       AND pacientes.nascimento IS NOT NULL
       AND pacientes.cns IS NOT NULL;


     DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_procedimentos;
     CREATE TABLE bi_sistema_ciesglobal_org.bi_procedimentos
     SELECT DISTINCT procedimentos.id AS procedimento,
                     procedimentos.sus,
                     procedimentos.nome,
                     procedimentos.principal
     FROM sistema_ciesglobal_org.procedimentos AS procedimentos
     WHERE procedimentos.ativo = 1;


     DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_profissionais;
     CREATE TABLE bi_sistema_ciesglobal_org.bi_profissionais
     SELECT DISTINCT profissionais.id AS profissional,
                     profissionais.nome,
                     profissionais.cns,
                     profissionais.cpf
     FROM sistema_ciesglobal_org.profissionais AS profissionais;

     DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_contrato_procedimentos;
     CREATE TABLE bi_sistema_ciesglobal_org.bi_contrato_procedimentos
     SELECT DISTINCT contrato_procedimentos.contrato,
                     contrato_procedimentos.procedimento,
                     contrato_procedimentos.valor_unitario,
                     contrato_procedimentos.quantidade
     FROM sistema_ciesglobal_org.contrato_procedimentos AS contrato_procedimentos
     WHERE contrato_procedimentos.contrato = 2
       AND contrato_procedimentos.lote = 7;

     DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_ofertas;
     CREATE TABLE bi_sistema_ciesglobal_org.bi_ofertas
     SELECT ofertas.data,
            ofertas.arena,
            ofertas.linha_cuidado,
            ofertas.profissional,
            ofertas.status,
            SUM(ofertas.quantidade) AS total
     FROM sistema_ciesglobal_org.ofertas AS ofertas
     WHERE ofertas.deleted_at IS NULL
       AND ofertas.status IN (1,2,3,5,6,7,8,22,13,14,16)
     GROUP BY ofertas.arena,
              ofertas.linha_cuidado,
              ofertas.data,
              ofertas.profissional,
              ofertas.status;

     DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_especialidade_procedimentos;
     CREATE TABLE bi_sistema_ciesglobal_org.bi_especialidade_procedimentos
     SELECT procedimentos.id AS procedimento,
            linha_cuidado_procedimentos.linha_cuidado AS especialidade,
            IF(COUNT(1) > 1, 1, 0) AS multiplos
     FROM sistema_ciesglobal_org.procedimentos AS procedimentos
            JOIN sistema_ciesglobal_org.linha_cuidado_procedimentos AS linha_cuidado_procedimentos ON linha_cuidado_procedimentos.procedimento = procedimentos.id
     WHERE procedimentos.ativo = 1
     GROUP BY procedimentos.id,
              procedimentos.nome
     ORDER BY multiplos DESC;

     DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_especialidade_dias_atendimento;
     CREATE TABLE bi_sistema_ciesglobal_org.bi_especialidade_dias_atendimento
     SELECT DISTINCT ofertas.data,
            especialidade.id AS especialidade
     FROM sistema_ciesglobal_org.linha_cuidado AS especialidade
            JOIN sistema_ciesglobal_org.ofertas AS ofertas ON ofertas.linha_cuidado = especialidade.id;

     DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_fpo_indicadores;
     CREATE TABLE bi_sistema_ciesglobal_org.bi_fpo_indicadores
     SELECT  fpo_indicadores.especialidade,
             fpo_indicadores.meta,
             fpo_indicadores.fator_pontuacao
     FROM sistema_ciesglobal_org.fpo_indicadores AS fpo_indicadores;

     DROP TABLE IF EXISTS bi_sistema_ciesglobal_org.bi_fpo_fator_multiplicador;
     CREATE TABLE bi_sistema_ciesglobal_org.bi_fpo_fator_multiplicador
     SELECT  fpo_fator_multiplicador.especialidade,
             fpo_fator_multiplicador.fator
     FROM sistema_ciesglobal_org.fpo_fator_multiplicador AS fpo_fator_multiplicador;

END
$
