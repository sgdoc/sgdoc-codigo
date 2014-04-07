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

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class Base {

    public $_usuario = null;

    /**
     * 
     */
    public function __construct() {

        /**
         * Verificar Session
         * @remover: porque o ZendAuth faz esse papel...
         */
        if (!Zend_Auth::getInstance()->getIdentity()) {
            return null;
        }

        /**
         * Iniciar Usuario
         */
        try {

            $this->_usuario = Controlador::getInstance()->usuario;

            foreach ($this->_usuario as $attr => $value) {
                $this->_usuario->{strtolower($attr)} = $value;
            }
            //@deprecated
            Session::set('_usuario', $this->_usuario);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 
     */
    public function registerExtraordinaryAttributeUser($attr, $value) {
        $usuario = Controlador::getInstance()->usuario;
        $usuario->{strtolower($attr)} = $value;
        Session::set('_usuario', $usuario);
    }

    /**
     * 
     */
    public function printJson() {
        print(json_encode($this->out));
    }

    /**
     * 
     */
    public function iisset($array) {
        foreach ($array as $key => $value) {
            if (strlen($value) == 0) {
                return false;
            }
        }
        return true;
    }

}