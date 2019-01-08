<?php

chdir(__DIR__ . "/..");

$codes = [
    "base",
    "ca",
    "cs",
    "de",
    "el",
    "es",
    "fr",
    "it",
    "nl",
    "pt_BR",
    "ru",
    "tr",
    "zh",
];

foreach ($codes as $code) {
    $files = new DirectoryIterator("./{$code}");
    foreach ($files as $file) {
        if (!$file->isDot() && $file->getExtension() === "csv") {
            echo "File: " . $file->getFilename() . PHP_EOL;
            $newValues = [];
            $lineNumber = 1;
            $resource = fopen($file->getPathname(), "r");
            while ($content = fgetcsv($resource, 1024, ";", '"')) {
                $key = str_replace('\"', '"', $content[0]);
                if (isset($content[1])) {
                    $value = str_replace('\"', '"', $content[1]);
                } else {
                    $value = $key;
                }

                $newValues[] = [
                    "ref" => "{$code}/{$file->getFilename()}:{$lineNumber}",
                    "msgid" => $key,
                    "msgstr" => $value,
                ];

                $lineNumber++;
            }

            $newContent = "";
            foreach ($newValues as $newValue) {
                $newContent .= "#: {$newValue["ref"]} 
msgid \"{$newValue["msgid"]}\"
msgstr \"{$newValue["msgstr"]}\"

";
            }

            file_put_contents(str_replace(".csv", ".po", $file->getPathname()), $newContent);
            echo "Done" . PHP_EOL;
        }

    }
}