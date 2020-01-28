<?php

namespace src;

use IntlDateFormatter;
use Medoo\Medoo;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Utils
{

    /**
     * Replace a string (needle) with another one (replace) in a string (haystack) only once
     *
     * @param $haystack string String that contains the needle
     * @param $needle string String to be replaced
     * @param $replace string String that replaces the needle
     *
     * @return string Haystack with needle replaced
     */
    public static function str_replace_once($haystack, $needle, $replace)
    {
        $pos = strpos($haystack, $needle);
        if ($pos !== false) {
            return substr_replace($haystack, $replace, $pos, strlen($needle));
        } else {
            return $haystack;
        }
    }

    /**
     * Generates a random string (used with classrooms and lists.php codes)
     * Credits to Baba (https://stackoverflow.com/a/15198493/7520280)
     *
     * @param int $length
     * @return string
     */
    public static function randomString($length)
    {
        $char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $char = str_shuffle($char);
        for ($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i++) {
            $rand .= $char{mt_rand(0, $l)};
        }
        return $rand;
    }

    /**
     * Generates a random, UNIQUE code for lists.php and classrooms
     *
     * @param Medoo $db
     * @param int $length
     * @return string
     */
    public static function generateCode($db, $length = 5)
    {
        do {
            $code = static::randomString($length);
        } while ($db->has("classrooms", ["code" => $code]) or $db->has("lists", ["code" => $code]));
        return $code;
    }

    /**
     * Gets the IP address of the current user
     *
     * @return string
     */
    public static function getUserIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Sends an email using the PHPMailer library
     *
     * @param $recipient_email string Recipient email
     * @param $recipient_name string Recipient name
     * @param $subject string Subject of the email
     * @param $body string The content of the email. This can be whether HTML or plain text
     * @param $html bool Is the body HTML or not
     *
     * @return array
     */
    public static function sendMail($recipient_email, $recipient_name, $subject, $body, $html = true)
    {
        $mail = new PHPMailer(TRUE);
        try {
            $mail->setFrom('noreply@account.maicol07.it', __("Maicol07 Account"));
            $mail->addAddress($recipient_email, $recipient_name);
            $mail->Subject = "$subject - " . __("Maicol07 Account");
            !$html ?: $mail->isHTML();
            $mail->Body = $body;
            $mail->send();
            return [
                "success" => true
            ];
        } catch (Exception $e) {
            /* PHPMailer exception */
            return [
                "success" => false,
                "error" => __("Si è verificato un errore inaspettato:") . $e->errorMessage()
            ];
        } catch (\Exception $e) {
            /* PHP exception */
            return [
                "success" => false,
                "error" => __("Si è verificato un errore inaspettato:") . $e->getMessage()
            ];
        }
    }

    /**
     * Retuns the formatted date (based on current user language)
     *
     * @param $date string
     * @param $locale string
     *
     * @return string
     * @throws \Exception
     */
    public static function getLocaleDate($date, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        if ($formatter === null)
            throw new \Exception(intl_get_error_message());

        return $formatter->format(strtotime($date)) ?: $date;
    }

    /**
     * Gets a time ago string.
     * Timestamp will be converted to XX days/hour/minutes/seconds ago
     *
     * @param $time
     * @param $short
     * @return string
     */
    public static function get_time_ago($time, $short = false)
    {
        if (is_string($time)) {
            $time = strtotime($time);
        }
        $time_difference = time() - $time;

        if ($time_difference < 1) {
            return __("meno di 1 secondo fa");
        }

        $tr = [
            'y' => [__("anno"), __("anni")],
            'm' => [__("mese"), __("mesi")],
            'd' => [__("giorno"), __("giorni")],
            'h' => [__("ora"), __("ore")],
            'min' => [__("minuto"), __("minuti")],
            's' => [__("secondo"), __("secondi")]
        ];

        $condition = array(12 * 30 * 24 * 60 * 60 => 'y',
            30 * 24 * 60 * 60 => 'm',
            24 * 60 * 60 => 'd',
            60 * 60 => 'h',
            60 => 'min',
            1 => 's'
        );

        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;

            if ($d >= 1) {
                $t = round($d);
                if ($short) {
                    return "$t " . ($t > 1 ? $tr[$str][1] : $tr[$str][1]);
                }
                return __("circa") . " $t " . ($t > 1 ? $tr[$str][1] : $tr[$str][1]) . ' ' . __("fa");
            }
        }
    }

    /**
     * Checks to see if the page is being served over SSL or not.
     *
     * @param $trust_proxy_headers bool
     * @return bool
     */
    public static function isHTTPS($trust_proxy_headers = false)
    {
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            // Check the standard HTTPS headers
            return true;
        } elseif ($trust_proxy_headers && isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            // Check proxy headers if allowed
            return true;
        } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }

        return false;
    }

    /**
     * Generates the proper HTML code for assets resources (Glob patterns supported)
     * Example:
     * If $assets array includes all .js files, then the method will build <script> tags. The same with .css files
     *
     * @param array $assets
     * @return string
     * @deprecated
     */
    public static function buildAssetsImport($assets)
    {
        // Search for glob patterns
        $assets_list = [];
        foreach ($assets as $asset => $options) {
            if (is_array($options)) {
                array_push($assets_list, $asset);
            } else {
                array_push($assets_list, $options);
            }
        }
        $patterns = preg_grep("/\*/", $assets_list);
        if (count($patterns)) {
            foreach ($patterns as $pattern) {
                $files = glob(Utils::buildAssetsURI($pattern, true));
                // Remove duplicates (Minified and non-minified versions)
                if (count($minified = preg_grep("/min.(js|css)/", $files))) {
                    $files = array_diff($files, $minified);
                }
                // Search for a init file
                if (count($init = preg_grep("/init.(js|css)/", $files))) {
                    $files = array_diff($files, $init);
                    $init = array_shift($init);
                    array_unshift($files, $init);
                }
                $key = array_search($pattern, $assets);
                if (!$key and array_key_exists($pattern, $assets)) {
                    $key = $pattern;
                }
                $list = array_map(function ($path) {
                    return str_replace(DOCROOT, '', $path);
                }, $files);
                $options = $assets[$key];
                foreach ($list as $asset) {
                    if (!empty($options)) {
                        $assets[$asset] = $options;
                    } else {
                        array_push($assets, $asset);
                    }
                }
                unset($assets[$key]);
            }
        }

        $html = '';
        foreach ($assets as $asset => $options) {
            if (is_array($options) and !empty($options)) {
                $type = $options['type'];
            } else {
                $type = "text/javascript";
                $asset = $options;
            }
            if (!static::is_url($asset)) {
                $asset = Utils::buildAssetsURI($asset);
            }
            if (preg_match("/.js/", $asset)) {
                $html .= '<script type="' . $type . '" src="' . $asset . '"></script>';
            } elseif (preg_match("/.css/", $asset)) {
                $html .= '<link rel="stylesheet" href="' . $asset . '">';
            }
        }
        return $html;
    }

    /**
     * Build an Assets URI using the ROOTDIR constant. If a minified version exists and current environment is PRODUCTION,
     * then it uses that version
     *
     * @param string $dir
     * @param bool $usedocroot If this variable is set to TRUE then it will be used the DOCROOT constant instead of the ROOTDIR one
     * @return string
     */
    public static function buildAssetsURI($dir, $usedocroot = false)
    {
        // Check if URI starts with a '/' or not
        if (substr($dir, 0, 1) != "/") {
            $dir = "/" . $dir;
        }
        // Check if URI is a minified version (only CSS and JS)
        if (strpos($dir, ".css") or strpos($dir, ".js")) {
            $pieces = explode(".", $dir);
            $minified = array_search("min", $pieces);
            if ($minified and empty(PRODUCTION)) {
                if (file_exists(DOCROOT . implode(".", array_diff($pieces, ['min'])))) {
                    unset($pieces[$minified]);
                }
            } elseif (!$minified and !empty(PRODUCTION)) {
                array_splice($pieces, -2, 0, ["min"]);
            }
            $dir = implode(".", $pieces);
        }
        return ($usedocroot ? DOCROOT : ROOTDIR) . $dir;
    }

    /**
     * Checks whether a string is a valid URL or not
     *
     * @param $str string String to check
     *
     * @return bool
     */
    public static function is_url($str)
    {
        if (!is_string($str)) {
            return false;
        }
        $regex = "((https?|ftp)\:\/\/)?"; // SCHEME
        $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
        $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
        $regex .= "(\:[0-9]{2,5})?"; // Port
        $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
        $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
        $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor

        if (preg_match("/^$regex$/i", $str)) { // `i` flag for case-insensitive
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns a validated GET request parameter
     *
     * @param $name string
     * @return string|bool
     */
    public static function get($name)
    {
        if (isset($_GET[$name])) {
            return self::validate($_GET[$name]);
        }
        return false;
    }

    /**
     * Validate post strings (removes html special chars and spaces at the beginning and at the end of the string)
     *
     * @param $str string String to validate
     *
     * @return string
     */
    public static function validate($str)
    {
        if (is_string($str)) {
            return trim(htmlspecialchars($str));
        }
        return $str;
    }

    /**
     * Returns a validated POST request parameter
     *
     * @param $name string
     * @return string|bool
     */
    public static function post($name)
    {
        if (isset($_POST[$name])) {
            return self::validate($_POST[$name]);
        }
        return null;
    }

    /**
     * Returns system version
     *
     * @return false|string
     */
    public static function getVersion()
    {
        return file_get_contents(DOCROOT . "/VERSION");
    }

}