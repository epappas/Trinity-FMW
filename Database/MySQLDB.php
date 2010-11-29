<?php
/**
 * @author GiAr - papas.evagelos@gmail.com
 * @copyright 2010
 *
 */
//Basics Includes
//require_once('include/Config/dbinfo.php'); //DB infoes
//require_once ('include/Database/DataBase.php');

/**
 * A class about MySQL Connection and Managment Queries Extending a Database Class
 * @author Pappas Evangelos - papas.evagelos@gmail.com
 * @copyright 2010
 * @constructor MySQLDB($dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
 *
 */
class MySQLDB extends Database
{
    /**
     * Initiate and Creates a Connecytio to the MySQL Server Acording
     * to Parameters
     * @param String $dbhost
     * @param String $dbuser
     * @param String $dbpass
     * @param String $dbname
     * @param String $dbprefix
     */
    function  MySQLDB($dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
    {
        parent::__construct($dbhost, $dbuser, $dbpass, $dbname, $dbprefix);
        $this->connectDB();
        $this->selectDB();
    }

    /**
     * Initiate and Creates a Connecytio to the MySQL Server Acording
     * to Parameters
     * @param String $dbhost
     * @param String $dbuser
     * @param String $dbpass
     * @param String $dbname
     * @param String $dbprefix
     */
    function  __construct($dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
    {
        $this->MySQLDB($dbhost, $dbuser, $dbpass, $dbname, $dbprefix);
    }

    /**
     * A method that Unsets and Close the DB Connection
     */
//    function  __destruct()
//    {
//        parent::__destruct();
//    }

    public function connectDB()
    {
        $this->dbLink = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass) or die($this->getError());
    }

    /**
     * Selects the Databace
     * @param String $dbName The Name of the Database to Connect
     */
    public function selectDB($dbName="")
    {
        if(!empty ($dbName))
        {
            $this->dbName = $dbName;
        }
        mysql_select_db($this->dbName,$this->dbLink) or die($this->getError());
    }

    public function setError()
    {
        $this->dbError = mysql_error($this->dbLink);
    }

    public function getError()
    {
        return $this->dbError;
    }

    public function showError()
    {
        print $this->getError();
    }

    /**
     * Sets and exequtes the Query to the DB Server
     * @param String $qry
     * @param bool $fetchRS a Switcher if is Needed or Not to fetch resultSet with Row Data - default: true
     * @return null|resultSet
     */
    public function exequteQuery($qry="", $fetchRS = true)
    {

        if(!empty($qry))
        {
            $this->setQryStr($qry);
        }

        if(empty($this->qryStr)) //if $this->setQryStr is already called
        {
            die("Error: Query string is empty.");
            return null;
        }
        else
        {
            $this->result=mysql_query($this->qryStr,$this->dbLink) or die($this->getError());
            if($fetchRS)
            {
                return $this->fetchResultSet();
            }
            else
            {
                return $this->getArr();
            }
        }
    }

    /**
     * Executes a Query without returning avalue.
     * Usefull for Creation, Insertion, and Deletion Queries.
     * @param String $qry
     */
    public function exequteUpdate($qry="")
    {
        if(!empty($qry))
        {
            $this->setQryStr($qry);
        }

        if(empty($this->qryStr)) //if $this->setQryStr is already called
        {
            die("Error: Query string is empty.");
            return null;
        }
        else
        {
            $this->result=mysql_query($this->qryStr,$this->dbLink) or die($this->getError());
        }
    }

    /**
     * Executes a Function Stored in DB Server
     * @param String $func -> function's Name to be executed
     */
    public function executeFunction($func)
    {

    }

    /**
     * Returns The last ID inserted
     * @return row The last ID Inserted
     */
    public function getInsertedID()
    {
        return mysql_insert_id($this->dbLink);
    }

    /**
     * Gets the array of rows from the Executed query
     * @return array Array of Rows ( int ID )
     */
    public function getRow()
    {
        return mysql_fetch_row($this->result);
    }

    /**
     * Get number of rows in result set
     * @return int
     */
    public function getRowNum()
    {
        return mysql_num_rows($this->result);
    }

    /**
     * Fetches the array od the resultSetet
     * @return Array Array of Rows ( String ID )
     */
    public function getArr()
    {
        return mysql_fetch_array($this->result,MYSQL_ASSOC);
    }

    /**
     * Get number of affected rows in previous MySQL operation
     * @return array (integer ID )
     */
    public function getAffectedRowNum()
    {
        return mysql_affected_rows($this->dbLink);
    }

    /**
     * Get thee Status of the DB Connection
     * @return Status
     */
    public function getStatus()
    {
        return mysql_stat($this->dbLink);
    }

    /**
     * Free result memory
     */
    public function freeResultSet()
    {
        //mysql_free_result($this->resultSet);
        $this->resultSet = array();
        $this->prs = -1; //No real Row
    }

    public function closeDB()
    {
        mysql_close($this->dbLink);
    }
}
?>