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

class CFModelDocumentoCamposDemanda extends CFModelAbstract {

    /**
     * @var string
     */
    protected $_schema = 'sgdoc';

    /**
     * @var string
     */
    protected $_table = 'TB_DOCUMENTOS_CAMPOS';

    /**
     * @var string
     */
    protected $_primary = 'ID';

    /**
     * @var string
     */
    protected $_sequence = 'EXT__SNAS__TB_DOCUMENTOS_CAMPOS_ID_SEQ';

    /**
     * @var array
     */
    protected $_fields = array(
        'ID' => 'integer',
        'DIGITAL' => 'string',
        'ATIVO' => 'integer',
        'ID_PESSOA' => 'integer',
        'ID_PRIORIDADE' => 'integer',
        'TIPO' => 'string',
        'DT_ATIVACAO' => 'date',
        'DT_DESATIVACAO' => 'date'
    );

    /**
     * @todo Cria um campo extra conforme o tipo do campo
     * @param string $digital
     * @param int $idTabelaEstrangeira Registro que deverá ser guardado (Pessoa ou Prioridade
     * @param string $tipo: pode ser PA-Participante; PR-Prioridade
     * @return int O id da tabela
     * @throws PDOException
     */
    public function createAssociationWithDigital($digital, $idTabelaEstrangeira, $tipo) {
        try {

            $associacao = $this->getAssociacao($tipo);

            $stmt = $this->_conn->prepare("insert into sgdoc.ext__snas__tb_documentos_campos 
                                         (digital, {$associacao}, ativo, tipo) 
                                         values (?,?,1,?)");
            $stmt->bindParam(1, $digital, PDO::PARAM_STR);
            $stmt->bindParam(2, $idTabelaEstrangeira, PDO::PARAM_INT);
            $stmt->bindParam(3, $tipo, PDO::PARAM_STR);
            $stmt->execute();

            return $this->_conn->lastInsertId($this->_sequence ? $this->_sequence : "{$this->_schema}.{$this->_sequence}");
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @todo Atualiza um campo extra conforme o tipo do campo
     * @param string $digital
     * @param int $idTabelaEstrangeira Valor da tabela extrangeira
     * @param int $ativo
     * @param string $tipo: pode ser PA-Participante; PR-Prioridade
     * @return boolean
     * @throws PDOException
     */
    public function updateAssociationWithDigital($digital, $idTabelaEstrangeira, $ativo, $tipo) {
        try {
            
            $associacao = $this->getAssociacao($tipo);

            $stmt = $this->_conn->prepare("update sgdoc.ext__snas__tb_documentos_campos set 
                                            ativo = ?
                                          where {$associacao} = ?
                                            and digital = ? ");

            $stmt->bindParam(1, $ativo, PDO::PARAM_INT);
            $stmt->bindParam(2, $idTabelaEstrangeira, PDO::PARAM_INT);
            $stmt->bindParam(3, $digital, PDO::PARAM_STR);

            return (boolean) $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @param string $digital
     */
    public function disassociateAllByDigital($digital, $tipo) {
        try {
            $stmt = $this->_conn->prepare("update sgdoc.ext__snas__tb_documentos_campos set 
                                            ativo          = 0
                                            where digital  = ? 
                                              and tipo     = ?");
            $stmt->bindParam(1, $digital, PDO::PARAM_STR);
            $stmt->bindParam(2, $tipo, PDO::PARAM_INT);
            
            $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }


    /**
     * @todo Verifica se um campo já está cadastrado.
     * @param string $digital
     * @param int $idCampo Valor da tabela externa (Pessoa ou Prioridade)
     * @param string $tipo: pode ser PA-Participante; PR-Prioridade
     * @return bool
     * @throws PDOException
     */
    public function isExists($digital, $idCampo, $tipo) {
        try {
            
            $associacao = $this->getAssociacao($tipo);
            
            
            $stmt = $this->_conn->prepare("select ativo from sgdoc.ext__snas__tb_documentos_campos 
                                           where digital       = ?
                                             and {$associacao} = ?" );
            $stmt->bindParam(1, $digital, PDO::PARAM_INT);
            $stmt->bindParam(2, $idCampo, PDO::PARAM_INT);
            $stmt->execute();

            return (boolean) $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }
    
    /**
     * @todo Retorna o tipo do campo de um registro.
     * @param string $tipo: pode ser PA-Participante; PR-Prioridade
     * @return string Retorna o campo da tabela.
     */
    private function getAssociacao($tipo){

            switch ($tipo) {
                //Tipo participante
                case 'PA':
                    return "id_pessoa";
                    break;
                //Tipo prioridade
                case 'PR': 
                    return "id_prioridade";
                    break;
                default:
                    return "erro";
                    break;
            }
    }

}
