<?php
/**
 * @author Pappas Evangelos - papas.evagelos@gmail.com
 * @copyright 2009
 * @extends class MySQLDB
 * @constructor ChechDB($dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
 *
 */

//Basics Includes
include_once('db.php');

class CheckDB extends MySQLDB
{
    var $err;
    var $checkLink;
    
    function CheckDB($dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
    {
        $this->dbhost=$dbhost;
        $this->dbuser=$dbuser;
        $this->dbpass=$dbpass;
        $this->dbname=$dbname;
        $this->dbprefix=$dbprefix;
        $this->results = array();
        $this->err = array();
        if(!$this->doCheck())
        {
            print_r($this->err);
            die();
        }
    }

    function doCheck()
    {
        if($this->checkConnectDB() && $this->checkDBSelection() /*&& $this->checkTables(6) */)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function checkConnectDB()
    {
        $this->checkLink = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass) or $this->errorHandle();
        return true;
    }

    function checkDBSelection()
    {
        mysql_select_db($this->dbname) or $this->errorHandle();
        return true;
    }

    function checkTables($n)
    {
        $sql="SELECT count(*) as Number from information_schema.tables WHERE table_schema = '".$this->dbname."'";
        $this->quote($sql);
        $arr = $this->getarr();
        if($arr['Number'] == $n)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function errorHandle()
    {
        $this->err[] = mysql_error($this->checkLink);
        return false;
    }

    /**
     * @desc Gets all erros in array
     * @return array
     */
    function getErrors()
    {
        return $this->err;
    }

    /**
     * @desc Gets error by pointer
     * @return uknown
     */
    function getError($p)
    {
        return $this->err[$p];
    }

    /**
     * @desc Gets the last error
     * @return String
     */
    function getLastError()
    {
        //if there are no Errors OR $err has only one Row
        return $this->err[(count($this->err)>0? count($this->err)-1:0)];
    }
}

$checkDB = new CheckDB($dtbs['host'], $dtbs['username'], $dtbs['password'], $dtbs['db'], $dtbs['prefix']);
?>
