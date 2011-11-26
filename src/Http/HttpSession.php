<?php
/**
 * @author GiAr - papas.evagelos@gmail.com
 * @copyright 2010
 *
 */

/**
 * Description of HttpSession
 *
 * @author Pappas Evangelos - papas.evagelos@gmail.com
 * @copyright 2010
 * @constructor MySQLDB($dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
 */
class HttpSession
{

    private $data;
    private $sessionID;
    private $expirationMinutes; // = 3600; // TIME TO MAINTAIN DATA ON DB
    private $sessionDBTD;

    
    /**
     *
     * @global array $SESSION Sessions Data
     * @param Database $db
     * @param String sessionID a fixed SessionID ( not hashed )
     * @param String $sessionDBTable the Database's Table for the Sessions
     * @param int $expirMin minutes for a session expiration
     */
    public function __construct( $db, $sessionID=null, $sessionDBTable=null, $expirMin=null )
    {
        global $SESSION;

        if (isset($_COOKIE['sessionID']))
        {
            $this->sessionID = $_COOKIE['sessionID'];
        }
        else
        {
            $this->sessionID = md5(microtime() . rand(1, 9999999999999999999999999)); // GENERATE A RANDOM ID

            setcookie('sessionID', $this->sessionID);

            $sql = "INSERT INTO " . $this->sessionDBTD . " (sessionID, updatedTime) "
                    . "VALUES ('{$this->sessionID}', NOW())";
                
            mysql_query($sql);
        }

        $sql = "SELECT value FROM " . $this->sessionDBTD
                . " WHERE sessionID='{$this->sessionID}'";
        $query = mysql_query($sql);

        $this->data = unserialize(mysql_result($query, 0, 'value'));
        $SESSION = $this->data;
    }

    /**
     * A function that excecute a DB query for expiring SESSION's time effect
     * @return <type>
     */
    private function expire()
    {        
        $sql = "DELETE FROM " . $this->sessionDBTD
                . " WHERE updatedTime <= '"
                . date("Y-m-d H:i:s", time() - 60 * $this->expirationMinutes) . "'";
        
        mysql_query($sql);
        return true;
    }

    /**
     *
     * @global array $SESSION 
     */
    public function __destruct()
    {
        global $SESSION;

        $this->data = serialize($SESSION);

        $sql = "UPDATE " . $this->sessionDBTD . " "
                . "SET value='{$this->data}', "
                . "updatedTime=NOW() "
                . "WHERE sessionID='{$this->sessionID}'";
            
        mysql_query($sql);

        $this->expire();
    }
}

?>
