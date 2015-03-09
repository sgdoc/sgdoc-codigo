<?php

include 'app/AppMigrate.php';

{
    $output = '';

    try {
        App::factory($_REQUEST['digital'], __BASE_PATH__ . '/cache/import')->run(function($response) use (&$output) {
            $output = $response;
        });
    } catch (Exception $e) {
        $output = array('success' => false, 'error' => $e->getMessage());
    }

    print json_encode($output);
}



