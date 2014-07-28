<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class ManterPessoaFisicaSgdoceSuite1CadastrarPessoaFisica1DadosBasicos extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.ManterPessoaFisicaSgdoce.Suite1CadastrarPessoaFisica");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "1DadosBasicos");
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
| $nmTipoPessoa= | is | storeExpression | on | Pessoa Física |
| $nmPessoaFisica= | is | storeExpression | on | Zezinho Matuto sem CPF |
| $responsavel= | is | storeExpression | on | xxxxxxx |
| $login= | is | storeExpression | on | 737.623.851-49 |
| $senha= | is | storeExpression | on | 0123456789 |
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
| ensure | do | select | on | id=sqTipoPessoa | with | label=$nmTipoPessoa |
| ensure | do | click | on | css=div.control-group.dvGeralNomePF &gt; div.controls &gt; div.input-append.dropdown &gt; button.btn.right |
| ensure | do | click | on | xpath=(//a[contains(text(),"Cadastrar")])[3] |
| do | selectPopUp | on |  |
| ensure | do | type | on | id=noPessoaFisica | with | $nmPessoaFisica |
| ensure | do | click | on | link=Próxima |
| ensure | do | type | on | id=sqResponsavel | with | $responsavel |
| ensure | do | type | on | id=nuCpfResponsavel | with | $login |
| ensure | do | type | on | id=txSenhaResponsavel | with | $senha |
| ensure | do | type | on | id=txJustificativa | with | justificativa para cadastro sem cpf |
| ensure | do | click | on | id=btn-concluir-justificativa |
| ensure | do | click | on | id=btnConcluir |
| check | is | verifyText | on | //div[2]/div[2] | × Deve-se informar pelo menos um documento. |
| do | close | on |  |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
