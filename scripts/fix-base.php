<?php

chdir(__DIR__ . "/..");

$files = new DirectoryIterator("./base");
foreach ($files as $file) {
    if ($file->getExtension() === "po") {
        $newContent = [];
        foreach(file($file->getPathname()) as $line) {
            if (preg_match("/^msgid/i", $line)) {
                //
                $newContent[] = str_replace("msgid", "msgctxt", $line);
            }
            $newContent[] = $line;
        }
        file_put_contents($file->getPathname(), implode("\n", $newContent));
    }
}
