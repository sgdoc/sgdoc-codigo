<h1>Configuração necessárias para a consulta PPA/LOA</h1>
<br />
1. rodar o arquivo script.sql para gerar as tabelas onde ficarão a cópia dos dados disponibilizados pelo webservice; antes disso é necessário substituir a string "usr_pr_sgdoc4" (sem aspas) pelo usuário com os direitos devidos do banco de dados.<br />
<br />
2. carregar os dados do webservice:<br />
<br />
2.1. preencher os dados que constam no arquivo <b>cfg/configuration.ini</b> relativos a url, namespace, usuario, senha e perfil de cada um dos webservices (WSQualitativo e WSQuantitativo), assim como informações de proxy, caso seja utilizado um:<br />
<pre>
ws.siop.exercicio.inicial              =   '2004'

ws.siop.proxy.server					=	''
ws.siop.proxy.port						= 	''
ws.siop.proxy.username					=	''
ws.siop.proxy.password					= 	''

ws.siop.qualitativo.wsdl_url            =   ''
ws.siop.qualitativo.namespace           =   ''
ws.siop.qualitativo.usuario             =   ''
ws.siop.qualitativo.senha               =   ''
ws.siop.qualitativo.perfil              =   ''

ws.siop.quantitativo.wsdl_url            =   ''
ws.siop.quantitativo.namespace           =   ''
ws.siop.quantitativo.usuario             =   ''
ws.siop.quantitativo.senha               =   ''
ws.siop.quantitativo.perfil              =   ''
</pre><br />
2.2. executar em modo terminal o script <b>cargaSiop</b> da seguinte maneira:<br />
<pre>
php <b>[caminho]</b>/cargaSiop -a <b>[ambiente]</b> -c <b>[carga]</b> -e <b>[exercicio]</b>
</pre>
onde:<br />
<br />
<b>[caminho]</b> = {diretorio do SGDOC}/extensoes/pr_snas/1.2/webservices/ <br />
<b>[ambiente]</b> = <i>prd</i> ou <i>prd-presidencia</i> ou <i>hmg</i> ou <i>dsv</i> ou <i>trn</i><br />
<b>[carga]</b> = <i>ambas</i> ou <i>quantitativo</i> ou <i>qualitativo</i><br />
<b>[exercicio]</b> = ano no formato 9999<br />
</pre>