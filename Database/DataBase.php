<?php
/**
 * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
 * @copyright 2010
 *
 */

//Basics Includes
//---

/**
 * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
 * @copyright 2009
 * @constructor Database($dbHost, $dbUser, $dbPass, $dbName, $dbPrefix)
 */
abstract class Database
{
    var $dbHost;
    var $dbUser;
    var $dbPass;
    var $dbName;
    var $dbPrefix;
    var $dbLink;
    var $qryStr;
    var $result;
    var $resultSet;
    var $dbError;
    /**
     * @var int* ResultSet Pointer
     */
    private $prs;


    /**
     * Initiate and Creates a Connecytio to the SQL Server Acording
     * to Parameters
     * @param String $dbHost
     * @param String $dbUser
     * @param String $dbPass
     * @param String $dbName
     * @param String $dbPrefix
     */
    function Database($dbHost, $dbUser, $dbPass, $dbName, $dbPrefix)
    {
        $this->dbHost=$dbHost;
        $this->dbUser=$dbUser;
        $this->dbPass=$dbPass;
        $this->dbName=$dbName;
        $this->dbPrefix=$dbPrefix;
        $this->resultSet = array();
        $this->prs = -1; //No Real row
    }

    /**
     * Constructor: See $this->Database
     * @param Sting $dbHost
     * @param Sting $dbUser
     * @param Sting $dbPass
     * @param Sting $dbName
     * @param Sting $dbPrefix
     */
    function __construct($dbHost, $dbUser, $dbPass, $dbName, $dbPrefix)
    {
        $this->Database($dbHost, $dbUser, $dbPass, $dbName, $dbPrefix);
    }

    /**
     * A method that Unsets and Close the DB Connection
     */
//    function  __destruct()
//    {
//        $this->closeDB();
//        $this->freeResultSet();
//    }

    /**
     * This Method is used to connect PHP with the Server
     */
    abstract public function connectDB();

    /**
     * Selects the Databace
     * @param String $dbName The Name of the Database to Connect
     */
    abstract public function selectDB($dbName="");

    /**
     * Sets Error Feedback
     */
    abstract public function setError();

    /**
     * Returns the text of the error message from previous SQL operation
     * @return String
     */
    abstract public function getError();

    /**
     * Prints Databace FeedBack
     */
    abstract public function showError();

    /**
     * Sets and exequtes the Query to the DB Server
     * @param String $qry
     * @param bool $fetchRS a Switcher if is Needed or Not to fetch resultSet with Row Data - default: true
     * @return null|resultSet
     */
    abstract public function exequteQuery($qry="", $fetchRS = true);

    /**
     * Executes a Query without returning avalue.
     * Usefull for Creation, Insertion, and Deletion Queries.
     * @param String $qry
     */
    abstract public function exequteUpdate($qry="");

    /**
     * Executes a Function Stored in DB Server
     * @param String $func -> function's Name to be executed
     */
    abstract public function executeFunction($func);

    /**
     * Sets The Query
     * @param String $qry
     */
    public function setQryStr($qry,$quted=false)
    {
        $this->qryStr = ($quted ? $this->quote($qry) : $qry );
    }

    /**
     * Fetches result in the Result Set
     * $this->resultSet[$i]['...'],
     * @return ResultSet $this->resultSet
     */
    public function fetchResultSet()
    {
        $i=0;
        while($result = $this->getArr())
        {
            $this->resultSet[$i++]=$result;
        }
        return $this->resultSet;
    }

    /**
     * @return bool true if The Pointer Shows a Real Existed Row in the resultSet
     */
    public function hasNext()
    {
        if($this->prs < count($this->resultSet))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Gets the Next Row of the ResultSet as an array
     * eg .. array['<entity name>'] => $value
     * @return array|null the Next row - Null if Don't have next
     */
    public function getNext()
    {
        if($this->hasNext())
        {
            return $this->resultSet[ $this->prs++ ];
        }
        else
        {
            return null;
        }
    }

    /**
     * Gets a Specific row or value in the ResultSet
     * @param int $id the Specify ID of the ResultSet - Null if ID = Fake
     * @param String $entityName Points the Colum of the Specify Row - Null if $entityName = Fake
     * @return array|String|null
     */
    public function getResultSet($id, $entityName="")
    {
        if(abs($id) < count($this->resultSet) ) //if ID-row exist
        {
            if(!empty ($entityName))
            {
                return $this->resultSet[$id][$entityName];
            }
            else
            {
                return $this->resultSet[$id];
            }
        }
        else
        {
            return null;
        }
    }

    /**
     * Returns The last ID inserted
     * @return row The last ID Inserted
     */
    abstract public function getInsertedID();

    /**
     * Gets the array of rows from the Executed query
     * @return array Array of Rows ( int ID )
     */
    abstract public function getRow();

    /**
     * Get number of rows in result set
     * @return int
     */
    abstract public function getRowNum();

    /**
     * Fetches the array od the resultSetet
     * @return Array Array of Rows ( String ID )
     */
    abstract public function getArr();

    /**
     * Get number of affected rows in previous MySQL operation
     * @return array (integer ID )
     */
    abstract public function getAffectedRowNum();

    /**
     * Get thee Status of the DB Connection
     * @return Status
     */
    abstract public function getStatus();

    /**
     * Free result memory
     */
    abstract public function freeResultSet();

    /**
     *Close Database's connection
     */
//    abstract public function closeDB();

    /**
     * Uses to add the Table's Prefix (if ther's one )
     * @param String $tablename DB-TableName
     * @return String $this->dbprefix."_".$tablename - The table's name with the Prefix
     */
    public function tbPrefix($tablename)
    {
        if(empty($this->dbPrefix))
        {
            return $tablename;
        }
        else
        {
            return $this->dbPrefix."_".$tablename;
        }
    }

    /**
     * Makes String-SQL-Script to LowerCase
     * @param String $str
     * @return sql
     */
    public function quote(&$str)
    {
        return addslashes( strtolower(&$str) );
    }
}
