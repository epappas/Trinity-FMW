<?php

//Basics Includes
require_once('user.php');

/**
 * @extends class MySQLDB
 * @constructor User($dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
 * @author GiAr - papas.evagelos@gmail.com
 * @copyright 2009
 *
 */
class Moderator extends User
{
    /**
     *
     * @param String $dbhost DB Host (eg localhost)
     * @param String $dbuser The user to access DB
     * @param String $dbpass the Pass, use "" if not exists
     * @param String $dbname The Name of the DB who will be used
     * @param String $dbprefix If there is a prefix in the Tables - Use "" If not Exists
     */
    public function __construct($dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
    {
        //super() Call...
        parent::__construct($dbhost, $dbuser, $dbpass, $dbname, $dbprefix); 
    }

    public function setUser($username,$userpass,$userfname,$userlname,$priv="")
    {
        //Check if User Exists
        $sql = "SELECT *
            FROM `user` WHERE "
            . "`USER_AEM` = '"
            . $username ."' ";
        $this->query($sql);
        if(empty($username) || empty($userpass) || empty($userfname) || empty($userlname))//if No argumens are given
        {
            return false;
        }
        elseif($this->getrownum()>0) //If getrownum return 0 Value -> No Such user Exists
        {
            return false;
        }
        else //If Not -> Add a new user
        {
            $sql="INSERT INTO `user` "
                . "(`USER_AEM`,`USER_PASS`,`USER_FIRSTNAME`,`USER_LASTNAME`,`USER_PRIVILAGES`)"
                ." VALUES "
                ."('".$username."', "
                ."'".$userpass."', "
                ."'".$userfname."', "
                ."'".$userlname."', "
                .(empty($priv)? "'0')":"'".$priv."')");
            $this->query($sql);
            return true;
        }
    }

    public function setUserHost()
    {
        return $this->host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    }

    private function setSession(&$values)
    {
        foreach($values as $k => $v)
        {
            $_SESSION[$k] = $v;
        }
        $_SESSION['IP'] = $this->ip;
        $_SESSION['HOST'] = $this->host;
        $_SESSION['DATE'] = $this->date;
    }

    public function doCheckLogin($username, $password)
    {
        $this->doRenewStats($username,$password);
        if( count($this->userValues['USER_ID'])>0 )
        {
            $this->setSession($this->userValues); // Set a Session in Server Side Location For Security Issues
            //print_r($this->userValues); //Debugging
            return true;
        }
        else
        {
            $this->logout($username, $this->userValues['USER_ID']);
            return false;
        }
    }

    public function doRenewStats($usr='', $pass='')
    {
        if(($usr!='') && ($pass!=''))
        {
            $sql = "SELECT *
                FROM `user` WHERE "
                . "`USER_AEM` = '"
                . $usr ."' "
                . "AND `USER_PASS` = '"
                . $pass . "'";
            $this->query($sql);
        }
        elseif($usr!='')
        {
            $sql = "SELECT `USER_FIRSTNAME`,`USER_LASTNAME`
                FROM `user` WHERE "
                . "`USER_ID` = '"
                . $usr . "'";
            $this->query($sql);
        }
        else
        {
            $sql = "SELECT *
                FROM `user` WHERE "
                . "`USER_ID` = '"
                . $_SESSION['USER_ID'] . "'";
            $this->query($sql);
        }

        $this->userValues = $this->getarr();

        return (count($this->userValues)>0? true : false);
    }
    
    public function doLogout($username=null, $id=null)
    {
        session_start();
        session_unset();
        session_destroy();
        unset($_SESSION);
        session_write_close();
        $_SESSION = array();
        return true;
    }

    public function getUserStats($usrid)
    {
        $sql = 
            "SELECT `USER_FIRSTNAME`,`USER_LASTNAME`
            FROM `user` WHERE "
            . "`USER_AEM` = '"
            . $usrid . "'";
        
        $this->query($sql);
        $this->results = $this->getarr();
        return (count($this->results)>0? true : false);
    }
    
    public function doChecksession()
    {
        if(session_id()==NULL)
        {
            session_start();
            return true;
        }
    }
}

$user = new User($dtbs['host'], $dtbs['username'], $dtbs['password'], $dtbs['db'], $dtbs['prefix']);
?>