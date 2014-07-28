<?php

require 'classes/Bootstrap.php';
require 'vendor/domhtmlparse/simple_html_dom.php';

$path = $_REQUEST['path'];
$html = file_get_html($path);
$name = '';
$command = "commands[%d] = new Command('%s', '%s', '%s');\n";
$commands = '';
$url = $html->find('link', 0)->href;
$key = 0;

foreach ($html->find('tr') as $i => $trs) {
    if (!$key) {
        $name = $trs->find('td', 0)->innertext;
        //add test login ...
        //require commands ...
        $commands .= sprintf($command, $key, 'open', '/', '');
        $commands .= sprintf($command, ++$key, 'type', 'id=USUARIO', Bootstrap::factory()->config('config.app.auth.user'));
        $commands .= sprintf($command, ++$key, 'type', 'id=SENHA', Bootstrap::factory()->config('config.app.auth.pass'));
        $commands .= sprintf($command, ++$key, 'clickAndWait', 'css=img.botao48', '');
        $commands .= sprintf($command, ++$key, 'assertText', 'css=span.style25', 'Olá, Administrador - Setor de Gerência da Informação.');

        continue;
    }
    $commands .= sprintf(
            $command, ++$key, $trs->find('td', 0)->innertext, str_replace(array('\\', "'"), array('\\\\', '"'), $trs->find('td', 1)->innertext), str_replace(array('\\', "'"), array('\\\\', '"'), $trs->find('td', 2)->innertext)
    );
}

$script = "
                var transport  = {};
                
                var testCase   = new TestCase('%s');
                    testCase.setBaseURL('%s');
                                var commands = [];  
                                                   %s
                    testCase.setCommands(commands);
                    
              transport.xebium = format(testCase, '%s');
              transport.url    = '%s';
              transport.name   = '%s';
              transport.path   = '%s';
          ";

print sprintf($script, $name, $url, $commands, $name, $url, $name, $path);
