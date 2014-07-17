<?php

/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuíção e/ou modifição dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuíção na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */

/**
 * Caso exista o travamento na geração de imagens de cache, o método de geração de cache será chamado novamente.
 */
$imagens  = Imagens::factory();
$digital  = $_REQUEST['digital'];
$lastFile = sprintf('%s/cache/%s/%s/last-1', __CAM_UPLOAD__, $imagens->generateLote($digital), $digital);

if($imagens->isLocked($digital) ){
    if (file_exists($lastFile)) {
        $tempoEntreGeracaoDeArquivos = time() - filemtime($lastFile); // hora atual em segundos menos a hora de geração do arquivo.

        if($tempoEntreGeracaoDeArquivos > 10){
            $imagens->unlock($digital);
            
            print json_encode(array(
                'percent' => 'error'
            ));
            
            // apenas atualiza a data de alteração do arquivo para que ele não 
            // seja chamado imediatamente depois quando este arquivo for chamado 
            // novamente.
            touch($lastFile);
            
            exit;
        }
    } 
}
if (!file_exists($lastFile)) {
    file_put_contents($lastFile, 'XXX/XXX');
}

print json_encode(array(
    'percent' => $imagens->percentCacheDigital($digital)
));