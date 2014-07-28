<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class ManterMensagemSuite4AtivarDesativarMensagemCasoTeste010AtivarDesativarMensagemDesativar extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.ManterMensagem.Suite4AtivarDesativarMensagem");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "CasoTeste010AtivarDesativarMensagemDesativar");
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
| ensure | do | clickAndWait | on | link=Mensagem |
| $nmTipoDocumento= | is | storeExpression | on | ALVARA |
| ensure | do | select | on | id=sqTipoDocumento | with | label=$nmTipoDocumento |
| ensure | do | click | on | css=button.btn.btn-primary |
| ensure | do | waitForText | on | !-//table[@id="table-grid"]/tbody/tr/td[5]/a[3]/span-! | with | Desativar |
| ensure | do | click | on | !-//table[@id="table-grid"]/tbody/tr/td[5]/a[3]/span-! |
| ensure | do | waitForText | on | css=div.modal-body | with | exact:Tem certeza que deseja desativar a mensagem? |
| ensure | do | click | on | link=Sim |
| ensure | do | waitForText | on | css=p | with | Mensagem desativada com Sucesso |
| ensure | do | click | on | link=Fechar |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
