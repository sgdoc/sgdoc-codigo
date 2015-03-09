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
class Session
{

    /**
     * 
     */
    public static function set ($name, $value = null)
    {
        $_SESSION['sgdoc'][$name] = serialize($value);
    }

    /**
     * 
     */
    public static function get ($name)
    {
        if (!isset($_SESSION['sgdoc'][$name])) {
            return null;
        }
        return unserialize($_SESSION['sgdoc'][$name]);
    }

    /**
     * 
     */
    public static function destroy ($name = false)
    {
        if ($name) {
            unset($_SESSION['sgdoc'][$name]);
        } else {
            unset($_SESSION['sgdoc']);
        }
    }

    /**
     * 
     */
    public static function exists ($name)
    {
        return isset($_SESSION['sgdoc'][$name]);
    }

}