<?php
/**
 * @author Pappas Evangelos - BagosGiAr - papas.evagelos@gmail.com
 * @copyright 2010
 *
 */

//Basics Includes
require_once ('DataBase.php');

/**
 * Abstract Class For DBObjects Like Users, News, Fors etc
 * Has a $db - Databse - And initiates every Connection to
 * The DataBase...
 * @author Pappas Evangelos - papas.evagelos@gmail.com
 * @copyright 2010
 */
class DBObject
{
    /**
     * @var Database
     */
    public $db;
    /**
     * @var SQL_Server_Type MySQL, MSSQL
     */
    private $dbServer;
    /**
     * @var array Error List Default -> Null
     */
    public $errorList;

    /**
     * Initiates the Conection to the SQL Server
     * @param SQL_Server_Type $dbServer MySQL, MSSQL
     * @param String $dbHost
     * @param String $dbUser
     * @param String $dbPass
     * @param String $dbName
     * @param String $dbPrefix
     */
    function DBObject($dbServer,$dbHost, $dbUser, $dbPass, $dbName, $dbPrefix)
    {
        switch ($dbServer)
        {
            case "MySQL":

                $this->db = new MySQLDB($dbHost, $dbUser, $dbPass, $dbName, $dbPrefix);
                $this->dbServer = $dbServer;

                break;
            case "MSSQL":
                $this->db = new MSSQL($dbHost, $dbUser, $dbPass, $dbName, $dbPrefix);
                $this->dbServer = $dbServer;
                break;
        }
        $this->errorList = array();
    }

    /**
     * Constructor: See $this->DBObject
     * @param SQL_Server_Type $dbServer MySQL, MSSQL
     * @param String $dbHost
     * @param String $dbUser
     * @param String $dbPass
     * @param String $dbName
     * @param String $dbPrefix
     */
    function __construct($dbServer,$dbHost, $dbUser, $dbPass, $dbName, $dbPrefix)
    {
        $this->DBObject($dbServer, $dbHost, $dbUser, $dbPass, $dbName, $dbPrefix);
    }

    /**
     * Sets The Query
     * @param String $qry
     */
    public function setQryStr($qry)
    {
        $this->db->setQryStr($qry);
    }

    /**
     *
     * @param String $qryStr Executes the Query with the given parameter and return the fetched rows
     */
    public function executeQry($qryStr='', $fetched=false)
    {
        return $this->db->exequteQuery($qryStr,$fetched);
    }

    /**
     *
     * @param String $qryStr Executes the Query with the given parameter
     */
    public function executeSimpleQry($qryStr='')
    {
        $this->db->exequteUpdate($qryStr);
    }

    /**
     * Fetches result in the Result Set
     * $this->resultSet[$i]['...'],
     * @return ResultSet $this->resultSet
     */
    public function fetchResultSet()
    {
        $i=0;
        while($result = $this->db->getArr())
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
    public function getInsertedID()
    {
        return $this->db->getInsertedID();
    }

    /**
     * Get number of rows in result set
     * @return int
     */
    public function getRowNum()
    {
        return $this->db->getRowNum();
    }

    /**
     * Get number of affected rows in previous MySQL operation
     * @return array (integer ID )
     */
    public function getAffectedRowNum()
    {
        return $this->db->getAffectedRowNum();
    }

    /**
     * Get thee Status of the DB Connection
     * @return Status
     */
    public function getStatus()
    {
        return $this->db->getStatus();
    }

    /**
     * Gets Error's Value by ID
     * @param ID errorID
     * @return String error's value
     */
    public function getErrorByID($id)
    {
        $returnVal = "";

        foreach($this->errorList as $k => $v)
        {
            if($k == $id)
            {
                $returnVal = $v;
            }
        }
        return $returnVal;
    }

    /**
     * @return Array Whole ErrorList..
     */
    public function getErrorList()
    {
        return $this->errorList;
    }

    /**
     * Store an error value to $this->errorList[]
     * Usage:
     * 0. setError("This error...");
     * 1. setError( array( "101" => "This Error..", "110" => "That Error..." ) )
     * setError( array() ) <== useful for ID Search
     * @param String|array
     */
    public function setError($value)
    {
        if( is_array($value) )
        {
            foreach($value as $k => $v)
            {
                $this->errorList[$k] = $v;
            }
        }
        else
        {
            $this->errorList[] = $value;
        }
    }
}
?>
