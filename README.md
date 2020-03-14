<img src="app/assets/img/logo.svg" width="150" align="right" alt="Scheduled Exams">

# Scheduled Exams
<!--[![GitHub license](https://img.shields.io/github/license/maicol07/scheduled_exams.svg)](https://github.com/maicol07/scheduled_exams/blob/master/LICENSE)
[![Inline docs](http://inch-ci.org/github/maicol07/scheduled_exams.svg?branch=master)](http://inch-ci.org/github/maicol07/scheduled_exams)
[![HitCount](http://hits.dwyl.io/maicol07/scheduled_exams.svg)](http://hits.dwyl.io/maicol07/scheduled_exams)
[![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/maicol07/scheduled_exams/issues)
[![GitHub release](https://img.shields.io/github/release/maicol07/scheduled_exams/all.svg)](https://github.com/maicol07/scheduled_exams/releases/)
[![Downloads](https://img.shields.io/github/downloads/maicol07/scheduled_exams/total.svg)](https://github.com/maicol07/scheduled_exams/releases/)-->

[![forthebadge](https://forthebadge.com/images/badges/uses-html.svg)](https://forthebadge.com)
[![forthebadge](https://forthebadge.com/images/badges/built-with-love.svg)](https://forthebadge.com)
[![Stato traduzione](https://translate.maicol07.it/widgets/scheduled-exams/-/webapp/svg-badge.svg)](https://translate.maicol07.it/engage/scheduled-exams/?utm_source=widget)

Scheduled Exams is a webapp that helps you in exams management.

## Requirements
**Web server**: PHP 7.2+, MySQL 5.7+ Other DB types are not currently
supported (they need to support JSON field)

**Minimum browsers**: Google Chrome 46+, Mozilla Firefox 50+, Opera 33+,
Opera Mobile 46, Firefox Android 60+, UC Browser Android 11.8+, Browser
Android 5.x+, Samsung Internet 5+, Safari 11+

**Suggested browsers**: Google Chrome 75+, Mozilla Firefox 74+, Opera
33+, Opera Mobile 46, Firefox Android 60+

_A notification will advice user to update the browser if he is not
using a suggested browser, while he will be blocked if using a browser
prior to minor ones._

## Installation on your own web server
### Dev requirements
Assuming you already satisfy general requirements reported above you need also the following tools installed on your web server or PC:
- **Composer**: <https://getcomposer.org/>
- **Yarn**: <https://yarnpkg.com/>
- **SSH** (if these two tools above are installed on your web server) or **Shell/CMD/PowerShell**
- Some tool to access the database and execute SQL queries like PHPMyAdmin

### Installation
1. First, we need to install dependencies from Composer and Yarn. Open your command line and execute these two commands in your working directory:
```
composer install
yarn install
```

2. Then, we need to set up the database. Create a database if you don't have one and import the sql/tables.sql file into your database using your preferred tool (i.e. PHPMyAdmin)
3. Config the app. Rename the file config/config.example.ini to config.ini and set up all the required settings (at the time I'm writing this guide, all settings are required)
4. Upload all the files to your web server

## Development and contributing
Read file [CONTRIBUTING.md](CONTRIBUTING.md)

## Donate
You can use the sponsor button at the top and choose your preferred type