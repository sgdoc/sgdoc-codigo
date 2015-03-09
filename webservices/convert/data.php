<?php

include 'app/App.php';

$response = array(
    array(
        'label' => 'Pendentes',
        'data' => App::factory()->countPendents()
    ),
    array(
        'label' => 'Convertidos',
        'data' => App::factory()->countOk()
    ),
    array(
        'locked' => App::factory()->isLocked()
    ),
    array(
        'errors' => App::factory()->log()
    )
);

print json_encode($response);
