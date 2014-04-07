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

class CFModelUnidade extends CFModelAbstract {

    /**
     * @var string
     */
    protected $_schema = 'sgdoc';

    /**
     * @var string
     */
    protected $_table = 'TB_UNIDADES';

    /**
     * @var string
     */
    protected $_primary = 'ID';

    /**
     * @var string
     */
    protected $_sequence = 'TB_UNIDADES_ID_SEQ';

    /**
     * @var array
     */
    protected $_fields = array(
        'NOME' => 'string',
        'SIGLA' => 'string',
        'UAAF' => 'integer',
        'CR' => 'integer',
        'SUPERIOR' => 'integer',
        'DIRETORIA' => 'integer',
        'TIPO' => 'integer',
        'UP' => 'integer',
        'CODIGO' => 'string',
        'UF' => 'integer',
        'EMAIL' => 'string',
        'ST_ATIVO' => 'integer',
        'UOP' => 'integer'
    );

    /**
     * @return $array
     */
    public function retriveUnidadeOrgaoPrincipal() {
        try {

            $stmt = $this->_conn->prepare("SELECT * FROM TB_UNIDADES WHERE ID = UOP ORDER BY NOME ASC");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

}