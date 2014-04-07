<?php
/**
 * File for class QualitativoStructIndicadorDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructIndicadorDTO originally named indicadorDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructIndicadorDTO extends QualitativoStructBaseDTO
{
	/**
	 * The codigoBaseGeografica
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoBaseGeografica;
	/**
	 * The codigoIndicador
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoIndicador;
	/**
	 * The codigoMomento
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoMomento;
	/**
	 * The codigoPeriodicidade
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoPeriodicidade;
	/**
	 * The codigoPrograma
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoPrograma;
	/**
	 * The codigoUnidadeMedidaIndicador
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoUnidadeMedidaIndicador;
	/**
	 * The dataApuracao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var dateTime
	 */
	public $dataApuracao;
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
	 * The fonte
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $fonte;
	/**
	 * The formula
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $formula;
	/**
	 * The identificadorUnico
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $identificadorUnico;
	/**
	 * The snApuracaoReferencia
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snApuracaoReferencia;
	/**
	 * The snExclusaoLogica
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snExclusaoLogica;
	/**
	 * The valorReferencia
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var decimal
	 */
	public $valorReferencia;
	/**
	 * Constructor method for indicadorDTO
	 * @see parent::__construct()
	 * @param int $_codigoBaseGeografica
	 * @param int $_codigoIndicador
	 * @param int $_codigoMomento
	 * @param int $_codigoPeriodicidade
	 * @param string $_codigoPrograma
	 * @param int $_codigoUnidadeMedidaIndicador
	 * @param dateTime $_dataApuracao
	 * @param string $_descricao
	 * @param int $_exercicio
	 * @param string $_fonte
	 * @param string $_formula
	 * @param int $_identificadorUnico
	 * @param boolean $_snApuracaoReferencia
	 * @param boolean $_snExclusaoLogica
	 * @param decimal $_valorReferencia
	 * @return QualitativoStructIndicadorDTO
	 */
	public function __construct($_codigoBaseGeografica = NULL,$_codigoIndicador = NULL,$_codigoMomento = NULL,$_codigoPeriodicidade = NULL,$_codigoPrograma = NULL,$_codigoUnidadeMedidaIndicador = NULL,$_dataApuracao = NULL,$_descricao = NULL,$_exercicio = NULL,$_fonte = NULL,$_formula = NULL,$_identificadorUnico = NULL,$_snApuracaoReferencia = NULL,$_snExclusaoLogica = NULL,$_valorReferencia = NULL)
	{
		QualitativoWsdlClass::__construct(array('codigoBaseGeografica'=>$_codigoBaseGeografica,'codigoIndicador'=>$_codigoIndicador,'codigoMomento'=>$_codigoMomento,'codigoPeriodicidade'=>$_codigoPeriodicidade,'codigoPrograma'=>$_codigoPrograma,'codigoUnidadeMedidaIndicador'=>$_codigoUnidadeMedidaIndicador,'dataApuracao'=>$_dataApuracao,'descricao'=>$_descricao,'exercicio'=>$_exercicio,'fonte'=>$_fonte,'formula'=>$_formula,'identificadorUnico'=>$_identificadorUnico,'snApuracaoReferencia'=>$_snApuracaoReferencia,'snExclusaoLogica'=>$_snExclusaoLogica,'valorReferencia'=>$_valorReferencia));
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
	 * Get codigoIndicador value
	 * @return int|null
	 */
	public function getCodigoIndicador()
	{
		return $this->codigoIndicador;
	}
	/**
	 * Set codigoIndicador value
	 * @param int $_codigoIndicador the codigoIndicador
	 * @return int
	 */
	public function setCodigoIndicador($_codigoIndicador)
	{
		return ($this->codigoIndicador = $_codigoIndicador);
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
	 * Get codigoPeriodicidade value
	 * @return int|null
	 */
	public function getCodigoPeriodicidade()
	{
		return $this->codigoPeriodicidade;
	}
	/**
	 * Set codigoPeriodicidade value
	 * @param int $_codigoPeriodicidade the codigoPeriodicidade
	 * @return int
	 */
	public function setCodigoPeriodicidade($_codigoPeriodicidade)
	{
		return ($this->codigoPeriodicidade = $_codigoPeriodicidade);
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
	 * Get dataApuracao value
	 * @return dateTime|null
	 */
	public function getDataApuracao()
	{
		return $this->dataApuracao;
	}
	/**
	 * Set dataApuracao value
	 * @param dateTime $_dataApuracao the dataApuracao
	 * @return dateTime
	 */
	public function setDataApuracao($_dataApuracao)
	{
		return ($this->dataApuracao = $_dataApuracao);
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
	 * Get fonte value
	 * @return string|null
	 */
	public function getFonte()
	{
		return $this->fonte;
	}
	/**
	 * Set fonte value
	 * @param string $_fonte the fonte
	 * @return string
	 */
	public function setFonte($_fonte)
	{
		return ($this->fonte = $_fonte);
	}
	/**
	 * Get formula value
	 * @return string|null
	 */
	public function getFormula()
	{
		return $this->formula;
	}
	/**
	 * Set formula value
	 * @param string $_formula the formula
	 * @return string
	 */
	public function setFormula($_formula)
	{
		return ($this->formula = $_formula);
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
	 * Get snApuracaoReferencia value
	 * @return boolean|null
	 */
	public function getSnApuracaoReferencia()
	{
		return $this->snApuracaoReferencia;
	}
	/**
	 * Set snApuracaoReferencia value
	 * @param boolean $_snApuracaoReferencia the snApuracaoReferencia
	 * @return boolean
	 */
	public function setSnApuracaoReferencia($_snApuracaoReferencia)
	{
		return ($this->snApuracaoReferencia = $_snApuracaoReferencia);
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
	 * Get valorReferencia value
	 * @return decimal|null
	 */
	public function getValorReferencia()
	{
		return $this->valorReferencia;
	}
	/**
	 * Set valorReferencia value
	 * @param decimal $_valorReferencia the valorReferencia
	 * @return decimal
	 */
	public function setValorReferencia($_valorReferencia)
	{
		return ($this->valorReferencia = $_valorReferencia);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructIndicadorDTO
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