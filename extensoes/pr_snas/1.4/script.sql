begin;

--CRIACAO DE PERMISSOES
INSERT INTO sgdoc.tb_recursos(nome, descricao, url, id_recurso_tipo)
values ('(PR-SNAS) Carrega os prazos filhos ao responder um prazo','Carrega os dados de prazos filhos em abas, na tela de respota de prazos','aba_resposta_prazo_filho.php',1);

INSERT INTO sgdoc.tb_privilegios_usuarios(id_usuario, permissao, id_recurso)
select id_usuario, permissao, (select id from sgdoc.tb_recursos where url = 'aba_resposta_prazo_filho.php')
from sgdoc.tb_privilegios_usuarios pu
  inner join sgdoc.tb_recursos r on (pu.id_recurso=r.id)
where url = 'grid_demanda_cadastrada.php' and id_recurso_tipo = 1;

commit;
