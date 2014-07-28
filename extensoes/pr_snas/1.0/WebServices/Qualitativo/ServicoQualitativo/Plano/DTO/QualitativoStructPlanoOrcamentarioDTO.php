<?php
/**
 * File for class QualitativoStructPlanoOrcamentarioDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructPlanoOrcamentarioDTO originally named planoOrcamentarioDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructPlanoOrcamentarioDTO extends QualitativoStructBaseDTO
{
	/**
	 * The codigoIndicadorPlanoOrcamentario
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoIndicadorPlanoOrcamentario;
	/**
	 * The codigoMomento
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoMomento;
	/**
	 * The codigoProduto
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoProduto;
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
	 * The detalhamento
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $detalhamento;
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
	 * The planoOrcamentario
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $planoOrcamentario;
	/**
	 * The snAtual
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snAtual;
	/**
	 * The titulo
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $titulo;
	/**
	 * Constructor method for planoOrcamentarioDTO
	 * @see parent::__construct()
	 * @param string $_codigoIndicadorPlanoOrcamentario
	 * @param int $_codigoMomento
	 * @param int $_codigoProduto
	 * @param string $_codigoUnidadeMedida
	 * @param dateTime $_dataHoraAlteracao
	 * @param string $_detalhamento
	 * @param int $_exercicio
	 * @param int $_identificadorUnico
	 * @param int $_identificadorUnicoAcao
	 * @param string $_planoOrcamentario
	 * @param boolean $_snAtual
	 * @param string $_titulo
	 * @return QualitativoStructPlanoOrcamentarioDTO
	 */
	public function __construct($_codigoIndicadorPlanoOrcamentario = NULL,$_codigoMomento = NULL,$_codigoProduto = NULL,$_codigoUnidadeMedida = NULL,$_dataHoraAlteracao = NULL,$_detalhamento = NULL,$_exercicio = NULL,$_identificadorUnico = NULL,$_identificadorUnicoAcao = NULL,$_planoOrcamentario = NULL,$_snAtual = NULL,$_titulo = NULL)
	{
		QualitativoWsdlClass::__construct(array('codigoIndicadorPlanoOrcamentario'=>$_codigoIndicadorPlanoOrcamentario,'codigoMomento'=>$_codigoMomento,'codigoProduto'=>$_codigoProduto,'codigoUnidadeMedida'=>$_codigoUnidadeMedida,'dataHoraAlteracao'=>$_dataHoraAlteracao,'detalhamento'=>$_detalhamento,'exercicio'=>$_exercicio,'identificadorUnico'=>$_identificadorUnico,'identificadorUnicoAcao'=>$_identificadorUnicoAcao,'planoOrcamentario'=>$_planoOrcamentario,'snAtual'=>$_snAtual,'titulo'=>$_titulo));
	}
	/**
	 * Get codigoIndicadorPlanoOrcamentario value
	 * @return string|null
	 */
	public function getCodigoIndicadorPlanoOrcamentario()
	{
		return $this->codigoIndicadorPlanoOrcamentario;
	}
	/**
	 * Set codigoIndicadorPlanoOrcamentario value
	 * @param string $_codigoIndicadorPlanoOrcamentario the codigoIndicadorPlanoOrcamentario
	 * @return string
	 */
	public function setCodigoIndicadorPlanoOrcamentario($_codigoIndicadorPlanoOrcamentario)
	{
		return ($this->codigoIndicadorPlanoOrcamentario = $_codigoIndicadorPlanoOrcamentario);
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
	 * Get codigoProduto value
	 * @return int|null
	 */
	public function getCodigoProduto()
	{
		return $this->codigoProduto;
	}
	/**
	 * Set codigoProduto value
	 * @param int $_codigoProduto the codigoProduto
	 * @return int
	 */
	public function setCodigoProduto($_codigoProduto)
	{
		return ($this->codigoProduto = $_codigoProduto);
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
	 * Get detalhamento value
	 * @return string|null
	 */
	public function getDetalhamento()
	{
		return $this->detalhamento;
	}
	/**
	 * Set detalhamento value
	 * @param string $_detalhamento the detalhamento
	 * @return string
	 */
	public function setDetalhamento($_detalhamento)
	{
		return ($this->detalhamento = $_detalhamento);
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
	 * Get planoOrcamentario value
	 * @return string|null
	 */
	public function getPlanoOrcamentario()
	{
		return $this->planoOrcamentario;
	}
	/**
	 * Set planoOrcamentario value
	 * @param string $_planoOrcamentario the planoOrcamentario
	 * @return string
	 */
	public function setPlanoOrcamentario($_planoOrcamentario)
	{
		return ($this->planoOrcamentario = $_planoOrcamentario);
	}
	/**
	 * Get snAtual value
	 * @return boolean|null
	 */
	public function getSnAtual()
	{
		return $this->snAtual;
	}
	/**
	 * Set snAtual value
	 * @param boolean $_snAtual the snAtual
	 * @return boolean
	 */
	public function setSnAtual($_snAtual)
	{
		return ($this->snAtual = $_snAtual);
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
	 * @return QualitativoStructPlanoOrcamentarioDTO
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