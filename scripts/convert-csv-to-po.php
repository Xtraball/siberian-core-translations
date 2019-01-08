<?php

chdir(__DIR__ . "/..");

$files = new DirectoryIterator("./.source");
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
                "msgid" => $key,
                "msgstr" => $value,
            ];

            $lineNumber++;
        }

        $newContent = "";
        foreach ($newValues as $newValue) {
            $newContent .= "msgid \"{$newValue["msgid"]}\"
msgstr \"{$newValue["msgstr"]}\"

";
        }

        $newPath = str_replace(".csv", ".po", $file->getPathname());
        $newPath = str_replace(".source", "base", $newPath);

        file_put_contents($newPath, $newContent);
        echo "Done" . PHP_EOL;
    }

}
