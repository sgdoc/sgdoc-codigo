<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class ManterTipoDePrioridadeSuite3AlterarTipoPrioridadeCasoTeste005AlterarTipoPrioridade extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.ManterTipoDePrioridade.Suite3AlterarTipoPrioridade");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "CasoTeste005AlterarTipoPrioridade");
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
| $txTipoPrioridade_search= | is | storeExpression | on | Tipo Prioridade Selenium |
| $sqPrioridade_search= | is | storeExpression | on | 3 |
| $sqPrioridade_alter= | is | storeExpression | on | 2 |
| ensure | do | type | on | id=txTipoPrioridade | with | $txTipoPrioridade_search |
| ensure | do | type | on | id=sqPrioridade | with | $sqPrioridade_search |
| ensure | do | click | on | id=pesquisar |
| ensure | do | waitForText | on | !-//*[@id="table-grid"]/tbody/tr/td[contains(.,"$txTipoPrioridade_search")]-! | with | $txTipoPrioridade_search |
| ensure | do | clickAndWait | on | !-//*[@id="table-grid"]/tbody/tr/td[contains(.,"$txTipoPrioridade_search")]/../td[3]/a[@title="Editar"]-! | with | $txTipoPrioridade_search |
| ensure | do | type | on | id=sqPrioridade | with | $sqPrioridade_alter |
| ensure | do | clickAndWait | on | css=button.btn.btn-primary |
| ensure | do | waitForText | on | css=div.alert.alert-success | with | × Alteração realizada com sucesso. |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
