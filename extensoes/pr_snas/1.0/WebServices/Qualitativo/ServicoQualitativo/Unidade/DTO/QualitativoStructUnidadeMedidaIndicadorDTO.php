<?php
/**
 * File for class QualitativoStructUnidadeMedidaIndicadorDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructUnidadeMedidaIndicadorDTO originally named unidadeMedidaIndicadorDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructUnidadeMedidaIndicadorDTO extends QualitativoStructBaseDTO
{
	/**
	 * The codigoUnidadeMedidaIndicador
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoUnidadeMedidaIndicador;
	/**
	 * The descricao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $descricao;
	/**
	 * The exercicio
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $exercicio;
	/**
	 * The snAtivo
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snAtivo;
	/**
	 * Constructor method for unidadeMedidaIndicadorDTO
	 * @see parent::__construct()
	 * @param int $_codigoUnidadeMedidaIndicador
	 * @param string $_descricao
	 * @param int $_exercicio
	 * @param boolean $_snAtivo
	 * @return QualitativoStructUnidadeMedidaIndicadorDTO
	 */
	public function __construct($_codigoUnidadeMedidaIndicador = NULL,$_descricao = NULL,$_exercicio = NULL,$_snAtivo = NULL)
	{
		QualitativoWsdlClass::__construct(array('codigoUnidadeMedidaIndicador'=>$_codigoUnidadeMedidaIndicador,'descricao'=>$_descricao,'exercicio'=>$_exercicio,'snAtivo'=>$_snAtivo));
	}
	/**
	 * Get codigoUnidadeMedidaIndicador value
	 * @return int|null
	 */
	public function getCodigoUnidadeMedidaIndicador()
	{
		return $this->codigoUnidadeMedidaIndicador;
	}
	/**
	 * Set codigoUnidadeMedidaIndicador value
	 * @param int $_codigoUnidadeMedidaIndicador the codigoUnidadeMedidaIndicador
	 * @return int
	 */
	public function setCodigoUnidadeMedidaIndicador($_codigoUnidadeMedidaIndicador)
	{
		return ($this->codigoUnidadeMedidaIndicador = $_codigoUnidadeMedidaIndicador);
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
	 * Get exercicio value
	 * @return int|null
	 */
	public function getExercicio()
	{
		return $this->exercicio;
	}
	/**
	 * Set exercicio value
	 * @param int $_exercicio the exercicio
	 * @return int
	 */
	public function setExercicio($_exercicio)
	{
		return ($this->exercicio = $_exercicio);
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
	 * @return QualitativoStructUnidadeMedidaIndicadorDTO
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