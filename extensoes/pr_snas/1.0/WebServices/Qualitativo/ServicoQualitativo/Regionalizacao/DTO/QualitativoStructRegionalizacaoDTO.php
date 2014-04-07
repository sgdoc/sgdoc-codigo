<?php
/**
 * File for class QualitativoStructRegionalizacaoDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructRegionalizacaoDTO originally named regionalizacaoDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructRegionalizacaoDTO extends QualitativoStructBaseDTO
{
	/**
	 * The codigoMeta
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoMeta;
	/**
	 * The codigoMomento
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoMomento;
	/**
	 * The codigoObjetivo
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoObjetivo;
	/**
	 * The codigoPrograma
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoPrograma;
	/**
	 * The codigoUnidadeMedida
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoUnidadeMedida;
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
	 * The identificadorUnicoMeta
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $identificadorUnicoMeta;
	/**
	 * The regionalizacaoId
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $regionalizacaoId;
	/**
	 * The sigla
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $sigla;
	/**
	 * The valor
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var decimal
	 */
	public $valor;
	/**
	 * Constructor method for regionalizacaoDTO
	 * @see parent::__construct()
	 * @param int $_codigoMeta
	 * @param int $_codigoMomento
	 * @param string $_codigoObjetivo
	 * @param string $_codigoPrograma
	 * @param string $_codigoUnidadeMedida
	 * @param string $_descricao
	 * @param int $_exercicio
	 * @param int $_identificadorUnicoMeta
	 * @param int $_regionalizacaoId
	 * @param string $_sigla
	 * @param decimal $_valor
	 * @return QualitativoStructRegionalizacaoDTO
	 */
	public function __construct($_codigoMeta = NULL,$_codigoMomento = NULL,$_codigoObjetivo = NULL,$_codigoPrograma = NULL,$_codigoUnidadeMedida = NULL,$_descricao = NULL,$_exercicio = NULL,$_identificadorUnicoMeta = NULL,$_regionalizacaoId = NULL,$_sigla = NULL,$_valor = NULL)
	{
		QualitativoWsdlClass::__construct(array('codigoMeta'=>$_codigoMeta,'codigoMomento'=>$_codigoMomento,'codigoObjetivo'=>$_codigoObjetivo,'codigoPrograma'=>$_codigoPrograma,'codigoUnidadeMedida'=>$_codigoUnidadeMedida,'descricao'=>$_descricao,'exercicio'=>$_exercicio,'identificadorUnicoMeta'=>$_identificadorUnicoMeta,'regionalizacaoId'=>$_regionalizacaoId,'sigla'=>$_sigla,'valor'=>$_valor));
	}
	/**
	 * Get codigoMeta value
	 * @return int|null
	 */
	public function getCodigoMeta()
	{
		return $this->codigoMeta;
	}
	/**
	 * Set codigoMeta value
	 * @param int $_codigoMeta the codigoMeta
	 * @return int
	 */
	public function setCodigoMeta($_codigoMeta)
	{
		return ($this->codigoMeta = $_codigoMeta);
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
	 * Get codigoObjetivo value
	 * @return string|null
	 */
	public function getCodigoObjetivo()
	{
		return $this->codigoObjetivo;
	}
	/**
	 * Set codigoObjetivo value
	 * @param string $_codigoObjetivo the codigoObjetivo
	 * @return string
	 */
	public function setCodigoObjetivo($_codigoObjetivo)
	{
		return ($this->codigoObjetivo = $_codigoObjetivo);
	}
	/**
	 * Get codigoPrograma value
	 * @return string|null
	 */
	public function getCodigoPrograma()
	{
		return $this->codigoPrograma;
	}
	/**
	 * Set codigoPrograma value
	 * @param string $_codigoPrograma the codigoPrograma
	 * @return string
	 */
	public function setCodigoPrograma($_codigoPrograma)
	{
		return ($this->codigoPrograma = $_codigoPrograma);
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
	 * Get identificadorUnicoMeta value
	 * @return int|null
	 */
	public function getIdentificadorUnicoMeta()
	{
		return $this->identificadorUnicoMeta;
	}
	/**
	 * Set identificadorUnicoMeta value
	 * @param int $_identificadorUnicoMeta the identificadorUnicoMeta
	 * @return int
	 */
	public function setIdentificadorUnicoMeta($_identificadorUnicoMeta)
	{
		return ($this->identificadorUnicoMeta = $_identificadorUnicoMeta);
	}
	/**
	 * Get regionalizacaoId value
	 * @return int|null
	 */
	public function getRegionalizacaoId()
	{
		return $this->regionalizacaoId;
	}
	/**
	 * Set regionalizacaoId value
	 * @param int $_regionalizacaoId the regionalizacaoId
	 * @return int
	 */
	public function setRegionalizacaoId($_regionalizacaoId)
	{
		return ($this->regionalizacaoId = $_regionalizacaoId);
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
	 * Get valor value
	 * @return decimal|null
	 */
	public function getValor()
	{
		return $this->valor;
	}
	/**
	 * Set valor value
	 * @param decimal $_valor the valor
	 * @return decimal
	 */
	public function setValor($_valor)
	{
		return ($this->valor = $_valor);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructRegionalizacaoDTO
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