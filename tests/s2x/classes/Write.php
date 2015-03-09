<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class Write {

    /**
     * @return Write
     */
    public static function factory() {
        return new self();
    }

    /**
     * @return void
     */
    private function __construct() {
        
    }

    /**
     * @param string $file
     * @param string $content
     * @return boolean
     */
    public function write($file, $content) {

        $pathSavePHPUnit = Bootstrap::factory()->config('config.output.phpunit');
        $pathReadFiles   = Bootstrap::factory()->config('config.input.selenium');
        $namespaces = explode('/', str_replace(array('_de_', '_', $pathReadFiles.'/', '.selenium'), array('De', '', '', ''), $file));

        $namespaces[1] = "Suite{$namespaces[1]}";

        $tmplStaticPageTest = file_get_contents('templates/TemplateStaticPageTest.php');
        $tmplSuitePageTest = file_get_contents('templates/TemplateSuitePageTest.php');
        $tmplTestPageTest = file_get_contents('templates/TemplateTestPageTest.php');

        //write function
        $tmplStaticPageTest = str_replace(array('CLASS_NAME', 'FUNCTION_NAME'), array($namespaces[0], $namespaces[0]), $tmplStaticPageTest);
        $s1 = @file_put_contents("{$pathSavePHPUnit}/TemplateStaticPage{$namespaces[0]}Test.php", $tmplStaticPageTest);

        //write suite
        $tmplSuitePageTest = str_replace(array('CLASS_NAME', 'FUNCTION_NAME', 'SUITE_NAME'), array($namespaces[0] . $namespaces[1], $namespaces[0], $namespaces[1]), $tmplSuitePageTest);
        $s2 = @file_put_contents("{$pathSavePHPUnit}/TemplateSuitePage{$namespaces[1]}Test.php", $tmplSuitePageTest);

        //write test
        $tmplTestPageTest = str_replace(array('CONTENT_XEBIUM', 'CLASS_NAME', 'FUNCTION_NAME', 'SUITE_NAME', 'TEST_NAME'), array($content, $namespaces[0] . $namespaces[1] . $namespaces[2], $namespaces[0], "{$namespaces[1]}", $namespaces[2]), $tmplTestPageTest);
        $s3 = @file_put_contents("{$pathSavePHPUnit}/TemplateTestPage{$namespaces[2]}Test.php", $tmplTestPageTest);

        if (!$s1) {
            throw new Exception('Ocorreu um erro na criação da classe TemplateStaticPage!');
        }

        if (!$s2) {
            throw new Exception('Ocorreu um erro na criação da classe TemplateSuitePage!');
        }

        if (!$s3) {
            throw new Exception('Ocorreu um erro na criação da classe TemplateTestPage!');
        }

        return true;
    }

}
