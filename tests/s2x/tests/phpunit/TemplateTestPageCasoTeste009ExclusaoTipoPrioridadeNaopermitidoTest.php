<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class ManterTipoDePrioridadeSuite4ExcluirTipoPrioridadeCasoTeste009ExclusaoTipoPrioridadeNaopermitido extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.ManterTipoDePrioridade.Suite4ExcluirTipoPrioridade");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "CasoTeste009ExclusaoTipoPrioridadeNaopermitido");
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
| ensure | do | clickAndWait | on | link=Tipo de Prioridade |
| $txTipoPrioridade_search= | is | storeExpression | on | Teste CTIS |
| $sqPrioridade_search= | is | storeExpression | on | 1 |
| ensure | do | type | on | id=txTipoPrioridade | with | $txTipoPrioridade_search |
| ensure | do | type | on | id=sqPrioridade | with | $sqPrioridade_search |
| ensure | do | click | on | id=pesquisar |
| ensure | do | waitForText | on | !-//*[@id="table-grid"]/tbody/tr/td[contains(.,"$txTipoPrioridade_search")]-! | with | $txTipoPrioridade_search |
| ensure | do | click | on | !-//*[@id="table-grid"]/tbody/tr/td[contains(.,"$txTipoPrioridade_search")]/../td[3]/a[@title="Excluir"]-! | with | $txTipoPrioridade_search |
| ensure | do | clickAndWait | on | link=Sim |
| ensure | do | waitForText | on | css=div.alert | with | × Exclusão não permitida. Tipo de prioridade já utilizado. |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
