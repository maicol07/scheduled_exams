<?php
if (defined("DOCROOT")) {
    require_once DOCROOT . "/config/class_loader.php";
} else {
    require_once "class_loader.php";
}

use Chirp\FileList;
use Gettext\Generator\JsonGenerator;
use Gettext\Generator\PoGenerator;
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
    /*$pr_folder = null;
    foreach (explode("/", $locale_path) as $folder) {
        empty($pr_folder) ? mkdir($folder, 0755) : (file_exists($pr_folder)) ?: mkdir($pr_folder . $folder, 0755);
        $pr_folder = $folder . "/";
    }*/
    foreach ($app_dirs as $dir => $options) {
        $f = new FileList();
        if (is_array($options) and empty($options['recursive'])) {
            $path = DOCROOT . "/" . $dir;
        } else {
            $path = DOCROOT . '/' . $options;
            $f->recurse();
        }

        $php_files = array_column($f->scan($path, 'ext', ['php']), 'pathname');
        $js_files = array_column($f->scan($path, 'ext', ['js']), 'pathname');

        if (!empty($php_files)) {
            $php_scanner = new PhpScanner(Translations::create('messages'));
            $php_scanner->setDefaultDomain('messages');
            foreach ($php_files as $php_file) {
                $php_scanner->scanFile($php_file);
            }
        }
        if (!empty($js_files)) {
            $js_scanner = new JsScanner(Translations::create('messages'));
            $php_scanner->setDefaultDomain('messages');
            foreach ($js_files as $js_file) {
                $js_scanner->scanFile($js_file);
            }
        }
        // Debug info
        /*if (!isset($php_scanner) or !isset($js_scanner)) {
            echo "<pre>";
            var_dump($options);
            var_dump($dir);
            var_dump($files);
            //var_dump($php_scanner);
            var_dump($php_scanner->getTranslations());
            echo "<pre>";
            exit;
        }*/
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
        $messages->setDomain("messages");
        $messages->setLanguage("it_IT");
        $generator = new PoGenerator();
        $generator->generateFile($messages, $locale_path . "messages.pot");
        foreach ($messages as $message) {
            $message->translate($message->getOriginal());
        }
        $generator->generateFile($messages, $locale_path . "messages.gen.po");
    }
}

if (!file_exists($locale_path . "messages.json") or get("regenerate_tr") or get("regenerate_json")) {
    /* Export translations in JSON for JS */
    $f = new FileList();
    $f->recurse();
    $f->add_filter('ext', ['po']);
    $files = array_column($f->scan(DOCROOT . '/locale'), 'pathname');
    foreach ($files as $l) {
        // Load the po file with the translations
        $po = new PoLoader();
        $tr = $po->loadFile($l);

        // Export to a json file
        $tr->setDomain("messages");
        $tr->setLanguage("it_IT");
        $json = new JsonGenerator();
        if (!$json->generateFile($tr, str_replace(".gen.po", ".json", $l))) {
            trigger_error("Impossibile salvare il file!", E_USER_WARNING);
        };
        unlink($l);
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
// Set language and domain
$t->loadDomain("messages", DOCROOT . "/locale");
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