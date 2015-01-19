<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class ManterPessoaFisicaSgdoceSuite1CadastrarPessoaFisica4CadastroDocumentosRG extends PHPUnit_Extensions_SeleniumTestCase {

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
        $this->type("id=pagename", "4CadastroDocumentosRG");
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
| $cpf= | is | storeExpression | on | 000.000.001-91 |
| $nmPessoaFisica= | is | storeExpression | on | Zezinho Matuto |
| $path_to_attach= | is | storeExpression | on | /root/Xebium/FitNesseRoot/files/ProjectSgdoc/anexos |
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
| ensure | do | type | on | id=nuCpf | with | $cpf |
| ensure | do | focus | on | id=noPessoaFisica |
| ensure | do | waitForText | on | css=#modalCpf &gt; div.modal-body &gt; div.row-fluid &gt; form.form-horizontal &gt; fieldset &gt; p | with | exact:O CPF informado já existe. Deseja alterar as informações? |
| ensure | do | click | on | link=Sim |
| ensure | do | click | on | link=Documentos |
| ensure | do | click | on | id=btn-adicionar-documento |
| ensure | do | select | on | id=sqTipoDocumento | with | label=Identidade |
| ensure | do | type | on | !-//form[@id="form-documento-modal"]/fieldset/div[21]/label/following-sibling::div/input[3]-! | with | 1111111 |
| ensure | do | type | on | !-//form[@id="form-documento-modal"]/fieldset/div[22]/label/following-sibling::div/input[3]-! | with | SSP/DF |
| ensure | do | select | on | !-//form[@id="form-documento-modal"]/fieldset/div[23]/label/following-sibling::div/select-! | with | label=Distrito Federal |
| ensure | do | type | on | !-//form[@id="form-documento-modal"]/fieldset/div[24]/label/following-sibling::div/div/input-! | with | 01/01/2001 |
| ensure | do | type | on | id=txImagem | with | $path_to_attach\png\larger_100MB.png |
| check | is | verifyText | on | css=span.help-block.show | O tamanho do arquivo é superior ao permitido. O tamanho máximo permitido é 25Mb. |
| ensure | do | click | on | xpath=(//a[contains(text(),"Fechar")])[3] |
| do | close | on |  |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
