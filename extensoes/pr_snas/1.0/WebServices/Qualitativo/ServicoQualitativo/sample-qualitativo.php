<?php

/**
 * Test with Qualitativo
 * @package Qualitativo
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131207-01
 * @date 2014-01-04
 */
ini_set('memory_limit', '512M');
ini_set('display_errors', true);
error_reporting(-1);
/**
 * Load autoload
 */
require_once dirname(__FILE__) . '/QualitativoAutoload.php';

/**
 * Qualitativo Informations
 */
define('QUALITATIVO_WSDL_URL', 'https://testews.siop.gov.br/services/WSQualitativo?wsdl');
define('QUALITATIVO_USER_LOGIN', 'WS-SGDOC');
define('QUALITATIVO_USER_PASSWORD', 'eebeca4ba6657c201d96cd06bec548a1');
/**
 * Wsdl instanciation infos
 */
$wsdl = array();
$wsdl[QualitativoWsdlClass::WSDL_URL] = QUALITATIVO_WSDL_URL;
$wsdl[QualitativoWsdlClass::WSDL_CACHE_WSDL] = WSDL_CACHE_NONE;
$wsdl[QualitativoWsdlClass::WSDL_TRACE] = true;

if (QUALITATIVO_USER_LOGIN !== '')
    $wsdl[QualitativoWsdlClass::WSDL_LOGIN] = QUALITATIVO_USER_LOGIN;
if (QUALITATIVO_USER_PASSWORD !== '')
    $wsdl[QualitativoWsdlClass::WSDL_PASSWD] = QUALITATIVO_USER_PASSWORD;
// etc....
/**
 * Examples
 */
/* * ***********************************
 * Example for QualitativoServiceObter
 */
$qualitativoServiceObter = new QualitativoServiceObter($wsdl);

$credencial = new QualitativoStructCredencialDTO('32', QUALITATIVO_USER_PASSWORD, QUALITATIVO_USER_LOGIN);

if ($qualitativoServiceObter->obterProgramacaoCompleta(new QualitativoStructObterProgramacaoCompleta($credencial, '2013', NULL, NULL, true, null, null, null, null, null, null, null, null, null, null, null))) {
    $resultado = new QualitativoStructObterProgramacaoCompletaResponse();
    $resultado = $qualitativoServiceObter->getResult();
    
    var_dump($resultado->return->return->programasDTO);
}

?>