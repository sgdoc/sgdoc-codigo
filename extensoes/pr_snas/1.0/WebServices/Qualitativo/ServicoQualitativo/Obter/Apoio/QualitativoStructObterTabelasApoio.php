<?php
/**
 * File for class QualitativoStructObterTabelasApoio
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoStructObterTabelasApoio originally named obterTabelasApoio
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://testews.siop.gov.br:443/services/WSQualitativo?wsdl}
 * @package Qualitativo
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoStructObterTabelasApoio extends QualitativoWsdlClass
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
	 * The retornarMomentos
	 * @var boolean
	 */
	public $retornarMomentos;
	/**
	 * The retornarEsferas
	 * @var boolean
	 */
	public $retornarEsferas;
	/**
	 * The retornarTiposInclusao
	 * @var boolean
	 */
	public $retornarTiposInclusao;
	/**
	 * The retonarFuncoes
	 * @var boolean
	 */
	public $retonarFuncoes;
	/**
	 * The retornarSubFuncoes
	 * @var boolean
	 */
	public $retornarSubFuncoes;
	/**
	 * The retornarTiposAcao
	 * @var boolean
	 */
	public $retornarTiposAcao;
	/**
	 * The retornarProdutos
	 * @var boolean
	 */
	public $retornarProdutos;
	/**
	 * The retornarUnidadesMedida
	 * @var boolean
	 */
	public $retornarUnidadesMedida;
	/**
	 * The retornarRegioes
	 * @var boolean
	 */
	public $retornarRegioes;
	/**
	 * The retornarPerfis
	 * @var boolean
	 */
	public $retornarPerfis;
	/**
	 * The retornarTiposPrograma
	 * @var boolean
	 */
	public $retornarTiposPrograma;
	/**
	 * The retornarMacroDesafios
	 * @var boolean
	 */
	public $retornarMacroDesafios;
	/**
	 * The retornarUnidadesMedidaIndicador
	 * @var boolean
	 */
	public $retornarUnidadesMedidaIndicador;
	/**
	 * The retornarPeriodicidades
	 * @var boolean
	 */
	public $retornarPeriodicidades;
	/**
	 * The retornarBasesGeograficas
	 * @var boolean
	 */
	public $retornarBasesGeograficas;
	/**
	 * The dataHoraReferencia
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var dateTime
	 */
	public $dataHoraReferencia;
	/**
	 * Constructor method for obterTabelasApoio
	 * @see parent::__construct()
	 * @param QualitativoStructCredencialDTO $_credencial
	 * @param int $_exercicio
	 * @param boolean $_retornarMomentos
	 * @param boolean $_retornarEsferas
	 * @param boolean $_retornarTiposInclusao
	 * @param boolean $_retonarFuncoes
	 * @param boolean $_retornarSubFuncoes
	 * @param boolean $_retornarTiposAcao
	 * @param boolean $_retornarProdutos
	 * @param boolean $_retornarUnidadesMedida
	 * @param boolean $_retornarRegioes
	 * @param boolean $_retornarPerfis
	 * @param boolean $_retornarTiposPrograma
	 * @param boolean $_retornarMacroDesafios
	 * @param boolean $_retornarUnidadesMedidaIndicador
	 * @param boolean $_retornarPeriodicidades
	 * @param boolean $_retornarBasesGeograficas
	 * @param dateTime $_dataHoraReferencia
	 * @return QualitativoStructObterTabelasApoio
	 */
	public function __construct($_credencial = NULL,$_exercicio = NULL,$_retornarMomentos = NULL,$_retornarEsferas = NULL,$_retornarTiposInclusao = NULL,$_retonarFuncoes = NULL,$_retornarSubFuncoes = NULL,$_retornarTiposAcao = NULL,$_retornarProdutos = NULL,$_retornarUnidadesMedida = NULL,$_retornarRegioes = NULL,$_retornarPerfis = NULL,$_retornarTiposPrograma = NULL,$_retornarMacroDesafios = NULL,$_retornarUnidadesMedidaIndicador = NULL,$_retornarPeriodicidades = NULL,$_retornarBasesGeograficas = NULL,$_dataHoraReferencia = NULL)
	{
		parent::__construct(array('credencial'=>$_credencial,'exercicio'=>$_exercicio,'retornarMomentos'=>$_retornarMomentos,'retornarEsferas'=>$_retornarEsferas,'retornarTiposInclusao'=>$_retornarTiposInclusao,'retonarFuncoes'=>$_retonarFuncoes,'retornarSubFuncoes'=>$_retornarSubFuncoes,'retornarTiposAcao'=>$_retornarTiposAcao,'retornarProdutos'=>$_retornarProdutos,'retornarUnidadesMedida'=>$_retornarUnidadesMedida,'retornarRegioes'=>$_retornarRegioes,'retornarPerfis'=>$_retornarPerfis,'retornarTiposPrograma'=>$_retornarTiposPrograma,'retornarMacroDesafios'=>$_retornarMacroDesafios,'retornarUnidadesMedidaIndicador'=>$_retornarUnidadesMedidaIndicador,'retornarPeriodicidades'=>$_retornarPeriodicidades,'retornarBasesGeograficas'=>$_retornarBasesGeograficas,'dataHoraReferencia'=>$_dataHoraReferencia));
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
	 * Get retornarMomentos value
	 * @return boolean|null
	 */
	public function getRetornarMomentos()
	{
		return $this->retornarMomentos;
	}
	/**
	 * Set retornarMomentos value
	 * @param boolean $_retornarMomentos the retornarMomentos
	 * @return boolean
	 */
	public function setRetornarMomentos($_retornarMomentos)
	{
		return ($this->retornarMomentos = $_retornarMomentos);
	}
	/**
	 * Get retornarEsferas value
	 * @return boolean|null
	 */
	public function getRetornarEsferas()
	{
		return $this->retornarEsferas;
	}
	/**
	 * Set retornarEsferas value
	 * @param boolean $_retornarEsferas the retornarEsferas
	 * @return boolean
	 */
	public function setRetornarEsferas($_retornarEsferas)
	{
		return ($this->retornarEsferas = $_retornarEsferas);
	}
	/**
	 * Get retornarTiposInclusao value
	 * @return boolean|null
	 */
	public function getRetornarTiposInclusao()
	{
		return $this->retornarTiposInclusao;
	}
	/**
	 * Set retornarTiposInclusao value
	 * @param boolean $_retornarTiposInclusao the retornarTiposInclusao
	 * @return boolean
	 */
	public function setRetornarTiposInclusao($_retornarTiposInclusao)
	{
		return ($this->retornarTiposInclusao = $_retornarTiposInclusao);
	}
	/**
	 * Get retonarFuncoes value
	 * @return boolean|null
	 */
	public function getRetonarFuncoes()
	{
		return $this->retonarFuncoes;
	}
	/**
	 * Set retonarFuncoes value
	 * @param boolean $_retonarFuncoes the retonarFuncoes
	 * @return boolean
	 */
	public function setRetonarFuncoes($_retonarFuncoes)
	{
		return ($this->retonarFuncoes = $_retonarFuncoes);
	}
	/**
	 * Get retornarSubFuncoes value
	 * @return boolean|null
	 */
	public function getRetornarSubFuncoes()
	{
		return $this->retornarSubFuncoes;
	}
	/**
	 * Set retornarSubFuncoes value
	 * @param boolean $_retornarSubFuncoes the retornarSubFuncoes
	 * @return boolean
	 */
	public function setRetornarSubFuncoes($_retornarSubFuncoes)
	{
		return ($this->retornarSubFuncoes = $_retornarSubFuncoes);
	}
	/**
	 * Get retornarTiposAcao value
	 * @return boolean|null
	 */
	public function getRetornarTiposAcao()
	{
		return $this->retornarTiposAcao;
	}
	/**
	 * Set retornarTiposAcao value
	 * @param boolean $_retornarTiposAcao the retornarTiposAcao
	 * @return boolean
	 */
	public function setRetornarTiposAcao($_retornarTiposAcao)
	{
		return ($this->retornarTiposAcao = $_retornarTiposAcao);
	}
	/**
	 * Get retornarProdutos value
	 * @return boolean|null
	 */
	public function getRetornarProdutos()
	{
		return $this->retornarProdutos;
	}
	/**
	 * Set retornarProdutos value
	 * @param boolean $_retornarProdutos the retornarProdutos
	 * @return boolean
	 */
	public function setRetornarProdutos($_retornarProdutos)
	{
		return ($this->retornarProdutos = $_retornarProdutos);
	}
	/**
	 * Get retornarUnidadesMedida value
	 * @return boolean|null
	 */
	public function getRetornarUnidadesMedida()
	{
		return $this->retornarUnidadesMedida;
	}
	/**
	 * Set retornarUnidadesMedida value
	 * @param boolean $_retornarUnidadesMedida the retornarUnidadesMedida
	 * @return boolean
	 */
	public function setRetornarUnidadesMedida($_retornarUnidadesMedida)
	{
		return ($this->retornarUnidadesMedida = $_retornarUnidadesMedida);
	}
	/**
	 * Get retornarRegioes value
	 * @return boolean|null
	 */
	public function getRetornarRegioes()
	{
		return $this->retornarRegioes;
	}
	/**
	 * Set retornarRegioes value
	 * @param boolean $_retornarRegioes the retornarRegioes
	 * @return boolean
	 */
	public function setRetornarRegioes($_retornarRegioes)
	{
		return ($this->retornarRegioes = $_retornarRegioes);
	}
	/**
	 * Get retornarPerfis value
	 * @return boolean|null
	 */
	public function getRetornarPerfis()
	{
		return $this->retornarPerfis;
	}
	/**
	 * Set retornarPerfis value
	 * @param boolean $_retornarPerfis the retornarPerfis
	 * @return boolean
	 */
	public function setRetornarPerfis($_retornarPerfis)
	{
		return ($this->retornarPerfis = $_retornarPerfis);
	}
	/**
	 * Get retornarTiposPrograma value
	 * @return boolean|null
	 */
	public function getRetornarTiposPrograma()
	{
		return $this->retornarTiposPrograma;
	}
	/**
	 * Set retornarTiposPrograma value
	 * @param boolean $_retornarTiposPrograma the retornarTiposPrograma
	 * @return boolean
	 */
	public function setRetornarTiposPrograma($_retornarTiposPrograma)
	{
		return ($this->retornarTiposPrograma = $_retornarTiposPrograma);
	}
	/**
	 * Get retornarMacroDesafios value
	 * @return boolean|null
	 */
	public function getRetornarMacroDesafios()
	{
		return $this->retornarMacroDesafios;
	}
	/**
	 * Set retornarMacroDesafios value
	 * @param boolean $_retornarMacroDesafios the retornarMacroDesafios
	 * @return boolean
	 */
	public function setRetornarMacroDesafios($_retornarMacroDesafios)
	{
		return ($this->retornarMacroDesafios = $_retornarMacroDesafios);
	}
	/**
	 * Get retornarUnidadesMedidaIndicador value
	 * @return boolean|null
	 */
	public function getRetornarUnidadesMedidaIndicador()
	{
		return $this->retornarUnidadesMedidaIndicador;
	}
	/**
	 * Set retornarUnidadesMedidaIndicador value
	 * @param boolean $_retornarUnidadesMedidaIndicador the retornarUnidadesMedidaIndicador
	 * @return boolean
	 */
	public function setRetornarUnidadesMedidaIndicador($_retornarUnidadesMedidaIndicador)
	{
		return ($this->retornarUnidadesMedidaIndicador = $_retornarUnidadesMedidaIndicador);
	}
	/**
	 * Get retornarPeriodicidades value
	 * @return boolean|null
	 */
	public function getRetornarPeriodicidades()
	{
		return $this->retornarPeriodicidades;
	}
	/**
	 * Set retornarPeriodicidades value
	 * @param boolean $_retornarPeriodicidades the retornarPeriodicidades
	 * @return boolean
	 */
	public function setRetornarPeriodicidades($_retornarPeriodicidades)
	{
		return ($this->retornarPeriodicidades = $_retornarPeriodicidades);
	}
	/**
	 * Get retornarBasesGeograficas value
	 * @return boolean|null
	 */
	public function getRetornarBasesGeograficas()
	{
		return $this->retornarBasesGeograficas;
	}
	/**
	 * Set retornarBasesGeograficas value
	 * @param boolean $_retornarBasesGeograficas the retornarBasesGeograficas
	 * @return boolean
	 */
	public function setRetornarBasesGeograficas($_retornarBasesGeograficas)
	{
		return ($this->retornarBasesGeograficas = $_retornarBasesGeograficas);
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
	 * @return QualitativoStructObterTabelasApoio
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