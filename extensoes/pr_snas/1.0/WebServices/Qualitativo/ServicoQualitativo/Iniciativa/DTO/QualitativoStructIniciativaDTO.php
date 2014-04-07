<?php
/**
 * File for class QualitativoStructIniciativaDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructIniciativaDTO originally named iniciativaDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructIniciativaDTO extends QualitativoStructBaseDTO
{
	/**
	 * The codigoIniciativa
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoIniciativa;
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
	 * The exercicio
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $exercicio;
	/**
	 * The identificadorUnico
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $identificadorUnico;
	/**
	 * The snExclusaoLogica
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snExclusaoLogica;
	/**
	 * The snIndividualizada
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snIndividualizada;
	/**
	 * The titulo
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $titulo;
	/**
	 * Constructor method for iniciativaDTO
	 * @see parent::__construct()
	 * @param string $_codigoIniciativa
	 * @param int $_codigoMomento
	 * @param string $_codigoObjetivo
	 * @param string $_codigoOrgao
	 * @param string $_codigoPrograma
	 * @param int $_exercicio
	 * @param int $_identificadorUnico
	 * @param boolean $_snExclusaoLogica
	 * @param boolean $_snIndividualizada
	 * @param string $_titulo
	 * @return QualitativoStructIniciativaDTO
	 */
	public function __construct($_codigoIniciativa = NULL,$_codigoMomento = NULL,$_codigoObjetivo = NULL,$_codigoOrgao = NULL,$_codigoPrograma = NULL,$_exercicio = NULL,$_identificadorUnico = NULL,$_snExclusaoLogica = NULL,$_snIndividualizada = NULL,$_titulo = NULL)
	{
		QualitativoWsdlClass::__construct(array('codigoIniciativa'=>$_codigoIniciativa,'codigoMomento'=>$_codigoMomento,'codigoObjetivo'=>$_codigoObjetivo,'codigoOrgao'=>$_codigoOrgao,'codigoPrograma'=>$_codigoPrograma,'exercicio'=>$_exercicio,'identificadorUnico'=>$_identificadorUnico,'snExclusaoLogica'=>$_snExclusaoLogica,'snIndividualizada'=>$_snIndividualizada,'titulo'=>$_titulo));
	}
	/**
	 * Get codigoIniciativa value
	 * @return string|null
	 */
	public function getCodigoIniciativa()
	{
		return $this->codigoIniciativa;
	}
	/**
	 * Set codigoIniciativa value
	 * @param string $_codigoIniciativa the codigoIniciativa
	 * @return string
	 */
	public function setCodigoIniciativa($_codigoIniciativa)
	{
		return ($this->codigoIniciativa = $_codigoIniciativa);
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
	 * Get snIndividualizada value
	 * @return boolean|null
	 */
	public function getSnIndividualizada()
	{
		return $this->snIndividualizada;
	}
	/**
	 * Set snIndividualizada value
	 * @param boolean $_snIndividualizada the snIndividualizada
	 * @return boolean
	 */
	public function setSnIndividualizada($_snIndividualizada)
	{
		return ($this->snIndividualizada = $_snIndividualizada);
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
	 * @return QualitativoStructIniciativaDTO
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