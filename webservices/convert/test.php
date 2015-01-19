<?php

include 'app/App.php';

//App::factory()->lock();
//App::factory()->unlock();
//$r = App::factory()->isLocked();

//$test = App::factory()->retrieveDocuments();
  App::factory()->deleteImages('0000001');
var_dump($r, $test);
