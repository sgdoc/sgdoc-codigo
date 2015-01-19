<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class CLASS_NAME extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort((int) PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT);
    }

    public function testMyTestCase() {
        $this->open("/ProjectSgdoc.FUNCTION_NAME.SUITE_NAME");
        $this->click("link=Add");
        $this->click("link=Test page");
        $this->waitForPageToLoad("30000");
        $this->type("id=pagename", "TEST_NAME");
        $this->type("id=pageContent", "!contents -R2 -g -p -f -h\n" . 'CONTENT_XEBIUM');
        $this->click("name=save");
        $this->waitForPageToLoad("30000");
    }

}
