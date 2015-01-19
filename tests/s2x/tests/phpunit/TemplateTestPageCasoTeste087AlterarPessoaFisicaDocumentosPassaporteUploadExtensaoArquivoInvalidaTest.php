<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class ManterPessoaFisicaSgdoceSuite3AlterarPessoaFisicaCasoTeste087AlterarPessoaFisicaDocumentosPassaporteUploadExtensaoArquivoInvalida extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.ManterPessoaFisicaSgdoce.Suite3AlterarPessoaFisica");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "CasoTeste087AlterarPessoaFisicaDocumentosPassaporteUploadExtensaoArquivoInvalida");
        $this->type("id=pageContent", "!contents -R2 -g -p -f -h\n" . '| script | selenium driver fixture |
| start browser | firefox | on url | https://projeto.sgdoc.icmbio.gov.br/ |
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
| ensure | do | type | on | id=sqPessoaDestinatarioPf | with | $nmPessoaFisica |
| ensure | do | typeKeys | on | id=sqPessoaDestinatarioPf | with | keyUp |
| ensure | do | waitForElementPresent | on | class=sel |
| ensure | do | click | on | class=sel |
| do | fireEvent | on | id=sqPessoaDestinatarioPf | with | blur |
| ensure | do | waitForVisible | on | css=h4 | with | Dados do Corporativo |
| ensure | do | click | on | css=div.control-group.dvGeralNomePF &gt; div.controls &gt; div.input-append.dropdown &gt; button.btn.right |
| ensure | do | click | on | id=alterarPF |
| do | selectPopUp | on |  |
| ensure | do | waitForText | on | css=h1 | with | Alterar Pessoa Física |
| ensure | do | click | on | link=Documentos |
| ensure | do | select | on | name=table-documento_length | with | label=100 |
| ensure | do | click | on | !-//*[@id="table-documento"]/tbody/tr/td[contains(.,"Passaporte")]/../td[4]/div/a[@title="Alterar"]-! | with | Passaporte |
| ensure | do | waitForText | on | css=h3.title-main | with | Alterar Documento |
| ensure | do | type | on | id=txImagem | with | $path_to_attach\jpg\colors_900x563.jpg |
| check | is | verifyText | on | css=span.help-block.show | Extensão do arquivo inválida. Selecione arquivos no formato .PNG. |
| ensure | do | click | on | xpath=(//a[contains(text(),"Fechar")])[3] |
| do | close | on |  |
| stop browser |
');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
