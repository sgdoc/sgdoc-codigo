<?php

class TPPrazo {

    /**
     * @return void
     */
    protected function __construct() {
        
    }

    /**
     * @return TPPrazo
     */
    public static function factory() {
        return new self();
    }

    /**
     * @return array
     * @param integer $idUsuario
     * @param integer $idUnidades
     */
    public function retrivePrazosVencidosUsuarioByIdUnidade($idUsuario, $idUnidade) {
        try {
            return CFModelControlePrazos::factory()->retrivePrazosIndividualByIdUnidade($idUsuario, $idUnidade);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     * @param integer $idUnidade
     */
    public function retrivePrazosVencidosUnidadeById($idUnidade) {
        try {
            return CFModelControlePrazos::factory()->retrivePrazosVencidosUnidadeById($idUnidade);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     * @param integer $idUsuario
     * @param integet $idUnidade
     */
    public function retrivePrazosPendentesByIdUsuario($idUsuario, $idUnidade) {
        try {
            return CFModelControlePrazos::factory()->retrivePrazosPendentesByIdUsuario($idUsuario, $idUnidade);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     * @param integer $idUnidades
     */
    public function retrivePrazosPendentesByIdUnidade($idUnidade) {
        try {
            return CFModelControlePrazos::factory()->retrivePrazosPendentesByIdUnidade($idUnidade);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     * @param integer $idPrazo
     */
    public function retrivePrazoOpenedById($idPrazo) {
        try {
            return CFModelControlePrazos::factory()->retrivePrazoById($idPrazo);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     * @param integer $idPrazo
     */
    public function notifyUserByIdPrazo($idPrazo) {

        $result = TPPrazo::factory()->retrivePrazoOpenedById($idPrazo);

        $retrive = array_change_key_case($result, CASE_LOWER);

        $content = sprintf("<strong>N. Proc/Dig. Ref.: </strong>%s<br><strong>Unid. Origem: </strong>%s<br>
                <strong>Unid. Destino: </strong>%s<br><strong>Remetente: </strong>%s<br>
                <strong>Solicitação: </strong>%s<br><strong>Data do Prazo: </strong>%s<br>
                <strong>Dias Restantes: </strong>%s<br>", $retrive['nu_referencia'], $retrive['nm_unidade_origem'], $retrive['nm_unidade_destino'], $retrive['nm_usuario_origem'], $retrive['tx_solicitacao'], $retrive['dt_prazo'], $retrive['dias_restantes']);

        $send = Email::factory()->sendEmail(
                __EMAILLOGS__, $retrive['nm_usuario_origem'], array($retrive['tx_email_destino']), sprintf('Notificação Prazo SGDoc %s [%s]', __VERSAO__, microtime()), $content, true
        );

        if (!$send) {
            $response = array(
                'success' => true,
                'email' => $retrive['tx_email_destino']
            );
        } else {
            $response = array(
                'success' => false,
                'error' => 'Solicitação de notificação encaminhada para o serviço de envio de emails!'
            );
        }

        return $response;
    }

    /**
     * @return TPPrazo
     */
    public function notifyUsersAllPrazosOpened() {
        try {

            $dispatch = array();

            $result = CFModelControlePrazos::factory()->retriveAllPrazosOpened();

            foreach ($result as $destinatario) {
                $dispatch[$destinatario['ID_DESTINATARIO']][] = array_change_key_case($destinatario, CASE_LOWER);
            }

            unset($destinatario);

            foreach ($dispatch as $destinatario) {

                $content = '';

                foreach ($destinatario as $record) {

                    $content .= sprintf("<strong>N. Proc/Dig. Ref.: </strong>%s<br><strong>Unid. Origem: </strong>%s<br>
                                        <strong>Unid. Destino: </strong>%s<br><strong>Remetente: </strong>%s<br>
                                        <strong>Solicitação: </strong>%s<br><strong>Data do Prazo: </strong>%s<br>
                                        <strong>Dias Restantes: </strong>%s<br><hr>", $record['nu_referencia'], $record['nm_unidade_origem'], $record['nm_unidade_destino'], $record['nm_usuario_origem'], $record['tx_solicitacao'], $record['dt_prazo'], $record['dias_restantes']);
                }

                Email::factory()->sendEmail(
                        __EMAILLOGS__, $record['nm_usuario_origem'], array($record['tx_email_destino']), sprintf('Notificação Prazo SGDoc %s [%s]', __VERSAO__, microtime()), $content, true
                );
            }

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

}