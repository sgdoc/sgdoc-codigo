<?php
/**
 * File for class QualitativoStructSubFuncaoDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructSubFuncaoDTO originally named subFuncaoDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructSubFuncaoDTO extends QualitativoWsdlClass
{
	/**
	 * The codigoFuncao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoFuncao;
	/**
	 * The codigoSubFuncao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoSubFuncao;
	/**
	 * The dataHoraAlteracao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var dateTime
	 */
	public $dataHoraAlteracao;
	/**
	 * The descricao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $descricao;
	/**
	 * The descricaoAbreviada
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $descricaoAbreviada;
	/**
	 * The exercicio
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $exercicio;
	/**
	 * The snAtivo
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snAtivo;
	/**
	 * Constructor method for subFuncaoDTO
	 * @see parent::__construct()
	 * @param string $_codigoFuncao
	 * @param string $_codigoSubFuncao
	 * @param dateTime $_dataHoraAlteracao
	 * @param string $_descricao
	 * @param string $_descricaoAbreviada
	 * @param int $_exercicio
	 * @param boolean $_snAtivo
	 * @return QualitativoStructSubFuncaoDTO
	 */
	public function __construct($_codigoFuncao = NULL,$_codigoSubFuncao = NULL,$_dataHoraAlteracao = NULL,$_descricao = NULL,$_descricaoAbreviada = NULL,$_exercicio = NULL,$_snAtivo = NULL)
	{
		parent::__construct(array('codigoFuncao'=>$_codigoFuncao,'codigoSubFuncao'=>$_codigoSubFuncao,'dataHoraAlteracao'=>$_dataHoraAlteracao,'descricao'=>$_descricao,'descricaoAbreviada'=>$_descricaoAbreviada,'exercicio'=>$_exercicio,'snAtivo'=>$_snAtivo));
	}
	/**
	 * Get codigoFuncao value
	 * @return string|null
	 */
	public function getCodigoFuncao()
	{
		return $this->codigoFuncao;
	}
	/**
	 * Set codigoFuncao value
	 * @param string $_codigoFuncao the codigoFuncao
	 * @return string
	 */
	public function setCodigoFuncao($_codigoFuncao)
	{
		return ($this->codigoFuncao = $_codigoFuncao);
	}
	/**
	 * Get codigoSubFuncao value
	 * @return string|null
	 */
	public function getCodigoSubFuncao()
	{
		return $this->codigoSubFuncao;
	}
	/**
	 * Set codigoSubFuncao value
	 * @param string $_codigoSubFuncao the codigoSubFuncao
	 * @return string
	 */
	public function setCodigoSubFuncao($_codigoSubFuncao)
	{
		return ($this->codigoSubFuncao = $_codigoSubFuncao);
	}
	/**
	 * Get dataHoraAlteracao value
	 * @return dateTime|null
	 */
	public function getDataHoraAlteracao()
	{
		return $this->dataHoraAlteracao;
	}
	/**
	 * Set dataHoraAlteracao value
	 * @param dateTime $_dataHoraAlteracao the dataHoraAlteracao
	 * @return dateTime
	 */
	public function setDataHoraAlteracao($_dataHoraAlteracao)
	{
		return ($this->dataHoraAlteracao = $_dataHoraAlteracao);
	}
	/**
	 * Get descricao value
	 * @return string|null
	 */
	public function getDescricao()
	{
		return $this->descricao;
	}
	/**
	 * Set descricao value
	 * @param string $_descricao the descricao
	 * @return string
	 */
	public function setDescricao($_descricao)
	{
		return ($this->descricao = $_descricao);
	}
	/**
	 * Get descricaoAbreviada value
	 * @return string|null
	 */
	public function getDescricaoAbreviada()
	{
		return $this->descricaoAbreviada;
	}
	/**
	 * Set descricaoAbreviada value
	 * @param string $_descricaoAbreviada the descricaoAbreviada
	 * @return string
	 */
	public function setDescricaoAbreviada($_descricaoAbreviada)
	{
		return ($this->descricaoAbreviada = $_descricaoAbreviada);
	}
	/**
	 * Get exercicio value
	 * @return int|null
	 */
	public function getExercicio()
	{
		return $this->exercicio;
	}
	/**
	 * Set exercicio value
	 * @param int $_exercicio the exercicio
	 * @return int
	 */
	public function setExercicio($_exercicio)
	{
		return ($this->exercicio = $_exercicio);
	}
	/**
	 * Get snAtivo value
	 * @return boolean|null
	 */
	public function getSnAtivo()
	{
		return $this->snAtivo;
	}
	/**
	 * Set snAtivo value
	 * @param boolean $_snAtivo the snAtivo
	 * @return boolean
	 */
	public function setSnAtivo($_snAtivo)
	{
		return ($this->snAtivo = $_snAtivo);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructSubFuncaoDTO
	 */
	public static function __set_state(array $_array,$_className = __CLASS__)
	{
		return parent::__set_state($_array,$_className);
	}
	/**
	 * Method returning the class name
	 * @return string __CLASS__
	 */
	public function __toString()
	{
		return __CLASS__;
	}
}
?>