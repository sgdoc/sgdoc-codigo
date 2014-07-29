<?php
/**
 * File for class QualitativoStructTipoProgramaDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructTipoProgramaDTO originally named tipoProgramaDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructTipoProgramaDTO extends QualitativoStructBaseDTO
{
	/**
	 * The codigoTipoPrograma
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoTipoPrograma;
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
	 * Constructor method for tipoProgramaDTO
	 * @see parent::__construct()
	 * @param string $_codigoTipoPrograma
	 * @param string $_descricao
	 * @param int $_exercicio
	 * @param boolean $_snAtivo
	 * @return QualitativoStructTipoProgramaDTO
	 */
	public function __construct($_codigoTipoPrograma = NULL,$_descricao = NULL,$_exercicio = NULL,$_snAtivo = NULL)
	{
		QualitativoWsdlClass::__construct(array('codigoTipoPrograma'=>$_codigoTipoPrograma,'descricao'=>$_descricao,'exercicio'=>$_exercicio,'snAtivo'=>$_snAtivo));
	}
	/**
	 * Get codigoTipoPrograma value
	 * @return string|null
	 */
	public function getCodigoTipoPrograma()
	{
		return $this->codigoTipoPrograma;
	}
	/**
	 * Set codigoTipoPrograma value
	 * @param string $_codigoTipoPrograma the codigoTipoPrograma
	 * @return string
	 */
	public function setCodigoTipoPrograma($_codigoTipoPrograma)
	{
		return ($this->codigoTipoPrograma = $_codigoTipoPrograma);
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
	 * @return QualitativoStructTipoProgramaDTO
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