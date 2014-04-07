<?php
/**
 * File for class QualitativoStructObterPlanosOrcamentariosPorAcao
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructObterPlanosOrcamentariosPorAcao originally named obterPlanosOrcamentariosPorAcao
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructObterPlanosOrcamentariosPorAcao extends QualitativoWsdlClass
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
	 * The identificadorUnicoAcao
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var int
	 */
	public $identificadorUnicoAcao;
	/**
	 * Constructor method for obterPlanosOrcamentariosPorAcao
	 * @see parent::__construct()
	 * @param QualitativoStructCredencialDTO $_credencial
	 * @param int $_exercicio
	 * @param int $_codigoMomento
	 * @param int $_identificadorUnicoAcao
	 * @return QualitativoStructObterPlanosOrcamentariosPorAcao
	 */
	public function __construct($_credencial = NULL,$_exercicio = NULL,$_codigoMomento = NULL,$_identificadorUnicoAcao = NULL)
	{
		parent::__construct(array('credencial'=>$_credencial,'exercicio'=>$_exercicio,'codigoMomento'=>$_codigoMomento,'identificadorUnicoAcao'=>$_identificadorUnicoAcao));
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
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see QualitativoWsdlClass::__set_state()
	 * @uses QualitativoWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return QualitativoStructObterPlanosOrcamentariosPorAcao
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