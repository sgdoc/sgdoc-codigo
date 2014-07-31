<?php
/**
 * File for class QualitativoStructUnidadeMedidaDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructUnidadeMedidaDTO originally named unidadeMedidaDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructUnidadeMedidaDTO extends QualitativoWsdlClass
{
	/**
	 * The codigoUnidadeMedida
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoUnidadeMedida;
	/**
	 * The dataHoraAlteracao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var dateTime
	 */
	public $dataHoraAlteracao;
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
	 * Constructor method for unidadeMedidaDTO
	 * @see parent::__construct()
	 * @param string $_codigoUnidadeMedida
	 * @param dateTime $_dataHoraAlteracao
	 * @param string $_descricao
	 * @param boolean $_snAtivo
	 * @return QualitativoStructUnidadeMedidaDTO
	 */
	public function __construct($_codigoUnidadeMedida = NULL,$_dataHoraAlteracao = NULL,$_descricao = NULL,$_snAtivo = NULL)
	{
		parent::__construct(array('codigoUnidadeMedida'=>$_codigoUnidadeMedida,'dataHoraAlteracao'=>$_dataHoraAlteracao,'descricao'=>$_descricao,'snAtivo'=>$_snAtivo));
	}
	/**
	 * Get codigoUnidadeMedida value
	 * @return string|null
	 */
	public function getCodigoUnidadeMedida()
	{
		return $this->codigoUnidadeMedida;
	}
	/**
	 * Set codigoUnidadeMedida value
	 * @param string $_codigoUnidadeMedida the codigoUnidadeMedida
	 * @return string
	 */
	public function setCodigoUnidadeMedida($_codigoUnidadeMedida)
	{
		return ($this->codigoUnidadeMedida = $_codigoUnidadeMedida);
	}
	/**
	 * Get dataHoraAlteracao value
	 * @return dateTime|null
	 */
	public function getDataHoraAlteracao()
	{
		return $this->dataHoraAlteracao;
	}
	/**
	 * Set dataHoraAlteracao value
	 * @param dateTime $_dataHoraAlteracao the dataHoraAlteracao
	 * @return dateTime
	 */
	public function setDataHoraAlteracao($_dataHoraAlteracao)
	{
		return ($this->dataHoraAlteracao = $_dataHoraAlteracao);
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
	 * @return QualitativoStructUnidadeMedidaDTO
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