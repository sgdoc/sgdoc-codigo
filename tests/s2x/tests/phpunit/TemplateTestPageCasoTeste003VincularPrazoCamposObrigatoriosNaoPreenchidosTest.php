<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class VincularPrazoSuite2VincularPrazoCasoTeste003VincularPrazoCamposObrigatoriosNaoPreenchidos extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.VincularPrazo.Suite2VincularPrazo");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "CasoTeste003VincularPrazoCamposObrigatoriosNaoPreenchidos");
        $this->type("id=pageContent", "!contents -R2 -g -p -f -h\n" . '| script | selenium driver fixture |
| start browser | firefox | on url | https://tcti.sgdoce.sisicmbio.icmbio.gov.br/ |
| save screenshot after | every step | in folder | http://files/ProjectSgdoc/testResults/screenshots/${PAGE_NAME}_on_action |
| set step delay to | slow |
| do | open | on | / |
| ensure | do | type | on | id=nuCpf | with | 737.623.851-49 |
| ensure | do | type | on | id=senha | with | 0123456789 |
| ensure | do | clickAndWait | on | css=button.btn.btn-primary |
| ensure | do | clickAndWait | on | link=Acessar » |
| do | open | on | /auxiliar/vincularprazo/create |
| $sqTipoDocumento_search= | is | storeExpression | on | atestado |
| $sqAssunto_search= | is | storeExpression | on | limites de area |
| ensure | do | type | on | id=sqTipoDocumento | with | $sqTipoDocumento_search |
| ensure | do | typeKeys | on | id=sqTipoDocumento | with | keyUp |
| ensure | do | waitForElementPresent | on | class=sel |
| ensure | do | click | on | class=sel |
| ensure | do | click | on | css=button.btn.btn-primary |
| ensure | do | waitForText | on | css=p.help-block | with | Campo de preenchimento obrigatório. |
| ensure | do | type | on | id=sqAssunto | with | $sqAssunto_search |
| ensure | do | typeKeys | on | id=sqAssunto | with | keyUp |
| ensure | do | waitForElementPresent | on | class=sel |
| ensure | do | click | on | class=sel |
| ensure | do | click | on | css=button.btn.btn-primary |
| ensure | do | assertElementNotPresent | on | css=div.modal-header |
| do | selectWindow | on | null |
| check | is | assertText | on | css=div.control-group.error &gt; div.controls &gt; p.help-block | Campo de preenchimento obrigatório. |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
