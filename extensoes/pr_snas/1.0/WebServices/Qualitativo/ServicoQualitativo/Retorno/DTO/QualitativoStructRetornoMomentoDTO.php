<?php
/**
 * File for class QualitativoStructRetornoMomentoDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructRetornoMomentoDTO originally named retornoMomentoDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructRetornoMomentoDTO extends QualitativoStructRetornoDTO
{
	/**
	 * The momento
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var QualitativoStructMomentoDTO
	 */
	public $momento;
	/**
	 * Constructor method for retornoMomentoDTO
	 * @see parent::__construct()
	 * @param QualitativoStructMomentoDTO $_momento
	 * @return QualitativoStructRetornoMomentoDTO
	 */
	public function __construct($_momento = NULL)
	{
		QualitativoWsdlClass::__construct(array('momento'=>$_momento));
	}
	/**
	 * Get momento value
	 * @return QualitativoStructMomentoDTO|null
	 */
	public function getMomento()
	{
		return $this->momento;
	}
	/**
	 * Set momento value
	 * @param QualitativoStructMomentoDTO $_momento the momento
	 * @return QualitativoStructMomentoDTO
	 */
	public function setMomento($_momento)
	{
		return ($this->momento = $_momento);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructRetornoMomentoDTO
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