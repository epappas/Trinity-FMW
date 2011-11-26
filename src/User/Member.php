<?php
//Basics Includes
require_once('User.php');

/**
 *
 * @extends class User
 * @constructor Member($dbServer, $dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
 * @author Pappas Evangelos - papas.evagelos@gmail.com
 * @copyright 2010
 *
 */
class Member extends User
{
    //put your code here
    public function doCheckLogin($username, $password) 
    {

    }

    public function doRegister($username, $userpass, $userfname, $userlname, $priv = "")
    {

    }

    public function doRegisterLatestSession()
    {

    }

    public function doRenewStats($usr = '', $pass = '')
    {
        
    }

    public function getUserStats($usrid) 
    {
        
    }
}
?>
