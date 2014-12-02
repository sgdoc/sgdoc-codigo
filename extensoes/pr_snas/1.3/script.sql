--SELECIONAR AÇÕES PPA/LOA
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

-- ENCAMINHAR MULTIPLOS PRAZOS (DEMANDAS)
ALTER TABLE sgdoc.ext__snas__tb_controle_prazos ADD COLUMN id_prazo_pai integer;

-- CORREÇÕES DE TEXTOS
update sgdoc.tb_recursos set nome = replace(nome, 'Area', 'Área') where nome like '%Area%';

update sgdoc.tb_recursos set descricao = replace(descricao, 'Area', 'Área') where descricao like '%Area%';
