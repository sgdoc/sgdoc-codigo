-- APÓS RESTAURAR O DUMP DA TABELA sgdoc.tb_pessoa_siorg_carga, EXECUTAR O QUE SEGUE

ALTER TABLE sgdoc.tb_pessoa_siorg ADD COLUMN tx_versao_consulta character varying;

--ATUALIZA REGISTROS EXISTENTES - tb_pessoa_siorg
update sgdoc.tb_pessoa_siorg s
set in_organizacao = c.in_organizacao,
  tx_versao_consulta = c.tx_versao_consulta
from sgdoc.tb_pessoa_siorg_carga c
where (s.co_orgao=c.co_orgao);

--INCLUI NOVOS REGISTROS - tb_pessoa_siorg
insert into sgdoc.tb_pessoa_siorg (co_orgao, co_orgao_pai, co_tipo_orgao, no_tipo_orgao, no_orgao, sg_orgao, in_organizacao, tx_versao_consulta, sg_uf, ch_email_internet)
select c.co_orgao, c.co_orgao_pai, c.co_tipo_orgao, c.no_tipo_orgao, c.no_orgao, c.sg_orgao, c.in_organizacao, c.tx_versao_consulta, c.sg_uf, c.ch_email_internet
from sgdoc.tb_pessoa_siorg_carga c
  left join sgdoc.tb_pessoa_siorg s on (s.co_orgao=c.co_orgao)
where s.co_siorg is null;

--INCLUI NOVOS REGISTROS - tb_unidades
INSERT INTO sgdoc.tb_unidades (nome, sigla, uf, email, uop, co_siorg)
select ps.no_orgao, ps.sg_orgao, coalesce(uf.id, 27), ps.ch_email_internet, up.id as unidade_pai, ps.co_siorg
from sgdoc.tb_pessoa_siorg ps
  left join sgdoc.tb_unidades u on (u.co_siorg=ps.co_siorg)
  left join sgdoc.tb_pessoa_siorg psp on (psp.co_orgao=ps.co_orgao_pai)
  left join sgdoc.tb_unidades up on (up.co_siorg=psp.co_siorg)
  left join sgdoc.tb_uf uf on (uf.sigla_uf=ps.sg_uf)
where u.co_siorg is null;

--ATUALIZA NOMES - tb_unidades
update sgdoc.tb_unidades u
set nome = concat(psp.sg_orgao, ' - ', ps.no_orgao, coalesce(concat(' - ', sgdoc.func_get_hierarq_siorg(ps.co_orgao)), ''))
from sgdoc.tb_pessoa_siorg ps
  left join sgdoc.tb_pessoa_siorg psp on (psp.co_orgao=ps.co_orgao_pai)
where (ps.co_siorg=u.co_siorg);
