<?php
/**
 * File for class QualitativoStructRetornoAcoesDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructRetornoAcoesDTO originally named retornoAcoesDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructRetornoAcoesDTO extends QualitativoStructRetornoDTO
{
	/**
	 * The registros
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructAcaoDTO
	 */
	public $registros;
	/**
	 * Constructor method for retornoAcoesDTO
	 * @see parent::__construct()
	 * @param QualitativoStructAcaoDTO $_registros
	 * @return QualitativoStructRetornoAcoesDTO
	 */
	public function __construct($_registros = NULL)
	{
		QualitativoWsdlClass::__construct(array('registros'=>$_registros));
	}
	/**
	 * Get registros value
	 * @return QualitativoStructAcaoDTO|null
	 */
	public function getRegistros()
	{
		return $this->registros;
	}
	/**
	 * Set registros value
	 * @param QualitativoStructAcaoDTO $_registros the registros
	 * @return QualitativoStructAcaoDTO
	 */
	public function setRegistros($_registros)
	{
		return ($this->registros = $_registros);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructRetornoAcoesDTO
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