<?php
/**
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
 */

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class Error {

    /**
     * @var string
     */
    private $_type;

    /**
     * @var string
     */
    private $_file;

    /**
     * @var integer
     */
    private $_line;

    /**
     * @var string
     */
    private $_message;

    /**
     * Construtor privado para Singleton
     * @return void
     */
    private function __construct() {}

    /**
     * Fábrica do objeto Singleton de Tratamento de Erros
     * @return Error
     */
    public static function factory() {
        return new self();
    }

    /**
     * @return void
     */
    public function handleFatalError() {

        $this->_file = "unknown file";
        $this->_message = "shutdown";
        $this->_type = E_CORE_ERROR;
        $this->_line = 0;

        $error = error_get_last();

        if (!is_null($error)) {
            $this->_type = $error["type"];
            $this->_file = $error["file"];
            $this->_line = $error["line"];
            $this->_message = $error["message"];
        }

        return $this;
    }

    /**
     * @return Error
     */
    public function setSpecificError($message, $file = '', $line = '') {

        $this->_type = 999;
        $this->_file = $file;
        $this->_line = $line;
        $this->_message = $message;

        return $this;
    }

    /**
     * Envia e-mail com erro fatal
     * @return boolean
     */
    public function sendEmailFatalError() 
    {
        return Email::factory()->sendEmail(
                __EMAILLOGS__, 
                __EMAILLOGS__, 
                explode(',', __EMAILSNOTIFICATIONFATALERROR__), 
                sprintf('Fatal Error - sgdoc - %s [%s][%s]', __VERSAO__, __ENVIRONMENT__, microtime()), 
                $this->formatFatalError(), 
                false
        );
    }

    /**
     * Formata Erro Fatal para enviar por e-mail
     * @return string
     */
    public function formatFatalError() {

        $types = array(
            1       => 'E_ERROR',
            16      => 'E_CORE_ERROR',
            64      => 'E_COMPILE_ERROR',
            999     => 'E_ERROR_SPECIFIC',
            4096    => 'E_RECOVERABLE_ERROR'
        );

        if (isset($_SESSION['SENHA'])) {
            unset($_SESSION['SENHA']);
        }

        return sprintf("<table><thead bgcolor='#c8c8c8'><th>Item</th><th>Descrição</th></thead><tbody>"
                . "<tr valign='top'><td><b>Url</b></td><td><pre>%s</pre></td></tr>"
                . "<tr valign='top'><td><b>Type</b></td><td><pre>%s</pre></td></tr>"
                . "<tr valign='top'><td><b>Message</b></td><td><pre>%s</pre></td></tr>"
                . "<tr valign='top'><td><b>File:</b></td><td>%s</td></tr>"
                . "<tr valign='top'><td><b>Line:</b></td><td>%s</td></tr>"
                . "<tr valign='top'><td><b>Trace:</b></td><td><pre>%s</pre></td></tr>"
                . "<tr valign='top'><td><b>Request:</b></td><td><pre>%s</pre></td></tr>"
                . "<tr valign='top'><td><b>Session:</b></td><td><pre>%s</pre></td></tr>"
                . '</tbody></table>', 
                __URLSERVERAPP__, 
                $types[$this->_type], 
                $this->_message, 
                $this->_file, 
                $this->_line, 
                print_r(debug_backtrace(false), true), 
                print_r($_REQUEST, true), 
                print_r($_SESSION, true));
    }

}