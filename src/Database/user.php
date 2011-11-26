<?php
/**
 * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
 * @copyright 2010
 *
 */

//Basics Includes
require_once('DBObject.php');

/**
 * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
 * @copyright 2010
 * @extends class DBObject
 * @constractor User($dbServer,$dbHost, $dbUser, $dbPass, $dbName, $dbPrefix)
 *
 */
class User extends DBObject
{
    var $failed = false;
    var $usid = 0;
    var $usrValues = array();
    var $ip = 0;
    var $host = '';
    var $results= array();
    //var $date = gmdate("M d Y");

    /**
     *
     * @param String $dbServer MySQL, MSSQL, Oracle
     * @param String $dbHost e.g. localhost
     * @param String $dbUser username of the DBServer Login
     * @param String $dbPass Password of the DBSever Login
     * @param String $dbName Database Name
     * @param String $dbPrefix The Prefix of alla tables e.g. myTestPrefix_Users
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function User($dbServer,$dbHost, $dbUser, $dbPass, $dbName, $dbPrefix)
    {
        $this->checkSession();
        parent::__construct($dbServer,$dbHost, $dbUser, $dbPass, $dbName, $dbPrefix);
        $this->db->connectDB();
        $this->db->selectDB();

        $this->date = $GLOBALS['date'];
        $this->ip = $this->getUserIP();
        $this->host = $this->getUserHost();

        $this->renewStats(); //Get user stats from the DataBase into user_values array
    }

    /**
     *
     * @return String
     * Get the Internet host name corresponding to a given IP address
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function getUserHost()
    {
            return gethostbyaddr($_SERVER['REMOTE_ADDR']);
    }

    /**
     *
     * @return String
     * Get the Internet Protocol Address of the User
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function getUserIP()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Sets a new Session with the values and keyValues of a given array
     * @param array $values the given array
     * @param bool $remember true to enable cookie store
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function setSession(&$values = array(), $remember = false )
    {
        $this->checkSession();

        foreach($values as $k => $v)
        {
            $_SESSION[$k] = $v;
        }
        $_SESSION['IP'] = $this->ip;
        $_SESSION['HOST'] = $this->host;

        if($remember)
        {
            //here plugs the code for cookie store
        }
    }

    /**
     * Check and logs-in the user according to the parameters
     * @param String $usrName the UserName of the User
     * @param String $usrPass The Password of the User
     * @param bool $remember Create cookie, default = true
     * @param bool $convert_pass_md5 Switch to true if you want to md5_hash your Password String, default = false
     * @return bool
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function checkLogin( $usrName, $usrPass, $remember=true, $convert_pass_md5=false)
    {
        if($convert_pass_md5)
        {
            $usrPass  = md5($usrPass);
        }

        $today = date("Y-m-j G:i:s");
        $this->renewStats($usrName, $usrPass);
        if( count($this->usrValues)>0 )
        {
            //update Session
            $this->setSession($this->usrValues, $remember);
            return true;
        }
        else
        {
            $this->logout();
        }
        return false;
    }

    function httpRedirect($destination)
    {
        ?>
            <script language="JavaScript" type="text/javascript">
                    document.location="<?=$destination?>";
            </script>
        <?php
        /*
        header("Location http://" . $destination . "");
        return true;
        */
    }

    /**
     * log outs User and kills Session's Data
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        unset($_SESSION);
        session_write_close();
        $_SESSION = array();
    }

    /**
     * Updates the User's Cookie Values
     * @param <type> $values
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function updateCookie($values)
    {

    }

    /**
     * Checks if user has a remind Cookie and checks its status
     * @param <type> $cookie
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function checkRemembered($cookie)
    {

    }

    /**
     * Gets Users Privilages by its ID
     * @param <type> $user_key
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function getPrivilages($user_key)
    {

    }

    /**
     * Checks if a Session has already been started otherwise, restart.
     * 2: Some Failure
     * 1: New Start
     * 2: already Started
     * @return int
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function checkSession()
    {
        if(session_id()==NULL)
        {
                session_start();
                return 1;
        }
        else
        {
            return 2;
        }

        return 0;
    }

    /**
     * Check if this IP/User/Host is banned
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function checkBan($value='', $key='')
    {

    }

    /**
     * Just Renew the usrValues Values of the user in case they might have change..!
     * It can handle as parametre a new userName & userPass or recheck the strored
     * $_SESSION fileds of the user, and authorize.
     * @param String $usrName
     * @param String $usrPass
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function renewStats($usrName='', $usrPass='')
    {
        if(empty($usrName) && !isset($_SESSION[USER_NAME_FIELD]))
        {
            return false;
        }

        $sql = "SELECT * FROM ".USER_NAME_ENTITY." WHERE ".USER_NAME_FIELD." = '"
            . (strlen($usrName)>0 ? $usrName :  $_SESSION[USER_NAME_FIELD]) . "' AND "
            . USER_PASS_FIELD . " = '"
            . (strlen($usrPass)>0 ? $usrPass :  $_SESSION[USER_PASS_FIELD]) . "' ";
        $this->setQryStr($sql);
        $this->setUsrValues( $this->executeQry() );
//        $this->setUsrValues( $this->executeQry($sql) );
        return true;
    }

    /**
     * sets alla the values of the $usrValues
     * @param array $vArr the Array of Values
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function setUsrValues($vArr)
    {
        foreach($vArr as $k => $v)
        {
            $this->usrValues[$k] = $v;
        }
    }

    /**
     * Sets a specific Value of a key
     * @param String $key
     * @param variable $val
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function setUsrValue($key, $val)
    {
        $this->usrValues[$key] = $val;
    }

    /**
     * Adds a new key in the array
     * @param String $key
     * @param Variable $value default = Empty
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function addUsrValues($key, $value='')
    {
        $this->setUsrvalues($key, $value);
    }

    /**
     *
     * @tutorial Seeks if user really exists
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function getUserExistance($userKey)
    {

    }

    /**
     * This Function is usefull for simple "Joins" implemantations of User
     * @example User::getContent('user','','','ID',Query);
     * @example User::getContent('user','user_name','B%','ID','Row');
     * @example User::getContent('Files','File_Name','%.jpg','ID','Simple');
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function getContent($table, $column='', $arg='', $order='', $command='')
    {
        $sql = "SELECT "
                . ($column != '' ? "$column" : "*") . " FROM "
                . $table . " WHERE ".USER_KEY_FIELD." = '"
                . $this->usrValues[USER_KEY_FIELD] . "'"
                . ($arg != '' && $column !='' ? " AND $column LIKE '$arg'" : '')
                . ($order != '' ? " ORDER BY $order DESC" : '');

        switch($command)
        {
            case 'Query': //return Query only
                    $this->setQryStr($sql);
                    $this->results = $this->executeQry();
                    return $sql;
                    break;
            case 'Row': //return Array as Array[0], Array[1] ...
                    $this->setQryStr($sql);
                    $this->executeQry();
                    return ( $this->results = $this->fetchResultSet() );
                    break;
            case 'Simple': //Simple Query
                    $sql = "SELECT * FROM "
                            . $table . ""
                            . ($arg != '' && $column !='' ? " WHERE $column = '$arg'" : '')
                            . ($order != '' ? " ORDER BY $order DESC" : '');
            default:
                    $this->setQryStr($sql);
                    $this->executeQry();
                    return ( $this->results = $this->fetchResultSet() );
                    break;
        }
    }

    /**
     * This Function is usefull for simple "Joins" implemantations of User.
     * Can result a Rowset of values between those two arguments $minArg && $maxArg
     * @example User::getContentBetween('userDates','Date','minDay','maxDay','Date','SimpleJoin');
     * @example User::getContentBetween('Dates','day','minDay','maxDay','dayID','Simple');
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    function getContentBetween($table, $column='', $minArg='', $maxArg='', $order='', $command='')
    {
        $sql = "SELECT "
                . ($column != '' ? "$column" : "*") . " FROM "
                . $table . " WHERE ".USER_KEY_FIELD." = '"
                . $this->usrValues[USER_KEY_FIELD] . "'"
                . ($minArg != '' && $column !='' ? " AND $column >= '$minArg'" : '')
                . ($maxArg != '' && $column !='' ? " AND $column <= '$maxArg'" : '')
                . ($order != '' ? " ORDER BY $order DESC" : '');

        switch($command)
        {
            case 'Query': //return Query only
                    $this->setQryStr($sql);
                    $this->results = $this->executeQry();
                    return $sql;
                    break;
            case 'Row': //return Array as Array[0], Array[1] ...
                    $this->setQryStr($sql);
                    $this->executeQry();
                    return ( $this->results = $this->fetchResultSet() );
                    break;
            case 'SimpleJoin':
                    $sql = "SELECT * FROM "
                        . $table . " WHERE ".USER_KEY_FIELD." = '"
                        . $this->usrValues[USER_KEY_FIELD] . "'"
                        . ($minArg != '' && $column !='' ? " AND $column >= '$minArg'" : '')
                        . ($maxArg != '' && $column !='' ? " AND $column <= '$maxArg'" : '')
                        . ($order != '' ? " ORDER BY $order DESC" : '');
            case 'Simple': //Simple Query
                    $sql = "SELECT * FROM "
                        . $table . " WHERE "
                        . ($minArg != '' && $column !='' ? " $column >= '$minArg'" : '')
                        . ($maxArg != '' && $column !='' ? " AND $column <= '$maxArg'" : '')
                        . ($order != '' ? " ORDER BY $order DESC" : '');
            default:
                    $this->setQryStr($sql);
                    $this->executeQry($sql,true);
                    return ( $this->results = $this->fetchResultSet() );
                    break;
        }
    }
}
//
////$usr = new user($db);
//$usr = new user("MySQL", $dtbs['host'], $dtbs['username'], $dtbs['password'], $dtbs['db'], "");
?>