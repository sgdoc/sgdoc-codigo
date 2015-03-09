<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class ManterModeloDeCarimbo extends PHPUnit_Extensions_SeleniumTestCase {

    /**
     * @return void
     */
    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    /**
     * @return void
     */
    public function testMyTestCase() {
        $this->open("/ProjectSgdoc");
        $this->click("link=Add");
        $this->click("link=Static page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "ManterModeloDeCarimbo");
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}