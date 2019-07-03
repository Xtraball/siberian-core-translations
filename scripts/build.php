<?php

chdir(__DIR__ . "/..");

exec("npm version patch");

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

$tplData = <<<DATA
<?php

// Create the folder at install time if it doesn't exists.
\$folder = Core_Model_Directory::getBasePathTo("languages/#FOLDER#");
if (!is_dir(\$folder)) {
    mkdir(\$folder, 0777, true);
}
\$defaultFile = "{\$folder}/default.csv";
if (!is_file(\$defaultFile)) {
    file_put_contents(\$defaultFile, '"";""' . "\\n");
}
DATA;

$emptyPackage = [
    "name" => "EMPTY",
    "type" => "module",
    "version" => "1.0.0",
    "description" => "#HUMAN# translations",
    "dependencies" => [
        "system" => [
            "type" => "SAE",
            "version" => "4.17.0",
        ]
    ],
];

$projectPackage = json_decode(file_get_contents("./package.json"), true);


// Clean-up builds!
exec("rm -rf ./builds/*");

foreach ($locales as $folder => $human) {
    $files = new DirectoryIterator("{$folder}/");
    $lowerHuman = strtolower($human);

    // Create folder tree
    $name = "./builds/Locale{$human}";
    exec("mkdir -p {$name}/resources/db/data");
    exec("mkdir -p {$name}/resources/translations/{$folder}");
    exec("chmod -R 777 {$name}");

    $tpl = str_replace("#FOLDER#", $folder, $tplData);
    file_put_contents("./builds/Locale{$human}/resources/db/data/locale-{$lowerHuman}.php", $tpl);

    $package = $emptyPackage;
    $package["name"] = "Locale{$human}";
    $package["description"] = "{$human} translations.";
    $package["version"] = $projectPackage["version"];
    file_put_contents("./builds/Locale{$human}/package.json", json_encode($package, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    foreach ($files as $file) {
        if (!$file->isDot() &&
            $file->getExtension() === "po") {

            // With context
            $withContext = preg_match("/^c_/", $file->getFilename()) === 1;

            // Clean-up msgctxt
            if (!$withContext) {
                $newContent = [];
                foreach (file($file->getPathname()) as $line) {
                    if (preg_match("/^msgctxt /i", $line)) {
                        // Skip
                        $keep = false;
                    }
                    if (preg_match("/^msgid /i", $line)) {
                        // Skip
                        $keep = true;
                    }

                    if ($keep) {
                        $newContent[] = $line;
                    }
                }
            }
            file_put_contents($file->getPathname(), implode("", $newContent));

            // Copy
            exec("cp {$file->getPathname()} {$name}/resources/translations/{$file->getFilename()}");
        }
    }

    $version = $package["version"];
    exec("cd ./builds/Locale{$human}/; zip -r -9 -x@../../scripts/exclude.list ../Locale{$human}.zip ./ ; cd ../../");
}
