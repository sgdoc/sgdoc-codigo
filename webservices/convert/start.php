<?php

ob_end_clean();
header("Connection: close");
ignore_user_abort(); // optional
ob_start();
$size = ob_get_length();
header("Content-Length: $size");
ob_end_flush(); // Strange behaviour, will not work
flush();            // Unless both are called !
session_write_close(); // Added a line suggested in the comment

include __DIR__ . '/app/App.php';
include __DIR__ . '/../migracao/app/AppMigrate.php';

try {
    App::factory()->lock()->run();
} catch (Exception $e) {
    App::factory()->error($e->getMessage());
    App::factory()->unlock();
}