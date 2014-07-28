<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class ManterTipoDeDocumentoSuite3AlterarTipoDocumentoCasoTeste009AterarTipoDocumentoTipoDocumentoJaCadastrado extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.ManterTipoDeDocumento.Suite3AlterarTipoDocumento");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "CasoTeste009AterarTipoDocumentoTipoDocumentoJaCadastrado");
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
| ensure | do | clickAndWait | on | link=Tipo de Documento |
| $nmTipoDocumento= | is | storeExpression | on | MEMORANDO SELENIUM |
| $nmTipoDocumento_alter= | is | storeExpression | on | ALVARA |
| ensure | do | type | on | id=noTipoDocumento | with | $nmTipoDocumento |
| ensure | do | click | on | id=pesquisar |
| ensure | do | clickAndWait | on | css=span.icon-pencil |
| ensure | do | type | on | id=noTipoDocumento | with | $nmTipoDocumento_alter |
| ensure | do | clickAndWait | on | css=button.btn.btn-primary |
| check | is | assertText | on | css=div.alert.alert-success | × Tipo de Documento já cadastrado. |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
