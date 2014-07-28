<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class AreaTrabalhoSuite1ListaDocumentosCasoTeste001NenhumDocumentoEncontrado extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.AreaTrabalho.Suite1ListaDocumentos");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "CasoTeste001NenhumDocumentoEncontrado");
        $this->type("id=pageContent", "!contents -R2 -g -p -f -h\n" . '| script | selenium driver fixture |
| start browser | firefox | on url | http://dsvm.sgdoc.sisicmbio.icmbio.gov.br/ |
| save screenshot after | every step | in folder | http://files/ProjectSgdoc/testResults/screenshots/${PAGE_NAME}_on_action |
| set step delay to | slow |
| do | open | on | / |
| ensure | do | type | on | id=USUARIO | with | admin |
| ensure | do | type | on | id=SENHA | with | admin |
| ensure | do | clickAndWait | on | css=img.botao48 |
| check | is | assertText | on | css=span.style25 | Olá, Administrador - Setor de Gerência da Informação. |
| do | open | on | / |
| ensure | do | clickAndWait | on | css=#botao_3 &gt; img.botao48 |
| check | is | assertText | on | css=td.dataTables_empty | Nenhum documento encontrado. |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
