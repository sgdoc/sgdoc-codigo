<?php
/**
 * File for class QualitativoStructObterObjetivosPorProgramaResponse
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructObterObjetivosPorProgramaResponse originally named obterObjetivosPorProgramaResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructObterObjetivosPorProgramaResponse extends QualitativoWsdlClass
{
	/**
	 * The return
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var QualitativoStructRetornoObjetivosDTO
	 */
	public $return;
	/**
	 * Constructor method for obterObjetivosPorProgramaResponse
	 * @see parent::__construct()
	 * @param QualitativoStructRetornoObjetivosDTO $_return
	 * @return QualitativoStructObterObjetivosPorProgramaResponse
	 */
	public function __construct($_return = NULL)
	{
		parent::__construct(array('return'=>$_return));
	}
	/**
	 * Get return value
	 * @return QualitativoStructRetornoObjetivosDTO|null
	 */
	public function getReturn()
	{
		return $this->return;
	}
	/**
	 * Set return value
	 * @param QualitativoStructRetornoObjetivosDTO $_return the return
	 * @return QualitativoStructRetornoObjetivosDTO
	 */
	public function setReturn($_return)
	{
		return ($this->return = $_return);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructObterObjetivosPorProgramaResponse
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