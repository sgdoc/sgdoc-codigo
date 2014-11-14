CREATE TABLE snas.tb_prazo_vinculo_ppa_acoes(
  id serial NOT NULL,
  id_prazo integer NOT NULL,
  codigo_orgao text,
  codigo_programa text,
  codigo_acao text,
  exercicio integer,
  st_ativo smallint NOT NULL DEFAULT (1)::smallint,
  CONSTRAINT tb_prazo_vinculo_ppa_acoes_pkey PRIMARY KEY (id),
  CONSTRAINT fk_vinculo_ppa_acoes_prazo FOREIGN KEY (id_prazo)
      REFERENCES sgdoc.tb_controle_prazos (sq_prazo) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT
) WITH ( OIDS=FALSE );

ALTER TABLE snas.tb_prazo_vinculo_ppa_acoes OWNER TO usr_pr_sgdoc4;

CREATE INDEX fki_vinculo_ppa_acoes_prazo ON snas.tb_prazo_vinculo_ppa_acoes USING btree (id_prazo);

COMMENT ON COLUMN snas.tb_prazo_vinculo_ppa_acoes.codigo_orgao IS 'Orgao do tipo "U"';

ALTER TABLE snas.tb_siop_execucao_orcamentaria ADD COLUMN "codigoOrgao" text;

DROP INDEX snas."ind_execucaoOrcamentaria_siop";

CREATE INDEX ind_execucaoOrcamentaria_siop
  ON snas.tb_siop_execucao_orcamentaria
  USING btree
  ("codigoOrgao" COLLATE pg_catalog."default", "codigoOrgao" COLLATE pg_catalog."default", "codigoAcao" COLLATE pg_catalog."default", exercicio COLLATE pg_catalog."default");
