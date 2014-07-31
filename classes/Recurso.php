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

/**
 * Classe que armazena informacoes sobre um recurso da aplicacao
 *
 * @author jhonatan.flach <jhonatan.flach@icmbio.gov.br>
 */
class Recurso implements Zend_Acl_Resource_Interface {

    const TIPO_PAGINA = 1;
    const TIPO_DIALOG = 2;
    const TIPO_BOTAO = 3;
    const TIPO_ESPECIAL = 4;
    const TIPO_ABA = 5;
    const TIPO_POPUP = 6;
    const TIPO_PRINT = 7;

    public $contexto = null;
    public $id;
    public $nome;
    public $descricao;
    public $url;
    public $img;
    public $id_recurso_tipo;
    public $id_recurso_dialog;
    public $controlador;
    public $acao;
    public $dom_id;
    public $classe_assertion = null;

    /**
     *
     * @var array 
     */
    public $filhos;

    /**
     *
     * @var array 
     */
    public $abas;

    /**
     *
     * @var Recurso 
     */
    public $dialog;

    /**
     *
     * @var array
     */
    public $dependencias = array();

    /**
     * Construtor que recebe uma array e coloca os valores nas propriedades 
     * 
     * @param Array $array
     */
    public function __construct(Array $array = null) {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Metodo magico para dar get nos valores de propriedades desta classe
     * 
     * @param string $var
     * @throw Exception
     */
    public function __get($var) {
        $var = strtolower($var);
        if (property_exists(get_class(), $var)) {
            return $this->$var;
        } else {
            throw new Exception('Tentativa de pegar valor de propriedade inexistente: ' . $var);
        }
    }

    /**
     * Metodo magico para settar valores para propriedades desta classe
     * somente funciona se a propriedade existir
     * 
     * @param string $var
     * @param mixed $value
     * @throws Exception
     */
    public function __set($var, $value) {
        $var = strtolower($var);
        if (property_exists(get_class(), $var)) {
            $this->$var = $value;
        } else {
            throw new Exception('Tentativa de declarar valor em propriedade inexistente: ' . $var);
        }
    }

    /**
     * 
     * @param Recurso $aba 
     */
    public function addAba(Recurso $aba) {
        $this->abas[$aba->id] = $aba;
    }

    /**
     *
     * @param Recurso $dependencia 
     */
    public function addDependencia(Recurso $dependencia) {
        $this->dependencias[$dependencia->id] = $dependencia->url;
    }

    /**
     * 
     * @return boolean 
     */
    public function hasDomId() {
        if (!is_null($this->dom_id)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @return boolean 
     */
    public function hasClasseAssertion() {
        if (!is_null($this->classe_assertion) && $this->classe_assertion != '') {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @return boolean 
     */
    public function hasDialog() {
        if (!is_null($this->id_recurso_dialog)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @return boolean 
     */
    public function hasUrl() {
        if (!is_null($this->url)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @return boolean 
     */
    public function hasImage() {
        if (!is_null($this->img) && $this->img != '') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Metodo que retorna o link do recurso
     * @return string 
     */
    public function getLink() {
        if ($this->url && (!$this->hasDomId())) {
            return '"' . $this->url . '"';
        } else {
            return '"#"';
        }
    }

    /**
     * Metodo que retorna o id do botao no codigo DOM da pagina
     * @return string
     */
    public function getDomId() {
        if ($this->hasDomId()) {
            return $this->dom_id;
        } else {
            return "botao_{$this->id}";
        }
    }

    public function getResourceId() {
        return $this->id;
    }

    public function getContexto($justLast = true) {
        $this->contexto = Controlador::getInstance()->getContexto($justLast);
        return $this->contexto;
    }

}