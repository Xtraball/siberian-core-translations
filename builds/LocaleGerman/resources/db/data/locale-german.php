<?php

// Create the folder at install time if it doesn't exists.
$folder = Core_Model_Directory::getBasePathTo("languages/de");
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}
$defaultFile = "{$folder}/default.csv";
if (!is_file($defaultFile)) {
    file_put_contents($defaultFile, '"";""' . "\n");
}