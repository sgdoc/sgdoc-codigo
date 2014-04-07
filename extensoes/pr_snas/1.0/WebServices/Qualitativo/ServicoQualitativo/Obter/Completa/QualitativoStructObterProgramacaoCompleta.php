<?php
/**
 * File for class QualitativoStructObterProgramacaoCompleta
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructObterProgramacaoCompleta originally named obterProgramacaoCompleta
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructObterProgramacaoCompleta extends QualitativoWsdlClass
{
	/**
	 * The credencial
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var QualitativoStructCredencialDTO
	 */
	public $credencial;
	/**
	 * The exercicio
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $exercicio;
	/**
	 * The codigoMomento
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $codigoMomento;
	/**
	 * The retornarOrgaos
	 * @var boolean
	 */
	public $retornarOrgaos;
	/**
	 * The retornarProgramas
	 * @var boolean
	 */
	public $retornarProgramas;
	/**
	 * The retornarIndicadores
	 * @var boolean
	 */
	public $retornarIndicadores;
	/**
	 * The retornarObjetivos
	 * @var boolean
	 */
	public $retornarObjetivos;
	/**
	 * The retornarIniciativas
	 * @var boolean
	 */
	public $retornarIniciativas;
	/**
	 * The retornarAcoes
	 * @var boolean
	 */
	public $retornarAcoes;
	/**
	 * The retornarLocalizadores
	 * @var boolean
	 */
	public $retornarLocalizadores;
	/**
	 * The retornarMetas
	 * @var boolean
	 */
	public $retornarMetas;
	/**
	 * The retornarRegionalizacoes
	 * @var boolean
	 */
	public $retornarRegionalizacoes;
	/**
	 * The retornarPlanosOrcamentarios
	 * @var boolean
	 */
	public $retornarPlanosOrcamentarios;
	/**
	 * The retornarAgendaSam
	 * @var boolean
	 */
	public $retornarAgendaSam;
	/**
	 * The retornarMedidasInstitucionaisNormativas
	 * @var boolean
	 */
	public $retornarMedidasInstitucionaisNormativas;
	/**
	 * The dataHoraReferencia
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var dateTime
	 */
	public $dataHoraReferencia;
	/**
	 * Constructor method for obterProgramacaoCompleta
	 * @see parent::__construct()
	 * @param QualitativoStructCredencialDTO $_credencial
	 * @param int $_exercicio
	 * @param int $_codigoMomento
	 * @param boolean $_retornarOrgaos
	 * @param boolean $_retornarProgramas
	 * @param boolean $_retornarIndicadores
	 * @param boolean $_retornarObjetivos
	 * @param boolean $_retornarIniciativas
	 * @param boolean $_retornarAcoes
	 * @param boolean $_retornarLocalizadores
	 * @param boolean $_retornarMetas
	 * @param boolean $_retornarRegionalizacoes
	 * @param boolean $_retornarPlanosOrcamentarios
	 * @param boolean $_retornarAgendaSam
	 * @param boolean $_retornarMedidasInstitucionaisNormativas
	 * @param dateTime $_dataHoraReferencia
	 * @return QualitativoStructObterProgramacaoCompleta
	 */
	public function __construct($_credencial = NULL,$_exercicio = NULL,$_codigoMomento = NULL,$_retornarOrgaos = NULL,$_retornarProgramas = NULL,$_retornarIndicadores = NULL,$_retornarObjetivos = NULL,$_retornarIniciativas = NULL,$_retornarAcoes = NULL,$_retornarLocalizadores = NULL,$_retornarMetas = NULL,$_retornarRegionalizacoes = NULL,$_retornarPlanosOrcamentarios = NULL,$_retornarAgendaSam = NULL,$_retornarMedidasInstitucionaisNormativas = NULL,$_dataHoraReferencia = NULL)
	{
		parent::__construct(array('credencial'=>$_credencial,'exercicio'=>$_exercicio,'codigoMomento'=>$_codigoMomento,'retornarOrgaos'=>$_retornarOrgaos,'retornarProgramas'=>$_retornarProgramas,'retornarIndicadores'=>$_retornarIndicadores,'retornarObjetivos'=>$_retornarObjetivos,'retornarIniciativas'=>$_retornarIniciativas,'retornarAcoes'=>$_retornarAcoes,'retornarLocalizadores'=>$_retornarLocalizadores,'retornarMetas'=>$_retornarMetas,'retornarRegionalizacoes'=>$_retornarRegionalizacoes,'retornarPlanosOrcamentarios'=>$_retornarPlanosOrcamentarios,'retornarAgendaSam'=>$_retornarAgendaSam,'retornarMedidasInstitucionaisNormativas'=>$_retornarMedidasInstitucionaisNormativas,'dataHoraReferencia'=>$_dataHoraReferencia));
	}
	/**
	 * Get credencial value
	 * @return QualitativoStructCredencialDTO|null
	 */
	public function getCredencial()
	{
		return $this->credencial;
	}
	/**
	 * Set credencial value
	 * @param QualitativoStructCredencialDTO $_credencial the credencial
	 * @return QualitativoStructCredencialDTO
	 */
	public function setCredencial($_credencial)
	{
		return ($this->credencial = $_credencial);
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
	 * Get retornarOrgaos value
	 * @return boolean|null
	 */
	public function getRetornarOrgaos()
	{
		return $this->retornarOrgaos;
	}
	/**
	 * Set retornarOrgaos value
	 * @param boolean $_retornarOrgaos the retornarOrgaos
	 * @return boolean
	 */
	public function setRetornarOrgaos($_retornarOrgaos)
	{
		return ($this->retornarOrgaos = $_retornarOrgaos);
	}
	/**
	 * Get retornarProgramas value
	 * @return boolean|null
	 */
	public function getRetornarProgramas()
	{
		return $this->retornarProgramas;
	}
	/**
	 * Set retornarProgramas value
	 * @param boolean $_retornarProgramas the retornarProgramas
	 * @return boolean
	 */
	public function setRetornarProgramas($_retornarProgramas)
	{
		return ($this->retornarProgramas = $_retornarProgramas);
	}
	/**
	 * Get retornarIndicadores value
	 * @return boolean|null
	 */
	public function getRetornarIndicadores()
	{
		return $this->retornarIndicadores;
	}
	/**
	 * Set retornarIndicadores value
	 * @param boolean $_retornarIndicadores the retornarIndicadores
	 * @return boolean
	 */
	public function setRetornarIndicadores($_retornarIndicadores)
	{
		return ($this->retornarIndicadores = $_retornarIndicadores);
	}
	/**
	 * Get retornarObjetivos value
	 * @return boolean|null
	 */
	public function getRetornarObjetivos()
	{
		return $this->retornarObjetivos;
	}
	/**
	 * Set retornarObjetivos value
	 * @param boolean $_retornarObjetivos the retornarObjetivos
	 * @return boolean
	 */
	public function setRetornarObjetivos($_retornarObjetivos)
	{
		return ($this->retornarObjetivos = $_retornarObjetivos);
	}
	/**
	 * Get retornarIniciativas value
	 * @return boolean|null
	 */
	public function getRetornarIniciativas()
	{
		return $this->retornarIniciativas;
	}
	/**
	 * Set retornarIniciativas value
	 * @param boolean $_retornarIniciativas the retornarIniciativas
	 * @return boolean
	 */
	public function setRetornarIniciativas($_retornarIniciativas)
	{
		return ($this->retornarIniciativas = $_retornarIniciativas);
	}
	/**
	 * Get retornarAcoes value
	 * @return boolean|null
	 */
	public function getRetornarAcoes()
	{
		return $this->retornarAcoes;
	}
	/**
	 * Set retornarAcoes value
	 * @param boolean $_retornarAcoes the retornarAcoes
	 * @return boolean
	 */
	public function setRetornarAcoes($_retornarAcoes)
	{
		return ($this->retornarAcoes = $_retornarAcoes);
	}
	/**
	 * Get retornarLocalizadores value
	 * @return boolean|null
	 */
	public function getRetornarLocalizadores()
	{
		return $this->retornarLocalizadores;
	}
	/**
	 * Set retornarLocalizadores value
	 * @param boolean $_retornarLocalizadores the retornarLocalizadores
	 * @return boolean
	 */
	public function setRetornarLocalizadores($_retornarLocalizadores)
	{
		return ($this->retornarLocalizadores = $_retornarLocalizadores);
	}
	/**
	 * Get retornarMetas value
	 * @return boolean|null
	 */
	public function getRetornarMetas()
	{
		return $this->retornarMetas;
	}
	/**
	 * Set retornarMetas value
	 * @param boolean $_retornarMetas the retornarMetas
	 * @return boolean
	 */
	public function setRetornarMetas($_retornarMetas)
	{
		return ($this->retornarMetas = $_retornarMetas);
	}
	/**
	 * Get retornarRegionalizacoes value
	 * @return boolean|null
	 */
	public function getRetornarRegionalizacoes()
	{
		return $this->retornarRegionalizacoes;
	}
	/**
	 * Set retornarRegionalizacoes value
	 * @param boolean $_retornarRegionalizacoes the retornarRegionalizacoes
	 * @return boolean
	 */
	public function setRetornarRegionalizacoes($_retornarRegionalizacoes)
	{
		return ($this->retornarRegionalizacoes = $_retornarRegionalizacoes);
	}
	/**
	 * Get retornarPlanosOrcamentarios value
	 * @return boolean|null
	 */
	public function getRetornarPlanosOrcamentarios()
	{
		return $this->retornarPlanosOrcamentarios;
	}
	/**
	 * Set retornarPlanosOrcamentarios value
	 * @param boolean $_retornarPlanosOrcamentarios the retornarPlanosOrcamentarios
	 * @return boolean
	 */
	public function setRetornarPlanosOrcamentarios($_retornarPlanosOrcamentarios)
	{
		return ($this->retornarPlanosOrcamentarios = $_retornarPlanosOrcamentarios);
	}
	/**
	 * Get retornarAgendaSam value
	 * @return boolean|null
	 */
	public function getRetornarAgendaSam()
	{
		return $this->retornarAgendaSam;
	}
	/**
	 * Set retornarAgendaSam value
	 * @param boolean $_retornarAgendaSam the retornarAgendaSam
	 * @return boolean
	 */
	public function setRetornarAgendaSam($_retornarAgendaSam)
	{
		return ($this->retornarAgendaSam = $_retornarAgendaSam);
	}
	/**
	 * Get retornarMedidasInstitucionaisNormativas value
	 * @return boolean|null
	 */
	public function getRetornarMedidasInstitucionaisNormativas()
	{
		return $this->retornarMedidasInstitucionaisNormativas;
	}
	/**
	 * Set retornarMedidasInstitucionaisNormativas value
	 * @param boolean $_retornarMedidasInstitucionaisNormativas the retornarMedidasInstitucionaisNormativas
	 * @return boolean
	 */
	public function setRetornarMedidasInstitucionaisNormativas($_retornarMedidasInstitucionaisNormativas)
	{
		return ($this->retornarMedidasInstitucionaisNormativas = $_retornarMedidasInstitucionaisNormativas);
	}
	/**
	 * Get dataHoraReferencia value
	 * @return dateTime|null
	 */
	public function getDataHoraReferencia()
	{
		return $this->dataHoraReferencia;
	}
	/**
	 * Set dataHoraReferencia value
	 * @param dateTime $_dataHoraReferencia the dataHoraReferencia
	 * @return dateTime
	 */
	public function setDataHoraReferencia($_dataHoraReferencia)
	{
		return ($this->dataHoraReferencia = $_dataHoraReferencia);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructObterProgramacaoCompleta
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