<?php
/**
 * File for class QualitativoStructEsferaDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructEsferaDTO originally named esferaDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructEsferaDTO extends QualitativoWsdlClass
{
	/**
	 * The codigoEsfera
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoEsfera;
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
	 * The snAtivo
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snAtivo;
	/**
	 * The snValorizacao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snValorizacao;
	/**
	 * Constructor method for esferaDTO
	 * @see parent::__construct()
	 * @param string $_codigoEsfera
	 * @param dateTime $_dataHoraAlteracao
	 * @param string $_descricao
	 * @param string $_descricaoAbreviada
	 * @param boolean $_snAtivo
	 * @param boolean $_snValorizacao
	 * @return QualitativoStructEsferaDTO
	 */
	public function __construct($_codigoEsfera = NULL,$_dataHoraAlteracao = NULL,$_descricao = NULL,$_descricaoAbreviada = NULL,$_snAtivo = NULL,$_snValorizacao = NULL)
	{
		parent::__construct(array('codigoEsfera'=>$_codigoEsfera,'dataHoraAlteracao'=>$_dataHoraAlteracao,'descricao'=>$_descricao,'descricaoAbreviada'=>$_descricaoAbreviada,'snAtivo'=>$_snAtivo,'snValorizacao'=>$_snValorizacao));
	}
	/**
	 * Get codigoEsfera value
	 * @return string|null
	 */
	public function getCodigoEsfera()
	{
		return $this->codigoEsfera;
	}
	/**
	 * Set codigoEsfera value
	 * @param string $_codigoEsfera the codigoEsfera
	 * @return string
	 */
	public function setCodigoEsfera($_codigoEsfera)
	{
		return ($this->codigoEsfera = $_codigoEsfera);
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
	 * Get snValorizacao value
	 * @return boolean|null
	 */
	public function getSnValorizacao()
	{
		return $this->snValorizacao;
	}
	/**
	 * Set snValorizacao value
	 * @param boolean $_snValorizacao the snValorizacao
	 * @return boolean
	 */
	public function setSnValorizacao($_snValorizacao)
	{
		return ($this->snValorizacao = $_snValorizacao);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructEsferaDTO
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