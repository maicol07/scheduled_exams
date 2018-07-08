<!DOCTYPE HTML>
<!-- Copyright 2017 maicol07. Original template by Makeroid (makeroid.io)-->
<html lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php
    require("layout/header/favicon.php")
    ?>
    <title>Accesso - Interrogazioni Programmate</title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified Materialize CSS -->
    <link rel="stylesheet" href="css/materialize.min.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway&amp;subset=latin-ext" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            margin: 0;
            font-family: Raleway, sans-serif;
        }

        .cd-container {
            font-family: "Raleway", sans-serif;
            width: 100%;
            margin: 0 auto;
            background: #ffffff;
        }

        .cd-container::after {
            content: '';
            display: table;
            clear: both;
        }

        /* --------------------------------

        Main components

        -------------------------------- */
        #cd-timeline {
            position: relative;
            padding: 2em 0;
        }

        #cd-timeline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 25px;
            height: 100%;
            width: 4px;
            background: #4C86BB;
        }

        @media only screen and (min-width: 1170px) {
            #cd-timeline {
            }

            #cd-timeline::before {
                left: 50%;
                margin-left: -2px;
            }
        }

        .cd-timeline-block {
            position: relative;
            margin: 2em 0;
            border: 10px
        }

        .cd-timeline-block:after {
            content: "";
            display: table;
            clear: both;
        }

        .cd-timeline-block:first-child {
            margin-top: 0;
        }

        .cd-timeline-block:last-child {
            margin-bottom: 0;
        }

        .cd-timeline-block:last-child::after {
            margin-bottom: 0;

        }

        @media only screen and (min-width: 1170px) {
            .cd-timeline-block {
                margin: 4em 0;
            }

            .cd-timeline-block:first-child {
                margin-top: 0;
            }

            .cd-timeline-block:last-child {
                margin-bottom: 0;
            }
        }

        .cd-timeline-img {
            position: absolute;
            top: 8px;
            left: 12px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            box-shadow: 0 0 0 4px #4c86bb, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
        }

        .cd-timeline-img {
            background: #005aad;
        }

        @media only screen and (min-width: 1170px) {
            .cd-timeline-img {
                width: 30px;
                height: 30px;
                left: 50%;
                margin-left: -15px;
                margin-top: 15px;
                /* Force Hardware Acceleration in WebKit */
                -webkit-transform: translateZ(0);
                -webkit-backface-visibility: hidden;
            }
        }

        .cd-timeline-content {
            position: relative;
            margin-left: 60px;
            margin-right: 30px;
            background: #e0e0e0;
            border-radius: 2px;
            padding: 1em;
            display: block;
        }

        .cd-timeline-content .timeline-content-info {
            background: #e0e0e0;
            padding: 5px 10px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            box-shadow: inset 0 2px 0 rgba(0, 0, 0, 0.08);
            border-radius: 2px;
        }

        .cd-timeline-content .timeline-content-info i {
            margin-right: 5px;
        }

        .cd-timeline-content .timeline-content-info .timeline-content-info-title, .cd-timeline-content .timeline-content-info .timeline-content-info-date {
            width: calc(50% - 2px);
            display: inline-block;
        }

        @media (max-width: 500px) {
            .cd-timeline-content .timeline-content-info .timeline-content-info-title, .cd-timeline-content .timeline-content-info .timeline-content-info-date {
                display: block;
                width: 100%;
            }
        }

        .cd-timeline-content .feature p {
            display: inline;
        }

        .cd-timeline-content .feature {
            padding-top: 3px;
            padding-bottom: 3px;
        }

        .cd-timeline-content .feature .info {
            padding-left: 50px;
            display: block;
        }

        .cd-timeline-content .content-skills {
            font-size: 12px;
            padding: 0;
            margin-bottom: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .cd-timeline-content .content-skills li {
            background: #f4f4f4;
            border-radius: 2px;
            display: inline-block;
            padding: 2px 10px;
            color: rgba(255, 255, 255, 0.7);
            margin: 3px 2px;
            text-align: center;
            flex-grow: 1;
        }

        .cd-timeline-content:after {
            content: "";
            display: table;
            clear: both;
        }

        .cd-timeline-content h2 {
            margin-top: 0;
            margin-bottom: 5px;
        }

        .cd-timeline-content p, .cd-timeline-content .cd-date {
            color: rgba(0, 0, 0, 0.87);
            font-size: 13px;
        }

        .cd-timeline-content .cd-date {
            display: inline-block;
        }

        .cd-timeline-content p {
            margin: 1em 0;
            line-height: 1.6;
        }

        .cd-timeline-content .new {
            background: #2196F3;
            width: 45px;
            font-size: 12px;
            color: white;
            padding-top: 2px;
            padding-bottom: 2px;
            padding-left: 4px;
            padding-right: 4px;
            border-radius: 5px;
            display: none;
            text-align: center;
        }

        .cd-timeline-content .improved {
            background: #4caf50;
            width: 45px;
            font-size: 12px;
            color: white;
            padding-top: 2px;
            padding-bottom: 2px;
            padding-left: 4px;
            padding-right: 4px;
            border-radius: 5px;
        }

        .cd-timeline-content .fixed {
            background: #ff5722;
            width: 45px;
            font-size: 12px;
            color: white;
            padding-top: 2px;
            padding-bottom: 2px;
            padding-left: 4px;
            padding-right: 4px;
            border-radius: 5px;
        }

        .cd-timeline-content::before {
            content: '';
            position: absolute;
            top: 16px;
            right: 100%;
            height: 0;
            width: 0;
            border: 7px solid transparent;
            border-right: 7px solid #e0e0e0;
        }

        @media only screen and (min-width: 768px) {
            .cd-timeline-content h2 {
                font-size: 20px;
            }

            .cd-timeline-content p {
                font-size: 16px;
            }

            .cd-timeline-content .cd-read-more, .cd-timeline-content .cd-date {
                font-size: 14px;
            }
        }

        @media only screen and (min-width: 1170px) {
            .cd-timeline-content {
                color: rgba(0, 0, 0, 0.87);
                padding: 1.6em;
                width: 36%;
                margin: 0 5%;
            }

            .cd-timeline-content::before {
                top: 24px;
                left: 100%;
                border-color: transparent;
                border-left-color: #e0e0e0;
            }

            .cd-timeline-content .cd-date {
                position: absolute;
                width: 100%;
                left: 122%;
                top: 6px;
                font-size: 16px;
                color: rgba(0, 0, 0, 0.87);
            }

            .cd-timeline-block:nth-child(even) .cd-timeline-content {
                float: right;
            }

            .cd-timeline-block:nth-child(even) .cd-timeline-content::before {
                top: 24px;
                left: auto;
                right: 100%;
                border-color: transparent;
                border-right-color: #e0e0e0;
            }

            .cd-timeline-block:nth-child(even) .cd-timeline-content .cd-read-more {
                float: right;
            }

            .cd-timeline-block:nth-child(even) .cd-timeline-content .cd-date {
                left: auto;
                right: 122%;
                text-align: right;
            }
        }

        div {
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="container">
    <section id="cd-timeline" class="cd-container">
        <div class="cd-timeline-block">
            <div class="cd-timeline-img cd-picture"></div>
            <div class="cd-timeline-content">
                <span class="cd-date"><i class="fa fa-calendar-o" aria-hidden="true"></i> Settembre 2018 </span>
                <h2>Versione 0.1a</h2>
                <div class="feature">
                    <p class="new">NUOVO</p>
                    <p>Versione iniziale</p>
                    <p></p>
                    <br>
                </div>
            </div>
        </div>
    </section>
    <script src='https://use.fontawesome.com/4d74086fc6.js'></script>
    <br>
    <a class="btn waves-effect waves-light" onClick="self.close()" align="center">Chiudi Finestra</a>
</div>
<!-- Compiled and minified JavaScript -->
<script src="js/materialize.min.js"></script>
</body>
</html>