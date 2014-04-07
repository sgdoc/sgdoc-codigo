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

class CFModelControleAcesso extends CFModelAbstract {

    /**
     * @var string
     */
    protected $_schema = 'sgdoc';

    /**
     * @var string
     */
    protected $_table = 'TB_CONTROLE_ACESSO';

    /**
     * @var string
     */
    protected $_primary = 'ID';

    /**
     * @var string
     */
    protected $_sequence = 'TB_CONTROLE_ACESSO_ID_SEQ';

    /**
     * @var array
     */
    protected $_fields = array(
        'IP_ACESSO' => 'string',
        'ID_USUARIO' => 'integer',
        'DT_ACESSO' => 'date',
    );

    /**
     * @return string
     * @param integer $idUsuario
     * @param date $lastDateAccess
     */
    public function lastAccess($idUsuario) {

        try {

            $stmt = $this->_conn->prepare("select substr(dt_acesso::text,0,11)::text::date - 'now'::text::date as dias  from sgdoc.tb_controle_acesso where id_usuario = ? order by id desc limit 1");
            $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
            $stmt->execute();

            $response = $stmt->fetch();

            return empty($response) ? 9999999 : $response['DIAS'] * -1;
        } catch (PDOException $e) {
            throw $e;
        }
    }

}