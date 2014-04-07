<?php
/**
 * File for class QualitativoStructRetornoDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructRetornoDTO originally named retornoDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructRetornoDTO extends QualitativoWsdlClass
{
	/**
	 * The mensagensErro
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var string
	 */
	public $mensagensErro;
	/**
	 * The sucesso
	 * @var boolean
	 */
	public $sucesso;
	/**
	 * Constructor method for retornoDTO
	 * @see parent::__construct()
	 * @param string $_mensagensErro
	 * @param boolean $_sucesso
	 * @return QualitativoStructRetornoDTO
	 */
	public function __construct($_mensagensErro = NULL,$_sucesso = NULL)
	{
		parent::__construct(array('mensagensErro'=>$_mensagensErro,'sucesso'=>$_sucesso));
	}
	/**
	 * Get mensagensErro value
	 * @return string|null
	 */
	public function getMensagensErro()
	{
		return $this->mensagensErro;
	}
	/**
	 * Set mensagensErro value
	 * @param string $_mensagensErro the mensagensErro
	 * @return string
	 */
	public function setMensagensErro($_mensagensErro)
	{
		return ($this->mensagensErro = $_mensagensErro);
	}
	/**
	 * Get sucesso value
	 * @return boolean|null
	 */
	public function getSucesso()
	{
		return $this->sucesso;
	}
	/**
	 * Set sucesso value
	 * @param boolean $_sucesso the sucesso
	 * @return boolean
	 */
	public function setSucesso($_sucesso)
	{
		return ($this->sucesso = $_sucesso);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructRetornoDTO
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