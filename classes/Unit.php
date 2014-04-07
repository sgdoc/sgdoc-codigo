<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
final class Unit {

    /**
     * @var integer
     */
    public $ID;

    /**
     * @var string
     */
    public $NOME;

    /**
     * @var string
     */
    public $SIGLA;

    /**
     * @var integer
     */
    public $UAAF;

    /**
     * @var integer
     */
    public $CR;

    /**
     * @var integer
     */
    public $SUPERIOR;

    /**
     * @var integer
     */
    public $DIRETORIA;

    /**
     * @var integer
     */
    public $TIPO;

    /**
     * @var integer
     */
    public $UP;

    /**
     * @var string
     */
    public $CODIGO;

    /**
     * @var integer
     */
    public $UF;

    /**
     * @var string
     */
    public $EMAIL;

    /**
     * @var boolean
     */
    public $ST_ATIVO;

    /**
     * @var integer
     */
    public $UOP;

    /**
     * @return void
     */
    private function __construct() {
        
    }

    /**
     * @return Unit
     */
    public static function factory() {
        return new self();
    }

    /**
     * @return boolean
     */
    public function isValid($target = self) {

        //campos obricatorios...
        if (!$target->ID || !$target->NOME || !$target->SIGLA || !isset($target->ST_ATIVO) || !$target->TIPO || !$target->UF || !isset($target->UP)) {
            return false;
        }

        //se unidade protocolizadora entao o codigo deve conter 5 caracteres obrigatoriamente...
        if ($this->UP == 1 && strlen($this->CODIGO) != 5) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     * @param string $attribute
     * @throws Exception
     */
    public function __get($attribute) {
        if (!property_exists(__CLASS__, $attribute)) {
            throw new Exception("A entidade TB_UNIDADES não possui o atributo {$attribute}");
        }
    }

    /**
     * @return void
     * @param string $attribute
     * @param string $value
     * @throws Exception
     */
    public function __set($attribute, $value) {
        if (!property_exists(__CLASS__, $attribute)) {
            throw new Exception("A entidade TB_UNIDADES não possui o atributo {$attribute}");
        }
    }

}