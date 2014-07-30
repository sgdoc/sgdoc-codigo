<?php
/**
 * File for class QualitativoStructMacroDesafioDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructMacroDesafioDTO originally named macroDesafioDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructMacroDesafioDTO extends QualitativoStructBaseDTO
{
	/**
	 * The codigoMacroDesafio
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoMacroDesafio;
	/**
	 * The descricao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $descricao;
	/**
	 * The titulo
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $titulo;
	/**
	 * Constructor method for macroDesafioDTO
	 * @see parent::__construct()
	 * @param int $_codigoMacroDesafio
	 * @param string $_descricao
	 * @param string $_titulo
	 * @return QualitativoStructMacroDesafioDTO
	 */
	public function __construct($_codigoMacroDesafio = NULL,$_descricao = NULL,$_titulo = NULL)
	{
		QualitativoWsdlClass::__construct(array('codigoMacroDesafio'=>$_codigoMacroDesafio,'descricao'=>$_descricao,'titulo'=>$_titulo));
	}
	/**
	 * Get codigoMacroDesafio value
	 * @return int|null
	 */
	public function getCodigoMacroDesafio()
	{
		return $this->codigoMacroDesafio;
	}
	/**
	 * Set codigoMacroDesafio value
	 * @param int $_codigoMacroDesafio the codigoMacroDesafio
	 * @return int
	 */
	public function setCodigoMacroDesafio($_codigoMacroDesafio)
	{
		return ($this->codigoMacroDesafio = $_codigoMacroDesafio);
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
	 * Get titulo value
	 * @return string|null
	 */
	public function getTitulo()
	{
		return $this->titulo;
	}
	/**
	 * Set titulo value
	 * @param string $_titulo the titulo
	 * @return string
	 */
	public function setTitulo($_titulo)
	{
		return ($this->titulo = $_titulo);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructMacroDesafioDTO
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