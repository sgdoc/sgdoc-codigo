<?php
/**
 * File for class QualitativoStructRetornoApoioQualitativoDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructRetornoApoioQualitativoDTO originally named retornoApoioQualitativoDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructRetornoApoioQualitativoDTO extends QualitativoStructRetornoDTO
{
	/**
	 * The basesGeograficasDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructBaseGeograficaDTO
	 */
	public $basesGeograficasDTO;
	/**
	 * The esferasDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructEsferaDTO
	 */
	public $esferasDTO;
	/**
	 * The funcoesDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructFuncaoDTO
	 */
	public $funcoesDTO;
	/**
	 * The macroDesafiosDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructMacroDesafioDTO
	 */
	public $macroDesafiosDTO;
	/**
	 * The momentosDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructMomentoDTO
	 */
	public $momentosDTO;
	/**
	 * The perfisDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructPerfilDTO
	 */
	public $perfisDTO;
	/**
	 * The periodicidadesDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructPeriodicidadeDTO
	 */
	public $periodicidadesDTO;
	/**
	 * The produtosDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructProdutoDTO
	 */
	public $produtosDTO;
	/**
	 * The regioesDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructRegiaoDTO
	 */
	public $regioesDTO;
	/**
	 * The subFuncoesDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructSubFuncaoDTO
	 */
	public $subFuncoesDTO;
	/**
	 * The tiposAcaoDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructTipoAcaoDTO
	 */
	public $tiposAcaoDTO;
	/**
	 * The tiposInclusaoDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructTipoInclusaoDTO
	 */
	public $tiposInclusaoDTO;
	/**
	 * The tiposProgramaDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructTipoProgramaDTO
	 */
	public $tiposProgramaDTO;
	/**
	 * The unidadesMedidaDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructUnidadeMedidaDTO
	 */
	public $unidadesMedidaDTO;
	/**
	 * The unidadesMedidaIndicadorDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructUnidadeMedidaIndicadorDTO
	 */
	public $unidadesMedidaIndicadorDTO;
	/**
	 * Constructor method for retornoApoioQualitativoDTO
	 * @see parent::__construct()
	 * @param QualitativoStructBaseGeograficaDTO $_basesGeograficasDTO
	 * @param QualitativoStructEsferaDTO $_esferasDTO
	 * @param QualitativoStructFuncaoDTO $_funcoesDTO
	 * @param QualitativoStructMacroDesafioDTO $_macroDesafiosDTO
	 * @param QualitativoStructMomentoDTO $_momentosDTO
	 * @param QualitativoStructPerfilDTO $_perfisDTO
	 * @param QualitativoStructPeriodicidadeDTO $_periodicidadesDTO
	 * @param QualitativoStructProdutoDTO $_produtosDTO
	 * @param QualitativoStructRegiaoDTO $_regioesDTO
	 * @param QualitativoStructSubFuncaoDTO $_subFuncoesDTO
	 * @param QualitativoStructTipoAcaoDTO $_tiposAcaoDTO
	 * @param QualitativoStructTipoInclusaoDTO $_tiposInclusaoDTO
	 * @param QualitativoStructTipoProgramaDTO $_tiposProgramaDTO
	 * @param QualitativoStructUnidadeMedidaDTO $_unidadesMedidaDTO
	 * @param QualitativoStructUnidadeMedidaIndicadorDTO $_unidadesMedidaIndicadorDTO
	 * @return QualitativoStructRetornoApoioQualitativoDTO
	 */
	public function __construct($_basesGeograficasDTO = NULL,$_esferasDTO = NULL,$_funcoesDTO = NULL,$_macroDesafiosDTO = NULL,$_momentosDTO = NULL,$_perfisDTO = NULL,$_periodicidadesDTO = NULL,$_produtosDTO = NULL,$_regioesDTO = NULL,$_subFuncoesDTO = NULL,$_tiposAcaoDTO = NULL,$_tiposInclusaoDTO = NULL,$_tiposProgramaDTO = NULL,$_unidadesMedidaDTO = NULL,$_unidadesMedidaIndicadorDTO = NULL)
	{
		QualitativoWsdlClass::__construct(array('basesGeograficasDTO'=>$_basesGeograficasDTO,'esferasDTO'=>$_esferasDTO,'funcoesDTO'=>$_funcoesDTO,'macroDesafiosDTO'=>$_macroDesafiosDTO,'momentosDTO'=>$_momentosDTO,'perfisDTO'=>$_perfisDTO,'periodicidadesDTO'=>$_periodicidadesDTO,'produtosDTO'=>$_produtosDTO,'regioesDTO'=>$_regioesDTO,'subFuncoesDTO'=>$_subFuncoesDTO,'tiposAcaoDTO'=>$_tiposAcaoDTO,'tiposInclusaoDTO'=>$_tiposInclusaoDTO,'tiposProgramaDTO'=>$_tiposProgramaDTO,'unidadesMedidaDTO'=>$_unidadesMedidaDTO,'unidadesMedidaIndicadorDTO'=>$_unidadesMedidaIndicadorDTO));
	}
	/**
	 * Get basesGeograficasDTO value
	 * @return QualitativoStructBaseGeograficaDTO|null
	 */
	public function getBasesGeograficasDTO()
	{
		return $this->basesGeograficasDTO;
	}
	/**
	 * Set basesGeograficasDTO value
	 * @param QualitativoStructBaseGeograficaDTO $_basesGeograficasDTO the basesGeograficasDTO
	 * @return QualitativoStructBaseGeograficaDTO
	 */
	public function setBasesGeograficasDTO($_basesGeograficasDTO)
	{
		return ($this->basesGeograficasDTO = $_basesGeograficasDTO);
	}
	/**
	 * Get esferasDTO value
	 * @return QualitativoStructEsferaDTO|null
	 */
	public function getEsferasDTO()
	{
		return $this->esferasDTO;
	}
	/**
	 * Set esferasDTO value
	 * @param QualitativoStructEsferaDTO $_esferasDTO the esferasDTO
	 * @return QualitativoStructEsferaDTO
	 */
	public function setEsferasDTO($_esferasDTO)
	{
		return ($this->esferasDTO = $_esferasDTO);
	}
	/**
	 * Get funcoesDTO value
	 * @return QualitativoStructFuncaoDTO|null
	 */
	public function getFuncoesDTO()
	{
		return $this->funcoesDTO;
	}
	/**
	 * Set funcoesDTO value
	 * @param QualitativoStructFuncaoDTO $_funcoesDTO the funcoesDTO
	 * @return QualitativoStructFuncaoDTO
	 */
	public function setFuncoesDTO($_funcoesDTO)
	{
		return ($this->funcoesDTO = $_funcoesDTO);
	}
	/**
	 * Get macroDesafiosDTO value
	 * @return QualitativoStructMacroDesafioDTO|null
	 */
	public function getMacroDesafiosDTO()
	{
		return $this->macroDesafiosDTO;
	}
	/**
	 * Set macroDesafiosDTO value
	 * @param QualitativoStructMacroDesafioDTO $_macroDesafiosDTO the macroDesafiosDTO
	 * @return QualitativoStructMacroDesafioDTO
	 */
	public function setMacroDesafiosDTO($_macroDesafiosDTO)
	{
		return ($this->macroDesafiosDTO = $_macroDesafiosDTO);
	}
	/**
	 * Get momentosDTO value
	 * @return QualitativoStructMomentoDTO|null
	 */
	public function getMomentosDTO()
	{
		return $this->momentosDTO;
	}
	/**
	 * Set momentosDTO value
	 * @param QualitativoStructMomentoDTO $_momentosDTO the momentosDTO
	 * @return QualitativoStructMomentoDTO
	 */
	public function setMomentosDTO($_momentosDTO)
	{
		return ($this->momentosDTO = $_momentosDTO);
	}
	/**
	 * Get perfisDTO value
	 * @return QualitativoStructPerfilDTO|null
	 */
	public function getPerfisDTO()
	{
		return $this->perfisDTO;
	}
	/**
	 * Set perfisDTO value
	 * @param QualitativoStructPerfilDTO $_perfisDTO the perfisDTO
	 * @return QualitativoStructPerfilDTO
	 */
	public function setPerfisDTO($_perfisDTO)
	{
		return ($this->perfisDTO = $_perfisDTO);
	}
	/**
	 * Get periodicidadesDTO value
	 * @return QualitativoStructPeriodicidadeDTO|null
	 */
	public function getPeriodicidadesDTO()
	{
		return $this->periodicidadesDTO;
	}
	/**
	 * Set periodicidadesDTO value
	 * @param QualitativoStructPeriodicidadeDTO $_periodicidadesDTO the periodicidadesDTO
	 * @return QualitativoStructPeriodicidadeDTO
	 */
	public function setPeriodicidadesDTO($_periodicidadesDTO)
	{
		return ($this->periodicidadesDTO = $_periodicidadesDTO);
	}
	/**
	 * Get produtosDTO value
	 * @return QualitativoStructProdutoDTO|null
	 */
	public function getProdutosDTO()
	{
		return $this->produtosDTO;
	}
	/**
	 * Set produtosDTO value
	 * @param QualitativoStructProdutoDTO $_produtosDTO the produtosDTO
	 * @return QualitativoStructProdutoDTO
	 */
	public function setProdutosDTO($_produtosDTO)
	{
		return ($this->produtosDTO = $_produtosDTO);
	}
	/**
	 * Get regioesDTO value
	 * @return QualitativoStructRegiaoDTO|null
	 */
	public function getRegioesDTO()
	{
		return $this->regioesDTO;
	}
	/**
	 * Set regioesDTO value
	 * @param QualitativoStructRegiaoDTO $_regioesDTO the regioesDTO
	 * @return QualitativoStructRegiaoDTO
	 */
	public function setRegioesDTO($_regioesDTO)
	{
		return ($this->regioesDTO = $_regioesDTO);
	}
	/**
	 * Get subFuncoesDTO value
	 * @return QualitativoStructSubFuncaoDTO|null
	 */
	public function getSubFuncoesDTO()
	{
		return $this->subFuncoesDTO;
	}
	/**
	 * Set subFuncoesDTO value
	 * @param QualitativoStructSubFuncaoDTO $_subFuncoesDTO the subFuncoesDTO
	 * @return QualitativoStructSubFuncaoDTO
	 */
	public function setSubFuncoesDTO($_subFuncoesDTO)
	{
		return ($this->subFuncoesDTO = $_subFuncoesDTO);
	}
	/**
	 * Get tiposAcaoDTO value
	 * @return QualitativoStructTipoAcaoDTO|null
	 */
	public function getTiposAcaoDTO()
	{
		return $this->tiposAcaoDTO;
	}
	/**
	 * Set tiposAcaoDTO value
	 * @param QualitativoStructTipoAcaoDTO $_tiposAcaoDTO the tiposAcaoDTO
	 * @return QualitativoStructTipoAcaoDTO
	 */
	public function setTiposAcaoDTO($_tiposAcaoDTO)
	{
		return ($this->tiposAcaoDTO = $_tiposAcaoDTO);
	}
	/**
	 * Get tiposInclusaoDTO value
	 * @return QualitativoStructTipoInclusaoDTO|null
	 */
	public function getTiposInclusaoDTO()
	{
		return $this->tiposInclusaoDTO;
	}
	/**
	 * Set tiposInclusaoDTO value
	 * @param QualitativoStructTipoInclusaoDTO $_tiposInclusaoDTO the tiposInclusaoDTO
	 * @return QualitativoStructTipoInclusaoDTO
	 */
	public function setTiposInclusaoDTO($_tiposInclusaoDTO)
	{
		return ($this->tiposInclusaoDTO = $_tiposInclusaoDTO);
	}
	/**
	 * Get tiposProgramaDTO value
	 * @return QualitativoStructTipoProgramaDTO|null
	 */
	public function getTiposProgramaDTO()
	{
		return $this->tiposProgramaDTO;
	}
	/**
	 * Set tiposProgramaDTO value
	 * @param QualitativoStructTipoProgramaDTO $_tiposProgramaDTO the tiposProgramaDTO
	 * @return QualitativoStructTipoProgramaDTO
	 */
	public function setTiposProgramaDTO($_tiposProgramaDTO)
	{
		return ($this->tiposProgramaDTO = $_tiposProgramaDTO);
	}
	/**
	 * Get unidadesMedidaDTO value
	 * @return QualitativoStructUnidadeMedidaDTO|null
	 */
	public function getUnidadesMedidaDTO()
	{
		return $this->unidadesMedidaDTO;
	}
	/**
	 * Set unidadesMedidaDTO value
	 * @param QualitativoStructUnidadeMedidaDTO $_unidadesMedidaDTO the unidadesMedidaDTO
	 * @return QualitativoStructUnidadeMedidaDTO
	 */
	public function setUnidadesMedidaDTO($_unidadesMedidaDTO)
	{
		return ($this->unidadesMedidaDTO = $_unidadesMedidaDTO);
	}
	/**
	 * Get unidadesMedidaIndicadorDTO value
	 * @return QualitativoStructUnidadeMedidaIndicadorDTO|null
	 */
	public function getUnidadesMedidaIndicadorDTO()
	{
		return $this->unidadesMedidaIndicadorDTO;
	}
	/**
	 * Set unidadesMedidaIndicadorDTO value
	 * @param QualitativoStructUnidadeMedidaIndicadorDTO $_unidadesMedidaIndicadorDTO the unidadesMedidaIndicadorDTO
	 * @return QualitativoStructUnidadeMedidaIndicadorDTO
	 */
	public function setUnidadesMedidaIndicadorDTO($_unidadesMedidaIndicadorDTO)
	{
		return ($this->unidadesMedidaIndicadorDTO = $_unidadesMedidaIndicadorDTO);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructRetornoApoioQualitativoDTO
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