<?php
/**
 * File for class QualitativoStructAgendaSamDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructAgendaSamDTO originally named agendaSamDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructAgendaSamDTO extends QualitativoStructBaseDTO
{
	/**
	 * The agendaSam
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $agendaSam;
	/**
	 * The codigoAgendaSam
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoAgendaSam;
	/**
	 * The descricao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $descricao;
	/**
	 * Constructor method for agendaSamDTO
	 * @see parent::__construct()
	 * @param string $_agendaSam
	 * @param int $_codigoAgendaSam
	 * @param string $_descricao
	 * @return QualitativoStructAgendaSamDTO
	 */
	public function __construct($_agendaSam = NULL,$_codigoAgendaSam = NULL,$_descricao = NULL)
	{
		QualitativoWsdlClass::__construct(array('agendaSam'=>$_agendaSam,'codigoAgendaSam'=>$_codigoAgendaSam,'descricao'=>$_descricao));
	}
	/**
	 * Get agendaSam value
	 * @return string|null
	 */
	public function getAgendaSam()
	{
		return $this->agendaSam;
	}
	/**
	 * Set agendaSam value
	 * @param string $_agendaSam the agendaSam
	 * @return string
	 */
	public function setAgendaSam($_agendaSam)
	{
		return ($this->agendaSam = $_agendaSam);
	}
	/**
	 * Get codigoAgendaSam value
	 * @return int|null
	 */
	public function getCodigoAgendaSam()
	{
		return $this->codigoAgendaSam;
	}
	/**
	 * Set codigoAgendaSam value
	 * @param int $_codigoAgendaSam the codigoAgendaSam
	 * @return int
	 */
	public function setCodigoAgendaSam($_codigoAgendaSam)
	{
		return ($this->codigoAgendaSam = $_codigoAgendaSam);
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
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructAgendaSamDTO
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