<?php

//Basics Includes
require_once('../Database/DBObject.php');

/**
 * 
 * @extends class DBObject
 * @constructor User($dbServer, $dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
 * @author Pappas Evangelos - papas.evagelos@gmail.com
 * @copyright 2010
 *
 */
class User extends DBObject
{
    /**
     * Stores all User Values IF is logged in
     */
    var $userValues = array();
    var $ip;
    var $host;
    var $date;
    
    /**
     * 
     * @param String $dbhost DB Host (eg localhost)
     * @param String $dbuser The user to access DB
     * @param String $dbpass the Pass, use "" if not exists
     * @param String $dbname The Name of the DB who will be used
     * @param String $dbprefix If there is a prefix in the Tables - Use "" If not Exists
     */
    function __construct($dbServer,$dbHost, $dbUser, $dbPass, $dbName, $dbPrefix)
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
     * @param userName $username
     * @param password $userpass
     * @param String $userfname
     * @param String $userlname
     * @return bool
     */
    abstract function doRegister($username,$userpass,$userfname,$userlname,$priv="");

    /**
     * @description Ceck && LogIn user
     * @return Boolean
     * @param UserName $username
     * @param Password $password
     */
    abstract function doCheckLogin($username, $password);

    /**
     * Just Renew the userValues Values of the user in case they might have change..!
     * @param Username $usr
     * @param Password $pass
     * @return boolean
     */
    abstract function doRenewStats($usr='', $pass='');

    /**
     * Checks with userid & Retrieves all User's vales ( if exist )
     * Store them to Database::resultSet
     * @param ID $usrid
     */
    abstract function getUserStats($usrid);

    /**
     *
     * @tutorial Seeks if user really exists
     * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
     */
    abstract function getUserExistance($userKey);

    /**
     * Register-Updates Latest session_id() to User
     * if user is Loged-in
     */
    abstract function doRegisterLatestSession();

    /**
     * Gets Host of client via IP
     * @return String $_SERVER['REMOTE_ADDR'] Host
     */
    function setUserHost()
    {
        return $this->host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    }

    /**
     * Adds or Sets to $_SESSION array Values
     * e.g. setSession( array( "pThis" => "vThat" ) );
     * ===>> $_SESSION['pThis'] <=> "vThat"
     * @param ArrayOfValues $values 
     */
    function setSession(&$values)
    {
        foreach($values as $k => $v)
        {
            $_SESSION[$k] = $v;
        }
        $_SESSION['IP'] = $this->ip;
        $_SESSION['HOST'] = $this->host;
        $_SESSION['DATE'] = $this->date;
    }

    /**
     * Retrns whole $_SESSION's data in an Array
     * @return Array
     */
    function getSession()
    {
        return $_SESSION;
    }

    /**
     * Log out the user, an delete alla Session Data, Renew session
     * Mark User Loged out..
     * @param String $username
     * @param ID $id
     * @return bool
     */
    function doLogout($username=null, $id=null)
    {
        session_start();
        session_unset();
        session_destroy();
        unset($_SESSION);
        session_write_close();
        $_SESSION = array();
        return true;
    }

    /**
     * Initialize session data
     * @return bool Returns true if session was started with success otherwise false
     */
    function doStartSession()
    {
        return session_start();
    }
    
    /**
     * Check Session if is already set
     * @return int
     */
    function doCheckSession()
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
}
?>