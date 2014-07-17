<?php

class Minuta {

    private $_html;
    private $_idMinuta;

    public function setHtml($tipologia)
    {
        $tipologia = strtolower($tipologia);
        $this->_html = (file_get_contents(__BASE_PATH__ . "/public/templates/minutas/{$tipologia}.html"));
    }

    public function setIdMinuta($idMinuta) {
        $this->_idMinuta = $idMinuta;
    }

    /**
     * É necessário definir o HTML e o idUnidade antes para acessar este método.
     * return String.
     */
    public function getTemplate() {
        $dados = $this->dadosMinutaTemplate();

        $dados['__ASSINATURA__'] = strtoupper($dados['__ASSINATURA__']);
        $dados['__CARGO__'] = ucwords($dados['__CARGO__']);

        $dataCriacao = strtotime($dados['__DATA_CRIACAO__']);

        $dia = date('d', $dataCriacao);
        $mes = Util::converteMesBr(date('m', $dataCriacao));
        $ano = date('Y', $dataCriacao);

        $dados['__DATA_CRIACAO__'] = "{$dia} de {$mes} de {$ano}";
        $dados['__ANO__'] = $ano;

        $this->setHtml($dados['__TIPOLOGIA__']);

        $html = strtr($this->_html, $dados);

        return $html;
    }
    public function dadosMinuta() {
        $manterMinuta = array();
        $manterMinuta['ID'] = $this->_idMinuta;
        $rs = DaoDocumentoMinuta::getMinutaCompleta($manterMinuta);

        $hierarquiaUnidade = new UnidadeHierarquia($rs->result[0]['ID_UNIDADE']);

        $rs->result[0]['HIERARQUIA_UNIDADE_NOME'] = $hierarquiaUnidade->getHierarquiaDecrescente();
        $rs->result[0]['HIERARQUIA_UNIDADE_SIGLA'] = $hierarquiaUnidade->getHierarquiaCrescente();

        $result = array_map('utf8_encode', $rs->result[0]);

        return $result;
    }
    public function dadosMinutaTemplate() {
        $dados = $this->dadosMinuta();
        $customData = array();
        array_walk($dados, function($val, $key) use(&$customData) {
                    $customData["__{$key}__"] = $val;
                });
        return $customData;
    }
    static public function utf8ToIso($params)
    {
        $customData = array();
        array_walk($params, function($val, $key) use(&$customData) {
                    $customData[$key] = mb_convert_encoding($val, 'ISO-8859-1', 'UTF-8');
                });
        return $customData;
    }
}