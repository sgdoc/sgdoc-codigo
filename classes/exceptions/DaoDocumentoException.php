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
class DaoDocumentoException {

    private $function = null;
    private $code = null;

    /**
     * 
     */
    public function __construct($e) {
        $trace = $e->getTrace();
        $info = $e->errorInfo;
        $this->code = $info[0];
        $this->function = $trace[1]['function'];
        $this->runException();
    }

    /**
     * 
     */
    private function printJsonError($error) {
        print(json_encode(array('success' => 'false', 'error' => $error)));
    }

    /**
     * 
     */
    private function runException() {
        switch ($this->function) {
            case 'salvarDocumento':
                switch ($this->code) {
                    case 23000:
                        $this->printJsonError('Este documento já esta cadastrado!');
                        break;
                }
                break;

            default: //default - function
                $this->printJsonError('Ocorreu um erro desconhecido! [' . $this->function . '[' . $this->code . '] não existe]');
                break;
        }
    }

}