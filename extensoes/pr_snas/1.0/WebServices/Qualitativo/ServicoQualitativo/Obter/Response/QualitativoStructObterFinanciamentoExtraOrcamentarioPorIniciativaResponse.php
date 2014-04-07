<?php
/**
 * File for class QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativaResponse
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativaResponse originally named obterFinanciamentoExtraOrcamentarioPorIniciativaResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativaResponse extends QualitativoWsdlClass
{
	/**
	 * The return
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var QualitativoStructRetornoFinanciamentoExtraOrcamentarioDTO
	 */
	public $return;
	/**
	 * Constructor method for obterFinanciamentoExtraOrcamentarioPorIniciativaResponse
	 * @see parent::__construct()
	 * @param QualitativoStructRetornoFinanciamentoExtraOrcamentarioDTO $_return
	 * @return QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativaResponse
	 */
	public function __construct($_return = NULL)
	{
		parent::__construct(array('return'=>$_return));
	}
	/**
	 * Get return value
	 * @return QualitativoStructRetornoFinanciamentoExtraOrcamentarioDTO|null
	 */
	public function getReturn()
	{
		return $this->return;
	}
	/**
	 * Set return value
	 * @param QualitativoStructRetornoFinanciamentoExtraOrcamentarioDTO $_return the return
	 * @return QualitativoStructRetornoFinanciamentoExtraOrcamentarioDTO
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
	 * @return QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativaResponse
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