CREATE OR REPLACE VIEW snas.vw_controle_prazos AS
SELECT p.sq_prazo, p.nu_proc_dig_ref, p.nu_proc_dig_res, p.id_usuario_destino, 
       p.id_usuario_origem, p.id_usuario_resposta, p.id_unid_origem, p.id_unid_destino, 
       p.dt_prazo, p.dt_resposta, p.fg_status, p.tx_resposta, p.tx_solicitacao, 
       p.id_unidade_usuario_resposta, ep.id, ep.nu_proc_dig_ref_pai, ep.ha_vinculo, ep.legislacao_situacao, ep.legislacao_descricao, 
       ep.dt_minuta_resposta, ep.id_prazo_pai
FROM sgdoc.tb_controle_prazos p
  left join sgdoc.ext__snas__tb_controle_prazos ep on (ep.id=p.sq_prazo);

COMMENT ON VIEW snas.vw_controle_prazos IS 'Essa view unifica os dados da tabela sgdoc.tb_controle_prazos e da tabela sgdoc.ext__snas__tb_controle_prazos, para simplificar consultas.';

ALTER TABLE snas.vw_controle_prazos OWNER TO usr_pr_sgdoc4;

CREATE OR REPLACE VIEW snas.vw_vinculo_documentos_filtro AS 
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
    prz.id_prazo_pai
   FROM sgdoc.tb_documentos_cadastro doc
   LEFT JOIN sgdoc.tb_documentos_vinculacao vinc ON vinc.id_documento_pai = doc.id AND vinc.fg_ativo = 1 AND vinc.id_vinculacao = 3
   LEFT JOIN sgdoc.tb_documentos_cadastro dem ON dem.id = vinc.id_documento_filho
   LEFT JOIN snas.vw_controle_prazos prz ON prz.nu_proc_dig_ref::text = dem.digital::text
   LEFT JOIN sgdoc.tb_unidades uorg ON uorg.id = prz.id_unid_origem
   LEFT JOIN sgdoc.tb_unidades udes ON udes.id = prz.id_unid_destino
  ORDER BY dem.digital, prz.sq_prazo;

ALTER TABLE snas.vw_vinculo_documentos_filtro OWNER TO usr_pr_sgdoc4;

grant all privileges on schema sgdoc,snas to postgres;
grant usage on schema sgdoc,snas to usr_pr_sgdoc4;
grant select,insert,update,delete on all tables in schema sgdoc,snas to usr_pr_sgdoc4;
grant usage on all sequences in schema sgdoc,snas to usr_pr_sgdoc4;
grant execute on all functions in schema sgdoc,snas to usr_pr_sgdoc4;
