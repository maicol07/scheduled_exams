<?php
if (defined("DOCROOT")) {
    require_once DOCROOT . "/config/class_loader.php";
} else {
    require_once "class_loader.php";
}

use Gettext\Generator\JsonGenerator;
use Gettext\Generator\PoGenerator;
use Gettext\GettextTranslator;
use Gettext\Loader\PoLoader;
use Gettext\Scanner\JsScanner;
use Gettext\Scanner\PhpScanner;

$locale_path = DOCROOT . "/locale/it/";
$app_dirs = [
    // Root (no recursive)
    "" => ['recursive' => false],
    // App
    "app",
    // Email templates
    //"template/email",
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
        if (isset($options['recursive']) and !$options['recursive']) {
            $files = glob(DOCROOT . $dir . "/*.{php,js}", GLOB_BRACE);
        } else {
            $path = DOCROOT . "/" . $options;
            $dir_ite = new RecursiveDirectoryIterator($path);
            $iterator = new RecursiveIteratorIterator($dir_ite);
            $result = new RegexIterator($iterator, '/^.+\.(php|js)$/i', RecursiveRegexIterator::GET_MATCH);
            $files = [];
            foreach (iterator_to_array($result) as $file) {
                $file = str_replace("\\", "/", $file);
                foreach (explode("/", $file[0]) as $subpath) {
                    if (in_array($subpath, ['vendors', 'vendor', 'scripts'])) {
                        continue;
                    }
                }
                $files[] = $file[0];
            }
        }
        if (!function_exists("files_ext_filter")) {
            /** @noinspection PhpMissingDocCommentInspection */
            function files_ext_filter($files, $extension)
            {
                global $ext;
                $ext = $extension;
                if (is_object($files)) {
                    $files = get_object_vars($files);
                }
                return array_filter($files, function ($file) {
                    global $ext;
                    $file_info = new SplFileInfo(DOCROOT . $file);
                    if ($file_info->getExtension() == $ext) {
                        return true;
                    }
                    return false;
                });
            }
        }
        //file_put_contents(DOCROOT . "/log/gettext.log", print_r($files, true));
        $php_files = files_ext_filter($files, "php");
        $js_files = files_ext_filter($files, "js");
        if (!empty($php_files)) {
            /** @noinspection PhpParamsInspection */
            $php_scanner = new PhpScanner();
            foreach ($php_files as $php_file) {
                $php_scanner->scanFile($php_file);
            }
        }
        if (!empty($js_files)) {
            /** @noinspection PhpParamsInspection */
            $js_scanner = new JsScanner();
            foreach ($js_files as $js_file) {
                $js_scanner->scanFile($js_file);
            }
        }
        // Debug info
        /*if (!isset($php_messages) or !isset($js_messages)) {
            echo "<pre>";
            var_dump($options);
            var_dump($path);
            var_dump($files);
            var_dump($file);
            echo "<pre>";
            exit;
        }*/
        if (!file_exists($locale_path . "messages.po")) {
            file_put_contents($locale_path . 'messages.po', ''); // File creation
        }


        $po = new PoLoader();
        $messages = $po->loadFile($locale_path . "messages.po");

        if (!empty($php_messages)) {
            foreach ($php_scanner->getTranslations() as $translation) {
                $messages->addOrMerge($translation);
            }
        }
        if (!empty($js_messages)) {
            foreach ($js_scanner->getTranslations() as $translation) {
                $messages->addOrMerge($translation);
            }
        }
        $messages->setDomain("messages");
        $messages->setLanguage("it_IT");
        $generator = new PoGenerator();
        $generator->generateFile($messages, $locale_path . "messages.po");
    }
}
if (!file_exists($locale_path . "messages.json") or get("regenerate_tr") or get("regenerate_json")) {
    /* Export translations in JSON for JS */
    $dir_ite = new RecursiveDirectoryIterator(DOCROOT . "/locale");
    $iterator = new RecursiveIteratorIterator($dir_ite);
    $result = new RegexIterator($iterator, '/^.+\.(po)$/i', RecursiveRegexIterator::GET_MATCH);
    $files = [];
    foreach (iterator_to_array($result) as $file) {
        $files[] = $file[0];
    }
    foreach ($files as $l) {
        // Load the po file with the translations
        $po = new PoLoader();
        $tr = $po->loadFile($l);
        // Export to a json file
        $tr->setDomain("messages");
        $json = new JsonGenerator();
        $json->generateFile($tr, str_replace(".po", ".json", $l));
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