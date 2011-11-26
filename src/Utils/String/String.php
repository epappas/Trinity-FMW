<?php


/**
 * @extends -
 * @constructor String($str)
 * @constructor String($chrArr=array())
 * @author GiAr - papas.evagelos@gmail.com
 * @copyright 2010
 *
 */
class String
{
    /**
     * @var String $source  The Sorce of the String
     */
    private $source;
    /**
     * @var String $value The Value of the Current String
     */
    private $value;

    /**
     * Constructor - Initiate the First Values
     * @param String $str - The Source Value of the String
     */
    function String($str)
    {
        if($this->validateString($str))
        {
            $this->source = $str;
            $this->value = $str;
        }
    }

    /**
     * Constructor - Initiate the First Values
     * @param char[] $chrArr - An array of Chars turn into String
     */
    function String($chrArr=array())
    {
        if($this->validateCharArr($chrArr))
        {
            $this->source = "";
            $this->value = "";
            foreach( $chrArr as $v)
            {
                $this->source .= $v;
                $this->value .=$v;
            }
        }
    }

    /**
     * Validates whether the given element is a string.
     * @param  string $value
     * @param  bool   $require_content If the string can be empty or not
     * @return bool
     */
    public function validateString($value, $require_content = true)
    {
        return (!is_string($value)) ? false : ($require_content && $value == '' ? false : true);
    }

    /**
     * Validates whether the given Value is a Char-Array.
     * @param  array $value
     * @param  bool  $require_content If the array can be empty or not
     * @return bool
     */
    public function validateCharArr($value, $require_content = true)
    {
        return (!is_array($value)) ? false : ($require_content && empty($value) ? false : true);
    }

    /**
     * Validates whether the String Has a Validate Email-Form.
     * @param  string $email
     * @return bool
     */
    public function validateEmail($email)
    {
        return ( ( eregi( "[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}", $email ) ) ? true : false );
    }

    /**
     * Generates a Random String - [A-Z][a-z][0-9]
     * @param int $len - The Char number of the string
     * @return Random String
     */
    public function getRanID($len)
    {
            $pool="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            $lchr=strlen($pool)-1;
            $ranid="";
            for($i=0;$i<$len;$i++)
            {
                $ranid.=$pool[mt_rand(0,$lchr)];
            }
            return $ranid;
    }

    /**
     *
     */
    public function encodeURL($ap = array())
    {
        $ar='';
        foreach($ap as $k => $v)
        {
            $ar.= strtolower($k . "=" . urldecode($v) . "&");
        }
        return $ar;
    }

    /**
     * Converts high-character symbols into their respective html entities.
     * @param  String $string
     * @return String
     */
    public function convertSymbolsToHTML($string)
    {
        $symbols = array
        (
            '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
            '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
            '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
            '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
            '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
            '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
            '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
            '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
            '�', '�'
        );
        $entities = array
        (
            '&#8218;',  '&#402;',   '&#8222;',  '&#8230;',  '&#8224;',  '&#8225;',  '&#710;',
            '&#8240;',  '&#352;',   '&#8249;',  '&#338;',   '&#8216;',  '&#8217;',  '&#8220;',
            '&#8221;',  '&#8226;',  '&#8211;',  '&#8212;',  '&#732;',   '&#8482;',  '&#353;',
            '&#8250;',  '&#339;',   '&#376;',   '&#8364;',  '&aelig;',  '&aacute;', '&acirc;',
            '&agrave;', '&aring;',  '&atilde;', '&auml;',   '&ccedil;', '&eth;',    '&eacute;',
            '&ecirc;',  '&egrave;', '&euml;',   '&iacute;', '&icirc;',  '&igrave;', '&iuml;',
            '&ntilde;', '&oacute;', '&ocirc;',  '&ograve;', '&oslash;', '&otilde;', '&ouml;',
            '&thorn;',  '&uacute;', '&ucirc;',  '&ugrave;', '&uuml;',   '&yacute;', '&aacute;',
            '&acirc;',  '&aelig;',  '&agrave;', '&aring;',  '&atilde;', '&auml;',   '&ccedil;',
            '&eacute;', '&ecirc;',  '&egrave;', '&eth;',    '&euml;',   '&iacute;', '&icirc;',
            '&igrave;', '&iuml;',   '&ntilde;', '&oacute;', '&ocirc;',  '&ograve;', '&oslash;',
            '&otilde;', '&ouml;',   '&szlig;',  '&thorn;',  '&uacute;', '&ucirc;',  '&ugrave;',
            '&uuml;',   '&yacute;', '&yuml;',   '&iexcl;',  '&pound;',  '&curren;', '&yen;',
            '&brvbar;', '&sect;',   '&uml;',    '&copy;',   '&ordf;',   '&laquo;',  '&not;',
            '&shy;',    '&reg;',    '&macr;',   '&deg;',    '&plusmn;', '&sup2;',   '&sup3;',
            '&acute;',  '&micro;',  '&para;',   '&middot;', '&cedil;',  '&sup1;',   '&ordm;',
            '&raquo;',  '&frac14;', '&frac12;', '&frac34;', '&iquest;', '&times;',  '&divide;',
            '&cent;',   '...',      '&micro;'
        );

        if ($this->validateString($string, false))
        {
            return str_replace( $symbols, $entities, $string );
        }
        else
        {
            return $string;
        }
    }

    /**
     * Clears $this->value, For Spetial Characters
     * @return String
     */
    function clearSpecialCharacters()
    {
        // Replace other special chars
        $specialCharacters = array
        (
            '#' => '',
            '%' => '',
            '&' => '',
            "\'" => '\\',
            "\"" => '\\',
            "  " => '',
            "" => '0'
        );

        trim($this->value);
        while (list($character, $replacement) = each($specialCharacters))
        {
            $string = str_replace($character, $replacement, $this->value);
        }

        $this->value = strtr
        (
            $this->value,
            "ÀÁÂÃÄÅ�áâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",
            "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"
        );

        // Remove all remaining other unknown characters
        $this->value = preg_replace('/[^a-zA-Z0-9\-]/', ' ', $string);
        $this->value = preg_replace('/^[\-]+/', '', $string);
        $this->value = preg_replace('/[\-]+$/', '', $string);
        $this->value = preg_replace('/[\-]{2,}/', ' ', $string);

        return $this->value;
    }

    /**
     * Displays a human readable file size.
     * @param  int $size
     * @param  bool   $round
     * @return String|null
     */
    public function formatFileSize($size, $round = true)
    {
        if (is_int($size))
        {
            $value = 0;
            if ($size >= 1073741824)
            {
                $value = round($size/1073741824*100)/100;
                return  ($round) ? round($value) . 'Gb' : "{$value}Gb";
            }
            else if ($size >= 1048576)
            {
                $value = round($size/1048576*100)/100;
                return  ($round) ? round($value) . 'Mb' : "{$value}Mb";
            }
            else if ($size >= 1024)
            {
                $value = round($size/1024*100)/100;
                return  ($round) ? round($value) . 'kb' : "{$value}kb";
            }
            else
            {
                return $size . " bytes";
            }
        }
        else
        {
            return null;
        }
    }

    /**
     * Counts number of words in a string.
     * if $real_words == true then remove things like '-', '+', that
     * are surrounded with white space.
     * @param  String $string
     * @param  bool   $real_words
     * @return String|null
     */
    public function countWords($string, $real_words = true)
    {
        if ($this->validateString($string))
        {
            if ($real_words == true)
            {
                $string = preg_replace('/(\s+)[^a-zA-Z0-9](\s+)/', ' ', $string);
            }
            return (count(split('[[:space:]]+', $string)));
        }
        else
        {
            return null;
        }
    }

    /**
     * Counts number of sentences in a string.
     *
     * @param  String $string
     * @return String|null
     */
    public function countSentences($string)
    {
        if ($this->validateString($string))
        {
            return preg_match_all('/[^\s]\.(?!\w)/', $string, $matches);
        }
        else
        {
            return null;
        }
    }

    /**
     * Counts number of sentences in a string.
     * @param  String $string
     * @return String|null
     */
    public function countParagraphs($string)
    {
        if ($this->validateString($string))
        {
            $string = str_replace("\r", "\n", $string);
            return count(preg_split('/[\n]+/', $string));
        }
        else
        {
            return false;
        }
    }

    /**
     * Re-Initiate the Vale of the String
     * @param String $val Value of the string
     */
    public function setValue($val)
    {
        $this->value = $val;
    }

    /**
     *
     * @return String - The Current value of the String
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return String - The Value of the Sorce String
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Gather information about a passed string.
     *
     * If $real_words == true then remove things like '-', '+', that are
     * surrounded with white space.
     * 
     * $info['character']
     * $info['word']
     * $info['sentence']
     * $info['paragraph']
     *
     * @param  bool   $real_words - True Default
     * @return String[] - Array of infos
     */
    public function getStringInformation($real_words = true)
    {
        $info = array();
        $info['character'] = ($real_words) ? preg_match_all('/[^\s]/', $this->value, $matches) : strlen($this->value);
        $info['word']      = $this->countWords($this->value, $real_words);
        $info['sentence']  = $this->countSentences($this->value);
        $info['paragraph'] = $this->countParagraphs($this->value);
        return $info;
    }
}
?>
