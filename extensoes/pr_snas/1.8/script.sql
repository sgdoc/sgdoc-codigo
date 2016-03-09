--CRIACAO TABELA DE AGRUPAMENTO DE DOCUMENTOS
DROP TABLE IF EXISTS snas.tb_agrupamento_documentos CASCADE; 
CREATE TABLE snas.tb_agrupamento_documentos
(
  id serial NOT NULL,
  id_grupo integer NOT NULL,
  digital character varying(7) NOT NULL
)
WITH (
  OIDS=FALSE
);
ALTER TABLE snas.tb_agrupamento_documentos
  OWNER TO usr_pr_sgdoc4;

-- CRIACAO DA VIEW snas.vw_vinculo_documentos_agrupados_filtro, SUBSTITUINDO A snas.vw_vinculo_documentos_filtro
DROP VIEW IF EXISTS snas.vw_vinculo_documentos_agrupados_filtro;
CREATE OR REPLACE VIEW snas.vw_vinculo_documentos_agrupados_filtro AS 
 SELECT doc.digital, dem.digital AS demanda, prz.sq_prazo, prz.tx_solicitacao, 
        CASE
            WHEN prz.dt_minuta_resposta IS NULL AND prz.dt_resposta IS NULL THEN ''::text
            WHEN prz.dt_minuta_resposta IS NOT NULL AND prz.dt_resposta IS NULL THEN 'Minuta'::text
            ELSE prz.tx_resposta
        END AS tx_resposta, 
    dem.interessado, prz.id_unid_origem AS id_unidade_origem, 
    uorg.nome AS nome_unidade_origem, prz.id_unid_destino AS id_unidade_destino, 
    udes.nome AS nome_unidade_destino, prz.dt_prazo AS data_prazo, 
        CASE
            WHEN prz.dt_minuta_resposta IS NOT NULL AND prz.dt_resposta IS NULL THEN 'MT'::bpchar
            ELSE prz.fg_status
        END AS status_prazo, 
    prz.id_prazo_pai,
    agr.id_grupo AS grupo
   FROM sgdoc.tb_documentos_cadastro doc
   LEFT JOIN sgdoc.tb_documentos_vinculacao vinc ON vinc.id_documento_pai = doc.id AND vinc.fg_ativo = 1 AND vinc.id_vinculacao = 3
   LEFT JOIN sgdoc.tb_documentos_cadastro dem ON dem.id = vinc.id_documento_filho
   LEFT JOIN snas.vw_controle_prazos prz ON prz.nu_proc_dig_ref::text = dem.digital::text
   LEFT JOIN sgdoc.tb_unidades uorg ON uorg.id = prz.id_unid_origem
   LEFT JOIN sgdoc.tb_unidades udes ON udes.id = prz.id_unid_destino
   LEFT JOIN snas.tb_agrupamento_documentos agr ON dem.digital = agr.digital
  ORDER BY doc.assunto, doc.assunto_complementar, dem.digital, prz.sq_prazo;

ALTER TABLE snas.vw_vinculo_documentos_agrupados_filtro
  OWNER TO postgres;

--PERMISSOES
grant all privileges on schema sgdoc, snas to postgres;
grant usage on schema sgdoc, snas to usr_pr_sgdoc4;
grant select, insert, update, delete on all tables in schema sgdoc, snas to usr_pr_sgdoc4;
grant usage on all sequences in schema sgdoc, snas to usr_pr_sgdoc4;
grant execute on all functions in schema sgdoc, snas to usr_pr_sgdoc4;
