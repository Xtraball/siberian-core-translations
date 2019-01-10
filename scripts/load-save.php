<?php

/**
 * Load & Save po files to fix encoding/escaping issues!
 */

require __DIR__ . "/../vendor/autoload.php";

use Gettext\Translations;

chdir(__DIR__ . "/..");

$locales = [
    "ca" => "Catalan",
    "cs" => "Czech",
    "de" => "German",
    "el" => "Greek",
    "es" => "Spanish",
    "fr" => "French",
    "it" => "Italian",
    "nl" => "Dutch",
    "pt_BR" => "PortugueseBrazil",
    "ru" => "Russian",
    "tr" => "Turkish",
    "zh_Hans" => "ChineseSimplified",
];

foreach ($locales as $code => $human) {
    $files = new DirectoryIterator("./{$code}");
    foreach ($files as $file) {
        if ($file->getExtension() === "po") {
            $translations = Translations::fromPoFile($file->getPathname());
            $translations->toPoFile($file->getPathname());
        }
    }
}