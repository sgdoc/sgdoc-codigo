<?php

//@todo Verificar do que se trata essa class, para que seja feita a distribuicao do sgdoc no portal do software publico...
//adicioanar idUnidade no get quando for usar o serviço.
//https://dsvm.sgdoc.icmbio.gov.br/sgdoc/webservices/siorg/client.php?idUnidade=26

class Siorg {

    //private $_arquivo;
    private $_conn = null;
    private $_wsConsultaOrgao;
    private $_contador = 1;

    public function getConn() {
        return $this->_conn;
    }

    public function __construct() {
        if (null === $this->_conn) {
            try {
                $this->_conn = Controlador::getInstance()->getConnection()->connection;
            } catch (PDOException $e) {
                var_dump($e->getMessage());
            }
        }

        try {
            $this->_wsConsultaOrgao = new SoapClient('http://www.siorg.redegoverno.gov.br/gestao/webservice/WSSiorg.asmx?WSDL', array(
                'soap_version' => SOAP_1_2,
                'exceptions' => true,
                'trace' => 1
            ));
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        //NÃ£o precisa mais criar tabela, sistema jÃ¡ estÃ¡ estÃ¡vel.
    }

    /**
     * Retorna webService referente a natureza juridica.
     * o serviço necessita de dois parametros:
     * vPa_Co_Natureza_Juridica -> CO_NAT_JURIDICA da natureza juridica -> obtido pelo serviço ConsultaOrgao
     * vPa_Tipo_Tratamento -> IN_EXTINTO do tipo podem ser N ou P, não sei do que se trata, 
     * coloquei N e funcionou conforme válidações antigas.
     * @param int $id
     * @return string pa_nome
     */
    public function getWsConsultaNaturezaJuridica($id, $inExtinto = 'N') {
        $consultaDadosFilhos = $this->_wsConsultaOrgao->ConsultaNaturezaJuridica(array('vPa_Co_Natureza_Juridica' => $id, 'vPa_Tipo_Tratamento' => $inExtinto));
        $rsConsultaOrgao = $consultaDadosFilhos->ConsultaNaturezaJuridicaResult;
        return Siorg::trataCaracteres($rsConsultaOrgao->pa_nome);
    }

    /**
     * Retorna webService referente a tipo do orgão
     * o serviço necessita de dois parametros:
     * vPa_Co_Tipo_Orgao -> CO_TIPO_ORGAO do tipo do orgão -> obtido pelo serviço ConsultaOrgao
     * vPa_Tipo_Tratamento -> IN_EXTINTO -> tipo podem ser N ou P, não sei do que se trata, 
     * coloquei N e funcionou conforme válidações antigas.
     * @param int $id
     * @return string pa_nome
     */
    public function getWsConsultaTipoOrgao($id, $inExtinto = 'N') {
        $consultaDadosFilhos = $this->_wsConsultaOrgao->ConsultaTipoOrgao(array('vPa_Co_Tipo_Orgao' => $id, 'vPa_Tipo_Tratamento' => $inExtinto));
        $rsConsultaOrgao = $consultaDadosFilhos->ConsultaTipoOrgaoResult;
        return Siorg::trataCaracteres($rsConsultaOrgao->pa_nome);
    }

    /**
     * Retorna os orgãoes e seus respectivos filhos.
     * o serviço necessita de um parametros:
     * pOrgao -> CO_ORGAO -> id do orgão -> obtido pelo serviço ConsultaOrgao
     * @param int $id
     * @return array
     */
    public function getWsConsultaOrgaoFilhos($objPai) {
        if (isset($objPai->co_orgao) && $objPai->co_orgao) {
            $wsFilhos = $this->_wsConsultaOrgao->consultaFilhos(array('pCo_Orgao_Pai' => $objPai->co_orgao));
            if (isset($wsFilhos->consultaFilhosResult) && !empty($wsFilhos->consultaFilhosResult)) {
                $arrRs = $wsFilhos->consultaFilhosResult;
                $arrFilhos = explode('^', $arrRs);
                $siorg = $this;
                array_walk($arrFilhos, function($value, $key) use (&$objPai, $siorg) {
                            $match = array();
                            $string = Siorg::trataCaracteres($value);
                            $regex = '/[0-9]{0,6}/';
                            preg_match($regex, $string, $match);
                            $value = $match[0];
                            if (isset($value) && !empty($value)) {
                                $obj = $siorg->getWsConsultaOrgao($value);
                                $objPai->filhos["{$value}"] = $siorg->getWsConsultaOrgaoFilhos($obj);
                            }
                        });
            }
        }
        return $objPai;
    }

    /**
     * Retorna os orgãoes e seus respectivos filhos.
     * o serviço necessita de um parametros:
     * pOrgao -> CO_ORGAO -> id do orgão -> obtido pelo serviço ConsultaOrgao
     * @param int $id
     * @return array
     */
    public function getWsConsultaOrgao($id) {
        $consultaDadosFilhos = null;
        $rsConsultaOrgao = null;
        try {

            if ($this->_contador % 1000 == 0) {
                sleep(1);
                $this->getConn()->commit();
                $this->getConn()->beginTransaction();
            }

            $consultaDadosFilhos = $this->_wsConsultaOrgao->ConsultaOrgao(array('pOrgao' => $id));
//            echo '<pre>';var_dump($consultaDadosFilhos);die;
            $rsConsultaOrgao = $consultaDadosFilhos->ConsultaOrgaoResult;
//            echo '<pre>';var_dump($rsConsultaOrgao);die;
//            var_dump($rsConsultaOrgao);die;
            if ($rsConsultaOrgao->co_erro == '000') {
                if (isset($rsConsultaOrgao->co_tipo_orgao) && $rsConsultaOrgao->in_extinto) {
                    $rsConsultaOrgao->no_tipo_orgao = $this->getWsConsultaTipoOrgao($rsConsultaOrgao->co_tipo_orgao, $rsConsultaOrgao->in_extinto);
                }
                if (isset($rsConsultaOrgao->co_nat_juridica) && $rsConsultaOrgao->in_extinto) {
                    $rsConsultaOrgao->no_nat_juridica = $this->getWsConsultaNaturezaJuridica($rsConsultaOrgao->co_nat_juridica, $rsConsultaOrgao->in_extinto);
                }
                if (isset($rsConsultaOrgao->co_orgao) && !empty($rsConsultaOrgao->co_orgao)) {

                    $this->salvar($rsConsultaOrgao, $rsConsultaOrgao->co_orgao);
                    echo ' * OK - ' . $this->_contador . ' [ ' . $rsConsultaOrgao->co_orgao . " -> " . $rsConsultaOrgao->no_orgao . " * " . $rsConsultaOrgao->no_tipo_orgao . ' ]' . PHP_EOL;
                    $this->_contador = ++$this->_contador;
                }
            } else {
                $log = "[{$id}] - " . $rsConsultaOrgao->tx_mensagem_erro . PHP_EOL;
                //file_put_contents('log.txt', $log);
                //echo $log;
                throw new Exception($rsConsultaOrgao->tx_mensagem_erro, $rsConsultaOrgao->co_erro);
            }
        } catch (Exception $e) {

            echo $e->getMessage() . "<br />";

            echo '<pre>';
            var_dump($consultaDadosFilhos);
            echo '</pre>';
            echo '<pre>';
            var_dump($rsConsultaOrgao);
            echo '</pre>';

            $log = "[{$id}]Problema ao salvar o registro vindo do webservice siorg! * " . $e->getMessage() . "<br />" . PHP_EOL;
            //file_put_contents('log.txt', $log);
            //$importa = new Siorg();
//            $orgao = $this->getWsConsultaOrgao($id);
//            $this->getWsConsultaOrgaoFilhos($orgao);

            echo $log;
        }
        return $rsConsultaOrgao;
    }

    //09549037

    /**
     * Retorna os orgãoes e seus respectivos filhos.
     * o serviço necessita de um parametros:
     * pOrgao -> CO_ORGAO -> id do orgão -> obtido pelo serviço ConsultaOrgao
     * @param int $id
     * @return array
     */
    public function getWsConsultaOrgaoTest($id) {
        $consultaDadosFilhos = $this->_wsConsultaOrgao->ConsultaOrgao(array('pOrgao' => $id));
        $rsConsultaOrgao = $consultaDadosFilhos->ConsultaOrgaoResult;
        if (isset($rsConsultaOrgao->co_tipo_orgao) && $rsConsultaOrgao->in_extinto)
            $rsConsultaOrgao->no_tipo_orgao = $this->getWsConsultaTipoOrgao($rsConsultaOrgao->co_tipo_orgao, $rsConsultaOrgao->in_extinto);
        if (isset($rsConsultaOrgao->co_nat_juridica) && $rsConsultaOrgao->in_extinto)
            $rsConsultaOrgao->no_nat_juridica = $this->getWsConsultaNaturezaJuridica($rsConsultaOrgao->co_nat_juridica, $rsConsultaOrgao->in_extinto);

        return $rsConsultaOrgao;
    }

    public function insert() {
        $sql = "INSERT INTO TB_PESSOA_SIORG (CO_ORGAO, 
                                             CO_ORGAO_PAI, 
                                             CO_TIPO_ORGAO, 
                                             CO_NAT_JURIDICA, 
                                             SG_CLASSE,
                                             CO_ORGAO_TOPO,
                                             CO_ORGAO_ANTECESSOR,
                                             TX_ESTRUTURA_ORGAO,
                                             IN_ORGANIZACAO,
                                             NO_ORGAO,
                                             NO_ORGAO_REDUZIDO,
                                             SG_ORGAO,
                                             TX_OBSERVACOES,
                                             IN_EXTINTO,
                                             NU_LEI_CRIACAO,
                                             DA_LEI_CRIACAO,
                                             CO_ORGAO_LEI_CRIACAO,
                                             CO_TIPO_DL_CRIACAO,
                                             NU_LEI_EXTINCAO,
                                             DA_LEI_EXTINCAO,
                                             CO_ORGAO_LEI_EXTINCAO,
                                             CO_TIPO_DL_EXTINCAO,
                                             TX_ENDERECO,
                                             TX_COMPLEMENTO,
                                             TX_BAIRRO,
                                             NO_CIDADE,
                                             SG_UF,
                                             NO_PAIS,
                                             NU_CEP,
                                             TX_DDD,
                                             NU_FONES,
                                             NU_FAX,
                                             NU_TELEX,
                                             CH_EMAIL_INTERNET,
                                             TX_ENDERECO_WWW,
                                             NO_TIPO_ORGAO,
                                             DS_NATUREZA_JURIDICA
                                   ) VALUES (:CO_ORGAO,
                                             :CO_ORGAO_PAI,
                                             :CO_TIPO_ORGAO,
                                             :CO_NAT_JURIDICA,
                                             :SG_CLASSE,
                                             :CO_ORGAO_TOPO,
                                             :CO_ORGAO_ANTECESSOR,
                                             :TX_ESTRUTURA_ORGAO,
                                             :IN_ORGANIZACAO,
                                             :NO_ORGAO,
                                             :NO_ORGAO_REDUZIDO,
                                             :SG_ORGAO,
                                             :TX_OBSERVACOES,
                                             :IN_EXTINTO,
                                             :NU_LEI_CRIACAO,
                                             :DA_LEI_CRIACAO,
                                             :CO_ORGAO_LEI_CRIACAO,
                                             :CO_TIPO_DL_CRIACAO,
                                             :NU_LEI_EXTINCAO,
                                             :DA_LEI_EXTINCAO,
                                             :CO_ORGAO_LEI_EXTINCAO,
                                             :CO_TIPO_DL_EXTINCAO,
                                             :TX_ENDERECO,
                                             :TX_COMPLEMENTO,
                                             :TX_BAIRRO,
                                             :NO_CIDADE,
                                             :SG_UF,
                                             :NO_PAIS,
                                             :NU_CEP,
                                             :TX_DDD,
                                             :NU_FONES,
                                             :NU_FAX,
                                             :NU_TELEX,
                                             :CH_EMAIL_INTERNET,
                                             :TX_ENDERECO_WWW,
                                             :NO_TIPO_ORGAO,
                                             :DS_NATUREZA_JURIDICA)";
        echo 'INSERT';
        return $sql;
    }

    public function update() {
        $sql = "UPDATE TB_PESSOA_SIORG SET CO_ORGAO_PAI = :CO_ORGAO_PAI, 
                                           CO_TIPO_ORGAO = :CO_TIPO_ORGAO,
                                           CO_NAT_JURIDICA = :CO_NAT_JURIDICA,
                                           SG_CLASSE = :SG_CLASSE,CO_ORGAO_TOPO = :CO_ORGAO_TOPO,
                                           CO_ORGAO_ANTECESSOR = :CO_ORGAO_ANTECESSOR,
                                           TX_ESTRUTURA_ORGAO = :TX_ESTRUTURA_ORGAO,
                                           IN_ORGANIZACAO = :IN_ORGANIZACAO,
                                           NO_ORGAO = :NO_ORGAO,
                                           NO_ORGAO_REDUZIDO= :NO_ORGAO_REDUZIDO,
                                           SG_ORGAO = :SG_ORGAO, 
                                           TX_OBSERVACOES = :TX_OBSERVACOES,
                                           IN_EXTINTO = :IN_EXTINTO,
                                           NU_LEI_CRIACAO = :NU_LEI_CRIACAO,
                                           DA_LEI_CRIACAO = :DA_LEI_CRIACAO,
                                           CO_ORGAO_LEI_CRIACAO = :CO_ORGAO_LEI_CRIACAO,
                                           CO_TIPO_DL_CRIACAO = :CO_TIPO_DL_CRIACAO,
                                           NU_LEI_EXTINCAO = :NU_LEI_EXTINCAO,
                                           DA_LEI_EXTINCAO = :DA_LEI_EXTINCAO,
                                           CO_ORGAO_LEI_EXTINCAO = :CO_ORGAO_LEI_EXTINCAO,
                                           CO_TIPO_DL_EXTINCAO = :CO_TIPO_DL_EXTINCAO,
                                           TX_ENDERECO = :TX_ENDERECO,
                                           TX_COMPLEMENTO= :TX_COMPLEMENTO,
                                           TX_BAIRRO = :TX_BAIRRO,
                                           NO_CIDADE= :NO_CIDADE,
                                           SG_UF= :SG_UF,
                                           NO_PAIS = :NO_PAIS,
                                           NU_CEP = :NU_CEP,
                                           TX_DDD = :TX_DDD,
                                           NU_FONES = :NU_FONES,
                                           NU_FAX = :NU_FAX,
                                           NU_TELEX = :NU_TELEX,
                                           CH_EMAIL_INTERNET = :CH_EMAIL_INTERNET,
                                           TX_ENDERECO_WWW = :TX_ENDERECO_WWW,
                                           NO_TIPO_ORGAO = :NO_TIPO_ORGAO,
                                           DS_NATUREZA_JURIDICA = :DS_NATUREZA_JURIDICA 
                                     WHERE CO_ORGAO = :CO_ORGAO";
        echo 'UPDATE';
        return $sql;
    }

    public function salvar(stdClass $dados, $id = null) {
        $rsId = $this->getRegistrosDoBancoById($id);
        if ($rsId == $id) {
            $sql = $this->update($dados, $id);
        } else {
            $sql = $this->insert($dados);
        }
        $stmt = $this->_conn->prepare($sql);

        $stmt->bindValue(':CO_ORGAO', $dados->co_orgao, PDO::PARAM_STR);
        $stmt->bindValue(':CO_ORGAO_PAI', $dados->Co_Orgao_Pai, PDO::PARAM_STR);
        $stmt->bindValue(':CO_TIPO_ORGAO', $dados->co_tipo_orgao, PDO::PARAM_STR);
        $stmt->bindValue(':CO_NAT_JURIDICA', $dados->co_nat_juridica, PDO::PARAM_STR);
        $stmt->bindValue(':SG_CLASSE', self::trataCaracteres($dados->sg_classe), PDO::PARAM_STR);
        $stmt->bindValue(':CO_ORGAO_TOPO', $dados->co_orgao_topo, PDO::PARAM_STR);
        $stmt->bindValue(':CO_ORGAO_ANTECESSOR', $dados->co_orgao_antecessor, PDO::PARAM_STR);
        $stmt->bindValue(':TX_ESTRUTURA_ORGAO', self::trataCaracteres($dados->tx_estrutura_orgao), PDO::PARAM_STR);
        $stmt->bindValue(':IN_ORGANIZACAO', $dados->in_organizacao, PDO::PARAM_STR);
        $stmt->bindValue(':NO_ORGAO', self::trataCaracteres($dados->no_orgao), PDO::PARAM_STR);
        $stmt->bindValue(':NO_ORGAO_REDUZIDO', self::trataCaracteres($dados->no_orgao_reduzido), PDO::PARAM_STR);
        $stmt->bindValue(':SG_ORGAO', self::trataCaracteres($dados->sg_orgao), PDO::PARAM_STR);
        $stmt->bindValue(':TX_OBSERVACOES', self::trataCaracteres($dados->tx_observacoes), PDO::PARAM_STR);
        $stmt->bindValue(':IN_EXTINTO', $dados->in_extinto, PDO::PARAM_STR);
        $stmt->bindValue(':NU_LEI_CRIACAO', $dados->nu_lei_criacao, PDO::PARAM_STR);
        $stmt->bindValue(':DA_LEI_CRIACAO', $dados->da_lei_criacao, PDO::PARAM_STR);
        $stmt->bindValue(':CO_ORGAO_LEI_CRIACAO', $dados->co_orgao_lei_criacao, PDO::PARAM_STR);
        $stmt->bindValue(':CO_TIPO_DL_CRIACAO', $dados->co_tipo_dl_criacao, PDO::PARAM_STR);
        $stmt->bindValue(':NU_LEI_EXTINCAO', $dados->nu_lei_extincao, PDO::PARAM_STR);
        $stmt->bindValue(':DA_LEI_EXTINCAO', $dados->da_lei_extincao, PDO::PARAM_STR);
        $stmt->bindValue(':CO_ORGAO_LEI_EXTINCAO', $dados->co_orgao_lei_extincao, PDO::PARAM_STR);
        $stmt->bindValue(':CO_TIPO_DL_EXTINCAO', $dados->co_tipo_dl_extincao, PDO::PARAM_STR);
        $stmt->bindValue(':TX_ENDERECO', self::trataCaracteres($dados->tx_endereco), PDO::PARAM_STR);
        $stmt->bindValue(':TX_COMPLEMENTO', self::trataCaracteres($dados->tx_complemento), PDO::PARAM_STR);
        $stmt->bindValue(':TX_BAIRRO', self::trataCaracteres($dados->tx_bairro), PDO::PARAM_STR);
        $stmt->bindValue(':NO_CIDADE', self::trataCaracteres($dados->no_cidade), PDO::PARAM_STR);
        $stmt->bindValue(':SG_UF', $dados->sg_uf, PDO::PARAM_STR);
        $stmt->bindValue(':NO_PAIS', self::trataCaracteres($dados->no_pais), PDO::PARAM_STR);
        $stmt->bindValue(':NU_CEP', $dados->nu_cep, PDO::PARAM_STR);
        $stmt->bindValue(':TX_DDD', $dados->tx_ddd, PDO::PARAM_STR);
        $stmt->bindValue(':NU_FONES', self::trataCaracteres($dados->nu_fones), PDO::PARAM_STR);
        $stmt->bindValue(':NU_FAX', self::trataCaracteres($dados->nu_fax), PDO::PARAM_STR);
        $stmt->bindValue(':NU_TELEX', self::trataCaracteres($dados->nu_telex), PDO::PARAM_STR);
        $stmt->bindValue(':CH_EMAIL_INTERNET', self::trataCaracteres($dados->ch_email_internet), PDO::PARAM_STR);
        $stmt->bindValue(':TX_ENDERECO_WWW', self::trataCaracteres($dados->tx_endereco_www), PDO::PARAM_STR);
        $stmt->bindValue(':NO_TIPO_ORGAO', self::trataCaracteres($dados->no_tipo_orgao), PDO::PARAM_STR);
        $stmt->bindValue(':DS_NATUREZA_JURIDICA', self::trataCaracteres($dados->no_nat_juridica), PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * Trata caracteres utf8 para iso, este pode ser um parametro em array ou uma string.
     * @param array|string $rsConsultaOrgao
     * @return array|string
     */
    public static function trataCaracteres($rsConsultaOrgao) {

        $rsConsultaOrgao = preg_replace("/'/", "\'", $rsConsultaOrgao);
        $rsConsultaOrgao = preg_replace("/º/", "", $rsConsultaOrgao);

        return $rsConsultaOrgao;
    }

    /**
     * Veririca os registros do banco de dados para ver se estão ok.
     */
    public function getRegistrosDoBancoById($id) {
        $sql = 'SELECT * FROM TB_PESSOA_SIORG WHERE CO_ORGAO = :id';
        $query = $this->_conn->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $rs = $query->fetch();
        $id = (integer) $rs['CO_ORGAO'];
        return $id;
    }

    /**
     * Veririca os registros do banco de dados para ver se estão ok.
     */
    public function getRegistrosDoBanco() {
        $sql = 'SELECT * FROM TB_PESSOA_SIORG';
        $query = $this->_conn->query($sql);
        $rs = $query->fetchAll();

        $arrSiorg = function() use($rs) {
                    echo 'Testando...';
                    return $rs;
                };
        var_dump($arrSiorg());
    }

    /**
     * SINCRONIZAÇÃO DA BASE SIORG COM TB_UNIDADES
     * REMOVIDO DO PROCESSO DE IMPORTAÇÃO.
     * DEVE SER REALIZADO EM UM SEGUNDO MOMENTO
     */
    public function finalizacaoBase() {
        $sql1 = "
            insert into TB_UNIDADES(NOME, SIGLA, TIPO, CODIGO, UF, EMAIL, CO_SIORG)
                select siorg.NO_ORGAO,
                    siorg.SG_ORGAO,
                    tp.ID,
                    '',
                    coalesce(uf.ID, 27),
                    siorg.CH_EMAIL_INTERNET,
                    siorg.CO_SIORG
                from TB_PESSOA_SIORG siorg
                  left join TB_UNIDADES und on und.CO_SIORG = CO_ORGAO
                  join TB_UNIDADES_TIPO tp on trim(tp.TIPO) = trim(siorg.NO_TIPO_ORGAO)
                  left join TB_UF uf on uf.SIGLA_UF = siorg.SG_UF
                where und.ID is null
        ";
        $sql2 = "
            update TB_UNIDADES
            set
                NOME = concat(NOME, coalesce(concat(' - ', func_get_hierarq_siorg(select TB_PESSOA_SIORG.CO_ORGAO from TB_PESSOA_SIORG where CO_ORGAO = TB_UNIDADES.CO_SIORG)))), '')
        ";
        $sql3 = "
            update TB_UNIDADES, TB_PESSOA_SIORG
            set
                 NOME = concat(TB_PESSOA_SIORG.NO_ORGAO, coalesce(concat(BINARY ' - ' , func_get_hierarq_siorg((select TB_PESSOA_SIORG.CO_ORGAO from TB_PESSOA_SIORG where TB_PESSOA_SIORG.CO_SIORG = TB_UNIDADES.CO_SIORG)))), '')
            where TB_UNIDADES.CO_SIORG = TB_PESSOA_SIORG.CO_SIORG
        ";

        //$query->bindValue(':id', $id, PDO::PARAM_INT);
        $query1 = $this->_conn->prepare($sql1);
        $result1 = $query1->execute();
        $query2 = $this->_conn->prepare($sql2);
        $result2 = $query2->execute();
        $query3 = $this->_conn->prepare($sql3);
        $result3 = $query3->execute();

        echo "\nTratamento Hierarquia OK!\n";

        return $result;
    }

}

/**
 * 
 */
$importa = new Siorg();

$idUnidade = 26;

if (isset($_GET['idUnidade']) && !empty($_GET['idUnidade'])) {
    $idUnidade = $_GET['idUnidade'];
}

$importa->getConn()->beginTransaction();

try {
    $start = microtime(true);
    //26 é o id referente a presidencia, pai de todos.
    $objPai = $importa->getWsConsultaOrgao($idUnidade);

    $importa->getWsConsultaOrgaoFilhos($objPai);
    $end = microtime(true);
    $tempoProcessamento = number_format(($end - $start), 2);

    echo "OK GERAL!!! -> Tempo Gasto: " . $tempoProcessamento . ' segundos.';

    echo "<br />";
    echo "A BASE DO SIORG LOCAL FOI ATUALIZADA COM SUCESSO. <br />";
    echo "O PRÓXIMO PASSO A SER REALIZADO É SINCRONIZAR COM A TABELA DE UNIDADES DO SGDOC<br />";


    $importa->getConn()->commit();
} catch (PDOException $e) {
    var_dump($e->getMessage(), $e->getCode());
    echo "ERROR GERAL!!!";
}
