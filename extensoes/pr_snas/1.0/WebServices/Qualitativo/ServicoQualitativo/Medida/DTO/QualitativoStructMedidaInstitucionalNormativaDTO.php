<?php
/**
 * File for class QualitativoStructMedidaInstitucionalNormativaDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructMedidaInstitucionalNormativaDTO originally named medidaInstitucionalNormativaDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructMedidaInstitucionalNormativaDTO extends QualitativoStructBaseDTO
{
	/**
	 * The codigoMomento
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoMomento;
	/**
	 * The codigoOrgaoSiorg
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoOrgaoSiorg;
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
	 * The identificadorIniciativa
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $identificadorIniciativa;
	/**
	 * The identificadorUnico
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $identificadorUnico;
	/**
	 * The produto
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $produto;
	/**
	 * The snExclusaoLogica
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snExclusaoLogica;
	/**
	 * Constructor method for medidaInstitucionalNormativaDTO
	 * @see parent::__construct()
	 * @param int $_codigoMomento
	 * @param string $_codigoOrgaoSiorg
	 * @param string $_descricao
	 * @param int $_exercicio
	 * @param int $_identificadorIniciativa
	 * @param int $_identificadorUnico
	 * @param string $_produto
	 * @param boolean $_snExclusaoLogica
	 * @return QualitativoStructMedidaInstitucionalNormativaDTO
	 */
	public function __construct($_codigoMomento = NULL,$_codigoOrgaoSiorg = NULL,$_descricao = NULL,$_exercicio = NULL,$_identificadorIniciativa = NULL,$_identificadorUnico = NULL,$_produto = NULL,$_snExclusaoLogica = NULL)
	{
		QualitativoWsdlClass::__construct(array('codigoMomento'=>$_codigoMomento,'codigoOrgaoSiorg'=>$_codigoOrgaoSiorg,'descricao'=>$_descricao,'exercicio'=>$_exercicio,'identificadorIniciativa'=>$_identificadorIniciativa,'identificadorUnico'=>$_identificadorUnico,'produto'=>$_produto,'snExclusaoLogica'=>$_snExclusaoLogica));
	}
	/**
	 * Get codigoMomento value
	 * @return int|null
	 */
	public function getCodigoMomento()
	{
		return $this->codigoMomento;
	}
	/**
	 * Set codigoMomento value
	 * @param int $_codigoMomento the codigoMomento
	 * @return int
	 */
	public function setCodigoMomento($_codigoMomento)
	{
		return ($this->codigoMomento = $_codigoMomento);
	}
	/**
	 * Get codigoOrgaoSiorg value
	 * @return string|null
	 */
	public function getCodigoOrgaoSiorg()
	{
		return $this->codigoOrgaoSiorg;
	}
	/**
	 * Set codigoOrgaoSiorg value
	 * @param string $_codigoOrgaoSiorg the codigoOrgaoSiorg
	 * @return string
	 */
	public function setCodigoOrgaoSiorg($_codigoOrgaoSiorg)
	{
		return ($this->codigoOrgaoSiorg = $_codigoOrgaoSiorg);
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
	 * Get identificadorIniciativa value
	 * @return int|null
	 */
	public function getIdentificadorIniciativa()
	{
		return $this->identificadorIniciativa;
	}
	/**
	 * Set identificadorIniciativa value
	 * @param int $_identificadorIniciativa the identificadorIniciativa
	 * @return int
	 */
	public function setIdentificadorIniciativa($_identificadorIniciativa)
	{
		return ($this->identificadorIniciativa = $_identificadorIniciativa);
	}
	/**
	 * Get identificadorUnico value
	 * @return int|null
	 */
	public function getIdentificadorUnico()
	{
		return $this->identificadorUnico;
	}
	/**
	 * Set identificadorUnico value
	 * @param int $_identificadorUnico the identificadorUnico
	 * @return int
	 */
	public function setIdentificadorUnico($_identificadorUnico)
	{
		return ($this->identificadorUnico = $_identificadorUnico);
	}
	/**
	 * Get produto value
	 * @return string|null
	 */
	public function getProduto()
	{
		return $this->produto;
	}
	/**
	 * Set produto value
	 * @param string $_produto the produto
	 * @return string
	 */
	public function setProduto($_produto)
	{
		return ($this->produto = $_produto);
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
	 * @return QualitativoStructMedidaInstitucionalNormativaDTO
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