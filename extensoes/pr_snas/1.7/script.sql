--INCLUSAO DAS NOVAS COLUNAS 
ALTER TABLE snas.tb_prazo_vinculo_ppa_acoes ADD COLUMN id_unico_acao integer;

ALTER TABLE snas.tb_prazo_vinculo_ppa_acoes ADD COLUMN id_unico_localizador integer;
ALTER TABLE snas.tb_prazo_vinculo_ppa_acoes ADD COLUMN codigo_localizador text;

ALTER TABLE snas.tb_prazo_vinculo_ppa_acoes ADD COLUMN id_unico_plano_orcamentario integer;
ALTER TABLE snas.tb_prazo_vinculo_ppa_acoes ADD COLUMN codigo_plano_orcamentario text;

--CRIACAO TABELAS LOCALIZADORES E PLANOS ORCAMENTARIOS
CREATE TABLE snas.tb_siop_localizadores (
  "codigoLocalizador" text,
  "codigoMomento" integer,
  "codigoRegiao" integer,
  "codigoTipoInclusao" integer,
  "dataHoraAlteracao" text,
  descricao text,
  exercicio integer,
  "identificadorUnico" integer,
  "identificadorUnicoAcao" integer,
  "justificativaRepercussao" text,
  "mesAnoInicio" text,
  "mesAnoTermino" text,
  "snExclusaoLogica" boolean,
  "totalFinanceiro" numeric,
  "totalFisico" numeric
) WITH ( OIDS=FALSE );

ALTER TABLE snas.tb_siop_localizadores OWNER TO usr_pr_sgdoc4;

CREATE TABLE snas.tb_siop_planos_orcamentarios (
  "identificadorUnico" integer,
  "identificadorUnicoAcao" integer,
  "codigoMomento" integer,
  exercicio integer,
  "planoOrcamentario" text,
  titulo text,
  detalhamento text,
  "codigoUnidadeMedida" integer,
  "codigoProduto" integer,
  "dataHoraAlteracao" text,
  "codigoIndicadorPlanoOrcamentario" text,
  "snAtual" boolean
) WITH ( OIDS=FALSE );

ALTER TABLE snas.tb_siop_planos_orcamentarios OWNER TO usr_pr_sgdoc4;

--EXCLUSAO DA TABELA PARA EXECUCAO ORCAMENTARIA
DROP INDEX snas."ind_execucaoOrcamentaria_siop";
DROP TABLE snas.tb_siop_execucao_orcamentaria;

--CRIACAO DE NOVAS TABELAS PARA EXECUCAO ORCAMENTARIA
CREATE TABLE snas.tb_siop_exec_orcam_acao (
  "codigoOrgao" text,
  "codigoPrograma" text,
  "codigoAcao" text,
  exercicio integer,
  "dotacaoAtual" numeric,
  empenhado numeric,
  liquidado numeric,
  "percentualLiquidadoEmpenhado" numeric
) WITH ( OIDS=FALSE );

ALTER TABLE snas.tb_siop_exec_orcam_acao OWNER TO usr_pr_sgdoc4;

CREATE TABLE snas.tb_siop_exec_orcam_localizador (
  "codigoOrgao" text,
  "codigoPrograma" text,
  "codigoAcao" text,
  "codigoLocalizador" text,
  exercicio integer,
  "dotacaoAtual" numeric,
  empenhado numeric,
  liquidado numeric,
  "percentualLiquidadoEmpenhado" numeric
) WITH ( OIDS=FALSE );

ALTER TABLE snas.tb_siop_exec_orcam_localizador OWNER TO usr_pr_sgdoc4;

CREATE TABLE snas.tb_siop_exec_orcam_plano_orcam (
  "codigoOrgao" text,
  "codigoPrograma" text,
  "codigoAcao" text,
  "codigoLocalizador" text,
  "planoOrcamentario" text,
  exercicio integer,
  "dotacaoAtual" numeric,
  empenhado numeric,
  liquidado numeric,
  "percentualLiquidadoEmpenhado" numeric
) WITH ( OIDS=FALSE );

ALTER TABLE snas.tb_siop_exec_orcam_plano_orcam OWNER TO usr_pr_sgdoc4;

grant all privileges on schema sgdoc, snas to postgres;
grant usage on schema sgdoc, snas to usr_pr_sgdoc4;
grant select, insert, update, delete on all tables in schema sgdoc, snas to usr_pr_sgdoc4;
grant usage on all sequences in schema sgdoc, snas to usr_pr_sgdoc4;
grant execute on all functions in schema sgdoc, snas to usr_pr_sgdoc4;
