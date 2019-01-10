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

$tplHeader = <<<HEADER
msgid ""
msgstr ""
"Last-Translator: Xtraball <dev@xtraball.com>\\n"
"Language: #CODE#\\n"
"MIME-Version: 1.0\\n"
"Content-Type: text/plain; charset=UTF-8\\n"
"Content-Transfer-Encoding: 8bit\\n"
"Plural-Forms: nplurals=2; plural=n > 1;\\n"
"X-Generator: Weblate 3.3\\n"
\n
HEADER;

foreach ($locales as $code => $human) {
    $files = new DirectoryIterator("./{$code}");
    foreach ($files as $file) {
        if ($file->getExtension() === "po") {
            $content = file_get_contents($file->getPathname());

            if (preg_match("/MIME-Version/m", $content) === 0) {
                $tpl = str_replace("#CODE#", $code, $tplHeader);

                $content = $tpl . $content;
                file_put_contents($file->getPathname(), $content);
            }
        }
    }
}