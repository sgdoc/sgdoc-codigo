<?php
/**
 * File for class QualitativoStructFinanciamentoExtraOrcamentarioDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructFinanciamentoExtraOrcamentarioDTO originally named financiamentoExtraOrcamentarioDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructFinanciamentoExtraOrcamentarioDTO extends QualitativoStructBaseDTO
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
	 * The custoTotal
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var double
	 */
	public $custoTotal;
	/**
	 * The dataInicio
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var dateTime
	 */
	public $dataInicio;
	/**
	 * The dataTermino
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var dateTime
	 */
	public $dataTermino;
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
	 * The fonteFinanciamento
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $fonteFinanciamento;
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
	 * The outraFonteFinanciamento
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $outraFonteFinanciamento;
	/**
	 * The produto
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $produto;
	/**
	 * The snProjeto
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snProjeto;
	/**
	 * The valorAno1Ppa
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var double
	 */
	public $valorAno1Ppa;
	/**
	 * The valorAno2Ppa
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var double
	 */
	public $valorAno2Ppa;
	/**
	 * The valorAno3Ppa
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var double
	 */
	public $valorAno3Ppa;
	/**
	 * The valorAno4Ppa
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var double
	 */
	public $valorAno4Ppa;
	/**
	 * The valorTotal
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var double
	 */
	public $valorTotal;
	/**
	 * Constructor method for financiamentoExtraOrcamentarioDTO
	 * @see parent::__construct()
	 * @param int $_codigoMomento
	 * @param string $_codigoOrgaoSiorg
	 * @param double $_custoTotal
	 * @param dateTime $_dataInicio
	 * @param dateTime $_dataTermino
	 * @param string $_descricao
	 * @param int $_exercicio
	 * @param string $_fonteFinanciamento
	 * @param int $_identificadorIniciativa
	 * @param int $_identificadorUnico
	 * @param string $_outraFonteFinanciamento
	 * @param string $_produto
	 * @param boolean $_snProjeto
	 * @param double $_valorAno1Ppa
	 * @param double $_valorAno2Ppa
	 * @param double $_valorAno3Ppa
	 * @param double $_valorAno4Ppa
	 * @param double $_valorTotal
	 * @return QualitativoStructFinanciamentoExtraOrcamentarioDTO
	 */
	public function __construct($_codigoMomento = NULL,$_codigoOrgaoSiorg = NULL,$_custoTotal = NULL,$_dataInicio = NULL,$_dataTermino = NULL,$_descricao = NULL,$_exercicio = NULL,$_fonteFinanciamento = NULL,$_identificadorIniciativa = NULL,$_identificadorUnico = NULL,$_outraFonteFinanciamento = NULL,$_produto = NULL,$_snProjeto = NULL,$_valorAno1Ppa = NULL,$_valorAno2Ppa = NULL,$_valorAno3Ppa = NULL,$_valorAno4Ppa = NULL,$_valorTotal = NULL)
	{
		QualitativoWsdlClass::__construct(array('codigoMomento'=>$_codigoMomento,'codigoOrgaoSiorg'=>$_codigoOrgaoSiorg,'custoTotal'=>$_custoTotal,'dataInicio'=>$_dataInicio,'dataTermino'=>$_dataTermino,'descricao'=>$_descricao,'exercicio'=>$_exercicio,'fonteFinanciamento'=>$_fonteFinanciamento,'identificadorIniciativa'=>$_identificadorIniciativa,'identificadorUnico'=>$_identificadorUnico,'outraFonteFinanciamento'=>$_outraFonteFinanciamento,'produto'=>$_produto,'snProjeto'=>$_snProjeto,'valorAno1Ppa'=>$_valorAno1Ppa,'valorAno2Ppa'=>$_valorAno2Ppa,'valorAno3Ppa'=>$_valorAno3Ppa,'valorAno4Ppa'=>$_valorAno4Ppa,'valorTotal'=>$_valorTotal));
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
	 * Get custoTotal value
	 * @return double|null
	 */
	public function getCustoTotal()
	{
		return $this->custoTotal;
	}
	/**
	 * Set custoTotal value
	 * @param double $_custoTotal the custoTotal
	 * @return double
	 */
	public function setCustoTotal($_custoTotal)
	{
		return ($this->custoTotal = $_custoTotal);
	}
	/**
	 * Get dataInicio value
	 * @return dateTime|null
	 */
	public function getDataInicio()
	{
		return $this->dataInicio;
	}
	/**
	 * Set dataInicio value
	 * @param dateTime $_dataInicio the dataInicio
	 * @return dateTime
	 */
	public function setDataInicio($_dataInicio)
	{
		return ($this->dataInicio = $_dataInicio);
	}
	/**
	 * Get dataTermino value
	 * @return dateTime|null
	 */
	public function getDataTermino()
	{
		return $this->dataTermino;
	}
	/**
	 * Set dataTermino value
	 * @param dateTime $_dataTermino the dataTermino
	 * @return dateTime
	 */
	public function setDataTermino($_dataTermino)
	{
		return ($this->dataTermino = $_dataTermino);
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
	 * Get fonteFinanciamento value
	 * @return string|null
	 */
	public function getFonteFinanciamento()
	{
		return $this->fonteFinanciamento;
	}
	/**
	 * Set fonteFinanciamento value
	 * @param string $_fonteFinanciamento the fonteFinanciamento
	 * @return string
	 */
	public function setFonteFinanciamento($_fonteFinanciamento)
	{
		return ($this->fonteFinanciamento = $_fonteFinanciamento);
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
	 * Get outraFonteFinanciamento value
	 * @return string|null
	 */
	public function getOutraFonteFinanciamento()
	{
		return $this->outraFonteFinanciamento;
	}
	/**
	 * Set outraFonteFinanciamento value
	 * @param string $_outraFonteFinanciamento the outraFonteFinanciamento
	 * @return string
	 */
	public function setOutraFonteFinanciamento($_outraFonteFinanciamento)
	{
		return ($this->outraFonteFinanciamento = $_outraFonteFinanciamento);
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
	 * Get snProjeto value
	 * @return boolean|null
	 */
	public function getSnProjeto()
	{
		return $this->snProjeto;
	}
	/**
	 * Set snProjeto value
	 * @param boolean $_snProjeto the snProjeto
	 * @return boolean
	 */
	public function setSnProjeto($_snProjeto)
	{
		return ($this->snProjeto = $_snProjeto);
	}
	/**
	 * Get valorAno1Ppa value
	 * @return double|null
	 */
	public function getValorAno1Ppa()
	{
		return $this->valorAno1Ppa;
	}
	/**
	 * Set valorAno1Ppa value
	 * @param double $_valorAno1Ppa the valorAno1Ppa
	 * @return double
	 */
	public function setValorAno1Ppa($_valorAno1Ppa)
	{
		return ($this->valorAno1Ppa = $_valorAno1Ppa);
	}
	/**
	 * Get valorAno2Ppa value
	 * @return double|null
	 */
	public function getValorAno2Ppa()
	{
		return $this->valorAno2Ppa;
	}
	/**
	 * Set valorAno2Ppa value
	 * @param double $_valorAno2Ppa the valorAno2Ppa
	 * @return double
	 */
	public function setValorAno2Ppa($_valorAno2Ppa)
	{
		return ($this->valorAno2Ppa = $_valorAno2Ppa);
	}
	/**
	 * Get valorAno3Ppa value
	 * @return double|null
	 */
	public function getValorAno3Ppa()
	{
		return $this->valorAno3Ppa;
	}
	/**
	 * Set valorAno3Ppa value
	 * @param double $_valorAno3Ppa the valorAno3Ppa
	 * @return double
	 */
	public function setValorAno3Ppa($_valorAno3Ppa)
	{
		return ($this->valorAno3Ppa = $_valorAno3Ppa);
	}
	/**
	 * Get valorAno4Ppa value
	 * @return double|null
	 */
	public function getValorAno4Ppa()
	{
		return $this->valorAno4Ppa;
	}
	/**
	 * Set valorAno4Ppa value
	 * @param double $_valorAno4Ppa the valorAno4Ppa
	 * @return double
	 */
	public function setValorAno4Ppa($_valorAno4Ppa)
	{
		return ($this->valorAno4Ppa = $_valorAno4Ppa);
	}
	/**
	 * Get valorTotal value
	 * @return double|null
	 */
	public function getValorTotal()
	{
		return $this->valorTotal;
	}
	/**
	 * Set valorTotal value
	 * @param double $_valorTotal the valorTotal
	 * @return double
	 */
	public function setValorTotal($_valorTotal)
	{
		return ($this->valorTotal = $_valorTotal);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructFinanciamentoExtraOrcamentarioDTO
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