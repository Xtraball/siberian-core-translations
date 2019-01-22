<?php

// https://translate.siberiancms.com/download/siberian/default-group2/fr/

$endpoint = "https://translate.siberiancms.com/download/siberian/#COMPONENT#/#LANG#/?format=po";

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

$components = [
    "acl",
    "admin",
    "api",
    "application",
    "backoffice",
    "booking",
    "catalog",
    "cms",
    "codescan",
    "comment",
    "contact",
    "customer",
    "default-group1",
    "default-group2",
    "default",
    "event",
    "features",
    "folder",
    "form",
    "installer",
    "loyaltycard",
    "maps",
    "mcommerce",
    "media",
    "message",
    "mobile",
    "padlock",
    "payment",
    "preview",
    "promotion",
    "push",
    "radio",
    "rss",
    "sales",
    "social",
    "socialgaming",
    "sourcecode",
    "subscription",
    "system",
    "tax",
    "template",
    "tip",
    "topic",
    "translation",
    "twitter",
    "weather",
    "weblink",
    "whitelabel",
    "wordpress",
];

$total = count($locales) * count($components);
$counter = 0;

foreach ($locales as $folder => $human) {
    echo "\nLocale: {$human} ({$folder}) \n";
    echo "==================================================\n";
    foreach ($components as $component) {
        $percentage = round($counter / $total * 100, 2);
        echo "Component: {$component} {$percentage}% \n";
        $url = str_replace("#COMPONENT#", $component, $endpoint);
        $url = str_replace("#LANG#", $folder, $url);

        $content = file_get_contents($url);

        file_put_contents("./{$folder}/{$component}.po", $content);
        $counter++;
    }
}

