<?php

include 'app/App.php';

if (App::factory()->isLocked()) {
    App::factory()->unlock();
}

