<?php
/**
 * File for the class which returns the class map definition
 * @package Qualitativo
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * Class which returns the class map definition by the static method QualitativoClassMap::classMap()
 * @package Qualitativo
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
class QualitativoClassMap
{
	/**
	 * This method returns the array containing the mapping between WSDL structs and generated classes
	 * This array is sent to the SoapClient when calling the WS
	 * @return array
	 */
	final public static function classMap()
	{
		return array (
  'acaoDTO' => 'QualitativoStructAcaoDTO',
  'agendaSamDTO' => 'QualitativoStructAgendaSamDTO',
  'baseDTO' => 'QualitativoStructBaseDTO',
  'baseGeograficaDTO' => 'QualitativoStructBaseGeograficaDTO',
  'credencialDTO' => 'QualitativoStructCredencialDTO',
  'esferaDTO' => 'QualitativoStructEsferaDTO',
  'financiamentoExtraOrcamentarioDTO' => 'QualitativoStructFinanciamentoExtraOrcamentarioDTO',
  'funcaoDTO' => 'QualitativoStructFuncaoDTO',
  'indicadorDTO' => 'QualitativoStructIndicadorDTO',
  'iniciativaDTO' => 'QualitativoStructIniciativaDTO',
  'localizadorDTO' => 'QualitativoStructLocalizadorDTO',
  'macroDesafioDTO' => 'QualitativoStructMacroDesafioDTO',
  'medidaInstitucionalNormativaDTO' => 'QualitativoStructMedidaInstitucionalNormativaDTO',
  'metaDTO' => 'QualitativoStructMetaDTO',
  'momentoDTO' => 'QualitativoStructMomentoDTO',
  'objetivoDTO' => 'QualitativoStructObjetivoDTO',
  'obterAcaoPorIdentificadorUnico' => 'QualitativoStructObterAcaoPorIdentificadorUnico',
  'obterAcaoPorIdentificadorUnicoResponse' => 'QualitativoStructObterAcaoPorIdentificadorUnicoResponse',
  'obterAcoesPorIniciativa' => 'QualitativoStructObterAcoesPorIniciativa',
  'obterAcoesPorIniciativaResponse' => 'QualitativoStructObterAcoesPorIniciativaResponse',
  'obterAcoesPorPrograma' => 'QualitativoStructObterAcoesPorPrograma',
  'obterAcoesPorProgramaResponse' => 'QualitativoStructObterAcoesPorProgramaResponse',
  'obterFinanciamentoExtraOrcamentarioPorIniciativa' => 'QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa',
  'obterFinanciamentoExtraOrcamentarioPorIniciativaResponse' => 'QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativaResponse',
  'obterIndicadoresPorPrograma' => 'QualitativoStructObterIndicadoresPorPrograma',
  'obterIndicadoresPorProgramaResponse' => 'QualitativoStructObterIndicadoresPorProgramaResponse',
  'obterIniciativasPorObjetivo' => 'QualitativoStructObterIniciativasPorObjetivo',
  'obterIniciativasPorObjetivoResponse' => 'QualitativoStructObterIniciativasPorObjetivoResponse',
  'obterLocalizadoresPorAcao' => 'QualitativoStructObterLocalizadoresPorAcao',
  'obterLocalizadoresPorAcaoResponse' => 'QualitativoStructObterLocalizadoresPorAcaoResponse',
  'obterMedidaInstitucionalPorIniciativa' => 'QualitativoStructObterMedidaInstitucionalPorIniciativa',
  'obterMedidaInstitucionalPorIniciativaResponse' => 'QualitativoStructObterMedidaInstitucionalPorIniciativaResponse',
  'obterMetasPorObjetivo' => 'QualitativoStructObterMetasPorObjetivo',
  'obterMetasPorObjetivoResponse' => 'QualitativoStructObterMetasPorObjetivoResponse',
  'obterMomentoCarga' => 'QualitativoStructObterMomentoCarga',
  'obterMomentoCargaResponse' => 'QualitativoStructObterMomentoCargaResponse',
  'obterObjetivosPorPrograma' => 'QualitativoStructObterObjetivosPorPrograma',
  'obterObjetivosPorProgramaResponse' => 'QualitativoStructObterObjetivosPorProgramaResponse',
  'obterOrgao' => 'QualitativoStructObterOrgao',
  'obterOrgaoPorCodigoSiorg' => 'QualitativoStructObterOrgaoPorCodigoSiorg',
  'obterOrgaoPorCodigoSiorgResponse' => 'QualitativoStructObterOrgaoPorCodigoSiorgResponse',
  'obterOrgaoResponse' => 'QualitativoStructObterOrgaoResponse',
  'obterPlanosOrcamentariosPorAcao' => 'QualitativoStructObterPlanosOrcamentariosPorAcao',
  'obterPlanosOrcamentariosPorAcaoResponse' => 'QualitativoStructObterPlanosOrcamentariosPorAcaoResponse',
  'obterProgramacaoCompleta' => 'QualitativoStructObterProgramacaoCompleta',
  'obterProgramacaoCompletaResponse' => 'QualitativoStructObterProgramacaoCompletaResponse',
  'obterProgramasPorOrgao' => 'QualitativoStructObterProgramasPorOrgao',
  'obterProgramasPorOrgaoResponse' => 'QualitativoStructObterProgramasPorOrgaoResponse',
  'obterRegionalizacoesPorMeta' => 'QualitativoStructObterRegionalizacoesPorMeta',
  'obterRegionalizacoesPorMetaResponse' => 'QualitativoStructObterRegionalizacoesPorMetaResponse',
  'obterTabelasApoio' => 'QualitativoStructObterTabelasApoio',
  'obterTabelasApoioResponse' => 'QualitativoStructObterTabelasApoioResponse',
  'obterUnidadesOrcamentariasOrgao' => 'QualitativoStructObterUnidadesOrcamentariasOrgao',
  'obterUnidadesOrcamentariasOrgaoResponse' => 'QualitativoStructObterUnidadesOrcamentariasOrgaoResponse',
  'orgaoDTO' => 'QualitativoStructOrgaoDTO',
  'perfilDTO' => 'QualitativoStructPerfilDTO',
  'periodicidadeDTO' => 'QualitativoStructPeriodicidadeDTO',
  'planoOrcamentarioDTO' => 'QualitativoStructPlanoOrcamentarioDTO',
  'produtoDTO' => 'QualitativoStructProdutoDTO',
  'programaDTO' => 'QualitativoStructProgramaDTO',
  'regiaoDTO' => 'QualitativoStructRegiaoDTO',
  'regionalizacaoDTO' => 'QualitativoStructRegionalizacaoDTO',
  'retornoAcoesDTO' => 'QualitativoStructRetornoAcoesDTO',
  'retornoApoioQualitativoDTO' => 'QualitativoStructRetornoApoioQualitativoDTO',
  'retornoDTO' => 'QualitativoStructRetornoDTO',
  'retornoFinanciamentoExtraOrcamentarioDTO' => 'QualitativoStructRetornoFinanciamentoExtraOrcamentarioDTO',
  'retornoIndicadoresDTO' => 'QualitativoStructRetornoIndicadoresDTO',
  'retornoIniciativasDTO' => 'QualitativoStructRetornoIniciativasDTO',
  'retornoLocalizadoresDTO' => 'QualitativoStructRetornoLocalizadoresDTO',
  'retornoMedidaInstitucionalNormativaDTO' => 'QualitativoStructRetornoMedidaInstitucionalNormativaDTO',
  'retornoMetasDTO' => 'QualitativoStructRetornoMetasDTO',
  'retornoMomentoDTO' => 'QualitativoStructRetornoMomentoDTO',
  'retornoObjetivosDTO' => 'QualitativoStructRetornoObjetivosDTO',
  'retornoOrgaosDTO' => 'QualitativoStructRetornoOrgaosDTO',
  'retornoPlanoOrcamentarioDTO' => 'QualitativoStructRetornoPlanoOrcamentarioDTO',
  'retornoProgramacaoQualitativoDTO' => 'QualitativoStructRetornoProgramacaoQualitativoDTO',
  'retornoProgramasDTO' => 'QualitativoStructRetornoProgramasDTO',
  'retornoRegionalizacoesDTO' => 'QualitativoStructRetornoRegionalizacoesDTO',
  'subFuncaoDTO' => 'QualitativoStructSubFuncaoDTO',
  'tipoAcaoDTO' => 'QualitativoStructTipoAcaoDTO',
  'tipoInclusaoDTO' => 'QualitativoStructTipoInclusaoDTO',
  'tipoProgramaDTO' => 'QualitativoStructTipoProgramaDTO',
  'unidadeMedidaDTO' => 'QualitativoStructUnidadeMedidaDTO',
  'unidadeMedidaIndicadorDTO' => 'QualitativoStructUnidadeMedidaIndicadorDTO',
);
	}
}
?>