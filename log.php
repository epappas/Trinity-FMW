<?php


//Basics Includes
require_once('db.php');

/**
 * @author GiAr - papas.evagelos@gmail.com
 * @copyright 2009
 * @extends class MySQLDB
 * @constructor log($dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
 *
 */
class Log extends MySQLDB
{
    var $logValues;
    var $err;

    /**
     * @param String $dbhost DB Host (eg localhost)
     * @param String $dbuser The user to access DB
     * @param String $dbpass the Pass, use "" if not exists
     * @param String $dbname The Name of the DB who will be used
     * @param String $dbprefix If there is a prefix in the Tables - Use "" If not Exists
     */
    public function Log($dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
    {
        $this->dbhost=$dbhost;
        $this->dbuser=$dbuser;
        $this->dbpass=$dbpass;
        $this->dbname=$dbname;
        $this->dbprefix=$dbprefix;
        $this->results = array();
        $this->connectdb();
        $this->selectdb();
        $this->logValues = array();
        $this->err = array();
    }

    /**
     * Adds new stat into Log
     * @return Boolean
     * @param ID $testid
     * @param username $username
     * @param <type> $rightAnsw
     * @param <type> $wrongAnsw
     */
    public function setLog($testid,$username,$rightAnsw,$wrongAnsw)
    {
        //gets user_id - Check if User exists
        $sql="SELECT `USER_ID` "
            ." FROM `user`"
            ." WHERE `USER_AEM` = "
            ."'".$username."'";
        $this->query($sql);
        $this->results = $this->getarr();
        if(count($this->results)>0 && !empty($testid))
        {
            $sql="INSERT INTO `test_log`"
                ."(`USER_ID`,`TEST_ID`,`LOG_RGHT_ANSWERS`,`LOG_WRONG_ANSWERS`,`LOG_AVERAGE`,`LOG_DATETIME`) "
                ." VALUES "
                ."('".$this->results['USER_ID']."', "
                ."'".$testid."', "
                ."'".$rightAnsw."', "
                ."'".$wrongAnsw."', "
                ."'".$this->calcAverage($rightAnsw, $wrongAnsw)."', "
                ."'".$this->calcDay()."')";
            $this->query($sql);
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     *
     * @param ID $userID
     * @return Boolean
     */
    public function getLogByUserID($userID)
    {
        $sql="SELSECT * FROM `test_log` WHERE `USER_ID` = ".$userID;
        $this->query($sql);
        $this->results = $this->getarr();
        if(count($this->results)>0)
        {
            return true;
        }
        else
        {
            $this->err[] = "No Log for this User";
            return false;
        }
    }

    /**
     *
     * @param ID $testID
     * @return Boolean
     */
    public function getLogByTestID($testID)
    {
        $sql="SELSECT * FROM `test_log` WHERE `TEST_ID` = ".$testID;
        $this->query($sql);
        $this->results = $this->getarr();
        if(count($this->results)>0)
        {
            return true;
        }
        else
        {
            $this->err[] = "No Log for this Test";
            return false;
        }
    }

    /**
     * Returns Formated DayTime like 9999-12-31 23:59:59
     * @description Calculate Greek DayTime
     * @return DayTime
     */
    public function calcDay()
    {
        date_default_timezone_set('GMT+2');
        return date("Y-m-j G:i:s");
    }
}

$log = new Log($dtbs['host'], $dtbs['username'], $dtbs['password'], $dtbs['db'], $dtbs['prefix']);
?>
