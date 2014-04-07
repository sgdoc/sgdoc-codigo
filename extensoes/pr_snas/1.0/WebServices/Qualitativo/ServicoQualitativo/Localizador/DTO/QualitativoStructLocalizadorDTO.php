<?php
/**
 * File for class QualitativoStructLocalizadorDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructLocalizadorDTO originally named localizadorDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructLocalizadorDTO extends QualitativoStructBaseDTO
{
	/**
	 * The codigoLocalizador
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoLocalizador;
	/**
	 * The codigoMomento
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoMomento;
	/**
	 * The codigoRegiao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoRegiao;
	/**
	 * The codigoTipoInclusao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoTipoInclusao;
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
	 * The identificadorUnicoAcao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $identificadorUnicoAcao;
	/**
	 * The justificativaRepercussao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $justificativaRepercussao;
	/**
	 * The mesAnoInicio
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var dateTime
	 */
	public $mesAnoInicio;
	/**
	 * The mesAnoTermino
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var dateTime
	 */
	public $mesAnoTermino;
	/**
	 * The municipio
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $municipio;
	/**
	 * The snExclusaoLogica
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snExclusaoLogica;
	/**
	 * The totalFinanceiro
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var double
	 */
	public $totalFinanceiro;
	/**
	 * The totalFisico
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var double
	 */
	public $totalFisico;
	/**
	 * The uf
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $uf;
	/**
	 * Constructor method for localizadorDTO
	 * @see parent::__construct()
	 * @param string $_codigoLocalizador
	 * @param int $_codigoMomento
	 * @param int $_codigoRegiao
	 * @param int $_codigoTipoInclusao
	 * @param dateTime $_dataHoraAlteracao
	 * @param string $_descricao
	 * @param int $_exercicio
	 * @param int $_identificadorUnico
	 * @param int $_identificadorUnicoAcao
	 * @param string $_justificativaRepercussao
	 * @param dateTime $_mesAnoInicio
	 * @param dateTime $_mesAnoTermino
	 * @param string $_municipio
	 * @param boolean $_snExclusaoLogica
	 * @param double $_totalFinanceiro
	 * @param double $_totalFisico
	 * @param string $_uf
	 * @return QualitativoStructLocalizadorDTO
	 */
	public function __construct($_codigoLocalizador = NULL,$_codigoMomento = NULL,$_codigoRegiao = NULL,$_codigoTipoInclusao = NULL,$_dataHoraAlteracao = NULL,$_descricao = NULL,$_exercicio = NULL,$_identificadorUnico = NULL,$_identificadorUnicoAcao = NULL,$_justificativaRepercussao = NULL,$_mesAnoInicio = NULL,$_mesAnoTermino = NULL,$_municipio = NULL,$_snExclusaoLogica = NULL,$_totalFinanceiro = NULL,$_totalFisico = NULL,$_uf = NULL)
	{
		QualitativoWsdlClass::__construct(array('codigoLocalizador'=>$_codigoLocalizador,'codigoMomento'=>$_codigoMomento,'codigoRegiao'=>$_codigoRegiao,'codigoTipoInclusao'=>$_codigoTipoInclusao,'dataHoraAlteracao'=>$_dataHoraAlteracao,'descricao'=>$_descricao,'exercicio'=>$_exercicio,'identificadorUnico'=>$_identificadorUnico,'identificadorUnicoAcao'=>$_identificadorUnicoAcao,'justificativaRepercussao'=>$_justificativaRepercussao,'mesAnoInicio'=>$_mesAnoInicio,'mesAnoTermino'=>$_mesAnoTermino,'municipio'=>$_municipio,'snExclusaoLogica'=>$_snExclusaoLogica,'totalFinanceiro'=>$_totalFinanceiro,'totalFisico'=>$_totalFisico,'uf'=>$_uf));
	}
	/**
	 * Get codigoLocalizador value
	 * @return string|null
	 */
	public function getCodigoLocalizador()
	{
		return $this->codigoLocalizador;
	}
	/**
	 * Set codigoLocalizador value
	 * @param string $_codigoLocalizador the codigoLocalizador
	 * @return string
	 */
	public function setCodigoLocalizador($_codigoLocalizador)
	{
		return ($this->codigoLocalizador = $_codigoLocalizador);
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
	 * Get codigoRegiao value
	 * @return int|null
	 */
	public function getCodigoRegiao()
	{
		return $this->codigoRegiao;
	}
	/**
	 * Set codigoRegiao value
	 * @param int $_codigoRegiao the codigoRegiao
	 * @return int
	 */
	public function setCodigoRegiao($_codigoRegiao)
	{
		return ($this->codigoRegiao = $_codigoRegiao);
	}
	/**
	 * Get codigoTipoInclusao value
	 * @return int|null
	 */
	public function getCodigoTipoInclusao()
	{
		return $this->codigoTipoInclusao;
	}
	/**
	 * Set codigoTipoInclusao value
	 * @param int $_codigoTipoInclusao the codigoTipoInclusao
	 * @return int
	 */
	public function setCodigoTipoInclusao($_codigoTipoInclusao)
	{
		return ($this->codigoTipoInclusao = $_codigoTipoInclusao);
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
	 * Get identificadorUnicoAcao value
	 * @return int|null
	 */
	public function getIdentificadorUnicoAcao()
	{
		return $this->identificadorUnicoAcao;
	}
	/**
	 * Set identificadorUnicoAcao value
	 * @param int $_identificadorUnicoAcao the identificadorUnicoAcao
	 * @return int
	 */
	public function setIdentificadorUnicoAcao($_identificadorUnicoAcao)
	{
		return ($this->identificadorUnicoAcao = $_identificadorUnicoAcao);
	}
	/**
	 * Get justificativaRepercussao value
	 * @return string|null
	 */
	public function getJustificativaRepercussao()
	{
		return $this->justificativaRepercussao;
	}
	/**
	 * Set justificativaRepercussao value
	 * @param string $_justificativaRepercussao the justificativaRepercussao
	 * @return string
	 */
	public function setJustificativaRepercussao($_justificativaRepercussao)
	{
		return ($this->justificativaRepercussao = $_justificativaRepercussao);
	}
	/**
	 * Get mesAnoInicio value
	 * @return dateTime|null
	 */
	public function getMesAnoInicio()
	{
		return $this->mesAnoInicio;
	}
	/**
	 * Set mesAnoInicio value
	 * @param dateTime $_mesAnoInicio the mesAnoInicio
	 * @return dateTime
	 */
	public function setMesAnoInicio($_mesAnoInicio)
	{
		return ($this->mesAnoInicio = $_mesAnoInicio);
	}
	/**
	 * Get mesAnoTermino value
	 * @return dateTime|null
	 */
	public function getMesAnoTermino()
	{
		return $this->mesAnoTermino;
	}
	/**
	 * Set mesAnoTermino value
	 * @param dateTime $_mesAnoTermino the mesAnoTermino
	 * @return dateTime
	 */
	public function setMesAnoTermino($_mesAnoTermino)
	{
		return ($this->mesAnoTermino = $_mesAnoTermino);
	}
	/**
	 * Get municipio value
	 * @return string|null
	 */
	public function getMunicipio()
	{
		return $this->municipio;
	}
	/**
	 * Set municipio value
	 * @param string $_municipio the municipio
	 * @return string
	 */
	public function setMunicipio($_municipio)
	{
		return ($this->municipio = $_municipio);
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
	 * Get totalFinanceiro value
	 * @return double|null
	 */
	public function getTotalFinanceiro()
	{
		return $this->totalFinanceiro;
	}
	/**
	 * Set totalFinanceiro value
	 * @param double $_totalFinanceiro the totalFinanceiro
	 * @return double
	 */
	public function setTotalFinanceiro($_totalFinanceiro)
	{
		return ($this->totalFinanceiro = $_totalFinanceiro);
	}
	/**
	 * Get totalFisico value
	 * @return double|null
	 */
	public function getTotalFisico()
	{
		return $this->totalFisico;
	}
	/**
	 * Set totalFisico value
	 * @param double $_totalFisico the totalFisico
	 * @return double
	 */
	public function setTotalFisico($_totalFisico)
	{
		return ($this->totalFisico = $_totalFisico);
	}
	/**
	 * Get uf value
	 * @return string|null
	 */
	public function getUf()
	{
		return $this->uf;
	}
	/**
	 * Set uf value
	 * @param string $_uf the uf
	 * @return string
	 */
	public function setUf($_uf)
	{
		return ($this->uf = $_uf);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructLocalizadorDTO
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