<?php
/**
 * File for class QualitativoStructPerfilDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructPerfilDTO originally named perfilDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructPerfilDTO extends QualitativoStructBaseDTO
{
	/**
	 * The descricao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $descricao;
	/**
	 * The perfilId
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $perfilId;
	/**
	 * Constructor method for perfilDTO
	 * @see parent::__construct()
	 * @param string $_descricao
	 * @param int $_perfilId
	 * @return QualitativoStructPerfilDTO
	 */
	public function __construct($_descricao = NULL,$_perfilId = NULL)
	{
		QualitativoWsdlClass::__construct(array('descricao'=>$_descricao,'perfilId'=>$_perfilId));
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
	 * Get perfilId value
	 * @return int|null
	 */
	public function getPerfilId()
	{
		return $this->perfilId;
	}
	/**
	 * Set perfilId value
	 * @param int $_perfilId the perfilId
	 * @return int
	 */
	public function setPerfilId($_perfilId)
	{
		return ($this->perfilId = $_perfilId);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructPerfilDTO
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