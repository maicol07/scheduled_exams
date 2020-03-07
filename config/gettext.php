<?php
$docroot = '../';
if (defined("DOCROOT")) {
    $docroot = DOCROOT;
}
require_once $docroot . "/dir.php";
require_once $docroot . "/config/config.php";
require_once DOCROOT . "/config/class_loader.php";

use Chirp\FileList;
use Gettext\Generator\JsonGenerator;
use Gettext\Generator\MoGenerator;
use Gettext\GettextTranslator;
use Gettext\Loader\PoLoader;
use Gettext\Scanner\JsScanner;
use Gettext\Scanner\PhpScanner;
use Gettext\Translations;

$locale_path = DOCROOT . "/locale/it_IT/LC_MESSAGES/";
$app_dirs = [
    // Root (no recursive)
    "" => ['recursive' => false],
    // App
    "app",
    // Classes
    "src"
];
if (get("regenerate_tr")) {
    if (file_exists($locale_path)) {
        foreach (glob($locale_path . "*") as $file) {
            if (!unlink($file)) {
                die("Regeneration failed!");
            };
        }
        rmdir($locale_path);
    }
}
if (!file_exists($locale_path)) {
    mkdir($locale_path, 0755, true);
    foreach ($app_dirs as $dir => $options) {
        $f = new FileList();
        if (is_array($options) and empty($options['recursive'])) {
            $path = DOCROOT . "/" . $dir;
        } else {
            $path = DOCROOT . '/' . $options;
            $f->recurse();
        }

        $php_files = array_column($f->scan($path, 0, 'ext', ['php']), 'pathname');
        $js_files = array_column($f->scan($path, 0, 'ext', ['js']), 'pathname');

        if (!empty($php_files)) {
            $php_scanner = new PhpScanner(Translations::create('messages'));
            $php_scanner->setDefaultDomain('messages');
            foreach ($php_files as $php_file) {
                $php_scanner->scanFile($php_file);
            }
        }
        if (!empty($js_files)) {
            $js_scanner = new JsScanner(Translations::create('messages'));
            $js_scanner->setDefaultDomain('messages');
            foreach ($js_files as $js_file) {
                $js_scanner->scanFile($js_file);
            }
        }


        if (!file_exists($locale_path . "messages.pot")) {
            file_put_contents($locale_path . 'messages.pot', ''); // File creation
        }

        $po = new PoLoader();
        $messages = $po->loadFile($locale_path . "messages.pot");

        if (!empty($php_scanner)) {
            foreach ($php_scanner->getTranslations() as $domain => $translations) {
                $messages = $messages->mergeWith($translations);
            }
        }
        if (!empty($js_scanner)) {
            foreach ($js_scanner->getTranslations() as $domain => $translations) {
                $messages = $messages->mergeWith($translations);
            }
        }
        foreach ($messages as $message) {
            if (!empty($message->getTranslation())) {
                $message->translate("");
            }
        }
        $messages->setDomain("messages");
        $messages->setLanguage("it_IT");
    }
}

$t = new GettextTranslator();
// Language detection
if (get("lang")) {
    $lang = get("lang"); // URL parameter
} elseif (isset($user) and $user->isAuthenticated()) {
    $lang = $user->getLanguage(); // User preferred language
} else {
    $lang = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : "en_US"; // Browser detection
}
$accepted_langs = array_map('basename', glob(DOCROOT . "/locale/*", GLOB_ONLYDIR));
$lang = (in_array($lang, $accepted_langs) and strlen($lang) == 5) ? $lang : 'en_US';

// MO and JSON Generation (if it doesn't exists)
$path = DOCROOT . "/locale/" . $lang;
if (!file_exists($path . '/LC_MESSAGES/messages.mo') or !file_exists($path . '/LC_MESSAGES/messages.json')) {
    // Import translations
    $loader = new PoLoader();
    if (file_exists($path . '/LC_MESSAGES/messages.pot')) {
        $ext = 'pot';
    } else {
        $ext = 'po';
    }
    $translations = $loader->loadFile($path . '/LC_MESSAGES/messages.' . $ext);

    // Export to a .mo file:
    $generator = new MoGenerator();
    if (!$generator->generateFile($translations, $path . '/LC_MESSAGES/messages.mo')) {
        trigger_error("Impossibile salvare il file!", E_USER_WARNING);
    };

    // Set translation equal to original for languages with a pot file
    if ($ext == "pot") {
        foreach ($translations as $translation) {
            $translation->translate($translation->getOriginal());
        }
    }

    // Export to a .json file
    $json = new JsonGenerator();
    if (!$json->generateFile($translations, $path . '/LC_MESSAGES/messages.json')) {
        trigger_error("Impossibile salvare il file!", E_USER_WARNING);
    };
}

// Set language and domain
$t->setLanguage($lang);
$t->loadDomain("messages", DOCROOT . '/locale');
Gettext\TranslatorFunctions::register($t);
$langs = [
    'it_IT' => [
        'text' => __("Italiano"),
        'flag' => 'it'
    ],
    'en_US' => [
        'text' => __("Inglese (Stati Uniti)"),
        'flag' => 'us'
    ]
];