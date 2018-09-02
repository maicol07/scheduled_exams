<?php

//
// test if gettext extension is installed with php
//

if (!function_exists("gettext")) {
    echo "gettext is not installed\n";
} else {
    echo "gettext is supported\n";
}

$locale = 'en_US';
//$locale = 'fr_CH';
$domain = 'messages';
$codeset = 'UTF-8';
$directory = __DIR__ . '/locale';

// Activate the locale settings
putenv('LC_ALL=' . $locale);
setlocale(LC_ALL, $locale);

// Debugging output
$file = sprintf('%s/%s/LC_MESSAGES/%s_%s.mo', $directory, $locale, $domain, $locale);
echo $file . "\n";

// Generate new text domain
$textDomain = sprintf('%s_%s', $domain, $locale);

// Set base directory for all locales
bindtextdomain($textDomain, $directory);

// Set domain codeset (optional)
bind_textdomain_codeset($textDomain, $codeset);

// File: ./locale/de_DE/LC_MESSAGES/messages_de_DE.mo
textdomain($textDomain);

// Test a translation
echo _('Buongiorno'); // Ja