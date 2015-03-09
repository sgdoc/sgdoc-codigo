<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class CadastrarSequencialDeUnidadeOrganizacionalSuite1PesquisarNumeroSequencialCasoTeste001PesquisarSequencialUnidadeOrganizacional extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.CadastrarSequencialDeUnidadeOrganizacional.Suite1PesquisarNumeroSequencial");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "CasoTeste001PesquisarSequencialUnidadeOrganizacional");
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
| ensure | do | clickAndWait | on | link=Sequencial de Unidade |
| $nmPessoa= | is | storeExpression | on | Área de Proteção Ambiental Anhatomirim |
| ensure | do | type | on | id=noPessoa | with | $nmPessoa |
| ensure | do | typeKeys | on | id=noPessoa | with | keyUp |
| ensure | do | waitForElementNotPresent | on | class=sel |
| ensure | do | click | on | class=sel |
| ensure | do | click | on | css=button.btn.btn-primary |
| do | selectWindow | on | null |
| ensure | do | waitForText | on | css=th.header.headerSortDown | with | Ano |
| check | is | assertText | on | !-//table[@id="table-grid"]/thead/tr/th[2]-! | Tipo de Documento |
| check | is | assertText | on | !-//table[@id="table-grid"]/thead/tr/th[3]-! | Sequencial |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
