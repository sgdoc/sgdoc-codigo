<?php
/**
 * File for class QualitativoStructTipoAcaoDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructTipoAcaoDTO originally named tipoAcaoDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructTipoAcaoDTO extends QualitativoWsdlClass
{
	/**
	 * The codigoTipoAcao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoTipoAcao;
	/**
	 * The descricao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $descricao;
	/**
	 * The snAtivo
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snAtivo;
	/**
	 * Constructor method for tipoAcaoDTO
	 * @see parent::__construct()
	 * @param string $_codigoTipoAcao
	 * @param string $_descricao
	 * @param boolean $_snAtivo
	 * @return QualitativoStructTipoAcaoDTO
	 */
	public function __construct($_codigoTipoAcao = NULL,$_descricao = NULL,$_snAtivo = NULL)
	{
		parent::__construct(array('codigoTipoAcao'=>$_codigoTipoAcao,'descricao'=>$_descricao,'snAtivo'=>$_snAtivo));
	}
	/**
	 * Get codigoTipoAcao value
	 * @return string|null
	 */
	public function getCodigoTipoAcao()
	{
		return $this->codigoTipoAcao;
	}
	/**
	 * Set codigoTipoAcao value
	 * @param string $_codigoTipoAcao the codigoTipoAcao
	 * @return string
	 */
	public function setCodigoTipoAcao($_codigoTipoAcao)
	{
		return ($this->codigoTipoAcao = $_codigoTipoAcao);
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
	 * @return QualitativoStructTipoAcaoDTO
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