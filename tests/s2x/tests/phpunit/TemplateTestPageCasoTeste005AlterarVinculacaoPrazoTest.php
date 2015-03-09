<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class VincularPrazoSuite3AterarVinculacaoPrazoCasoTeste005AlterarVinculacaoPrazo extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.VincularPrazo.Suite3AterarVinculacaoPrazo");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "CasoTeste005AlterarVinculacaoPrazo");
        $this->type("id=pageContent", "!contents -R2 -g -p -f -h\n" . '| script | selenium driver fixture |
| start browser | firefox | on url | https://tcti.sgdoce.sisicmbio.icmbio.gov.br/ |
| save screenshot after | every step | in folder | http://files/ProjectSgdoc/testResults/screenshots/${PAGE_NAME}_on_action |
| set step delay to | slow |
| do | open | on | / |
| ensure | do | type | on | id=nuCpf | with | 737.623.851-49 |
| ensure | do | type | on | id=senha | with | 0123456789 |
| ensure | do | clickAndWait | on | css=button.btn.btn-primary |
| ensure | do | clickAndWait | on | link=Acessar » |
| do | open | on | / |
| ensure | do | click | on | link=Cadastrar |
| ensure | do | clickAndWait | on | link=Vinculo de Prazo |
| $sqTipoDocumento_search= | is | storeExpression | on | atestado |
| $sqAssunto_search= | is | storeExpression | on | limites de area |
| ensure | do | type | on | id=sqTipoDocumento | with | $sqTipoDocumento_search |
| ensure | do | typeKeys | on | id=sqTipoDocumento | with | keyUp |
| ensure | do | waitForElementPresent | on | class=sel |
| ensure | do | click | on | class=sel |
| ensure | do | type | on | id=sqAssunto | with | $sqAssunto_search |
| ensure | do | typeKeys | on | id=sqAssunto | with | keyUp |
| ensure | do | waitForElementPresent | on | class=sel |
| ensure | do | click | on | class=sel |
| ensure | do | click | on | id=pesquisar |
| ensure | do | assertElementNotPresent | on | css=td.dataTables_empty |
| ensure | do | clickAndWait | on | !-//table[@id="table-grid"]/tbody/tr/td/a/span-! |
| ensure | do | select | on | id=inPrazoObrigatorio | with | label=Determinado |
| ensure | do | click | on | id=inPrazoObrigatorio |
| ensure | do | type | on | id=nuDiasPrazo | with | 10 |
| ensure | do | select | on | id=inDiasCorridos | with | label=Úteis |
| ensure | do | clickAndWait | on | css=button.btn.btn-primary |
| ensure | do | waitForText | on | css=div.alert.alert-success | with | × Alteração realizada com sucesso. |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
