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

class CFModelUsuarioUnidade extends CFModelAbstract {

    /**
     * @var string
     */
    protected $_schema = 'sgdoc';

    /**
     * @var string
     */
    protected $_table = 'TB_USUARIOS_UNIDADES';

    /**
     * @var string
     */
    protected $_primary = 'ID';

    /**
     * @var string
     */
    protected $_sequence = 'TB_USUARIOS_UNIDADES_ID_SEQ';

    /**
     * @var array
     */
    protected $_fields = array(
        'ID_USUARIO' => 'integer',
        'ID_UNIDADE' => 'integer',
        'ST_ATIVO' => 'integer'
    );

    /**
     * @return array
     * @param integer $id
     */
    public function retrieveUnitsAvailableByIdUser($id) {
        try {
            $stmt = $this->_conn->prepare("select un.id, un.nome from sgdoc.tb_usuarios_unidades uxu
                                            inner join sgdoc.tb_usuarios us on us.id = uxu.id_usuario
                                            inner join sgdoc.tb_unidades un on un.id = uxu.id_unidade
                                           where un.st_ativo = 1 and uxu.st_ativo = 1 and us.id = ? order by un.nome");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return integer
     * @param integer $idUsuario
     * @param integer $idUnidade
     */
    public function createUserAssociationWithUnit($idUsuario, $idUnidade) {
        try {

            $stmt = $this->_conn->prepare("insert into sgdoc.tb_usuarios_unidades (id_usuario,id_unidade,st_ativo) values (?,?,1)");
            $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(2, $idUnidade, PDO::PARAM_INT);
            $stmt->execute();

            return $this->_conn->lastInsertId($this->_sequence ? $this->_sequence : "{$this->_schema}.{$this->_sequence}");
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return integer
     * @param integer $idUsuario
     * @param integer $idUnidade
     * @param integer $stAtivo
     */
    public function updateUserAssociationWithUnit($idUsuario, $idUnidade, $stAtivo) {
        try {

            $stmt = $this->_conn->prepare("update sgdoc.tb_usuarios_unidades set st_ativo = ? where id_usuario = ? and id_unidade = ?");
            $stmt->bindParam(1, $stAtivo, PDO::PARAM_INT);
            $stmt->bindParam(2, $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(3, $idUnidade, PDO::PARAM_INT);
            return (boolean) $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @param integer $idUsuario
     */
    public function disassociateAllByUserId($idUsuario) {
        try {
            $stmt = $this->_conn->prepare("update sgdoc.tb_usuarios_unidades set st_ativo = 0 where id_usuario = ?");
            $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return boolean
     * @param integer $idUsuario
     * @param integer $idUnidade
     */
    public function isAssociated($idUsuario, $idUnidade) {
        try {
            $stmt = $this->_conn->prepare("select st_ativo from sgdoc.tb_usuarios_unidades where id_usuario = ? and id_unidade = ? and st_ativo = 1 limit 1");
            $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(2, $idUnidade, PDO::PARAM_INT);
            $stmt->execute();
            return is_array($stmt->fetch(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return boolean
     * @param integer $idUsuario
     * @param integer $idUnidade
     */
    public function isExists($idUsuario, $idUnidade) {
        try {
            $stmt = $this->_conn->prepare("select st_ativo from sgdoc.tb_usuarios_unidades where id_usuario = ? and id_unidade = ? limit 1");
            $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(2, $idUnidade, PDO::PARAM_INT);
            $stmt->execute();
            return (boolean) $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

}