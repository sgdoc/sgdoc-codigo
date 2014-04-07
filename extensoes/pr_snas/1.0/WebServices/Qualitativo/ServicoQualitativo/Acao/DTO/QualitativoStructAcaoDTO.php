<?php
/**
 * File for class QualitativoStructAcaoDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructAcaoDTO originally named acaoDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructAcaoDTO extends QualitativoWsdlClass
{
	/**
	 * The baseLegal
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $baseLegal;
	/**
	 * The beneficiario
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $beneficiario;
	/**
	 * The codigoAcao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoAcao;
	/**
	 * The codigoEsfera
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoEsfera;
	/**
	 * The codigoFuncao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoFuncao;
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
	 * The codigoProduto
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoProduto;
	/**
	 * The codigoPrograma
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoPrograma;
	/**
	 * The codigoSubFuncao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoSubFuncao;
	/**
	 * The codigoTipoAcao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoTipoAcao;
	/**
	 * The codigoTipoInclusaoAcao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoTipoInclusaoAcao;
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
	 * The detalhamentoImplementacao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $detalhamentoImplementacao;
	/**
	 * The especificacaoProduto
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $especificacaoProduto;
	/**
	 * The exercicio
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $exercicio;
	/**
	 * The finalidade
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $finalidade;
	/**
	 * The formaAcompanhamento
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $formaAcompanhamento;
	/**
	 * The identificacaoSazonalidade
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $identificacaoSazonalidade;
	/**
	 * The identificadorUnico
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $identificadorUnico;
	/**
	 * The insumosUtilizados
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $insumosUtilizados;
	/**
	 * The snDescentralizada
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snDescentralizada;
	/**
	 * The snDireta
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snDireta;
	/**
	 * The snExclusaoLogica
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snExclusaoLogica;
	/**
	 * The snLinhaCredito
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snLinhaCredito;
	/**
	 * The snRegionalizarNaExecucao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snRegionalizarNaExecucao;
	/**
	 * The snTransferenciaObrigatoria
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snTransferenciaObrigatoria;
	/**
	 * The snTransferenciaVoluntaria
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snTransferenciaVoluntaria;
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
	 * Constructor method for acaoDTO
	 * @see parent::__construct()
	 * @param string $_baseLegal
	 * @param string $_beneficiario
	 * @param string $_codigoAcao
	 * @param string $_codigoEsfera
	 * @param string $_codigoFuncao
	 * @param string $_codigoIniciativa
	 * @param int $_codigoMomento
	 * @param string $_codigoObjetivo
	 * @param string $_codigoOrgao
	 * @param int $_codigoProduto
	 * @param string $_codigoPrograma
	 * @param string $_codigoSubFuncao
	 * @param string $_codigoTipoAcao
	 * @param int $_codigoTipoInclusaoAcao
	 * @param string $_codigoUnidadeMedida
	 * @param string $_descricao
	 * @param string $_detalhamentoImplementacao
	 * @param string $_especificacaoProduto
	 * @param int $_exercicio
	 * @param string $_finalidade
	 * @param string $_formaAcompanhamento
	 * @param string $_identificacaoSazonalidade
	 * @param int $_identificadorUnico
	 * @param string $_insumosUtilizados
	 * @param boolean $_snDescentralizada
	 * @param boolean $_snDireta
	 * @param boolean $_snExclusaoLogica
	 * @param boolean $_snLinhaCredito
	 * @param boolean $_snRegionalizarNaExecucao
	 * @param boolean $_snTransferenciaObrigatoria
	 * @param boolean $_snTransferenciaVoluntaria
	 * @param string $_titulo
	 * @param string $_unidadeResponsavel
	 * @return QualitativoStructAcaoDTO
	 */
	public function __construct($_baseLegal = NULL,$_beneficiario = NULL,$_codigoAcao = NULL,$_codigoEsfera = NULL,$_codigoFuncao = NULL,$_codigoIniciativa = NULL,$_codigoMomento = NULL,$_codigoObjetivo = NULL,$_codigoOrgao = NULL,$_codigoProduto = NULL,$_codigoPrograma = NULL,$_codigoSubFuncao = NULL,$_codigoTipoAcao = NULL,$_codigoTipoInclusaoAcao = NULL,$_codigoUnidadeMedida = NULL,$_descricao = NULL,$_detalhamentoImplementacao = NULL,$_especificacaoProduto = NULL,$_exercicio = NULL,$_finalidade = NULL,$_formaAcompanhamento = NULL,$_identificacaoSazonalidade = NULL,$_identificadorUnico = NULL,$_insumosUtilizados = NULL,$_snDescentralizada = NULL,$_snDireta = NULL,$_snExclusaoLogica = NULL,$_snLinhaCredito = NULL,$_snRegionalizarNaExecucao = NULL,$_snTransferenciaObrigatoria = NULL,$_snTransferenciaVoluntaria = NULL,$_titulo = NULL,$_unidadeResponsavel = NULL)
	{
		parent::__construct(array('baseLegal'=>$_baseLegal,'beneficiario'=>$_beneficiario,'codigoAcao'=>$_codigoAcao,'codigoEsfera'=>$_codigoEsfera,'codigoFuncao'=>$_codigoFuncao,'codigoIniciativa'=>$_codigoIniciativa,'codigoMomento'=>$_codigoMomento,'codigoObjetivo'=>$_codigoObjetivo,'codigoOrgao'=>$_codigoOrgao,'codigoProduto'=>$_codigoProduto,'codigoPrograma'=>$_codigoPrograma,'codigoSubFuncao'=>$_codigoSubFuncao,'codigoTipoAcao'=>$_codigoTipoAcao,'codigoTipoInclusaoAcao'=>$_codigoTipoInclusaoAcao,'codigoUnidadeMedida'=>$_codigoUnidadeMedida,'descricao'=>$_descricao,'detalhamentoImplementacao'=>$_detalhamentoImplementacao,'especificacaoProduto'=>$_especificacaoProduto,'exercicio'=>$_exercicio,'finalidade'=>$_finalidade,'formaAcompanhamento'=>$_formaAcompanhamento,'identificacaoSazonalidade'=>$_identificacaoSazonalidade,'identificadorUnico'=>$_identificadorUnico,'insumosUtilizados'=>$_insumosUtilizados,'snDescentralizada'=>$_snDescentralizada,'snDireta'=>$_snDireta,'snExclusaoLogica'=>$_snExclusaoLogica,'snLinhaCredito'=>$_snLinhaCredito,'snRegionalizarNaExecucao'=>$_snRegionalizarNaExecucao,'snTransferenciaObrigatoria'=>$_snTransferenciaObrigatoria,'snTransferenciaVoluntaria'=>$_snTransferenciaVoluntaria,'titulo'=>$_titulo,'unidadeResponsavel'=>$_unidadeResponsavel));
	}
	/**
	 * Get baseLegal value
	 * @return string|null
	 */
	public function getBaseLegal()
	{
		return $this->baseLegal;
	}
	/**
	 * Set baseLegal value
	 * @param string $_baseLegal the baseLegal
	 * @return string
	 */
	public function setBaseLegal($_baseLegal)
	{
		return ($this->baseLegal = $_baseLegal);
	}
	/**
	 * Get beneficiario value
	 * @return string|null
	 */
	public function getBeneficiario()
	{
		return $this->beneficiario;
	}
	/**
	 * Set beneficiario value
	 * @param string $_beneficiario the beneficiario
	 * @return string
	 */
	public function setBeneficiario($_beneficiario)
	{
		return ($this->beneficiario = $_beneficiario);
	}
	/**
	 * Get codigoAcao value
	 * @return string|null
	 */
	public function getCodigoAcao()
	{
		return $this->codigoAcao;
	}
	/**
	 * Set codigoAcao value
	 * @param string $_codigoAcao the codigoAcao
	 * @return string
	 */
	public function setCodigoAcao($_codigoAcao)
	{
		return ($this->codigoAcao = $_codigoAcao);
	}
	/**
	 * Get codigoEsfera value
	 * @return string|null
	 */
	public function getCodigoEsfera()
	{
		return $this->codigoEsfera;
	}
	/**
	 * Set codigoEsfera value
	 * @param string $_codigoEsfera the codigoEsfera
	 * @return string
	 */
	public function setCodigoEsfera($_codigoEsfera)
	{
		return ($this->codigoEsfera = $_codigoEsfera);
	}
	/**
	 * Get codigoFuncao value
	 * @return string|null
	 */
	public function getCodigoFuncao()
	{
		return $this->codigoFuncao;
	}
	/**
	 * Set codigoFuncao value
	 * @param string $_codigoFuncao the codigoFuncao
	 * @return string
	 */
	public function setCodigoFuncao($_codigoFuncao)
	{
		return ($this->codigoFuncao = $_codigoFuncao);
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
	 * Get codigoSubFuncao value
	 * @return string|null
	 */
	public function getCodigoSubFuncao()
	{
		return $this->codigoSubFuncao;
	}
	/**
	 * Set codigoSubFuncao value
	 * @param string $_codigoSubFuncao the codigoSubFuncao
	 * @return string
	 */
	public function setCodigoSubFuncao($_codigoSubFuncao)
	{
		return ($this->codigoSubFuncao = $_codigoSubFuncao);
	}
	/**
	 * Get codigoTipoAcao value
	 * @return string|null
	 */
	public function getCodigoTipoAcao()
	{
		return $this->codigoTipoAcao;
	}
	/**
	 * Set codigoTipoAcao value
	 * @param string $_codigoTipoAcao the codigoTipoAcao
	 * @return string
	 */
	public function setCodigoTipoAcao($_codigoTipoAcao)
	{
		return ($this->codigoTipoAcao = $_codigoTipoAcao);
	}
	/**
	 * Get codigoTipoInclusaoAcao value
	 * @return int|null
	 */
	public function getCodigoTipoInclusaoAcao()
	{
		return $this->codigoTipoInclusaoAcao;
	}
	/**
	 * Set codigoTipoInclusaoAcao value
	 * @param int $_codigoTipoInclusaoAcao the codigoTipoInclusaoAcao
	 * @return int
	 */
	public function setCodigoTipoInclusaoAcao($_codigoTipoInclusaoAcao)
	{
		return ($this->codigoTipoInclusaoAcao = $_codigoTipoInclusaoAcao);
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
	 * Get detalhamentoImplementacao value
	 * @return string|null
	 */
	public function getDetalhamentoImplementacao()
	{
		return $this->detalhamentoImplementacao;
	}
	/**
	 * Set detalhamentoImplementacao value
	 * @param string $_detalhamentoImplementacao the detalhamentoImplementacao
	 * @return string
	 */
	public function setDetalhamentoImplementacao($_detalhamentoImplementacao)
	{
		return ($this->detalhamentoImplementacao = $_detalhamentoImplementacao);
	}
	/**
	 * Get especificacaoProduto value
	 * @return string|null
	 */
	public function getEspecificacaoProduto()
	{
		return $this->especificacaoProduto;
	}
	/**
	 * Set especificacaoProduto value
	 * @param string $_especificacaoProduto the especificacaoProduto
	 * @return string
	 */
	public function setEspecificacaoProduto($_especificacaoProduto)
	{
		return ($this->especificacaoProduto = $_especificacaoProduto);
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
	 * Get finalidade value
	 * @return string|null
	 */
	public function getFinalidade()
	{
		return $this->finalidade;
	}
	/**
	 * Set finalidade value
	 * @param string $_finalidade the finalidade
	 * @return string
	 */
	public function setFinalidade($_finalidade)
	{
		return ($this->finalidade = $_finalidade);
	}
	/**
	 * Get formaAcompanhamento value
	 * @return string|null
	 */
	public function getFormaAcompanhamento()
	{
		return $this->formaAcompanhamento;
	}
	/**
	 * Set formaAcompanhamento value
	 * @param string $_formaAcompanhamento the formaAcompanhamento
	 * @return string
	 */
	public function setFormaAcompanhamento($_formaAcompanhamento)
	{
		return ($this->formaAcompanhamento = $_formaAcompanhamento);
	}
	/**
	 * Get identificacaoSazonalidade value
	 * @return string|null
	 */
	public function getIdentificacaoSazonalidade()
	{
		return $this->identificacaoSazonalidade;
	}
	/**
	 * Set identificacaoSazonalidade value
	 * @param string $_identificacaoSazonalidade the identificacaoSazonalidade
	 * @return string
	 */
	public function setIdentificacaoSazonalidade($_identificacaoSazonalidade)
	{
		return ($this->identificacaoSazonalidade = $_identificacaoSazonalidade);
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
	 * Get insumosUtilizados value
	 * @return string|null
	 */
	public function getInsumosUtilizados()
	{
		return $this->insumosUtilizados;
	}
	/**
	 * Set insumosUtilizados value
	 * @param string $_insumosUtilizados the insumosUtilizados
	 * @return string
	 */
	public function setInsumosUtilizados($_insumosUtilizados)
	{
		return ($this->insumosUtilizados = $_insumosUtilizados);
	}
	/**
	 * Get snDescentralizada value
	 * @return boolean|null
	 */
	public function getSnDescentralizada()
	{
		return $this->snDescentralizada;
	}
	/**
	 * Set snDescentralizada value
	 * @param boolean $_snDescentralizada the snDescentralizada
	 * @return boolean
	 */
	public function setSnDescentralizada($_snDescentralizada)
	{
		return ($this->snDescentralizada = $_snDescentralizada);
	}
	/**
	 * Get snDireta value
	 * @return boolean|null
	 */
	public function getSnDireta()
	{
		return $this->snDireta;
	}
	/**
	 * Set snDireta value
	 * @param boolean $_snDireta the snDireta
	 * @return boolean
	 */
	public function setSnDireta($_snDireta)
	{
		return ($this->snDireta = $_snDireta);
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
	 * Get snLinhaCredito value
	 * @return boolean|null
	 */
	public function getSnLinhaCredito()
	{
		return $this->snLinhaCredito;
	}
	/**
	 * Set snLinhaCredito value
	 * @param boolean $_snLinhaCredito the snLinhaCredito
	 * @return boolean
	 */
	public function setSnLinhaCredito($_snLinhaCredito)
	{
		return ($this->snLinhaCredito = $_snLinhaCredito);
	}
	/**
	 * Get snRegionalizarNaExecucao value
	 * @return boolean|null
	 */
	public function getSnRegionalizarNaExecucao()
	{
		return $this->snRegionalizarNaExecucao;
	}
	/**
	 * Set snRegionalizarNaExecucao value
	 * @param boolean $_snRegionalizarNaExecucao the snRegionalizarNaExecucao
	 * @return boolean
	 */
	public function setSnRegionalizarNaExecucao($_snRegionalizarNaExecucao)
	{
		return ($this->snRegionalizarNaExecucao = $_snRegionalizarNaExecucao);
	}
	/**
	 * Get snTransferenciaObrigatoria value
	 * @return boolean|null
	 */
	public function getSnTransferenciaObrigatoria()
	{
		return $this->snTransferenciaObrigatoria;
	}
	/**
	 * Set snTransferenciaObrigatoria value
	 * @param boolean $_snTransferenciaObrigatoria the snTransferenciaObrigatoria
	 * @return boolean
	 */
	public function setSnTransferenciaObrigatoria($_snTransferenciaObrigatoria)
	{
		return ($this->snTransferenciaObrigatoria = $_snTransferenciaObrigatoria);
	}
	/**
	 * Get snTransferenciaVoluntaria value
	 * @return boolean|null
	 */
	public function getSnTransferenciaVoluntaria()
	{
		return $this->snTransferenciaVoluntaria;
	}
	/**
	 * Set snTransferenciaVoluntaria value
	 * @param boolean $_snTransferenciaVoluntaria the snTransferenciaVoluntaria
	 * @return boolean
	 */
	public function setSnTransferenciaVoluntaria($_snTransferenciaVoluntaria)
	{
		return ($this->snTransferenciaVoluntaria = $_snTransferenciaVoluntaria);
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
	 * @return QualitativoStructAcaoDTO
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