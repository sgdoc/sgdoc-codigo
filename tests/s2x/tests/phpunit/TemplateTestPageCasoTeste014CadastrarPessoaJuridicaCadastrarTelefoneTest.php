<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class ManterPessoaJuridicaSgdoceSuite2CadastrarPessoaJuridicaCasoTeste014CadastrarPessoaJuridicaCadastrarTelefone extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.ManterPessoaJuridicaSgdoce.Suite2CadastrarPessoaJuridica");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "CasoTeste014CadastrarPessoaJuridicaCadastrarTelefone");
        $this->type("id=pageContent", "!contents -R2 -g -p -f -h\n" . '| script | selenium driver fixture |
| start browser | firefox | on url | https://tcti.sgdoce.sisicmbio.icmbio.gov.br/ |
| save screenshot after | every step | in folder | http://files/ProjectSgdoc/testResults/screenshots/${PAGE_NAME}_on_action |
| set step delay to | slow |
| do | open | on | / |
| ensure | do | type | on | id=nuCpf | with | 737.623.851-49 |
| ensure | do | type | on | id=senha | with | 0123456789 |
| ensure | do | clickAndWait | on | css=button.btn.btn-primary |
| ensure | do | clickAndWait | on | link=Acessar » |
| do | open | on | /artefato/minuta-eletronica/index |
| $nmTipoDocumento= | is | storeExpression | on | CARTA |
| $nmAssunto= | is | storeExpression | on | ASSEMBLEIA GERAL |
| $nmEstado= | is | storeExpression | on | Distrito Federal |
| $nmMunicipio= | is | storeExpression | on | Brasília |
| $nmTipoPessoa= | is | storeExpression | on | Pessoa Jurídica |
| $nmPessoaJuridica= | is | storeExpression | on | João Matuto Ltda |
| $nmTipoTelefone= | is | storeExpression | on | Residencial |
| $nuTelefone= | is | storeExpression | on | 3012-1888 |
| ensure | do | type | on | id=sqTipoDocumento | with | $nmTipoDocumento |
| ensure | do | typeKeys | on | id=sqTipoDocumento | with | keyUp |
| ensure | do | waitForElementPresent | on | class=sel |
| ensure | do | click | on | class=sel |
| ensure | do | type | on | id=sqAssunto | with | $nmAssunto |
| ensure | do | typeKeys | on | id=sqAssunto | with | keyUp |
| ensure | do | waitForElementPresent | on | class=sel |
| ensure | do | click | on | class=sel |
| ensure | do | clickAndWait | on | css=button.btn.btn-primary |
| ensure | do | select | on | id=sqEstado | with | label=$nmEstado |
| ensure | do | type | on | id=sqMunicipio | with | $nmMunicipio |
| ensure | do | typeKeys | on | id=sqMunicipio | with | keyUp |
| ensure | do | waitForElementPresent | on | class=sel |
| ensure | do | click | on | class=sel |
| ensure | do | click | on | link=Destinatário |
| ensure | do | click | on | css=i.icon-plus |
| ensure | do | waitForElementPresent | on | css=h3 | with | Adicionar Destinatário |
| ensure | do | select | on | id=sqTipoPessoa | with | label=Selecione |
| ensure | do | select | on | id=sqTipoPessoa | with | label=$nmTipoPessoa |
| ensure | do | type | on | id=sqPessoaDestinatario | with | $nmPessoaJuridica |
| ensure | do | typeKeys | on | id=sqPessoaDestinatario | with | keyUp |
| ensure | do | waitForElementPresent | on | class=sel |
| ensure | do | click | on | class=sel |
| do | fireEvent | on | id=sqPessoaDestinatario | with | blur |
| ensure | do | click | on | !-xpath=(//button[@type="button"])[4]-! |
| ensure | do | click | on | id=alterarPJ |
| ensure | do | click | on | id=btnLimpar |
| do | selectPopUp | on |  |
| ensure | do | waitForText | on | css=h1 | with | Alterar Pessoa Jurídica |
| ensure | do | click | on | link=Contatos |
| ensure | do | click | on | id=btn-add-telefone |
| ensure | do | select | on | id=sqTipoTelefone | with | label=$nmTipoTelefone |
| ensure | do | type | on | id=nuDdd | with | 061 |
| ensure | do | type | on | id=nuTelefone | with | $nuTelefone |
| ensure | do | click | on | link=Concluir |
| ensure | do | waitForText | on | //body/div[5]/div[2]/div | with | Operação realizada com sucesso |
| ensure | do | click | on | xpath=(//a[contains(text(),"Fechar")])[5] |
| check | is | assertText | on | !-//*[@id="table-telefone"]/tbody/tr/td[contains(.,"$nmTipoTelefone")]/../td[3]-! | $nuTelefone |
| do | close | on |  |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
