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
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class Unidade extends Base
{
    public $unidade;
    
    /**
     * 
     */
    public function Unidade ($array = null)
    {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $this->unidade->{$key} = $value;
            }
        }
        if(empty($this->unidade->uop)) {
            $this->unidade->uop = null; 
        }
        parent::__construct();
    }
    /**
     * 
     */
    public function __set ($var, $value)
    {
        $this->unidade->{$var} = $value;
    }
    /**
     * 
     */
    public function __get ($var)
    {
        return $this->unidade->{$var};
    }
  
}