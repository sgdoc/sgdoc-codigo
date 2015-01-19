<?php

/**
 * 
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
include (__DIR__ . '/../bibliotecas/phpmailer/PHPMailer.php');

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class Email extends PHPMailer {

    /**
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * @return Email
     */
    public static function factory() {
        return new self();
    }

    /**
     * @return boolean]
     * @param string $email_from
     * @param string $name_from
     * @param array $emails
     * @param string $suject
     * @param string $message
     */
    public function sendEmail($email_from, $name_from, $emails = array(), $subject = '', $message = '', $html = false) {

        $this->IsSendmail();
        $this->IsHTML($html);
        $this->SetFrom($email_from, $name_from);

        foreach ($emails as $email) {
            $this->AddAddress($email);
        }

        $this->Subject = $subject;
        $this->MsgHTML($message);
        $this->ContentType = 'text/plain';

        return $this->Send();
    }

}