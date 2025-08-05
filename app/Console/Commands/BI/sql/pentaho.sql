DROP TABLE IF EXISTS dw_sistema_ciesglobal_org.PROFISSIONAIS;
CREATE TABLE dw_sistema_ciesglobal_org.PROFISSIONAIS
    SELECT
        profissionais.id 			AS PROFISSIONAL,
        profissionais.type 			AS TIPO,
        profissionais.nome 			AS NOME,
        profissionais.cro 			AS CODIGO,
        profissionais.cpf           AS CPF,
        profissionais.ativo			AS STATUS,
        profissionais.created_at 	AS CRIACAO,
        profissionais.updated_at	AS ATUALIZACAO
    FROM sistema_ciesglobal_org.profissionais AS profissionais;

DROP TABLE IF EXISTS dw_sistema_ciesglobal_org.PROCEDIMENTOS;
CREATE TABLE dw_sistema_ciesglobal_org.PROCEDIMENTOS
    SELECT DISTINCT
        procedimentos.id        AS PROCEDIMENTO,
        procedimentos.sus       AS SUS,
        procedimentos.nome      AS NOME,
        procedimentos.principal AS PRINCIPAL,
        procedimentos.ativo     AS ATIVO
    FROM sistema_ciesglobal_org.procedimentos AS procedimentos;

DROP TABLE IF EXISTS dw_sistema_ciesglobal_org.ESPECIALIDADES;
CREATE TABLE dw_sistema_ciesglobal_org.ESPECIALIDADES
    SELECT DISTINCT especialidades.id            AS ESPECIALIDADE,
                    especialidades.abreviacao    AS ALIAS,
                    especialidades.nome          AS NOME,
                    especialidades.especialidade AS TIPO
    FROM sistema_ciesglobal_org.linha_cuidado AS especialidades;

DROP TABLE IF EXISTS dw_sistema_ciesglobal_org.UNIDADES;
CREATE TABLE dw_sistema_ciesglobal_org.UNIDADES
    SELECT DISTINCT arenas.id       AS UNIDADE,
                    arenas.nome     AS NOME,
                    arenas.alias    AS ALIAS,
                    arenas.ativo	AS ATIVO
    FROM sistema_ciesglobal_org.arenas AS arenas

DROP TABLE IF EXISTS dw_sistema_ciesglobal_org.PACIENTES;
CREATE TABLE dw_sistema_ciesglobal_org.PACIENTES
    SELECT DISTINCT
        id              AS PACIENTE,
        nome            AS NOME,
        nascimento      AS NASCIMENTO,
        sexo            AS SEXO,
        estado_civil    AS ESTADO_CIVIL
    FROM sistema_ciesglobal_org.pacientes AS pacientes
    WHERE pacientes.nascimento != ''
        AND pacientes.nascimento IS NOT NULL
        AND pacientes.cns IS NOT NULL

DROP TABLE IF EXISTS dw_sistema_ciesglobal_org.ATENDIMENTOS;
CREATE TABLE dw_sistema_ciesglobal_org.ATENDIMENTOS
    SELECT
        atendimentos.id       AS ATENDIMENTO,
        atendimentos.medico   AS PROFISSIONAL,
        atendimentos.agenda   AS AGENDA
    FROM sistema_ciesglobal_org.atendimento AS atendimentos

DROP TABLE IF EXISTS dw_sistema_ciesglobal_org.AGENDAS;
CREATE TABLE dw_sistema_ciesglobal_org.AGENDAS
SELECT
    agendas.id               AS AGENDA,
    agendas.paciente         AS PACIENTE ,
    agendas.linha_cuidado    AS ESPECIALIDADE,
    agendas.arena            AS UNIDADE,
    agendas.data             AS DATA,
    agendas.status           AS STATUS
FROM sistema_ciesglobal_org.agendas AS agendas
WHERE agendas.data >= "2019-01-01 00:00:00"

DROP TABLE IF EXISTS dw_sistema_ciesglobal_org.ATENDIMENTO_PROCEDIMENTOS;
CREATE TABLE dw_sistema_ciesglobal_org.ATENDIMENTO_PROCEDIMENTOS
    SELECT
        atendimento_procedimentos.id                                                        AS ATENDIMENTO_PROCEDIMENTO,
        atendimento_procedimentos.atendimento                                               AS ATENDIMENTO,
        atendimento_procedimentos.procedimento                                              AS PROCEDIMENTO,
        atendimento_procedimentos.profissional                                              AS PROFISSIONAL,
        atendimento_procedimentos.faturado                                                  AS FATURADO,
        atendimento_procedimentos.quantidade                                                AS QUANTIDADE,
        atendimento_procedimentos.multiplicador                                             AS MULTIPLICADOR,
        (atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador)    AS QUANTIDADE_TOTAL,
        atendimento_procedimentos.finalizacao                                               AS FINALIZACAO
    FROM sistema_ciesglobal_org.atendimento_procedimentos AS atendimento_procedimentos