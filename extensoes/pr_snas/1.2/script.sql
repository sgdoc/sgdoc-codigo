ALTER TABLE sgdoc.ext__snas__tb_controle_prazos ADD COLUMN ha_vinculo boolean;
ALTER TABLE sgdoc.ext__snas__tb_controle_prazos ALTER COLUMN ha_vinculo SET DEFAULT false;

ALTER TABLE sgdoc.ext__snas__tb_controle_prazos ADD COLUMN legislacao_situacao integer;
ALTER TABLE sgdoc.ext__snas__tb_controle_prazos ALTER COLUMN legislacao_situacao SET DEFAULT 0;

ALTER TABLE sgdoc.ext__snas__tb_controle_prazos ADD COLUMN legislacao_descricao text;

CREATE TABLE snas.tb_prazo_anexos
(
  id serial NOT NULL,
  id_prazo integer NOT NULL,
  nome_arquivo_sistema text DEFAULT NULL::bpchar,
  nome_original text,
  st_ativo smallint NOT NULL DEFAULT (1)::smallint,
  dt_upload date,
  id_pessoa integer,
  CONSTRAINT tb_prazo_anexos_pkey PRIMARY KEY (id),
  CONSTRAINT fk_anexo_id_uploader FOREIGN KEY (id_pessoa)
      REFERENCES sgdoc.tb_usuarios (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fk_anexo_prazo FOREIGN KEY (id_prazo)
      REFERENCES sgdoc.tb_controle_prazos (sq_prazo) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE snas.tb_prazo_anexos
  OWNER TO usr_pr_sgdoc4;
CREATE INDEX fki_anexo_id_uploader
  ON snas.tb_prazo_anexos
  USING btree
  (id_pessoa);
CREATE INDEX fki_id_prazo
  ON snas.tb_prazo_anexos
  USING btree
  (id_prazo);


CREATE TABLE snas.tb_prazo_vinculo_ppa
(
  id serial NOT NULL,
  id_prazo integer NOT NULL,
  codigo_programa text,
  codigo_objetivo text,
  codigo_meta text,
  codigo_orgao text,
  exercicio integer,
  st_ativo smallint NOT NULL DEFAULT (1)::smallint,
  CONSTRAINT tb_prazo_vinculo_ppa_pkey PRIMARY KEY (id),
  CONSTRAINT fk_vinculo_ppa_prazo FOREIGN KEY (id_prazo)
      REFERENCES sgdoc.tb_controle_prazos (sq_prazo) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE snas.tb_prazo_vinculo_ppa
  OWNER TO usr_pr_sgdoc4;
CREATE INDEX fki_vinculo_ppa_prazo
  ON snas.tb_prazo_vinculo_ppa
  USING btree
  (id_prazo);


CREATE TABLE snas.tb_siop_acoes
(
  "identificadorUnico" integer,
  exercicio integer,
  "codigoMomento" integer,
  "codigoTipoInclusaoAcao" integer,
  titulo text,
  "baseLegal" text,
  descricao text,
  "codigoAcao" text,
  "codigoPrograma" text,
  "codigoFuncao" text,
  "codigoSubFuncao" text,
  "codigoOrgao" text,
  "codigoEsfera" text,
  "codigoTipoAcao" text,
  "snDireta" boolean,
  "snDescentralizada" boolean,
  "snLinhaCredito" boolean,
  "snTransferenciaObrigatoria" boolean,
  "snTransferenciaVoluntaria" boolean,
  "snExclusaoLogica" boolean,
  "snRegionalizarNaExecucao" boolean,
  "snAquisicaoInsumoEstrategico" boolean,
  "snParticipacaoSocial" boolean
)
WITH (
  OIDS=FALSE
);
ALTER TABLE snas.tb_siop_acoes
  OWNER TO usr_pr_sgdoc4;
CREATE INDEX "ind_codigoAcao_siop"
  ON snas.tb_siop_acoes
  USING btree
  ("codigoAcao" COLLATE pg_catalog."default");


CREATE TABLE snas.tb_siop_execucao_orcamentaria
(
  "codigoAcao" text,
  "codigoPrograma" text,
  exercicio integer,
  "dotacaoAtual" numeric,
  empenhado numeric,
  liquidado numeric,
  "percentualLiquidadoEmpenhado" numeric
)
WITH (
  OIDS=FALSE
);
ALTER TABLE snas.tb_siop_execucao_orcamentaria
  OWNER TO usr_pr_sgdoc4;
CREATE INDEX "ind_execucaoOrcamentaria_siop"
  ON snas.tb_siop_execucao_orcamentaria
  USING btree
  ("codigoAcao" COLLATE pg_catalog."default", "codigoPrograma" COLLATE pg_catalog."default");


CREATE TABLE snas.tb_siop_metas
(
  "identificadorUnico" integer,
  "codigoMomento" integer,
  "codigoMeta" integer,
  exercicio integer,
  "codigoObjetivo" text,
  "codigoPrograma" text,
  descricao text
)
WITH (
  OIDS=FALSE
);
ALTER TABLE snas.tb_siop_metas
  OWNER TO usr_pr_sgdoc4;
CREATE INDEX "ind_codigoMeta_siop"
  ON snas.tb_siop_metas
  USING btree
  ("codigoMeta");


CREATE TABLE snas.tb_siop_objetivos
(
  "identificadorUnico" integer,
  exercicio integer,
  "codigoMomento" integer,
  "codigoObjetivo" text,
  "codigoOrgao" text,
  "codigoPrograma" text,
  enunciado text,
  "snExclusaoLogica" boolean
)
WITH (
  OIDS=FALSE
);
ALTER TABLE snas.tb_siop_objetivos
  OWNER TO usr_pr_sgdoc4;
CREATE INDEX "ind_codigoObjetivo_siop"
  ON snas.tb_siop_objetivos
  USING btree
  ("codigoObjetivo" COLLATE pg_catalog."default");


CREATE TABLE snas.tb_siop_orgaos
(
  "codigoOrgao" integer,
  exercicio integer,
  "tipoOrgao" text,
  "codigoOrgaoPai" text,
  descricao text,
  "descricaoAbreviada" text,
  "orgaoId" integer,
  "orgaoSiorg" text,
  "snAtivo" boolean
)
WITH (
  OIDS=FALSE
);
ALTER TABLE snas.tb_siop_orgaos
  OWNER TO usr_pr_sgdoc4;
CREATE INDEX "ind_codigoOrgao_siop"
  ON snas.tb_siop_orgaos
  USING btree
  ("codigoOrgao");


CREATE TABLE snas.tb_siop_programas
(
  "identificadorUnico" integer,
  "codigoMomento" integer,
  "codigoOrgao" text,
  "codigoPrograma" text,
  "codigoTipoPrograma" text,
  "estrategiaImplementacao" text,
  exercicio integer,
  "horizonteTemporalContinuo" integer,
  justificativa text,
  objetivo text,
  problema text,
  "publicoAlvo" text,
  "snExclusaoLogica" boolean,
  titulo text
)
WITH (
  OIDS=FALSE
);
ALTER TABLE snas.tb_siop_programas
  OWNER TO usr_pr_sgdoc4;
CREATE INDEX "ind_codigoPrograma_siop"
  ON snas.tb_siop_programas
  USING btree
  ("codigoPrograma" COLLATE pg_catalog."default");

﻿DROP VIEW sgdoc.ext__snas__vw_vinculo_documentos;

CREATE OR REPLACE VIEW sgdoc.ext__snas__vw_vinculo_documentos AS 
 SELECT prazo.sq_prazo, sem_vinculo.digital AS demanda, 
    docprincipal.digital AS documento_vinculo_pai, 
    prazo.tx_solicitacao AS solicitacao_demanda, 
    prazo.tx_resposta AS resposta_demanda, uuu.nome AS orgao, 
    demanda.interessado, prazo.id_unid_destino AS unidade_destino, 
    prazo.dt_prazo AS prazo_demanda, 
    prazo.dt_resposta AS data_demanda_respondida, 
    ext.nu_proc_dig_ref_pai AS prazo_pai, prazo.nu_proc_dig_ref AS prazo_filho, 
    vinculo.fg_ativo
   FROM sgdoc.tb_controle_prazos prazo
   LEFT JOIN sgdoc.tb_unidades uuu ON uuu.id = prazo.id_unid_destino
   JOIN sgdoc.ext__snas__tb_controle_prazos ext ON ext.id = prazo.sq_prazo
   JOIN sgdoc.tb_documentos_cadastro demanda ON demanda.digital::text = prazo.nu_proc_dig_ref::text
   RIGHT JOIN sgdoc.tb_documentos_vinculacao vinculo ON vinculo.id_documento_filho = demanda.id
   JOIN sgdoc.tb_documentos_cadastro docprincipal ON vinculo.id_documento_pai = docprincipal.id
   JOIN sgdoc.tb_documentos_cadastro sem_vinculo ON sem_vinculo.id = vinculo.id_documento_filho
  WHERE vinculo.fg_ativo = 1 AND vinculo.id_vinculacao = 3
  GROUP BY prazo.sq_prazo, sem_vinculo.digital, docprincipal.digital, prazo.tx_solicitacao, prazo.tx_resposta, uuu.nome, demanda.interessado, demanda.tecnico_responsavel, prazo.id_unid_destino, prazo.dt_prazo, prazo.dt_resposta, ext.nu_proc_dig_ref_pai, prazo.nu_proc_dig_ref, vinculo.fg_ativo
  ORDER BY sem_vinculo.digital;

ALTER TABLE sgdoc.ext__snas__vw_vinculo_documentos
  OWNER TO postgres;
GRANT ALL ON TABLE sgdoc.ext__snas__vw_vinculo_documentos TO postgres;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE sgdoc.ext__snas__vw_vinculo_documentos TO usr_pr_sgdoc4;

begin;

INSERT INTO sgdoc.tb_recursos(nome, descricao, url, id_recurso_tipo)
values ('(PR-SNAS) Lista detalhes programas SIOP','Exibe a lista de objetivos, metas e ações, dos programas do sistema SIOP.','tab_detalhes_programas_siop.php',1);

INSERT INTO sgdoc.tb_privilegios_usuarios(id_usuario, permissao, id_recurso)
select id_usuario, permissao, (select id from sgdoc.tb_recursos where url = 'tab_detalhes_programas_siop.php')
from sgdoc.tb_privilegios_usuarios pu
  inner join sgdoc.tb_recursos r on (pu.id_recurso=r.id)
where url = 'grid_demanda_cadastrada.php' and id_recurso_tipo = 1;

INSERT INTO sgdoc.tb_recursos(nome, descricao, url, id_recurso_tipo)
values ('(PR-SNAS) Lista detalhes resposta prazo','Exibe a lista de metas e arquivos anexos das respostas para os prazos.','tab_detalhes_responder_prazo.php',1);

INSERT INTO sgdoc.tb_privilegios_usuarios(id_usuario, permissao, id_recurso)
select id_usuario, permissao, (select id from sgdoc.tb_recursos where url = 'tab_detalhes_responder_prazo.php')
from sgdoc.tb_privilegios_usuarios pu
  inner join sgdoc.tb_recursos r on (pu.id_recurso=r.id)
where url = 'grid_demanda_cadastrada.php' and id_recurso_tipo = 1;

commit;

