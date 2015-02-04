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

class CFModelControlePrazosDemanda extends CFModelAbstract {

    /**
     * @var string
     */
    protected $_schema = 'sgdoc';

    /**
     * @var string
     */
    protected $_table = 'TB_CONTROLE_PRAZOS';

    /**
     * @var string
     */
    protected $_primary = 'SQ_PRAZO';

    /**
     * @var string
     */
    protected $_sequence = 'TB_CONTROLE_PRAZOS_SQ_PRAZO_SEQ';

    /**
     * @var array
     */
    protected $_fields = array(
        'NU_PROC_DIG_REF' => 'string',
        'NU_PROC_DIG_RES' => 'string',
        'ID_USUARIO_DESTINO' => 'integer',
        'ID_USUARIO_ORIGEM' => 'integer',
        'ID_USUARIO_RESPOSTA' => 'integer',
        'ID_UNIDADE_USUARIO_RESPOSTA' => 'integer',
        'ID_UNID_ORIGEM' => 'integer',
        'ID_UNID_DESTINO' => 'integer',
        'DT_PRAZO' => 'date',
        'DT_RESPOSTA' => 'date',
        'FG_STATUS' => 'string',
        'TX_RESPOSTA' => 'string',
        'TX_SOLICITACAO' => 'string',

    );

    /**
     * @return PDOStatement
     * @param integer $idUsuario
     * @param integer $idUnidade
     */
    public function retrivePrazosIndividualByIdUnidade($idUsuario, $idUnidade) {
        try {

            $stmt = $this->_conn->prepare("SELECT SQ_PRAZO AS ID, DATE_PART('day', DT_PRAZO::timestamp - CURRENT_DATE::timestamp) AS DIAS_RESTANTES, "
                    . " to_char(DT_PRAZO, 'DD/MM/YYYY') AS DT_PRAZO "
                    . " FROM TB_CONTROLE_PRAZOS WHERE DATE_PART('day', DT_PRAZO::timestamp - CURRENT_DATE::timestamp) < 0 AND "
                    . " FG_STATUS = 'AR' AND ID_USUARIO_DESTINO = ? AND ID_UNID_DESTINO = ?");
            $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(2, $idUnidade, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return array
     * @param integer $idUnidade
     */
    public function retrivePrazosVencidosUnidadeById($idUnidade) {
        try {

            $stmt = $this->_conn->prepare("SELECT SQ_PRAZO AS ID, DATE_PART('day', DT_PRAZO::timestamp - CURRENT_DATE::timestamp) AS DIAS_RESTANTES, "
                    . "to_char(DT_PRAZO, 'DD/MM/YYYY') AS DT_PRAZO FROM TB_CONTROLE_PRAZOS WHERE "
                    . " DATE_PART('day', DT_PRAZO::timestamp - CURRENT_DATE::timestamp) < 0 AND FG_STATUS = 'AR' AND ID_UNID_DESTINO = ? "
                    . " AND (ID_USUARIO_DESTINO IS NULL OR ID_USUARIO_DESTINO = 0)");
            $stmt->bindParam(1, $idUnidade, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return array
     * @param integer $idUsuario
     * @param integer $idUnidade
     */
    public function retrivePrazosPendentesByIdUsuario($idUsuario, $idUnidade) {
        try {

            $stmt = $this->_conn->prepare("SELECT SQ_PRAZO AS ID, DATE_PART('day', DT_PRAZO::timestamp - CURRENT_DATE::timestamp) AS DIAS_RESTANTES, "
                    . "to_char(DT_PRAZO, 'DD/MM/YYYY') AS DT_PRAZO FROM TB_CONTROLE_PRAZOS WHERE "
                    . "DATE_PART('day', DT_PRAZO::timestamp - CURRENT_DATE::timestamp) >= 0 AND FG_STATUS = 'AR' AND "
                    . "ID_USUARIO_DESTINO = ? AND ID_UNID_DESTINO = ?");
            $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(2, $idUnidade, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return array
     * @param integer $idUnidade
     */
    public function retrivePrazosPendentesByIdUnidade($idUnidade) {
        try {

            $stmt = $this->_conn->prepare("SELECT SQ_PRAZO AS ID, DATE_PART('day', DT_PRAZO::timestamp - CURRENT_DATE::timestamp) AS DIAS_RESTANTES, "
                    . "to_char(DT_PRAZO, 'DD/MM/YYYY') AS DT_PRAZO FROM TB_CONTROLE_PRAZOS WHERE "
                    . "DATE_PART('day', DT_PRAZO::timestamp - CURRENT_DATE::timestamp) >= 0 AND FG_STATUS = 'AR' "
                    . "AND ID_UNID_DESTINO = ? AND (ID_USUARIO_DESTINO = 0 OR ID_USUARIO_DESTINO IS NULL)");
            $stmt->bindParam(1, $idUnidade, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return array
     * @param integer $idPrazo
     * @param string $status
     */
    public function retrivePrazoById($idPrazo, $status = 'AR') {
        try {

            $stmt = $this->_conn->prepare("SELECT 
                                        CP.NU_PROC_DIG_REF AS NU_REFERENCIA,
                                        CP.TX_SOLICITACAO AS TX_SOLICITACAO,
                                        USR.EMAIL AS TX_EMAIL_DESTINO,
                                        USR.NOME AS NM_USUARIO_DESTINO,
                                        US.EMAIL AS TX_EMAIL_ORIGEM,
                                        US.NOME AS NM_USUARIO_ORIGEM,
                                        UN.NOME AS NM_UNIDADE_ORIGEM,
                                        UND.NOME AS NM_UNIDADE_DESTINO,
                                        to_char(DT_PRAZO, 'DD/MM/YYYY') AS DT_PRAZO,
                                        DATE_PART('day', DT_PRAZO::timestamp - CURRENT_DATE::timestamp) AS DIAS_RESTANTES
                                    FROM TB_CONTROLE_PRAZOS AS CP
                                        LEFT JOIN TB_UNIDADES UN ON UN.ID = CP.ID_UNID_ORIGEM
                                        LEFT JOIN TB_UNIDADES UND ON UND.ID = CP.ID_UNID_DESTINO
                                        LEFT JOIN TB_USUARIOS US ON US.ID = CP.ID_USUARIO_ORIGEM
                                        LEFT JOIN TB_USUARIOS USR ON USR.ID = CP.ID_USUARIO_DESTINO
                                    WHERE SQ_PRAZO = ? AND CP.FG_STATUS = ? LIMIT 1");
            $stmt->bindParam(1, $idPrazo, PDO::PARAM_INT);
            $stmt->bindParam(2, $status, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return array
     */
    public function retriveAllPrazosOpened() {
        try {

            $stmt = $this->_conn->prepare("SELECT 
                                        USR.ID AS ID_DESTINATARIO,
                                        CP.NU_PROC_DIG_REF AS NU_REFERENCIA,
                                        CP.TX_SOLICITACAO AS TX_SOLICITACAO,
                                        USR.EMAIL AS TX_EMAIL_DESTINO,
                                        USR.NOME AS NM_USUARIO_DESTINO,
                                        US.EMAIL AS TX_EMAIL_ORIGEM,
                                        US.NOME AS NM_USUARIO_ORIGEM,
                                        UN.NOME AS NM_UNIDADE_ORIGEM,
                                        UND.NOME AS NM_UNIDADE_DESTINO,
                                        to_char(DT_PRAZO, 'DD/MM/YYYY') AS DT_PRAZO,
                                        DATE_PART('day', DT_PRAZO::timestamp - CURRENT_DATE::timestamp) AS DIAS_RESTANTES
                                    FROM TB_CONTROLE_PRAZOS AS CP
                                        LEFT JOIN TB_UNIDADES UN ON UN.ID = CP.ID_UNID_ORIGEM
                                        LEFT JOIN TB_UNIDADES UND ON UND.ID = CP.ID_UNID_DESTINO
                                        LEFT JOIN TB_USUARIOS US ON US.ID = CP.ID_USUARIO_ORIGEM
                                        LEFT JOIN TB_USUARIOS USR ON USR.ID = CP.ID_USUARIO_DESTINO
                                    WHERE CP.FG_STATUS = 'AR' AND USR.EMAIL != '' ORDER BY ID_DESTINATARIO, DIAS_RESTANTES");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

}