1) A estrutura de pastas e arquivos contidos neste diretorio dever�o existir no xebium na seguinte pasta so servidor XEBIUM: /files/nome_projeto/anexos

Exemplo:
/files/nome_projeto/anexos/extensao_arquivo
-- /files/sgdoc/anexos/png/imagem.png
-- /files/sgdoc/anexos/jpg/imagem.jpg
-- /files/sgdoc/anexos/pdf/xpto.pdf


2) Para utilizaçãoo nos scripts selenium deve seguir o padrão a seguir:

	2.1 Definir uma variavel para receber o path absoluto até o diretorio de imagens do projeto
		2.1.1 Criar uma variavel com (valor = "path_to_attach") com (alvo = "path_to_directory_image") e com (comando = storeExpression)
			- é DE SUMA IMPORT�NCIA que o nome da variavel utilizada para referencia de arquivos possua o nome "path_to_attach" pois esse nome ser� identificado na conversao
			  do script selenium para o xebium para alterar o path para o repositorio do servidor
		
		2.1.2 Exemplo de trecho de escritp selenium: 
			<pre>
				<tr>
					<td>storeExpression</td>
					<td>C:\Users\91011876191\Documents\Projetos\SGDOC\repositorio\selenium_scripts\anexos\anexos</td>
					<td>path_to_image_file_upload</td>
				</tr>
			</pre>
		2.1.3 Exemplo do script 2.1.2 convertido para o xebium:
			<pre>
				| $path_to_image_file_upload_extension_fail= | is | storeExpression | on | /root/Xebium/FitNesseRoot/files/ProjectSgdoc/anexos |
			</pre>
			