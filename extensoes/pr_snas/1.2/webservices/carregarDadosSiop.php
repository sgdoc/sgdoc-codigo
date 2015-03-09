<pre><?php

include_once(dirname(__FILE__) . '/include.soap.php');

foreach($anos as $ano) {
	$programas = obterTodosProgramas($configuracao, $ano);
	foreach($programas['registros'] as $programa) {
		print_r($programa);
		$acoesDoPrograma = obterAcoesPorPrograma($configuracao, $ano, $programa);
		echo "<blockquote>";
		print_r($acoesDoPrograma);
		echo "</blockquote>";
		
	}
}