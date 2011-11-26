<?php
/**
 * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
 * @copyright 2010
 *
 */


/**
 * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
 * @copyright 2010
 * Converts the string value of the given text, to Secure SQL text and
 * avoiding every SQL injection
 */
class SqlInjection
{
    /**
     * Converts the string value of the given text, to Secure SQL text and
     * avoiding every SQL injection
     * @param String $val
     * @return String The value Converted
     */
    function convDataValue($val)
    {
        return mysql_escape_string( //Escapes a string for use in a mysql_query
                    htmlspecialchars( //Convert special characters to HTML entities
                            stripslashes( //Un-quotes a quoted string
                                    trim($val) ) ) );
    }

    /**
     * Converts the string value of the given text, to Secure SQL text and
     * avoiding every SQL injection
     * @param String $value ValueText
     * @param String $key Array Key Index
     * @return String the Given Value Converted
     */
    function convGETValue(&$value, $key)
    {
        if(!is_array($_GET[$key]))
        {
            $_GET[$key] = mysql_escape_string( //Escapes a string for use in a mysql_query
                htmlspecialchars( //Convert special characters to HTML entities
                        stripslashes( //Un-quotes a quoted string
                                trim($_GET[$key]) ) ) );
        }
        else
        {
            foreach($_GET[$key] as $k => $v)
            {
                if(!is_array($_GET[$key][$k]))
                {
                    $_GET[$key][$k] = mysql_escape_string( //Escapes a string for use in a mysql_query
                        htmlspecialchars( //Convert special characters to HTML entities
                                stripslashes( //Un-quotes a quoted string
                                        trim($v) ) ) );
                }
                else
                {
                    foreach($_GET[$key][$k] as $kok => $vov)
                    {
                        $_GET[$key][$k][$kok] = mysql_escape_string( //Escapes a string for use in a mysql_query
                            htmlspecialchars( //Convert special characters to HTML entities
                                    stripslashes( //Un-quotes a quoted string
                                            trim($vov) ) ) );
                    }
                }
            }
        }
        return $_GET[$key];
    }

    /**
     * Converts the string value of the given text, to Secure SQL text and
     * avoiding every SQL injection
     * @param String $value ValueText
     * @param String $key Array Key Index
     * @return String the Given Value Converted
     */
    function convPOSTValue($value, $key)
    {
        if(!is_array($_POST[$key]))
        {
            $_POST[$key] = mysql_escape_string( //Escapes a string for use in a mysql_query
                htmlspecialchars( //Convert special characters to HTML entities
                        stripslashes( //Un-quotes a quoted string
                                trim($value) ) ) );
        }
        else
        {
            foreach($_POST[$key] as $k => $v)
            {
                if(!is_array($_POST[$key][$k]))
                {
                    $_POST[$key][$k] = mysql_escape_string( //Escapes a string for use in a mysql_query
                        htmlspecialchars( //Convert special characters to HTML entities
                                stripslashes( //Un-quotes a quoted string
                                        trim($v) ) ) );
                }
                else
                {
                    foreach($_POST[$key][$k] as $kok => $vov)
                    {
                        $_POST[$key][$k][$kok] = mysql_escape_string( //Escapes a string for use in a mysql_query
                            htmlspecialchars( //Convert special characters to HTML entities
                                    stripslashes( //Un-quotes a quoted string
                                            trim($vov) ) ) );
                    }
                }
            }
        }
        return $_POST[$key];
    }

    /**
     * Converts all the GET values
     * @return $_GET
     */
    function convGETArray()
    {
        foreach($_GET as $k => $v)
        {
            $this->convGETValue($v, $k);
        }
        return $_GET;
    }

    /**
     * Converts all the POST values
     * @return $_POST
     */
    function convPOSTArray()
    {
        foreach($_POST as $k => $v)
        {
            $this->convPOSTValue($v, $k);
        }
        return $_POST;
    }
}

?>