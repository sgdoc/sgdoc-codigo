<?php
/**
 * File for class QualitativoStructRetornoProgramacaoQualitativoDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructRetornoProgramacaoQualitativoDTO originally named retornoProgramacaoQualitativoDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructRetornoProgramacaoQualitativoDTO extends QualitativoStructRetornoDTO
{
	/**
	 * The acoesDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructAcaoDTO
	 */
	public $acoesDTO;
	/**
	 * The agendasSamDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructAgendaSamDTO
	 */
	public $agendasSamDTO;
	/**
	 * The indicadoresDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructIndicadorDTO
	 */
	public $indicadoresDTO;
	/**
	 * The iniciativasDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructIniciativaDTO
	 */
	public $iniciativasDTO;
	/**
	 * The localizadoresDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructLocalizadorDTO
	 */
	public $localizadoresDTO;
	/**
	 * The medidasInstitucionaisNormativasDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructMedidaInstitucionalNormativaDTO
	 */
	public $medidasInstitucionaisNormativasDTO;
	/**
	 * The metasDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructMetaDTO
	 */
	public $metasDTO;
	/**
	 * The objetivosDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructObjetivoDTO
	 */
	public $objetivosDTO;
	/**
	 * The orgaosDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructOrgaoDTO
	 */
	public $orgaosDTO;
	/**
	 * The planosOrcamentariosDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructPlanoOrcamentarioDTO
	 */
	public $planosOrcamentariosDTO;
	/**
	 * The programasDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructProgramaDTO
	 */
	public $programasDTO;
	/**
	 * The regionalizacoesDTO
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : unbounded
	 * - minOccurs : 0
	 * - nillable : true
	 * @var QualitativoStructRegionalizacaoDTO
	 */
	public $regionalizacoesDTO;
	/**
	 * Constructor method for retornoProgramacaoQualitativoDTO
	 * @see parent::__construct()
	 * @param QualitativoStructAcaoDTO $_acoesDTO
	 * @param QualitativoStructAgendaSamDTO $_agendasSamDTO
	 * @param QualitativoStructIndicadorDTO $_indicadoresDTO
	 * @param QualitativoStructIniciativaDTO $_iniciativasDTO
	 * @param QualitativoStructLocalizadorDTO $_localizadoresDTO
	 * @param QualitativoStructMedidaInstitucionalNormativaDTO $_medidasInstitucionaisNormativasDTO
	 * @param QualitativoStructMetaDTO $_metasDTO
	 * @param QualitativoStructObjetivoDTO $_objetivosDTO
	 * @param QualitativoStructOrgaoDTO $_orgaosDTO
	 * @param QualitativoStructPlanoOrcamentarioDTO $_planosOrcamentariosDTO
	 * @param QualitativoStructProgramaDTO $_programasDTO
	 * @param QualitativoStructRegionalizacaoDTO $_regionalizacoesDTO
	 * @return QualitativoStructRetornoProgramacaoQualitativoDTO
	 */
	public function __construct($_acoesDTO = NULL,$_agendasSamDTO = NULL,$_indicadoresDTO = NULL,$_iniciativasDTO = NULL,$_localizadoresDTO = NULL,$_medidasInstitucionaisNormativasDTO = NULL,$_metasDTO = NULL,$_objetivosDTO = NULL,$_orgaosDTO = NULL,$_planosOrcamentariosDTO = NULL,$_programasDTO = NULL,$_regionalizacoesDTO = NULL)
	{
		QualitativoWsdlClass::__construct(array('acoesDTO'=>$_acoesDTO,'agendasSamDTO'=>$_agendasSamDTO,'indicadoresDTO'=>$_indicadoresDTO,'iniciativasDTO'=>$_iniciativasDTO,'localizadoresDTO'=>$_localizadoresDTO,'medidasInstitucionaisNormativasDTO'=>$_medidasInstitucionaisNormativasDTO,'metasDTO'=>$_metasDTO,'objetivosDTO'=>$_objetivosDTO,'orgaosDTO'=>$_orgaosDTO,'planosOrcamentariosDTO'=>$_planosOrcamentariosDTO,'programasDTO'=>$_programasDTO,'regionalizacoesDTO'=>$_regionalizacoesDTO));
	}
	/**
	 * Get acoesDTO value
	 * @return QualitativoStructAcaoDTO|null
	 */
	public function getAcoesDTO()
	{
		return $this->acoesDTO;
	}
	/**
	 * Set acoesDTO value
	 * @param QualitativoStructAcaoDTO $_acoesDTO the acoesDTO
	 * @return QualitativoStructAcaoDTO
	 */
	public function setAcoesDTO($_acoesDTO)
	{
		return ($this->acoesDTO = $_acoesDTO);
	}
	/**
	 * Get agendasSamDTO value
	 * @return QualitativoStructAgendaSamDTO|null
	 */
	public function getAgendasSamDTO()
	{
		return $this->agendasSamDTO;
	}
	/**
	 * Set agendasSamDTO value
	 * @param QualitativoStructAgendaSamDTO $_agendasSamDTO the agendasSamDTO
	 * @return QualitativoStructAgendaSamDTO
	 */
	public function setAgendasSamDTO($_agendasSamDTO)
	{
		return ($this->agendasSamDTO = $_agendasSamDTO);
	}
	/**
	 * Get indicadoresDTO value
	 * @return QualitativoStructIndicadorDTO|null
	 */
	public function getIndicadoresDTO()
	{
		return $this->indicadoresDTO;
	}
	/**
	 * Set indicadoresDTO value
	 * @param QualitativoStructIndicadorDTO $_indicadoresDTO the indicadoresDTO
	 * @return QualitativoStructIndicadorDTO
	 */
	public function setIndicadoresDTO($_indicadoresDTO)
	{
		return ($this->indicadoresDTO = $_indicadoresDTO);
	}
	/**
	 * Get iniciativasDTO value
	 * @return QualitativoStructIniciativaDTO|null
	 */
	public function getIniciativasDTO()
	{
		return $this->iniciativasDTO;
	}
	/**
	 * Set iniciativasDTO value
	 * @param QualitativoStructIniciativaDTO $_iniciativasDTO the iniciativasDTO
	 * @return QualitativoStructIniciativaDTO
	 */
	public function setIniciativasDTO($_iniciativasDTO)
	{
		return ($this->iniciativasDTO = $_iniciativasDTO);
	}
	/**
	 * Get localizadoresDTO value
	 * @return QualitativoStructLocalizadorDTO|null
	 */
	public function getLocalizadoresDTO()
	{
		return $this->localizadoresDTO;
	}
	/**
	 * Set localizadoresDTO value
	 * @param QualitativoStructLocalizadorDTO $_localizadoresDTO the localizadoresDTO
	 * @return QualitativoStructLocalizadorDTO
	 */
	public function setLocalizadoresDTO($_localizadoresDTO)
	{
		return ($this->localizadoresDTO = $_localizadoresDTO);
	}
	/**
	 * Get medidasInstitucionaisNormativasDTO value
	 * @return QualitativoStructMedidaInstitucionalNormativaDTO|null
	 */
	public function getMedidasInstitucionaisNormativasDTO()
	{
		return $this->medidasInstitucionaisNormativasDTO;
	}
	/**
	 * Set medidasInstitucionaisNormativasDTO value
	 * @param QualitativoStructMedidaInstitucionalNormativaDTO $_medidasInstitucionaisNormativasDTO the medidasInstitucionaisNormativasDTO
	 * @return QualitativoStructMedidaInstitucionalNormativaDTO
	 */
	public function setMedidasInstitucionaisNormativasDTO($_medidasInstitucionaisNormativasDTO)
	{
		return ($this->medidasInstitucionaisNormativasDTO = $_medidasInstitucionaisNormativasDTO);
	}
	/**
	 * Get metasDTO value
	 * @return QualitativoStructMetaDTO|null
	 */
	public function getMetasDTO()
	{
		return $this->metasDTO;
	}
	/**
	 * Set metasDTO value
	 * @param QualitativoStructMetaDTO $_metasDTO the metasDTO
	 * @return QualitativoStructMetaDTO
	 */
	public function setMetasDTO($_metasDTO)
	{
		return ($this->metasDTO = $_metasDTO);
	}
	/**
	 * Get objetivosDTO value
	 * @return QualitativoStructObjetivoDTO|null
	 */
	public function getObjetivosDTO()
	{
		return $this->objetivosDTO;
	}
	/**
	 * Set objetivosDTO value
	 * @param QualitativoStructObjetivoDTO $_objetivosDTO the objetivosDTO
	 * @return QualitativoStructObjetivoDTO
	 */
	public function setObjetivosDTO($_objetivosDTO)
	{
		return ($this->objetivosDTO = $_objetivosDTO);
	}
	/**
	 * Get orgaosDTO value
	 * @return QualitativoStructOrgaoDTO|null
	 */
	public function getOrgaosDTO()
	{
		return $this->orgaosDTO;
	}
	/**
	 * Set orgaosDTO value
	 * @param QualitativoStructOrgaoDTO $_orgaosDTO the orgaosDTO
	 * @return QualitativoStructOrgaoDTO
	 */
	public function setOrgaosDTO($_orgaosDTO)
	{
		return ($this->orgaosDTO = $_orgaosDTO);
	}
	/**
	 * Get planosOrcamentariosDTO value
	 * @return QualitativoStructPlanoOrcamentarioDTO|null
	 */
	public function getPlanosOrcamentariosDTO()
	{
		return $this->planosOrcamentariosDTO;
	}
	/**
	 * Set planosOrcamentariosDTO value
	 * @param QualitativoStructPlanoOrcamentarioDTO $_planosOrcamentariosDTO the planosOrcamentariosDTO
	 * @return QualitativoStructPlanoOrcamentarioDTO
	 */
	public function setPlanosOrcamentariosDTO($_planosOrcamentariosDTO)
	{
		return ($this->planosOrcamentariosDTO = $_planosOrcamentariosDTO);
	}
	/**
	 * Get programasDTO value
	 * @return QualitativoStructProgramaDTO|null
	 */
	public function getProgramasDTO()
	{
		return $this->programasDTO;
	}
	/**
	 * Set programasDTO value
	 * @param QualitativoStructProgramaDTO $_programasDTO the programasDTO
	 * @return QualitativoStructProgramaDTO
	 */
	public function setProgramasDTO($_programasDTO)
	{
		return ($this->programasDTO = $_programasDTO);
	}
	/**
	 * Get regionalizacoesDTO value
	 * @return QualitativoStructRegionalizacaoDTO|null
	 */
	public function getRegionalizacoesDTO()
	{
		return $this->regionalizacoesDTO;
	}
	/**
	 * Set regionalizacoesDTO value
	 * @param QualitativoStructRegionalizacaoDTO $_regionalizacoesDTO the regionalizacoesDTO
	 * @return QualitativoStructRegionalizacaoDTO
	 */
	public function setRegionalizacoesDTO($_regionalizacoesDTO)
	{
		return ($this->regionalizacoesDTO = $_regionalizacoesDTO);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructRetornoProgramacaoQualitativoDTO
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