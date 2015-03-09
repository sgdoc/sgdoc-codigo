<?php
exit();
/**
 * Este script cria os arquivos PNG a partir de outros TIF
 * 
 * 
 */
date_default_timezone_set('America/Sao_Paulo');
// início - criando arquivos necessários
$data = date('d_m_Y');
$directory = __DIR__;
$digitaisFile = $directory . '/digitais_' . $data . '.txt';
$digitaisConvertidasFile = $directory . '/digitais_convertidas_' . $data . '.txt';

$runingFile = $directory . '/runing.txt';
// fim - criando arquivos necessários

// início - se o arquivo não existir, será criado
if(!file_exists($digitaisFile)){
    file_put_contents($digitaisFile, '');
}

if(!file_exists($digitaisConvertidasFile)){
    file_put_contents($digitaisConvertidasFile, '');
}

if(!file_exists($runingFile)) {
    file_put_contents($runingFile, date('d-m-Y H:i:s'));
}
// fim - se o arquivo não existir, será criado

// dados para a próxima verificação
$dataAtual          = new DateTime(date('d-m-Y'));
$atualizacaoArquivo = new DateTime(date('d-m-Y', filectime($runingFile)));
$intervalo = $dataAtual->diff($atualizacaoArquivo);

// verifico se o arquivo runing.txt, que indica se o script ainda está rodando,
// foi criado a menos de um minuto. Se sim, ele vai rodar o script para gerar novos 
// PNG's 
if($intervalo->format('%i') < 1){
    
    // pega o conteúdo do arquivo e transforma em um array
    $digitais = array_filter(explode(';', file_get_contents($digitaisFile)));
    $digitaisConvertidas = array_filter(explode(';', file_get_contents($digitaisConvertidasFile)));
    
    // extrai apenas as digitais que precisam ser convertidas
    $converterDigitais = array_diff($digitais, $digitaisConvertidas);

    // itera nas digitais que precisam ser convertidas 
    foreach($converterDigitais as $digital){

        // cria a pasta onde ficarão as digitais pertencentes a este lote
        $pastaLote = 'LOTE' . floor($digital / 10000);
        
        // escreve o caminho completo do diretório em uma variável
        $diretorio = '/var/www/html/sgdoc/documento_virtual' . 
                     DIRECTORY_SEPARATOR . 
                     $pastaLote . 
                     DIRECTORY_SEPARATOR .
                     $digital;
        
        // verifica a existencia do diretório
        if(is_dir($diretorio)){
            
            $handle = opendir($diretorio);
            while($fileDigitais = readdir($handle)){
                // verifica se o arquivo é do tipo tif
                if(substr($fileDigitais, -3) == 'tif'){
                    // extrai o nome do arquivo sem a extensão
                    $pngName = substr($fileDigitais, 0, -4);
                    // verifica se o arquivo png ainda não existe para poder cria-lo
                    if(!file_exists("$diretorio/$pngName.png")){
                        // -resize 595x842 aplica o tamanho A4
                        // -quality 100 aplica a melhor qualidade possível à esta conversão
                        // -rotate -90> gira a imagem em 90 graus caso a largura seja maior que a altura
                        // executa comando para converter a imagem tif em png
                        exec("/usr/local/bin/convert -rotate '-90>' -quality 100 -resize 595x842 {$diretorio}/{$fileDigitais} {$diretorio}/{$pngName}.png");
                        
                        // caso o tiff seja multipágina, apenas a primeira página será considerada.
                        if(file_exists("$diretorio/$pngName-0.png")){
                            // renomeia o primeiro png para o nome que já existe no banco
                            rename("$diretorio/$pngName-0.png", "$diretorio/$pngName.png");
                        }
                    }
                }
            }
            // escreve a digital no arquivo de digitais convertidas para identificar 
            // que esta já foi convertida
            file_put_contents($digitaisConvertidasFile, "$digital;", FILE_APPEND);
        }
    }
    // apaga o arquivo runing.txt para indicar que o script terminou a iteração
    unlink($runingFile);
}