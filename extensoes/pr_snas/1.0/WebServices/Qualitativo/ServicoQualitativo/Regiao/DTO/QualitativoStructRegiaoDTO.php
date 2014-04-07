<?php
/**
 * File for class QualitativoStructRegiaoDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructRegiaoDTO originally named regiaoDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructRegiaoDTO extends QualitativoStructBaseDTO
{
	/**
	 * The codigoRegiao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoRegiao;
	/**
	 * The descricao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $descricao;
	/**
	 * The sigla
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $sigla;
	/**
	 * Constructor method for regiaoDTO
	 * @see parent::__construct()
	 * @param int $_codigoRegiao
	 * @param string $_descricao
	 * @param string $_sigla
	 * @return QualitativoStructRegiaoDTO
	 */
	public function __construct($_codigoRegiao = NULL,$_descricao = NULL,$_sigla = NULL)
	{
		QualitativoWsdlClass::__construct(array('codigoRegiao'=>$_codigoRegiao,'descricao'=>$_descricao,'sigla'=>$_sigla));
	}
	/**
	 * Get codigoRegiao value
	 * @return int|null
	 */
	public function getCodigoRegiao()
	{
		return $this->codigoRegiao;
	}
	/**
	 * Set codigoRegiao value
	 * @param int $_codigoRegiao the codigoRegiao
	 * @return int
	 */
	public function setCodigoRegiao($_codigoRegiao)
	{
		return ($this->codigoRegiao = $_codigoRegiao);
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
	 * Get sigla value
	 * @return string|null
	 */
	public function getSigla()
	{
		return $this->sigla;
	}
	/**
	 * Set sigla value
	 * @param string $_sigla the sigla
	 * @return string
	 */
	public function setSigla($_sigla)
	{
		return ($this->sigla = $_sigla);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructRegiaoDTO
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