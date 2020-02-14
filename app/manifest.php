<?php

use App\Utils;

require_once '../dir.php';
$no_auth = true;
require_once DOCROOT . "/config/config.php";
require DOCROOT . "/config/gettext.php";


$manifest = [
    "name" => __("Interrogazioni Programmate"),
    "short_name" => __("Interrogazioni Programmate"),
    "description" => __("Gestisci le tue interrogazioni online insieme alla tua classe!"),
    "start_url" => BASEURL . "/app/",
    "display" => "standalone",
    "background_color" => "#ffffff",
    "theme_color" => "#ffffff",
    "icons" => [
        [
            "src" => Utils::buildAssetsURI("/app/assets/img/favicon/android-chrome-36x36.png"),
            "sizes" => "36x36",
            "type" => "image/png"
        ],
        [
            "src" => Utils::buildAssetsURI("/app/assets/img/favicon/android-chrome-48x48.png"),
            "sizes" => "48x48",
            "type" => "image/png"
        ],
        [
            "src" => Utils::buildAssetsURI("/app/assets/img/favicon/android-chrome-72x72.png"),
            "sizes" => "72x72",
            "type" => "image/png"
        ],
        [
            "src" => Utils::buildAssetsURI("/app/assets/img/favicon/android-chrome-96x96.png"),
            "sizes" => "96x96",
            "type" => "image/png"
        ],
        [
            "src" => Utils::buildAssetsURI("/app/assets/img/favicon/android-chrome-144x144.png"),
            "sizes" => "144x144",
            "type" => "image/png"
        ],
        [
            "src" => Utils::buildAssetsURI("/app/assets/img/favicon/android-chrome-192x192.png"),
            "sizes" => "192x192",
            "type" => "image/png"
        ],
        [
            "src" => Utils::buildAssetsURI("/app/assets/img/favicon/android-chrome-256x256.png"),
            "sizes" => "256x256",
            "type" => "image/png"
        ],
        [
            "src" => Utils::buildAssetsURI("/app/assets/img/favicon/android-chrome-384x384.png"),
            "sizes" => "384x384",
            "type" => "image/png"
        ],
        [
            "src" => Utils::buildAssetsURI("/app/assets/img/favicon/android-chrome-512x512.png"),
            "sizes" => "512x512",
            "type" => "image/png"
        ]
    ],
    "serviceworker" => [
        "src" => Utils::buildAssetsURI("app/sw.js"),
    ]
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($manifest);