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

class CFModelDigital extends CFModelAbstract {

    /**
     * @var string
     */
    protected $_schema = 'sgdoc';

    /**
     * @var string
     */
    protected $_table = 'TB_DIGITAL';

    /**
     * @var string
     */
    protected $_primary = 'ID';

    /**
     * @var string
     */
    protected $_sequence = 'TB_DIGITAL_ID_SEQ';

    /**
     * @var array
     */
    protected $_fields = array(
        'DIGITAL' => 'string',
        'USO' => 'integer',
        'ID_USUARIO' => 'integer',
        'LOTE' => 'integer',
        'ID_UNIDADE' => 'integer',
    );

    /**
     * @return string
     * @param integer $idUnidade
     */
    public function next($idUnidade) {

        try {

            $stmt = $this->_conn->prepare("SELECT DIGITAL FROM TB_DIGITAL WHERE USO != '1' AND ID_UNIDADE = ? ORDER BY ID ASC LIMIT 1");
            $stmt->bindParam(1, $idUnidade, PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetch(PDO::FETCH_ASSOC);

            if (isset($response['DIGITAL'])) {
                return $response['DIGITAL'];
            }

            return false;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return boolean
     * @param string $digital
     * @param integer $idUnidade
     * @param integer $idUsuario
     */
    public function mark($digital, $idUnidade, $idUsuario) {

        try {

            $recover = current($this->findByParam(array(
                        'USO' => 0,
                        'DIGITAL' => $digital,
                        'ID_UNIDADE' => $idUnidade
            )));

            if (empty($recover)) {
                throw new Exception('Digital Indisponível!');
            }

            $recover->USO = 1;
            $recover->ID_USUARIO = $idUsuario;

            return $this->update((array) $recover);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return boolean
     * @param string $digital
     */
    public function available($digital) {
        try {

            $recover = current($this->findByParam(array(
                        'USO' => '0',
                        'DIGITAL' => $digital,
                        'ID_USUARIO' => 'NULL'
            )));

            return empty($recover);
        } catch (PDOException $e) {
            throw $e;
        }
    }

}