<?php
/**
 * File for class QualitativoServiceObter
 * @package Qualitativo
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * This class stands for QualitativoServiceObter originally named Obter
 * @package Qualitativo
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoServiceObter extends QualitativoWsdlClass
{
	/**
	 * Method to call the operation originally named obterAcaoPorIdentificadorUnico
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterAcaoPorIdentificadorUnico::getCredencial()
	 * @uses QualitativoStructObterAcaoPorIdentificadorUnico::getExercicio()
	 * @uses QualitativoStructObterAcaoPorIdentificadorUnico::getCodigoMomento()
	 * @uses QualitativoStructObterAcaoPorIdentificadorUnico::getIdentificadorUnico()
	 * @param QualitativoStructObterAcaoPorIdentificadorUnico $_qualitativoStructObterAcaoPorIdentificadorUnico
	 * @return QualitativoStructObterAcaoPorIdentificadorUnicoResponse
	 */
	public function obterAcaoPorIdentificadorUnico(QualitativoStructObterAcaoPorIdentificadorUnico $_qualitativoStructObterAcaoPorIdentificadorUnico)
	{
		try
		{
			$this->setResult(new QualitativoStructObterAcaoPorIdentificadorUnicoResponse(self::getSoapClient()->obterAcaoPorIdentificadorUnico(array('credencial'=>$_qualitativoStructObterAcaoPorIdentificadorUnico->getCredencial(),'exercicio'=>$_qualitativoStructObterAcaoPorIdentificadorUnico->getExercicio(),'codigoMomento'=>$_qualitativoStructObterAcaoPorIdentificadorUnico->getCodigoMomento(),'identificadorUnico'=>$_qualitativoStructObterAcaoPorIdentificadorUnico->getIdentificadorUnico()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterAcoesPorIniciativa
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterAcoesPorIniciativa::getCredencial()
	 * @uses QualitativoStructObterAcoesPorIniciativa::getExercicio()
	 * @uses QualitativoStructObterAcoesPorIniciativa::getCodigoPrograma()
	 * @uses QualitativoStructObterAcoesPorIniciativa::getCodigoObjetivo()
	 * @uses QualitativoStructObterAcoesPorIniciativa::getCodigoIniciativa()
	 * @uses QualitativoStructObterAcoesPorIniciativa::getCodigoMomento()
	 * @uses QualitativoStructObterAcoesPorIniciativa::getDataHoraReferencia()
	 * @param QualitativoStructObterAcoesPorIniciativa $_qualitativoStructObterAcoesPorIniciativa
	 * @return QualitativoStructObterAcoesPorIniciativaResponse
	 */
	public function obterAcoesPorIniciativa(QualitativoStructObterAcoesPorIniciativa $_qualitativoStructObterAcoesPorIniciativa)
	{
		try
		{
			$this->setResult(new QualitativoStructObterAcoesPorIniciativaResponse(self::getSoapClient()->obterAcoesPorIniciativa(array('credencial'=>$_qualitativoStructObterAcoesPorIniciativa->getCredencial(),'exercicio'=>$_qualitativoStructObterAcoesPorIniciativa->getExercicio(),'codigoPrograma'=>$_qualitativoStructObterAcoesPorIniciativa->getCodigoPrograma(),'codigoObjetivo'=>$_qualitativoStructObterAcoesPorIniciativa->getCodigoObjetivo(),'codigoIniciativa'=>$_qualitativoStructObterAcoesPorIniciativa->getCodigoIniciativa(),'codigoMomento'=>$_qualitativoStructObterAcoesPorIniciativa->getCodigoMomento(),'dataHoraReferencia'=>$_qualitativoStructObterAcoesPorIniciativa->getDataHoraReferencia()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterAcoesPorPrograma
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterAcoesPorPrograma::getCredencial()
	 * @uses QualitativoStructObterAcoesPorPrograma::getExercicio()
	 * @uses QualitativoStructObterAcoesPorPrograma::getCodigoPrograma()
	 * @uses QualitativoStructObterAcoesPorPrograma::getCodigoMomento()
	 * @uses QualitativoStructObterAcoesPorPrograma::getDataHoraReferencia()
	 * @param QualitativoStructObterAcoesPorPrograma $_qualitativoStructObterAcoesPorPrograma
	 * @return QualitativoStructObterAcoesPorProgramaResponse
	 */
	public function obterAcoesPorPrograma(QualitativoStructObterAcoesPorPrograma $_qualitativoStructObterAcoesPorPrograma)
	{
		try
		{
			$this->setResult(new QualitativoStructObterAcoesPorProgramaResponse(self::getSoapClient()->obterAcoesPorPrograma(array('credencial'=>$_qualitativoStructObterAcoesPorPrograma->getCredencial(),'exercicio'=>$_qualitativoStructObterAcoesPorPrograma->getExercicio(),'codigoPrograma'=>$_qualitativoStructObterAcoesPorPrograma->getCodigoPrograma(),'codigoMomento'=>$_qualitativoStructObterAcoesPorPrograma->getCodigoMomento(),'dataHoraReferencia'=>$_qualitativoStructObterAcoesPorPrograma->getDataHoraReferencia()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterFinanciamentoExtraOrcamentarioPorIniciativa
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa::getCredencial()
	 * @uses QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa::getExercicio()
	 * @uses QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa::getCodigoPrograma()
	 * @uses QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa::getCodigoObjetivo()
	 * @uses QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa::getCodigoIniciativa()
	 * @uses QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa::getCodigoMomento()
	 * @param QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa $_qualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa
	 * @return QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativaResponse
	 */
	public function obterFinanciamentoExtraOrcamentarioPorIniciativa(QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa $_qualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa)
	{
		try
		{
			$this->setResult(new QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativaResponse(self::getSoapClient()->obterFinanciamentoExtraOrcamentarioPorIniciativa(array('credencial'=>$_qualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa->getCredencial(),'exercicio'=>$_qualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa->getExercicio(),'codigoPrograma'=>$_qualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa->getCodigoPrograma(),'codigoObjetivo'=>$_qualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa->getCodigoObjetivo(),'codigoIniciativa'=>$_qualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa->getCodigoIniciativa(),'codigoMomento'=>$_qualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa->getCodigoMomento()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterIndicadoresPorPrograma
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterIndicadoresPorPrograma::getCredencial()
	 * @uses QualitativoStructObterIndicadoresPorPrograma::getExercicio()
	 * @uses QualitativoStructObterIndicadoresPorPrograma::getCodigoPrograma()
	 * @uses QualitativoStructObterIndicadoresPorPrograma::getCodigoMomento()
	 * @uses QualitativoStructObterIndicadoresPorPrograma::getDataHoraReferencia()
	 * @param QualitativoStructObterIndicadoresPorPrograma $_qualitativoStructObterIndicadoresPorPrograma
	 * @return QualitativoStructObterIndicadoresPorProgramaResponse
	 */
	public function obterIndicadoresPorPrograma(QualitativoStructObterIndicadoresPorPrograma $_qualitativoStructObterIndicadoresPorPrograma)
	{
		try
		{
			$this->setResult(new QualitativoStructObterIndicadoresPorProgramaResponse(self::getSoapClient()->obterIndicadoresPorPrograma(array('credencial'=>$_qualitativoStructObterIndicadoresPorPrograma->getCredencial(),'exercicio'=>$_qualitativoStructObterIndicadoresPorPrograma->getExercicio(),'codigoPrograma'=>$_qualitativoStructObterIndicadoresPorPrograma->getCodigoPrograma(),'codigoMomento'=>$_qualitativoStructObterIndicadoresPorPrograma->getCodigoMomento(),'dataHoraReferencia'=>$_qualitativoStructObterIndicadoresPorPrograma->getDataHoraReferencia()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterIniciativasPorObjetivo
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterIniciativasPorObjetivo::getCredencial()
	 * @uses QualitativoStructObterIniciativasPorObjetivo::getExercicio()
	 * @uses QualitativoStructObterIniciativasPorObjetivo::getCodigoPrograma()
	 * @uses QualitativoStructObterIniciativasPorObjetivo::getCodigoObjetivo()
	 * @uses QualitativoStructObterIniciativasPorObjetivo::getCodigoMomento()
	 * @uses QualitativoStructObterIniciativasPorObjetivo::getDataHoraReferencia()
	 * @param QualitativoStructObterIniciativasPorObjetivo $_qualitativoStructObterIniciativasPorObjetivo
	 * @return QualitativoStructObterIniciativasPorObjetivoResponse
	 */
	public function obterIniciativasPorObjetivo(QualitativoStructObterIniciativasPorObjetivo $_qualitativoStructObterIniciativasPorObjetivo)
	{
		try
		{
			$this->setResult(new QualitativoStructObterIniciativasPorObjetivoResponse(self::getSoapClient()->obterIniciativasPorObjetivo(array('credencial'=>$_qualitativoStructObterIniciativasPorObjetivo->getCredencial(),'exercicio'=>$_qualitativoStructObterIniciativasPorObjetivo->getExercicio(),'codigoPrograma'=>$_qualitativoStructObterIniciativasPorObjetivo->getCodigoPrograma(),'codigoObjetivo'=>$_qualitativoStructObterIniciativasPorObjetivo->getCodigoObjetivo(),'codigoMomento'=>$_qualitativoStructObterIniciativasPorObjetivo->getCodigoMomento(),'dataHoraReferencia'=>$_qualitativoStructObterIniciativasPorObjetivo->getDataHoraReferencia()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterLocalizadoresPorAcao
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterLocalizadoresPorAcao::getCredencial()
	 * @uses QualitativoStructObterLocalizadoresPorAcao::getExercicio()
	 * @uses QualitativoStructObterLocalizadoresPorAcao::getIdentificadorUnicoAcao()
	 * @uses QualitativoStructObterLocalizadoresPorAcao::getCodigoMomento()
	 * @uses QualitativoStructObterLocalizadoresPorAcao::getDataHoraReferencia()
	 * @param QualitativoStructObterLocalizadoresPorAcao $_qualitativoStructObterLocalizadoresPorAcao
	 * @return QualitativoStructObterLocalizadoresPorAcaoResponse
	 */
	public function obterLocalizadoresPorAcao(QualitativoStructObterLocalizadoresPorAcao $_qualitativoStructObterLocalizadoresPorAcao)
	{
		try
		{
			$this->setResult(new QualitativoStructObterLocalizadoresPorAcaoResponse(self::getSoapClient()->obterLocalizadoresPorAcao(array('credencial'=>$_qualitativoStructObterLocalizadoresPorAcao->getCredencial(),'exercicio'=>$_qualitativoStructObterLocalizadoresPorAcao->getExercicio(),'identificadorUnicoAcao'=>$_qualitativoStructObterLocalizadoresPorAcao->getIdentificadorUnicoAcao(),'codigoMomento'=>$_qualitativoStructObterLocalizadoresPorAcao->getCodigoMomento(),'dataHoraReferencia'=>$_qualitativoStructObterLocalizadoresPorAcao->getDataHoraReferencia()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterMedidaInstitucionalPorIniciativa
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterMedidaInstitucionalPorIniciativa::getCredencial()
	 * @uses QualitativoStructObterMedidaInstitucionalPorIniciativa::getExercicio()
	 * @uses QualitativoStructObterMedidaInstitucionalPorIniciativa::getCodigoPrograma()
	 * @uses QualitativoStructObterMedidaInstitucionalPorIniciativa::getCodigoObjetivo()
	 * @uses QualitativoStructObterMedidaInstitucionalPorIniciativa::getCodigoIniciativa()
	 * @uses QualitativoStructObterMedidaInstitucionalPorIniciativa::getCodigoMomento()
	 * @param QualitativoStructObterMedidaInstitucionalPorIniciativa $_qualitativoStructObterMedidaInstitucionalPorIniciativa
	 * @return QualitativoStructObterMedidaInstitucionalPorIniciativaResponse
	 */
	public function obterMedidaInstitucionalPorIniciativa(QualitativoStructObterMedidaInstitucionalPorIniciativa $_qualitativoStructObterMedidaInstitucionalPorIniciativa)
	{
		try
		{
			$this->setResult(new QualitativoStructObterMedidaInstitucionalPorIniciativaResponse(self::getSoapClient()->obterMedidaInstitucionalPorIniciativa(array('credencial'=>$_qualitativoStructObterMedidaInstitucionalPorIniciativa->getCredencial(),'exercicio'=>$_qualitativoStructObterMedidaInstitucionalPorIniciativa->getExercicio(),'codigoPrograma'=>$_qualitativoStructObterMedidaInstitucionalPorIniciativa->getCodigoPrograma(),'codigoObjetivo'=>$_qualitativoStructObterMedidaInstitucionalPorIniciativa->getCodigoObjetivo(),'codigoIniciativa'=>$_qualitativoStructObterMedidaInstitucionalPorIniciativa->getCodigoIniciativa(),'codigoMomento'=>$_qualitativoStructObterMedidaInstitucionalPorIniciativa->getCodigoMomento()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterMetasPorObjetivo
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterMetasPorObjetivo::getCredencial()
	 * @uses QualitativoStructObterMetasPorObjetivo::getExercicio()
	 * @uses QualitativoStructObterMetasPorObjetivo::getCodigoPrograma()
	 * @uses QualitativoStructObterMetasPorObjetivo::getCodigoObjetivo()
	 * @uses QualitativoStructObterMetasPorObjetivo::getCodigoMomento()
	 * @uses QualitativoStructObterMetasPorObjetivo::getDataHoraReferencia()
	 * @param QualitativoStructObterMetasPorObjetivo $_qualitativoStructObterMetasPorObjetivo
	 * @return QualitativoStructObterMetasPorObjetivoResponse
	 */
	public function obterMetasPorObjetivo(QualitativoStructObterMetasPorObjetivo $_qualitativoStructObterMetasPorObjetivo)
	{
		try
		{
			$this->setResult(new QualitativoStructObterMetasPorObjetivoResponse(self::getSoapClient()->obterMetasPorObjetivo(array('credencial'=>$_qualitativoStructObterMetasPorObjetivo->getCredencial(),'exercicio'=>$_qualitativoStructObterMetasPorObjetivo->getExercicio(),'codigoPrograma'=>$_qualitativoStructObterMetasPorObjetivo->getCodigoPrograma(),'codigoObjetivo'=>$_qualitativoStructObterMetasPorObjetivo->getCodigoObjetivo(),'codigoMomento'=>$_qualitativoStructObterMetasPorObjetivo->getCodigoMomento(),'dataHoraReferencia'=>$_qualitativoStructObterMetasPorObjetivo->getDataHoraReferencia()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterMomentoCarga
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterMomentoCarga::getCredencial()
	 * @uses QualitativoStructObterMomentoCarga::getExercicio()
	 * @param QualitativoStructObterMomentoCarga $_qualitativoStructObterMomentoCarga
	 * @return QualitativoStructObterMomentoCargaResponse
	 */
	public function obterMomentoCarga(QualitativoStructObterMomentoCarga $_qualitativoStructObterMomentoCarga)
	{
		try
		{
			$this->setResult(new QualitativoStructObterMomentoCargaResponse(self::getSoapClient()->obterMomentoCarga(array('credencial'=>$_qualitativoStructObterMomentoCarga->getCredencial(),'exercicio'=>$_qualitativoStructObterMomentoCarga->getExercicio()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterObjetivosPorPrograma
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterObjetivosPorPrograma::getCredencial()
	 * @uses QualitativoStructObterObjetivosPorPrograma::getExercicio()
	 * @uses QualitativoStructObterObjetivosPorPrograma::getCodigoPrograma()
	 * @uses QualitativoStructObterObjetivosPorPrograma::getCodigoMomento()
	 * @uses QualitativoStructObterObjetivosPorPrograma::getDataHoraReferencia()
	 * @param QualitativoStructObterObjetivosPorPrograma $_qualitativoStructObterObjetivosPorPrograma
	 * @return QualitativoStructObterObjetivosPorProgramaResponse
	 */
	public function obterObjetivosPorPrograma(QualitativoStructObterObjetivosPorPrograma $_qualitativoStructObterObjetivosPorPrograma)
	{
		try
		{
			$this->setResult(new QualitativoStructObterObjetivosPorProgramaResponse(self::getSoapClient()->obterObjetivosPorPrograma(array('credencial'=>$_qualitativoStructObterObjetivosPorPrograma->getCredencial(),'exercicio'=>$_qualitativoStructObterObjetivosPorPrograma->getExercicio(),'codigoPrograma'=>$_qualitativoStructObterObjetivosPorPrograma->getCodigoPrograma(),'codigoMomento'=>$_qualitativoStructObterObjetivosPorPrograma->getCodigoMomento(),'dataHoraReferencia'=>$_qualitativoStructObterObjetivosPorPrograma->getDataHoraReferencia()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterOrgao
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterOrgao::getCredencial()
	 * @uses QualitativoStructObterOrgao::getExercicio()
	 * @uses QualitativoStructObterOrgao::getCodigoOrgao()
	 * @uses QualitativoStructObterOrgao::getTipoOrgao()
	 * @uses QualitativoStructObterOrgao::getDataHoraReferencia()
	 * @param QualitativoStructObterOrgao $_qualitativoStructObterOrgao
	 * @return QualitativoStructObterOrgaoResponse
	 */
	public function obterOrgao(QualitativoStructObterOrgao $_qualitativoStructObterOrgao)
	{
		try
		{
			$this->setResult(new QualitativoStructObterOrgaoResponse(self::getSoapClient()->obterOrgao(array('credencial'=>$_qualitativoStructObterOrgao->getCredencial(),'exercicio'=>$_qualitativoStructObterOrgao->getExercicio(),'codigoOrgao'=>$_qualitativoStructObterOrgao->getCodigoOrgao(),'tipoOrgao'=>$_qualitativoStructObterOrgao->getTipoOrgao(),'dataHoraReferencia'=>$_qualitativoStructObterOrgao->getDataHoraReferencia()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterOrgaoPorCodigoSiorg
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterOrgaoPorCodigoSiorg::getCredencial()
	 * @uses QualitativoStructObterOrgaoPorCodigoSiorg::getExercicio()
	 * @uses QualitativoStructObterOrgaoPorCodigoSiorg::getCodigoSiorg()
	 * @param QualitativoStructObterOrgaoPorCodigoSiorg $_qualitativoStructObterOrgaoPorCodigoSiorg
	 * @return QualitativoStructObterOrgaoPorCodigoSiorgResponse
	 */
	public function obterOrgaoPorCodigoSiorg(QualitativoStructObterOrgaoPorCodigoSiorg $_qualitativoStructObterOrgaoPorCodigoSiorg)
	{
		try
		{
			$this->setResult(new QualitativoStructObterOrgaoPorCodigoSiorgResponse(self::getSoapClient()->obterOrgaoPorCodigoSiorg(array('credencial'=>$_qualitativoStructObterOrgaoPorCodigoSiorg->getCredencial(),'exercicio'=>$_qualitativoStructObterOrgaoPorCodigoSiorg->getExercicio(),'codigoSiorg'=>$_qualitativoStructObterOrgaoPorCodigoSiorg->getCodigoSiorg()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterPlanosOrcamentariosPorAcao
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterPlanosOrcamentariosPorAcao::getCredencial()
	 * @uses QualitativoStructObterPlanosOrcamentariosPorAcao::getExercicio()
	 * @uses QualitativoStructObterPlanosOrcamentariosPorAcao::getCodigoMomento()
	 * @uses QualitativoStructObterPlanosOrcamentariosPorAcao::getIdentificadorUnicoAcao()
	 * @param QualitativoStructObterPlanosOrcamentariosPorAcao $_qualitativoStructObterPlanosOrcamentariosPorAcao
	 * @return QualitativoStructObterPlanosOrcamentariosPorAcaoResponse
	 */
	public function obterPlanosOrcamentariosPorAcao(QualitativoStructObterPlanosOrcamentariosPorAcao $_qualitativoStructObterPlanosOrcamentariosPorAcao)
	{
		try
		{
			$this->setResult(new QualitativoStructObterPlanosOrcamentariosPorAcaoResponse(self::getSoapClient()->obterPlanosOrcamentariosPorAcao(array('credencial'=>$_qualitativoStructObterPlanosOrcamentariosPorAcao->getCredencial(),'exercicio'=>$_qualitativoStructObterPlanosOrcamentariosPorAcao->getExercicio(),'codigoMomento'=>$_qualitativoStructObterPlanosOrcamentariosPorAcao->getCodigoMomento(),'identificadorUnicoAcao'=>$_qualitativoStructObterPlanosOrcamentariosPorAcao->getIdentificadorUnicoAcao()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterProgramacaoCompleta
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterProgramacaoCompleta::getCredencial()
	 * @uses QualitativoStructObterProgramacaoCompleta::getExercicio()
	 * @uses QualitativoStructObterProgramacaoCompleta::getCodigoMomento()
	 * @uses QualitativoStructObterProgramacaoCompleta::getRetornarOrgaos()
	 * @uses QualitativoStructObterProgramacaoCompleta::getRetornarProgramas()
	 * @uses QualitativoStructObterProgramacaoCompleta::getRetornarIndicadores()
	 * @uses QualitativoStructObterProgramacaoCompleta::getRetornarObjetivos()
	 * @uses QualitativoStructObterProgramacaoCompleta::getRetornarIniciativas()
	 * @uses QualitativoStructObterProgramacaoCompleta::getRetornarAcoes()
	 * @uses QualitativoStructObterProgramacaoCompleta::getRetornarLocalizadores()
	 * @uses QualitativoStructObterProgramacaoCompleta::getRetornarMetas()
	 * @uses QualitativoStructObterProgramacaoCompleta::getRetornarRegionalizacoes()
	 * @uses QualitativoStructObterProgramacaoCompleta::getRetornarPlanosOrcamentarios()
	 * @uses QualitativoStructObterProgramacaoCompleta::getRetornarAgendaSam()
	 * @uses QualitativoStructObterProgramacaoCompleta::getRetornarMedidasInstitucionaisNormativas()
	 * @uses QualitativoStructObterProgramacaoCompleta::getDataHoraReferencia()
	 * @param QualitativoStructObterProgramacaoCompleta $_qualitativoStructObterProgramacaoCompleta
	 * @return QualitativoStructObterProgramacaoCompletaResponse
	 */
	public function obterProgramacaoCompleta(QualitativoStructObterProgramacaoCompleta $_qualitativoStructObterProgramacaoCompleta)
	{
		try
		{
			$this->setResult(new QualitativoStructObterProgramacaoCompletaResponse(self::getSoapClient()->obterProgramacaoCompleta(array('credencial'=>$_qualitativoStructObterProgramacaoCompleta->getCredencial(),'exercicio'=>$_qualitativoStructObterProgramacaoCompleta->getExercicio(),'codigoMomento'=>$_qualitativoStructObterProgramacaoCompleta->getCodigoMomento(),'retornarOrgaos'=>$_qualitativoStructObterProgramacaoCompleta->getRetornarOrgaos(),'retornarProgramas'=>$_qualitativoStructObterProgramacaoCompleta->getRetornarProgramas(),'retornarIndicadores'=>$_qualitativoStructObterProgramacaoCompleta->getRetornarIndicadores(),'retornarObjetivos'=>$_qualitativoStructObterProgramacaoCompleta->getRetornarObjetivos(),'retornarIniciativas'=>$_qualitativoStructObterProgramacaoCompleta->getRetornarIniciativas(),'retornarAcoes'=>$_qualitativoStructObterProgramacaoCompleta->getRetornarAcoes(),'retornarLocalizadores'=>$_qualitativoStructObterProgramacaoCompleta->getRetornarLocalizadores(),'retornarMetas'=>$_qualitativoStructObterProgramacaoCompleta->getRetornarMetas(),'retornarRegionalizacoes'=>$_qualitativoStructObterProgramacaoCompleta->getRetornarRegionalizacoes(),'retornarPlanosOrcamentarios'=>$_qualitativoStructObterProgramacaoCompleta->getRetornarPlanosOrcamentarios(),'retornarAgendaSam'=>$_qualitativoStructObterProgramacaoCompleta->getRetornarAgendaSam(),'retornarMedidasInstitucionaisNormativas'=>$_qualitativoStructObterProgramacaoCompleta->getRetornarMedidasInstitucionaisNormativas(),'dataHoraReferencia'=>$_qualitativoStructObterProgramacaoCompleta->getDataHoraReferencia()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterProgramasPorOrgao
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterProgramasPorOrgao::getCredencial()
	 * @uses QualitativoStructObterProgramasPorOrgao::getExercicio()
	 * @uses QualitativoStructObterProgramasPorOrgao::getCodigoOrgao()
	 * @uses QualitativoStructObterProgramasPorOrgao::getCodigoMomento()
	 * @uses QualitativoStructObterProgramasPorOrgao::getDataHoraReferencia()
	 * @param QualitativoStructObterProgramasPorOrgao $_qualitativoStructObterProgramasPorOrgao
	 * @return QualitativoStructObterProgramasPorOrgaoResponse
	 */
	public function obterProgramasPorOrgao(QualitativoStructObterProgramasPorOrgao $_qualitativoStructObterProgramasPorOrgao)
	{
		try
		{
			$this->setResult(new QualitativoStructObterProgramasPorOrgaoResponse(self::getSoapClient()->obterProgramasPorOrgao(array('credencial'=>$_qualitativoStructObterProgramasPorOrgao->getCredencial(),'exercicio'=>$_qualitativoStructObterProgramasPorOrgao->getExercicio(),'codigoOrgao'=>$_qualitativoStructObterProgramasPorOrgao->getCodigoOrgao(),'codigoMomento'=>$_qualitativoStructObterProgramasPorOrgao->getCodigoMomento(),'dataHoraReferencia'=>$_qualitativoStructObterProgramasPorOrgao->getDataHoraReferencia()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterRegionalizacoesPorMeta
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterRegionalizacoesPorMeta::getCredencial()
	 * @uses QualitativoStructObterRegionalizacoesPorMeta::getExercicio()
	 * @uses QualitativoStructObterRegionalizacoesPorMeta::getCodigoPrograma()
	 * @uses QualitativoStructObterRegionalizacoesPorMeta::getCodigoObjetivo()
	 * @uses QualitativoStructObterRegionalizacoesPorMeta::getCodigoMeta()
	 * @uses QualitativoStructObterRegionalizacoesPorMeta::getCodigoMomento()
	 * @uses QualitativoStructObterRegionalizacoesPorMeta::getDataHoraReferencia()
	 * @param QualitativoStructObterRegionalizacoesPorMeta $_qualitativoStructObterRegionalizacoesPorMeta
	 * @return QualitativoStructObterRegionalizacoesPorMetaResponse
	 */
	public function obterRegionalizacoesPorMeta(QualitativoStructObterRegionalizacoesPorMeta $_qualitativoStructObterRegionalizacoesPorMeta)
	{
		try
		{
			$this->setResult(new QualitativoStructObterRegionalizacoesPorMetaResponse(self::getSoapClient()->obterRegionalizacoesPorMeta(array('credencial'=>$_qualitativoStructObterRegionalizacoesPorMeta->getCredencial(),'exercicio'=>$_qualitativoStructObterRegionalizacoesPorMeta->getExercicio(),'codigoPrograma'=>$_qualitativoStructObterRegionalizacoesPorMeta->getCodigoPrograma(),'codigoObjetivo'=>$_qualitativoStructObterRegionalizacoesPorMeta->getCodigoObjetivo(),'codigoMeta'=>$_qualitativoStructObterRegionalizacoesPorMeta->getCodigoMeta(),'codigoMomento'=>$_qualitativoStructObterRegionalizacoesPorMeta->getCodigoMomento(),'dataHoraReferencia'=>$_qualitativoStructObterRegionalizacoesPorMeta->getDataHoraReferencia()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterTabelasApoio
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterTabelasApoio::getCredencial()
	 * @uses QualitativoStructObterTabelasApoio::getExercicio()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarMomentos()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarEsferas()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarTiposInclusao()
	 * @uses QualitativoStructObterTabelasApoio::getRetonarFuncoes()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarSubFuncoes()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarTiposAcao()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarProdutos()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarUnidadesMedida()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarRegioes()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarPerfis()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarTiposPrograma()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarMacroDesafios()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarUnidadesMedidaIndicador()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarPeriodicidades()
	 * @uses QualitativoStructObterTabelasApoio::getRetornarBasesGeograficas()
	 * @uses QualitativoStructObterTabelasApoio::getDataHoraReferencia()
	 * @param QualitativoStructObterTabelasApoio $_qualitativoStructObterTabelasApoio
	 * @return QualitativoStructObterTabelasApoioResponse
	 */
	public function obterTabelasApoio(QualitativoStructObterTabelasApoio $_qualitativoStructObterTabelasApoio)
	{
		try
		{
			$this->setResult(new QualitativoStructObterTabelasApoioResponse(self::getSoapClient()->obterTabelasApoio(array('credencial'=>$_qualitativoStructObterTabelasApoio->getCredencial(),'exercicio'=>$_qualitativoStructObterTabelasApoio->getExercicio(),'retornarMomentos'=>$_qualitativoStructObterTabelasApoio->getRetornarMomentos(),'retornarEsferas'=>$_qualitativoStructObterTabelasApoio->getRetornarEsferas(),'retornarTiposInclusao'=>$_qualitativoStructObterTabelasApoio->getRetornarTiposInclusao(),'retonarFuncoes'=>$_qualitativoStructObterTabelasApoio->getRetonarFuncoes(),'retornarSubFuncoes'=>$_qualitativoStructObterTabelasApoio->getRetornarSubFuncoes(),'retornarTiposAcao'=>$_qualitativoStructObterTabelasApoio->getRetornarTiposAcao(),'retornarProdutos'=>$_qualitativoStructObterTabelasApoio->getRetornarProdutos(),'retornarUnidadesMedida'=>$_qualitativoStructObterTabelasApoio->getRetornarUnidadesMedida(),'retornarRegioes'=>$_qualitativoStructObterTabelasApoio->getRetornarRegioes(),'retornarPerfis'=>$_qualitativoStructObterTabelasApoio->getRetornarPerfis(),'retornarTiposPrograma'=>$_qualitativoStructObterTabelasApoio->getRetornarTiposPrograma(),'retornarMacroDesafios'=>$_qualitativoStructObterTabelasApoio->getRetornarMacroDesafios(),'retornarUnidadesMedidaIndicador'=>$_qualitativoStructObterTabelasApoio->getRetornarUnidadesMedidaIndicador(),'retornarPeriodicidades'=>$_qualitativoStructObterTabelasApoio->getRetornarPeriodicidades(),'retornarBasesGeograficas'=>$_qualitativoStructObterTabelasApoio->getRetornarBasesGeograficas(),'dataHoraReferencia'=>$_qualitativoStructObterTabelasApoio->getDataHoraReferencia()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named obterUnidadesOrcamentariasOrgao
	 * @uses QualitativoWsdlClass::getSoapClient()
	 * @uses QualitativoWsdlClass::setResult()
	 * @uses QualitativoWsdlClass::getResult()
	 * @uses QualitativoWsdlClass::saveLastError()
	 * @uses QualitativoStructObterUnidadesOrcamentariasOrgao::getCredencial()
	 * @uses QualitativoStructObterUnidadesOrcamentariasOrgao::getExercicio()
	 * @uses QualitativoStructObterUnidadesOrcamentariasOrgao::getCodigoOrgao()
	 * @param QualitativoStructObterUnidadesOrcamentariasOrgao $_qualitativoStructObterUnidadesOrcamentariasOrgao
	 * @return QualitativoStructObterUnidadesOrcamentariasOrgaoResponse
	 */
	public function obterUnidadesOrcamentariasOrgao(QualitativoStructObterUnidadesOrcamentariasOrgao $_qualitativoStructObterUnidadesOrcamentariasOrgao)
	{
		try
		{
			$this->setResult(new QualitativoStructObterUnidadesOrcamentariasOrgaoResponse(self::getSoapClient()->obterUnidadesOrcamentariasOrgao(array('credencial'=>$_qualitativoStructObterUnidadesOrcamentariasOrgao->getCredencial(),'exercicio'=>$_qualitativoStructObterUnidadesOrcamentariasOrgao->getExercicio(),'codigoOrgao'=>$_qualitativoStructObterUnidadesOrcamentariasOrgao->getCodigoOrgao()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Returns the result
	 * @see QualitativoWsdlClass::getResult()
	 * @return QualitativoStructObterAcaoPorIdentificadorUnicoResponse|QualitativoStructObterAcoesPorIniciativaResponse|QualitativoStructObterAcoesPorProgramaResponse|QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativaResponse|QualitativoStructObterIndicadoresPorProgramaResponse|QualitativoStructObterIniciativasPorObjetivoResponse|QualitativoStructObterLocalizadoresPorAcaoResponse|QualitativoStructObterMedidaInstitucionalPorIniciativaResponse|QualitativoStructObterMetasPorObjetivoResponse|QualitativoStructObterMomentoCargaResponse|QualitativoStructObterObjetivosPorProgramaResponse|QualitativoStructObterOrgaoPorCodigoSiorgResponse|QualitativoStructObterOrgaoResponse|QualitativoStructObterPlanosOrcamentariosPorAcaoResponse|QualitativoStructObterProgramacaoCompletaResponse|QualitativoStructObterProgramasPorOrgaoResponse|QualitativoStructObterRegionalizacoesPorMetaResponse|QualitativoStructObterTabelasApoioResponse|QualitativoStructObterUnidadesOrcamentariasOrgaoResponse
	 */
	public function getResult()
	{
		return parent::getResult();
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