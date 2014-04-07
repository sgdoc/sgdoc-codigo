<?php
/**
 * File for class QualitativoStructProgramaDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructProgramaDTO originally named programaDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructProgramaDTO extends QualitativoWsdlClass
{
	/**
	 * The codigoMacroDesafio
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoMacroDesafio;
	/**
	 * The codigoMomento
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoMomento;
	/**
	 * The codigoOrgao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoOrgao;
	/**
	 * The codigoPrograma
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoPrograma;
	/**
	 * The codigoTipoPrograma
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoTipoPrograma;
	/**
	 * The estrategiaImplementacao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $estrategiaImplementacao;
	/**
	 * The exercicio
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $exercicio;
	/**
	 * The horizonteTemporalContinuo
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $horizonteTemporalContinuo;
	/**
	 * The identificadorUnico
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $identificadorUnico;
	/**
	 * The justificativa
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $justificativa;
	/**
	 * The objetivo
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $objetivo;
	/**
	 * The objetivoGoverno
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $objetivoGoverno;
	/**
	 * The objetivoSetorial
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $objetivoSetorial;
	/**
	 * The problema
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $problema;
	/**
	 * The publicoAlvo
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $publicoAlvo;
	/**
	 * The snExclusaoLogica
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snExclusaoLogica;
	/**
	 * The titulo
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $titulo;
	/**
	 * The unidadeResponsavel
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $unidadeResponsavel;
	/**
	 * Constructor method for programaDTO
	 * @see parent::__construct()
	 * @param int $_codigoMacroDesafio
	 * @param int $_codigoMomento
	 * @param string $_codigoOrgao
	 * @param string $_codigoPrograma
	 * @param string $_codigoTipoPrograma
	 * @param string $_estrategiaImplementacao
	 * @param int $_exercicio
	 * @param int $_horizonteTemporalContinuo
	 * @param int $_identificadorUnico
	 * @param string $_justificativa
	 * @param string $_objetivo
	 * @param string $_objetivoGoverno
	 * @param string $_objetivoSetorial
	 * @param string $_problema
	 * @param string $_publicoAlvo
	 * @param boolean $_snExclusaoLogica
	 * @param string $_titulo
	 * @param string $_unidadeResponsavel
	 * @return QualitativoStructProgramaDTO
	 */
	public function __construct($_codigoMacroDesafio = NULL,$_codigoMomento = NULL,$_codigoOrgao = NULL,$_codigoPrograma = NULL,$_codigoTipoPrograma = NULL,$_estrategiaImplementacao = NULL,$_exercicio = NULL,$_horizonteTemporalContinuo = NULL,$_identificadorUnico = NULL,$_justificativa = NULL,$_objetivo = NULL,$_objetivoGoverno = NULL,$_objetivoSetorial = NULL,$_problema = NULL,$_publicoAlvo = NULL,$_snExclusaoLogica = NULL,$_titulo = NULL,$_unidadeResponsavel = NULL)
	{
		parent::__construct(array('codigoMacroDesafio'=>$_codigoMacroDesafio,'codigoMomento'=>$_codigoMomento,'codigoOrgao'=>$_codigoOrgao,'codigoPrograma'=>$_codigoPrograma,'codigoTipoPrograma'=>$_codigoTipoPrograma,'estrategiaImplementacao'=>$_estrategiaImplementacao,'exercicio'=>$_exercicio,'horizonteTemporalContinuo'=>$_horizonteTemporalContinuo,'identificadorUnico'=>$_identificadorUnico,'justificativa'=>$_justificativa,'objetivo'=>$_objetivo,'objetivoGoverno'=>$_objetivoGoverno,'objetivoSetorial'=>$_objetivoSetorial,'problema'=>$_problema,'publicoAlvo'=>$_publicoAlvo,'snExclusaoLogica'=>$_snExclusaoLogica,'titulo'=>$_titulo,'unidadeResponsavel'=>$_unidadeResponsavel));
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
	 * Get codigoOrgao value
	 * @return string|null
	 */
	public function getCodigoOrgao()
	{
		return $this->codigoOrgao;
	}
	/**
	 * Set codigoOrgao value
	 * @param string $_codigoOrgao the codigoOrgao
	 * @return string
	 */
	public function setCodigoOrgao($_codigoOrgao)
	{
		return ($this->codigoOrgao = $_codigoOrgao);
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
	 * Get estrategiaImplementacao value
	 * @return string|null
	 */
	public function getEstrategiaImplementacao()
	{
		return $this->estrategiaImplementacao;
	}
	/**
	 * Set estrategiaImplementacao value
	 * @param string $_estrategiaImplementacao the estrategiaImplementacao
	 * @return string
	 */
	public function setEstrategiaImplementacao($_estrategiaImplementacao)
	{
		return ($this->estrategiaImplementacao = $_estrategiaImplementacao);
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
	 * Get horizonteTemporalContinuo value
	 * @return int|null
	 */
	public function getHorizonteTemporalContinuo()
	{
		return $this->horizonteTemporalContinuo;
	}
	/**
	 * Set horizonteTemporalContinuo value
	 * @param int $_horizonteTemporalContinuo the horizonteTemporalContinuo
	 * @return int
	 */
	public function setHorizonteTemporalContinuo($_horizonteTemporalContinuo)
	{
		return ($this->horizonteTemporalContinuo = $_horizonteTemporalContinuo);
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
	 * Get justificativa value
	 * @return string|null
	 */
	public function getJustificativa()
	{
		return $this->justificativa;
	}
	/**
	 * Set justificativa value
	 * @param string $_justificativa the justificativa
	 * @return string
	 */
	public function setJustificativa($_justificativa)
	{
		return ($this->justificativa = $_justificativa);
	}
	/**
	 * Get objetivo value
	 * @return string|null
	 */
	public function getObjetivo()
	{
		return $this->objetivo;
	}
	/**
	 * Set objetivo value
	 * @param string $_objetivo the objetivo
	 * @return string
	 */
	public function setObjetivo($_objetivo)
	{
		return ($this->objetivo = $_objetivo);
	}
	/**
	 * Get objetivoGoverno value
	 * @return string|null
	 */
	public function getObjetivoGoverno()
	{
		return $this->objetivoGoverno;
	}
	/**
	 * Set objetivoGoverno value
	 * @param string $_objetivoGoverno the objetivoGoverno
	 * @return string
	 */
	public function setObjetivoGoverno($_objetivoGoverno)
	{
		return ($this->objetivoGoverno = $_objetivoGoverno);
	}
	/**
	 * Get objetivoSetorial value
	 * @return string|null
	 */
	public function getObjetivoSetorial()
	{
		return $this->objetivoSetorial;
	}
	/**
	 * Set objetivoSetorial value
	 * @param string $_objetivoSetorial the objetivoSetorial
	 * @return string
	 */
	public function setObjetivoSetorial($_objetivoSetorial)
	{
		return ($this->objetivoSetorial = $_objetivoSetorial);
	}
	/**
	 * Get problema value
	 * @return string|null
	 */
	public function getProblema()
	{
		return $this->problema;
	}
	/**
	 * Set problema value
	 * @param string $_problema the problema
	 * @return string
	 */
	public function setProblema($_problema)
	{
		return ($this->problema = $_problema);
	}
	/**
	 * Get publicoAlvo value
	 * @return string|null
	 */
	public function getPublicoAlvo()
	{
		return $this->publicoAlvo;
	}
	/**
	 * Set publicoAlvo value
	 * @param string $_publicoAlvo the publicoAlvo
	 * @return string
	 */
	public function setPublicoAlvo($_publicoAlvo)
	{
		return ($this->publicoAlvo = $_publicoAlvo);
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
	 * Get unidadeResponsavel value
	 * @return string|null
	 */
	public function getUnidadeResponsavel()
	{
		return $this->unidadeResponsavel;
	}
	/**
	 * Set unidadeResponsavel value
	 * @param string $_unidadeResponsavel the unidadeResponsavel
	 * @return string
	 */
	public function setUnidadeResponsavel($_unidadeResponsavel)
	{
		return ($this->unidadeResponsavel = $_unidadeResponsavel);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructProgramaDTO
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