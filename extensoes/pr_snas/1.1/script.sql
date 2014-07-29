CREATE OR REPLACE VIEW sgdoc.ext__snas__vw_area_trabalho_documentos AS 
SELECT d.id_unid_area_trabalho as area_busca, 'S' as permite_acao,
  d.id, d.dt_prazo, d.digital, d.dt_cadastro, d.id_unid_area_trabalho, 
  a.assunto, d.numero, d.tipo, d.origem, d.ultimo_tramite, 
  un.nome AS area_trabalho, dv.id_documento_pai AS pai, 
  cp.dt_prazo - 'now'::text::date AS dias_restantes
FROM sgdoc.tb_documentos_cadastro d
  LEFT JOIN sgdoc.tb_documentos_assunto a ON a.id = d.id_assunto
  LEFT JOIN sgdoc.tb_unidades un ON un.id = d.id_unid_area_trabalho
  LEFT JOIN sgdoc.tb_processos_documentos pd ON pd.id_documentos_cadastro = d.id
  LEFT JOIN sgdoc.tb_documentos_vinculacao vi ON vi.id_documento_filho = d.id AND vi.fg_ativo = 1
  LEFT JOIN sgdoc.tb_documentos_vinculacao dv ON dv.id_documento_pai = d.id AND dv.fg_ativo = 1 AND dv.st_ativo = 1
  LEFT JOIN sgdoc.tb_controle_prazos cp ON cp.nu_proc_dig_ref::text = d.digital::text AND cp.fg_status = 'AR'::bpchar AND cp.id_unid_origem = d.id_unid_area_trabalho
WHERE vi.id_documento_filho IS NULL AND pd.id_documentos_cadastro IS NULL
union
--PRAZOS DA UNIDADE
select prazo.id_unid_destino as area_busca, 'N' as permite_acao,
  d.id, prazo.data_prazo as dt_prazo, d.digital, d.dt_cadastro, d.id_unid_area_trabalho, 
  a.assunto, d.numero, d.tipo, d.origem, d.ultimo_tramite, 
  un.nome AS area_trabalho, null AS pai, 
  prazo.data_prazo - 'now'::text::date AS dias_restantes
from (
  select dv.id_documento_pai as id, p.id_unid_destino, max(p.dt_prazo) as data_prazo
  from sgdoc.tb_controle_prazos p
    inner join sgdoc.tb_documentos_cadastro monit on (p.nu_proc_dig_ref = monit.digital)
    inner join sgdoc.tb_documentos_vinculacao dv on (dv.id_documento_filho = monit.id AND dv.fg_ativo = 1)
  where p.fg_status = 'AR'
  group by dv.id_documento_pai, p.id_unid_destino
  order by dv.id_documento_pai
) as prazo
inner join sgdoc.tb_documentos_cadastro d on (prazo.id = d.id)
LEFT JOIN sgdoc.tb_documentos_assunto a ON a.id = d.id_assunto
LEFT JOIN sgdoc.tb_unidades un ON un.id = d.id_unid_area_trabalho
order by permite_acao desc;

COMMENT ON VIEW sgdoc.ext__snas__vw_area_trabalho_documentos IS 'permite visualizar documentos na área de trabalhos que estao 
em outra area de trabalho (unidade) mas que possuem algum documento vinculado com prazo para esta unidade corrente';

ALTER TABLE sgdoc.ext__snas__vw_area_trabalho_documentos OWNER TO postgres;
GRANT ALL ON TABLE sgdoc.ext__snas__vw_area_trabalho_documentos TO postgres;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE sgdoc.ext__snas__vw_area_trabalho_documentos TO usr_pr_sgdoc4;
