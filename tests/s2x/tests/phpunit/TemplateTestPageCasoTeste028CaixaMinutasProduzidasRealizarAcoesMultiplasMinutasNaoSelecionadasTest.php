<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class VisualizarCaixaDeMinutasSuite4CaixasMinutasProduzidasCasoTeste028CaixaMinutasProduzidasRealizarAcoesMultiplasMinutasNaoSelecionadas extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.VisualizarCaixaDeMinutas.Suite4CaixasMinutasProduzidas");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "CasoTeste028CaixaMinutasProduzidasRealizarAcoesMultiplasMinutasNaoSelecionadas");
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
| ensure | do | click | on | link=Caixa de Minutas |
| ensure | do | clickAndWait | on | xpath=(//a[contains(text(),"Caixa de Minutas")])[2] |
| ensure | do | clickAndWait | on | link=Produzidas |
| ensure | do | waitForElementNotPresent | on | css=td.dataTables_empty |
| ensure | do | waitForElementPresent | on | name=sqArtefato[] |
| ensure | do | click | on | !-//form[@id="form-minuta-acoes-multiplas"]/div/div/button-! |
| check | is | assertText | on | //div/div[2]/div | × Não há minutas selecionadas. Selecione ao menos uma para realizar a ação. |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
