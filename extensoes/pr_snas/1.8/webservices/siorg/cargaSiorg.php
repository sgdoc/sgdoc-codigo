<?php
	include("./include.db.php");
	include("./Siorg.php");
	include("./ConfigSiorg.php");
	
		
	$siorg = new Siorg();
	
	$db = new DB();
	
	$dados = $db->obterDados('sgdoc.tb_pessoa_siorg', 'co_orgao::int', '(sg_uf is null or ch_email_internet is null)', 'co_orgao');
	
	//$dados[0]['CO_ORGAO'] = 123335;
	
	foreach($dados as $k => $unidade){
		$enderecoContato = $siorg->consultarEnderecoContato($unidade['CO_ORGAO']);
		$uf = $mail = null;
		if($enderecoContato['sucesso']) {
			$uf = (isset($enderecoContato['registros']['endereco']['uf'])) ? $enderecoContato['registros']['endereco']['uf'] : '';
			$email = (isset($enderecoContato['registros']['contato']['email'])) ? $enderecoContato['registros']['contato']['email'] : '';		
			
			echo "{$unidade['CO_ORGAO']}: {$uf} - {$email}\n";

			$out = $db->atualizarDados('sgdoc.tb_pessoa_siorg', "co_orgao::int = {$unidade['CO_ORGAO']}", "sg_uf = '{$uf}', ch_email_internet = '{$email}'");
			echo "Número de registros alterados: {$out['count']}\n";
		}
	} 