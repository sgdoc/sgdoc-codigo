<?php

/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */

class Extensoes
{
    protected $_definitions = array();
    
    public function __construct( array $parExtensoes ) {
        foreach ($parExtensoes as $extensao) {
            //verifica se pasta existe
            $folder = __BASE_PATH__ . '/extensoes/' . $extensao;
            if(!is_dir( $folder )){
                throw new \Exception("Extensão cadastrada não está corretamenta implementada. " . $extensao);
            }
            
            //carrega definicao
            $file = $folder . '/definition.json';
            if(!is_file( $file )){
                throw new \Exception("
                    Extensão cadastrada não está corretamenta implementada. 
                    Não foi possível encontrar o arquivo de Definição: " . $file);
            }
            
            $definition = json_decode(file_get_contents( $file ));
            
            if(!$definition){
                throw new \Exception("Definição da extensão possui algum problema na estrutura JSON");
            }

            $this->_definitions[$extensao] = $definition;            
        }
        
        $this->_verify();
    }
    
    protected function _verify()
    {
        $allInterfaces = array();
        $allModelos = array();
        
        //Varre todas as definições e verifica se as extensões sobreescrevem um mesmo recurso
        foreach ($this->_definitions as $extensao => $definition) {
            if( count (array_intersect($allInterfaces, $definition->resources->interfaces)) ||
                count (array_intersect($allModelos, $definition->resources->modelos)) ){
                throw new Exception("Extensão [{$extensao}] tenta sobreescrever recurso (interface/modelo) 
                    já sobreescrito por outra Extensão.");
            }
            $allInterfaces = array_merge( $allInterfaces, $definition->resources->interfaces );
            $allModelos = array_merge( $allModelos, $definition->resources->interfaces );
        }
    }
    
    /**
     * 
     * @return boolean
     */
    public function overWrittenResource( $parResource )
    {
        $parResource = preg_replace('/\/interfaces\//', '', $parResource);
        $parResource = preg_replace('/\/modelos/', 'modelos', $parResource);
        
        //Varre array das definições das extensões em busca do recurso sobreescrito
        foreach ($this->_definitions as $extension => $definition) {
            if( in_array($parResource, $definition->resources->interfaces) ){
                $file = __BASE_PATH__ ."/extensoes/{$extension}/interfaces/{$parResource}";
                include_once $file;
                return true;
            }
            
            if( in_array($parResource, $definition->resources->modelos) ){
                $file = __BASE_PATH__ ."/extensoes/{$extension}/{$parResource}";
                include_once $file;
                return true;
            }

        }
                
        return false;
    }
    
}