<?php

/**
 * 
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuíção e/ou modifição dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuíção na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 *
 */
/**
 * Constants
 */
define('CF_APP_BASE_PATH', realpath(__DIR__ . '/..'));
define('CF_APP_ENVIRONMENT', getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'prd');

/**
 * Configurations
 */
include(CF_APP_BASE_PATH . '/classes/CFUtils.php');
include(CF_APP_BASE_PATH . '/classes/CFConfig.php');
include(CF_APP_BASE_PATH . '/classes/Config.php');

/**
 * HandleFatalError
 */
register_shutdown_function('HandleFatalError');

/**
 * HandleFatalError
 */
function HandleFatalError() {

    $error = error_get_last();

    if (!is_null($error) && (
            $error['type'] == E_ERROR ||
            $error['type'] == E_CORE_ERROR ||
            $error['type'] == E_COMPILE_ERROR ||
            $error['type'] == E_RECOVERABLE_ERROR)) {

        include('classes/Error.php');
        include('classes/Email.php');

        Error::factory()->handleFatalError()->sendEmailFatalError();
    }
}

/**
 * Config
 */
Config::factory()
        ->buildDBConfig()
        ->buildAppConstants()
        ->buildAppDefines()
        ->buildEnvironment()
;

TPPrazo::factory()->notifyUsersAllPrazosOpened();