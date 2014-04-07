<?php
/**
 * File for class QualitativoStructBaseGeograficaDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructBaseGeograficaDTO originally named baseGeograficaDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructBaseGeograficaDTO extends QualitativoStructBaseDTO
{
	/**
	 * The codigoBaseGeografica
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoBaseGeografica;
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
	 * The snExclusaoLogica
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snExclusaoLogica;
	/**
	 * Constructor method for baseGeograficaDTO
	 * @see parent::__construct()
	 * @param int $_codigoBaseGeografica
	 * @param string $_descricao
	 * @param boolean $_snAtivo
	 * @param boolean $_snExclusaoLogica
	 * @return QualitativoStructBaseGeograficaDTO
	 */
	public function __construct($_codigoBaseGeografica = NULL,$_descricao = NULL,$_snAtivo = NULL,$_snExclusaoLogica = NULL)
	{
		QualitativoWsdlClass::__construct(array('codigoBaseGeografica'=>$_codigoBaseGeografica,'descricao'=>$_descricao,'snAtivo'=>$_snAtivo,'snExclusaoLogica'=>$_snExclusaoLogica));
	}
	/**
	 * Get codigoBaseGeografica value
	 * @return int|null
	 */
	public function getCodigoBaseGeografica()
	{
		return $this->codigoBaseGeografica;
	}
	/**
	 * Set codigoBaseGeografica value
	 * @param int $_codigoBaseGeografica the codigoBaseGeografica
	 * @return int
	 */
	public function setCodigoBaseGeografica($_codigoBaseGeografica)
	{
		return ($this->codigoBaseGeografica = $_codigoBaseGeografica);
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
	 * Get snExclusaoLogica value
	 * @return boolean|null
	 */
	public function getSnExclusaoLogica()
	{
		return $this->snExclusaoLogica;
	}
	/**
	 * Set snExclusaoLogica value
	 * @param boolean $_snExclusaoLogica the snExclusaoLogica
	 * @return boolean
	 */
	public function setSnExclusaoLogica($_snExclusaoLogica)
	{
		return ($this->snExclusaoLogica = $_snExclusaoLogica);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructBaseGeograficaDTO
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