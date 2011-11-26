<?php
/**
 * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
 * @copyright 2010
 *
 */

//Basics Includes
require_once ('DataBase.php');

/**
 * A class about MSSQL Connection and Managment Queries Extending a Database Class
 * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
 * @copyright 2009
 * @constructor MSSQL($dbhost, $dbuser, $dbpass, $dbName, $dbprefix)
 *
 */
class MSSQL extends Database
{

    /**
     * Initiate and Creates a Connecytio to the MSSQL Server Acording
     * to Parameters
     * @param String $dbhost
     * @param String $dbuid -> dbuser
     * @param String $dbpass
     * @param String $dbName
     */
    function MSSQL($dbhost, $dbuid, $dbpass, $dbName, $dbprefix)
    {
        parent::__construct($dbhost, $dbuid, $dbpass, $dbName, $dbprefix);
    }

    /**
     * Initiate and Creates a Connecytio to the MSSQL Server Acording
     * to Parameters
     * @param String $dbhost
     * @param String $dbuid -> dbuser
     * @param String $dbpass
     * @param String $dbName
     */
    function  __construct($dbhost, $dbuid, $dbpass, $dbName, $dbprefix)
    {
        $this->MSSQL($dbhost, $dbuid, $dbpass, $dbName, $dbprefix);
    }

    public function connectDB()
    {
        $this->dbLink = mssql_connect($this->dbHost, $this->dbUser, $this->dbPass) or die ("Can't connect to Server: " . $this->show_error() );
        //($this->dbhost, array("UID" =>$this->dbuser, "PWD" => $This->dbpass, "Database"=> $$this->dbName))
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
        mssql_select_db($this->dbName,$this->dbLink) or die($this->show_error());
    }

    public function setError()
    {
        return ( $this->dbError = mssql_get_last_message() );
    }

    public function getError()
    {
        return $this->dbError;
    }

    public function showError()
    {
        print $this->setError();
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
            $this->result=mssql_query($this->qryStr,$this->dbLink) or die($this->showError());

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
            $this->result=mssql_query($this->qryStr,$this->dbLink) or die($this->showError());
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

    }

    /**
     * Fetches the array od the resultSetet
     * @return Array Array of Rows ( String ID )
     */
    public function getArr()
    {
        return mssql_fetch_assoc($this->result);// or die($this->showError());
    }

    /**
     * Gets the array of rows from the Executed query
     * @return array Array of Rows ( int ID )
     */
    public function getRow()
    {
        return mssql_fetch_array( $this->result, MSSQL_NUM) or die($this->showError());
    }

    /**
     * Get number of rows in result set
     * @return int
     */
    public function getRowNum()
    {
        return mssql_num_rows($this->result);
    }

    /**
     * Get number of affected rows in previous MySQL operation
     * @return array (integer ID )
     */
    public function getAffectedRowNum()
    {
        return mssql_rows_affected($this->dbLink);
    }

    /**
     * Get thee Status of the DB Connection
     * @return Status
     */
    public function getStatus()
    {

    }

    /**
     * Free result memory
     */
    public function freeResultSet()
    {
        mssql_free_result($this->result);
        $this->resultSet = array();
        $this->prs = -1; //No real Row
    }

    public function closeDB()
    {
        mssql_close($this->dbLink);
    }
}


?>
