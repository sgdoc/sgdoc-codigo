<?php
/**
 * File to load generated classes once at once time
 * @package Qualitativo
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
/**
 * Includes for all generated classes files
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
require_once dirname(__FILE__) . '/QualitativoWsdlClass.php';
require_once dirname(__FILE__) . '/Base/DTO/QualitativoStructBaseDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoDTO.php';
require_once dirname(__FILE__) . '/Esfera/DTO/QualitativoStructEsferaDTO.php';
require_once dirname(__FILE__) . '/Base/DTO/QualitativoStructBaseGeograficaDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoApoioQualitativoDTO.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterTabelasApoioResponse.php';
require_once dirname(__FILE__) . '/Funcao/DTO/QualitativoStructFuncaoDTO.php';
require_once dirname(__FILE__) . '/Macro/DTO/QualitativoStructMacroDesafioDTO.php';
require_once dirname(__FILE__) . '/Perfil/DTO/QualitativoStructPerfilDTO.php';
require_once dirname(__FILE__) . '/Momento/DTO/QualitativoStructMomentoDTO.php';
require_once dirname(__FILE__) . '/Obter/Apoio/QualitativoStructObterTabelasApoio.php';
require_once dirname(__FILE__) . '/Periodicidade/DTO/QualitativoStructPeriodicidadeDTO.php';
require_once dirname(__FILE__) . '/Obter/Siorg/QualitativoStructObterOrgaoPorCodigoSiorg.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterAcoesPorIniciativaResponse.php';
require_once dirname(__FILE__) . '/Obter/Iniciativa/QualitativoStructObterAcoesPorIniciativa.php';
require_once dirname(__FILE__) . '/Financiamento/DTO/QualitativoStructFinanciamentoExtraOrcamentarioDTO.php';
require_once dirname(__FILE__) . '/Obter/Acao/QualitativoStructObterLocalizadoresPorAcao.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterLocalizadoresPorAcaoResponse.php';
require_once dirname(__FILE__) . '/Produto/DTO/QualitativoStructProdutoDTO.php';
require_once dirname(__FILE__) . '/Localizador/DTO/QualitativoStructLocalizadorDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoLocalizadoresDTO.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterOrgaoPorCodigoSiorgResponse.php';
require_once dirname(__FILE__) . '/Sub/DTO/QualitativoStructSubFuncaoDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoMomentoDTO.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterMomentoCargaResponse.php';
require_once dirname(__FILE__) . '/Obter/Carga/QualitativoStructObterMomentoCarga.php';
require_once dirname(__FILE__) . '/Plano/DTO/QualitativoStructPlanoOrcamentarioDTO.php';
require_once dirname(__FILE__) . '/Obter/Acao/QualitativoStructObterPlanosOrcamentariosPorAcao.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterPlanosOrcamentariosPorAcaoResponse.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterAcaoPorIdentificadorUnicoResponse.php';
require_once dirname(__FILE__) . '/Obter/Unico/QualitativoStructObterAcaoPorIdentificadorUnico.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoPlanoOrcamentarioDTO.php';
require_once dirname(__FILE__) . '/Agenda/DTO/QualitativoStructAgendaSamDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoProgramacaoQualitativoDTO.php';
require_once dirname(__FILE__) . '/Tipo/DTO/QualitativoStructTipoInclusaoDTO.php';
require_once dirname(__FILE__) . '/Tipo/DTO/QualitativoStructTipoAcaoDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoFinanciamentoExtraOrcamentarioDTO.php';
require_once dirname(__FILE__) . '/Tipo/DTO/QualitativoStructTipoProgramaDTO.php';
require_once dirname(__FILE__) . '/Unidade/DTO/QualitativoStructUnidadeMedidaDTO.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterProgramacaoCompletaResponse.php';
require_once dirname(__FILE__) . '/Obter/Completa/QualitativoStructObterProgramacaoCompleta.php';
require_once dirname(__FILE__) . '/Unidade/DTO/QualitativoStructUnidadeMedidaIndicadorDTO.php';
require_once dirname(__FILE__) . '/Regiao/DTO/QualitativoStructRegiaoDTO.php';
require_once dirname(__FILE__) . '/Obter/Iniciativa/QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativa.php';
require_once dirname(__FILE__) . '/Acao/DTO/QualitativoStructAcaoDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoAcoesDTO.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterAcoesPorProgramaResponse.php';
require_once dirname(__FILE__) . '/Obter/Programa/QualitativoStructObterAcoesPorPrograma.php';
require_once dirname(__FILE__) . '/Obter/Programa/QualitativoStructObterIndicadoresPorPrograma.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterIndicadoresPorProgramaResponse.php';
require_once dirname(__FILE__) . '/Obter/Programa/QualitativoStructObterObjetivosPorPrograma.php';
require_once dirname(__FILE__) . '/Indicador/DTO/QualitativoStructIndicadorDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoIndicadoresDTO.php';
require_once dirname(__FILE__) . '/Programa/DTO/QualitativoStructProgramaDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoProgramasDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoOrgaosDTO.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterOrgaoResponse.php';
require_once dirname(__FILE__) . '/Credencial/DTO/QualitativoStructCredencialDTO.php';
require_once dirname(__FILE__) . '/Orgao/DTO/QualitativoStructOrgaoDTO.php';
require_once dirname(__FILE__) . '/Obter/Orgao/QualitativoStructObterUnidadesOrcamentariasOrgao.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterProgramasPorOrgaoResponse.php';
require_once dirname(__FILE__) . '/Obter/Orgao/QualitativoStructObterProgramasPorOrgao.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterUnidadesOrcamentariasOrgaoResponse.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterObjetivosPorProgramaResponse.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoObjetivosDTO.php';
require_once dirname(__FILE__) . '/Regionalizacao/DTO/QualitativoStructRegionalizacaoDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoRegionalizacoesDTO.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterRegionalizacoesPorMetaResponse.php';
require_once dirname(__FILE__) . '/Obter/Objetivo/QualitativoStructObterIniciativasPorObjetivo.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterIniciativasPorObjetivoResponse.php';
require_once dirname(__FILE__) . '/Obter/Orgao/QualitativoStructObterOrgao.php';
require_once dirname(__FILE__) . '/Iniciativa/DTO/QualitativoStructIniciativaDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoIniciativasDTO.php';
require_once dirname(__FILE__) . '/Obter/Meta/QualitativoStructObterRegionalizacoesPorMeta.php';
require_once dirname(__FILE__) . '/Medida/DTO/QualitativoStructMedidaInstitucionalNormativaDTO.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterMetasPorObjetivoResponse.php';
require_once dirname(__FILE__) . '/Obter/Objetivo/QualitativoStructObterMetasPorObjetivo.php';
require_once dirname(__FILE__) . '/Objetivo/DTO/QualitativoStructObjetivoDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoMetasDTO.php';
require_once dirname(__FILE__) . '/Meta/DTO/QualitativoStructMetaDTO.php';
require_once dirname(__FILE__) . '/Retorno/DTO/QualitativoStructRetornoMedidaInstitucionalNormativaDTO.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterMedidaInstitucionalPorIniciativaResponse.php';
require_once dirname(__FILE__) . '/Obter/Iniciativa/QualitativoStructObterMedidaInstitucionalPorIniciativa.php';
require_once dirname(__FILE__) . '/Obter/Response/QualitativoStructObterFinanciamentoExtraOrcamentarioPorIniciativaResponse.php';
require_once dirname(__FILE__) . '/Obter/QualitativoServiceObter.php';
require_once dirname(__FILE__) . '/QualitativoClassMap.php';
?>