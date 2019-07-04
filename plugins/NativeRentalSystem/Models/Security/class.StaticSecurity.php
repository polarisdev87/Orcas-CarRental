<?php
/**
 * NRS Guest/Customer data retriever for security purposes
 * Note 1: This model does not depend on any other class
 * Note 2: This model must be used in static context only
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 * NOTE: Do not use internal plugin class methods in this class,
 * because it might be initialized before plugin will be loaded
 */
namespace NativeRentalSystem\Models\Security;

class StaticSecurity
{
    public static function getAgent()
    {
        if( !empty($_SERVER['HTTP_USER_AGENT']) )
        {
            $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
        } elseif( !empty($HTTP_SERVER_VARS['HTTP_USER_AGENT']) )
        {
            $HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
        } elseif( !isset($HTTP_USER_AGENT) )
        {
            $HTTP_USER_AGENT = '';
        }
        return sanitize_text_field($HTTP_USER_AGENT);
    }

    public static function getRealIP()
    {
        if( !empty($_SERVER['HTTP_CLIENT_IP']) )
        {
            $REAL_IP = $_SERVER['HTTP_CLIENT_IP'];
        } elseif( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) )
        {
            $REAL_IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif( !isset($REAL_IP) )
        {
            $REAL_IP = 'UNKNOWN';
        }
        return sanitize_text_field($REAL_IP);
    }

    public static function isRobot($browserName)
    {
        //popular bots list
        $robots = array(
            'ia_archiver',
            'Scooter/',
            'Ask Jeeves',
            'Baiduspider+(',
            'Exabot/',
            'FAST Enterprise Crawler',
            'FAST-WebCrawler/',
            'http://www.neomo.de/',
            'Gigabot/',
            'Mediapartners-Google',
            'Google Desktop',
            'Feedfetcher-Google',
            'Googlebot',
            'heise-IT-Markt-Crawler',
            'heritrix/1.',
            'ibm.com/cs/crawler',
            'ICCrawler - ICjobs',
            'ichiro/2',
            'MJ12bot/',
            'MetagerBot/',
            'msnbot-NewsBlogs/',
            'msnbot/',
            'msnbot-media/',
            'NG-Search/',
            'http://lucene.apache.org/nutch/',
            'NutchCVS/',
            'OmniExplorer_Bot/',
            'online link validator',
            'psbot/0',
            'Seekbot/',
            'Sensis Web Crawler',
            'SEO search Crawler/',
            'Seoma [SEO Crawler]',
            'SEOsearch/',
            'Snappy/1.1 ( http://www.urltrends.com/ )',
            'http://www.tkl.iis.u-tokyo.ac.jp/~crawler/',
            'SynooBot/',
            'crawleradmin.t-info@telekom.de',
            'TurnitinBot/',
            'voyager/1.0',
            'W3 SiteSearch Crawler',
            'W3C-checklink/',
            'W3C_*Validator',
            'http://www.WISEnutbot.com',
            'yacybot',
            'Yahoo-MMCrawler/',
            'Yahoo! DE Slurp',
            'Yahoo! Slurp',
            'YahooSeeker/',
        );

        //setting the bot flag
        $isRobot = FALSE;
        //check from the bots list
        foreach ($robots as $botName)
        {
            //detect the bot name from the HTTP USER AGENT
            if($browserName != "" && (stristr($browserName, $botName) == TRUE))
            {
                $isRobot = TRUE;
                break;
            }
        }
        return $isRobot;
    }

    public static function getBrowser($agent)
    {
        $ret = $agent;
        $browserList = array(
            "Opera"		=> "Opera",
            "MSIE"		=> "MSIE",
            "rv:1"		=> "Mozilla 1.X",
            "Firefox"	=> "Firefox",
            "chrome"		=> "Google Chrome",
            "Safari"		=> "Safari",
            "Netscape\7"	=> "Netscape 7",
            "Galeon"		=> "Galeon",
            "Konqueror"		=> "Konqueror",
            "Googlebot"		=> "Google Bot",
            "Yahoo!"		=> "Yahoo! Bot",
            "msnbot"		=> "MSN Bot"
        );
        foreach($browserList as $key=>$value)
        {
            if(preg_match("/".$key."/i", $agent))
            {
                $ret = $value;
            }
        }

        return sanitize_text_field($ret);
    }

    public static function getOS($OS)
    {
        $ret = $OS;
        $osList = array(
            "Windows"	    => "Windows",
            "Windows NT"	=> "Windows NT",
            "Windows NT 6.3"=> "Windows 8.1",
            "Windows NT 6.2"=> "Windows 8",
            "Windows NT 6.1"=> "Windows 7",
            "Windows NT 6.0"=> "Windows Vista",
            "Windows NT 5.2"=> "Windows XP x64",
            "Windows NT 5.1"=> "Windows XP",
            "Windows XP"	=> "Windows XP",
            "Windows ME"	=> "Windows Me",
            "Win 9x 4.90"	=> "Windows Me",
            "Windows NT 5.0"=> "Windows 2000",
            "Windows 2000"	=> "Windows 2000",
            "Windows 98"	=> "Windows 98",
            "Windows 95"	=> "Windows 95",
            "Windows NT 4.0"=> "Windows NT 4.0",
            "Mac"			=> "Mac",
            "Macintosh"		=> "Macintosh",
            "Mac OS X"		=> "Mac OS X",
            "unix"			=> "Unix",
            "Linux"			=> "Linux",
            "SunOS"			=> "SunOS",
            "SunOS 5"		=> "SunOS 5",
            "SunOS 4"		=> "SunOS 4",
            "irix"			=> "irix",
            "irix6"			=> "irix6",
            "irix 6"		=> "irix 6",
            "irix 5"		=> "irix 5",
            "bsd"			=> "BSD",
            "freebsd"		=> "FreeBSD",
            "x11"			=> "x11",
            "sinix"			=> "Sinix",
            "Red-Hat"		=> "Red-Hat",
            "Ubuntu"		=> "Ubuntu"
        );
        foreach($osList as $key=>$value)
        {
            if(preg_match("/".$key."/i", $OS))
            {
                $ret = $value;
            }
        }

        return sanitize_text_field($ret);
    }

    public static function sanitizeNumberCommaOrDot($paramText)
    {
        $retText = '';
        if(!is_array($paramText))
        {
            $retText = preg_replace('[^0-9\.,]', '', $paramText);
        }

        return $retText;
    }

    public static function sanitizeLatinText($paramText)
    {
        $retText = '';
        if(!is_array($paramText))
        {
            $retText = preg_replace('[^a-zA-Z0-9_-\.,\s!:;]', '', $paramText);
        }

        return $retText;
    }


    /*
     * In the old times here was sanitizeTextInput (stripinput()) function
     * But it WordPress we have esc_html exactly for the same purpose, so there is no need for that function
     */

    /**
     * Strip file name
     * @param $filename
     * @return int|mixed|string
     */
    public static function sanitizeFilename($filename)
    {
        $filename = strtolower(str_replace(" ", "_", $filename));
        $filename = preg_replace("/[^a-zA-Z0-9_-]/", "", $filename);
        $filename = preg_replace("/^\W/", "", $filename);
        $filename = preg_replace('/([_-])\1+/', '$1', $filename);
        if($filename == "") { $filename = time(); }

        return $filename;
    }

}