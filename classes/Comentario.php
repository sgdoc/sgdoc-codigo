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
class Comentario
{

    public $comentario;

    /**
     * 
     */
    public function __set ($var, $value)
    {
        $this->comentario->{$var} = $value;
    }

    /**
     * 
     */
    public function __get ($var)
    {
        if (property_exists($this->comentario, $var)) {
            return $this->comentario->{$var};
        } else {
            return null;
        }
    }

    /**
     * 
     */
    public function __construct ($array)
    {
        /* Padronizar caixa baixa pra indice de array */
        $array = array_change_key_case(($array), CASE_LOWER);
        $usuario = Zend_Auth::getInstance()->getIdentity();
        /* Variaveis do usuario */
        $this->comentario->usuario = $usuario->NOME;
        $this->comentario->id_usuario = $usuario->ID;
        $this->comentario->data = date('Y-m-d H:m:s');
        $this->comentario->id_unidade = $usuario->ID_UNIDADE_ORIGINAL;
        $this->comentario->diretoria = DaoUnidade::getUnidade($this->comentario->id_unidade, 'nome');

        /* Variaveis do comentario especifico */
        foreach ($array as $key => $value) {
            $this->comentario->{$key} = $value;
        }
    }

}