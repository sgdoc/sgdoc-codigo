--CRIA O RECURSO EXPORTAR
begin;

insert into sgdoc.tb_recursos (nome, descricao, img, id_recurso_tipo, dom_id)
  values ('(PR-SNAS) Exportar Dados', 'Exporta os dados do documento e suas demandas.', 'imagens/exportar_itens.png', 4, 'botao-exportar-documento');

update sgdoc.tb_recursos_associacao
set ordem = ordem + 1
where id_recurso_pai in (select id from sgdoc.tb_recursos where url = 'detalhar_documentos.php');

insert into sgdoc.tb_recursos_associacao (id_recurso_pai, id_recurso_filho, ordem)
select id, (select id from sgdoc.tb_recursos where dom_id = 'botao-exportar-documento'), 1
from sgdoc.tb_recursos
where url = 'detalhar_documentos.php';

insert into sgdoc.tb_privilegios(id_unidade, id_recurso, permissao)
select id_unidade, (select id from sgdoc.tb_recursos where dom_id = 'botao-exportar-documento'), permissao
from sgdoc.tb_privilegios where id_recurso in (select id from sgdoc.tb_recursos where dom_id = 'botao-salvar-alteracoes-documentos');

insert into sgdoc.tb_recursos (nome, descricao, id_recurso_tipo, url)
  values ('(PR-SNAS) Download de Arquivos', 'Abre arquivos gerados pelo sistema para download.', 4, 'download_arquivo.php');

insert into sgdoc.tb_privilegios(id_unidade, id_recurso, permissao)
select id_unidade, (select id from sgdoc.tb_recursos where url = 'download_arquivo.php'), permissao
from sgdoc.tb_privilegios where id_recurso in (select id from sgdoc.tb_recursos where dom_id = 'botao-salvar-alteracoes-documentos');

commit;
