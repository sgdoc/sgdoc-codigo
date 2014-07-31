<?php
/**
 * File for class QualitativoStructOrgaoDTO
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructOrgaoDTO originally named orgaoDTO
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructOrgaoDTO extends QualitativoWsdlClass
{
	/**
	 * The codigoOrgao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoOrgao;
	/**
	 * The codigoOrgaoPai
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $codigoOrgaoPai;
	/**
	 * The descricao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $descricao;
	/**
	 * The descricaoAbreviada
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $descricaoAbreviada;
	/**
	 * The exercicio
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $exercicio;
	/**
	 * The orgaoId
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $orgaoId;
	/**
	 * The orgaoSiorg
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $orgaoSiorg;
	/**
	 * The snAtivo
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var boolean
	 */
	public $snAtivo;
	/**
	 * The tipoOrgao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $tipoOrgao;
	/**
	 * Constructor method for orgaoDTO
	 * @see parent::__construct()
	 * @param string $_codigoOrgao
	 * @param string $_codigoOrgaoPai
	 * @param string $_descricao
	 * @param string $_descricaoAbreviada
	 * @param int $_exercicio
	 * @param int $_orgaoId
	 * @param string $_orgaoSiorg
	 * @param boolean $_snAtivo
	 * @param string $_tipoOrgao
	 * @return QualitativoStructOrgaoDTO
	 */
	public function __construct($_codigoOrgao = NULL,$_codigoOrgaoPai = NULL,$_descricao = NULL,$_descricaoAbreviada = NULL,$_exercicio = NULL,$_orgaoId = NULL,$_orgaoSiorg = NULL,$_snAtivo = NULL,$_tipoOrgao = NULL)
	{
		parent::__construct(array('codigoOrgao'=>$_codigoOrgao,'codigoOrgaoPai'=>$_codigoOrgaoPai,'descricao'=>$_descricao,'descricaoAbreviada'=>$_descricaoAbreviada,'exercicio'=>$_exercicio,'orgaoId'=>$_orgaoId,'orgaoSiorg'=>$_orgaoSiorg,'snAtivo'=>$_snAtivo,'tipoOrgao'=>$_tipoOrgao));
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
	 * Get codigoOrgaoPai value
	 * @return string|null
	 */
	public function getCodigoOrgaoPai()
	{
		return $this->codigoOrgaoPai;
	}
	/**
	 * Set codigoOrgaoPai value
	 * @param string $_codigoOrgaoPai the codigoOrgaoPai
	 * @return string
	 */
	public function setCodigoOrgaoPai($_codigoOrgaoPai)
	{
		return ($this->codigoOrgaoPai = $_codigoOrgaoPai);
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
	 * Get descricaoAbreviada value
	 * @return string|null
	 */
	public function getDescricaoAbreviada()
	{
		return $this->descricaoAbreviada;
	}
	/**
	 * Set descricaoAbreviada value
	 * @param string $_descricaoAbreviada the descricaoAbreviada
	 * @return string
	 */
	public function setDescricaoAbreviada($_descricaoAbreviada)
	{
		return ($this->descricaoAbreviada = $_descricaoAbreviada);
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
	 * Get orgaoId value
	 * @return int|null
	 */
	public function getOrgaoId()
	{
		return $this->orgaoId;
	}
	/**
	 * Set orgaoId value
	 * @param int $_orgaoId the orgaoId
	 * @return int
	 */
	public function setOrgaoId($_orgaoId)
	{
		return ($this->orgaoId = $_orgaoId);
	}
	/**
	 * Get orgaoSiorg value
	 * @return string|null
	 */
	public function getOrgaoSiorg()
	{
		return $this->orgaoSiorg;
	}
	/**
	 * Set orgaoSiorg value
	 * @param string $_orgaoSiorg the orgaoSiorg
	 * @return string
	 */
	public function setOrgaoSiorg($_orgaoSiorg)
	{
		return ($this->orgaoSiorg = $_orgaoSiorg);
	}
	/**
	 * Get snAtivo value
	 * @return boolean|null
	 */
	public function getSnAtivo()
	{
		return $this->snAtivo;
	}
	/**
	 * Set snAtivo value
	 * @param boolean $_snAtivo the snAtivo
	 * @return boolean
	 */
	public function setSnAtivo($_snAtivo)
	{
		return ($this->snAtivo = $_snAtivo);
	}
	/**
	 * Get tipoOrgao value
	 * @return string|null
	 */
	public function getTipoOrgao()
	{
		return $this->tipoOrgao;
	}
	/**
	 * Set tipoOrgao value
	 * @param string $_tipoOrgao the tipoOrgao
	 * @return string
	 */
	public function setTipoOrgao($_tipoOrgao)
	{
		return ($this->tipoOrgao = $_tipoOrgao);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructOrgaoDTO
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