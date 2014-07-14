<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class ManterModeloDeCarimboSuite2IncluirModeloCarimboCasoTeste003IncluirModeloCarimboFormatoInvalido extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.ManterModeloDeCarimbo.Suite2IncluirModeloCarimbo");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "CasoTeste003IncluirModeloCarimboFormatoInvalido");
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
| ensure | do | clickAndWait | on | link=Modelo Carimbo |
| ensure | do | clickAndWait | on | id=novo |
| $noCarimbo_new= | is | storeExpression | on | teste desc carimbo |
| $path_to_attach= | is | storeExpression | on | /root/Xebium/FitNesseRoot/files/ProjectSgdoc/anexos |
| ensure | do | type | on | id=noCarimbo | with | $noCarimbo_new |
| ensure | do | type | on | name=deCaminhoArquivo | with | $path_to_attach\jpg\colors_900x563.jpg |
| ensure | do | select | on | id=sqTipoArtefato | with | label=Despacho |
| ensure | do | clickAndWait | on | css=button.btn.btn-primary |
| check | is | verifyText | on | css=div.alert.alert-error | × Extensão do arquivo inválida. A extensão permitida é .png |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
