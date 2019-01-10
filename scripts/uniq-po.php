<?php

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
            exec("msguniq -i {$file->getPathname()} -o {$file->getPathname()}");
        }
    }
}