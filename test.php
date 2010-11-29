<?php
/**
 * @author GiAr - papas.evagelos@gmail.com
 * @copyright 2009
 * @extends class MySQLDB 
 * @constructor test($dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
 * 
 */

//Basics Includes
include_once('db.php');

class Test extends MySQLDB
{
    var $test_values;
    var $err;

    /**
     *
     * @param <type> $dbhost
     * @param <type> $dbuser
     * @param <type> $dbpass
     * @param <type> $dbname
     * @param <type> $dbprefix
     */
    function Test($dbhost, $dbuser, $dbpass, $dbname, $dbprefix)
    {
        $this->dbhost=$dbhost;
        $this->dbuser=$dbuser;
        $this->dbpass=$dbpass;
        $this->dbname=$dbname;
        $this->dbprefix=$dbprefix;
        $this->results = array();
        $this->connectdb();
        $this->selectdb();
        $this->test_values = array();
        $this->err = array();
    }

    /**
     * @description Sets a new Test
     * @return -
     * @param <type> $title
     * @param <type> $category
     * @param <type> $helpReference
     * @return <type>
     */
    function addTest($title,$category,$union,$helpReference)
    {
        if(empty($title) && empty ($category) && empty ($helpReference))
        {
            return false;
        }
        else
        {
            $sql="INSERT INTO `test`"
                ."(`TEST_TITLE`,`TEST_CATEGORY`,`TEST_UNION`,`TEST_REFERENCE_HELP`)"
                ." VALUES "
                ."('".$title."', "
                ."'".$category."', "
                ."'".$union."', "
                ."'".$helpReference."')";
            $this->query($sql);
            return true;
        }
    }

    /**
     * @description Sets a new Test
     * @return -
     * @param <type> $testID
     * @param <type> $questionCategory
     * @param <type> $arp
     * @return <type>
     */
    function addQuestion($testID,$questionCategory,$arp)
    {
        switch($questionCategory)
        {
            case "MultiChoise":
                $this->getMQ($testID,$arp);
                break;
            case "RightWrong":
                $this->getRWQ($testID,$arp);
                break;
            case "Puzzle":
                $this->getPuzzleQ($testID,$arp);
                break;
            default:
                return false;
        }
    }

    /**
     * @description Sets a new Multiple Choise Question by TEST_ID
     * @return -
     * @param <type> $testID
     * @param <type> $arp
     * @return <type>
     */
    function addMultiChoiseQuestion($testID,$arp)
    {
        $sql="INSERT INTO `QUESTION_MULTIPLE_CHOISE`"
            ."(`TEST_ID`,`MQ_TITLE`,`MQ_TEXT`,`MQ_RIGHT`,`MQ_DUMMY1`,`MQ_DUMMY2`,`MQ_DUMMY3`)"
            ." VALUES "
            ."('".$testID."', "
            ."'".$arp['mq_title']."' "
            ."'".$arp['mq_text']."' "
            ."'".$arp['mq_right']."' "
            ."'".$arp['mq_dummy1']."' "
            ."'".$arp['mq_dummy2']."' "
            ."'".$arp['mq_dummy3']."')";
        $this->query($sql);
        return true;
    }

    /**
     * @description Sets a new Right-Wrong Question by TEST_ID
     * @return -
     * @param <type> $testID
     * @param <type> $arp
     * @return <type>
     */
    function addRightWrongQuestion($testID,$arp)
    {
        $sql="INSERT INTO `QUESTION_MULTIPLE_CHOISE`"
            ."(`TEST_ID`,`RWQ_TITLE`,`RWQ_TEXT`,`RWQ_RIGHT`,`RWQ_WRONG`)"
            ." VALUES "
            ."('".$testID."', "
            ."'".$arp['rwq_title']."' "
            ."'".$arp['rwq_text']."' "
            ."'".$arp['rwq_right']."' "
            ."'".$arp['rwq_wrong']."')";
        $this->query($sql);
        return true;
    }

    /**
     * @description Sets a new Puzzle Question by TEST_ID
     * @return -
     * @param <type> $testID
     * @param <type> $arp
     * @return <type>
     */
    function addPuzzleQuestion($testID,$arp)
    {
        $sql="INSERT INTO `QUESTION_MULTIPLE_CHOISE`"
            ."(`TEST_ID`,`PUZZLEQ_TITLE`,`PUZZLEQ_TEXT`,`PUZZLEQ_Q1`,`PUZZLEQ_LINK_TO`,`PUZZLEQ_Q2`,`PUZZLEQ_LINK_TO2`,`PUZZLEQ_Q3`,`PUZZLEQ_LINK_TO3`,,`PUZZLEQ_Q4`,`PUZZLEQ_LINK_TO4`)"
            ." VALUES "
            ."('".$testID."', "
            ."'".$arp['puzzleq_title']."' "
            ."'".$arp['puzzleq_text']."' "
            ."'".$arp['puzzle_q1']."' "
            ."'".$arp['puzzle_link_to']."' "
            ."'".$arp['puzzle_q2']."' "
            ."'".$arp['puzzle_link_to2']."' "
            ."'".$arp['puzzle_q3']."' "
            ."'".$arp['puzzle_link_to3']."' "
            ."'".$arp['puzzle_q4']."' "
            ."'".$arp['puzzle_link_to4']."' )";
        $this->query($sql);
        return true;
    }

    /**
     * @description Gets on Test by ID
     * @return Boolean
     * @param <type> $testID
     * @return <type>
     */
    function getTestByID($testID)
    {
        $sql="SELSECT * FROM `test` WHERE `TEST_ID` = ".$testID;
        $this->query($sql);
        $this->results = $this->getarr();
        if(count($this->results)>0)
        {
            return true;
        }
        else
        {
            $this->err[] = "Not Such Test Exists";
            return false;
        }
    }

    /**
     * @description Search Test By title
     * @description Sets MySQL::results
     * @return Boolean
     * @param <type> $testTitle
     * @return <type>
     */
    function getTestByTitle($testTitle)
    {
        $sql="SELSECT * FROM `test` WHERE `TEST_TITLE` = ".$testTitle;
        $this->query($sql);
        $this->results = $this->getarr();
        if(count($this->results)>0)
        {
            return true;
        }
        else
        {
            $this->err[] = "Not Such Title Exists";
            return false;
        }
    }

    /**
     * @description Gets all test by a specific Union of Content
     * @return Boolean
     * @param <type> $testUnion
     * @return <type>
     */
    function getTestByUnion($testUnion)
    {
        //Returns $this->results as a Double array Each 1st D. array has 1 row in the 2nd D. array
        $this->showcontent('test','TEST_UNION',$testUnion,'TEST_UNION','Simple');
        
        if(count($this->results)>0)
        {
            return true;
        }
        else
        {
            $this->err[] = "Not Such Union Exists";
            return false;
        }
    }

    /**
     * @description Returns Help text from a test
     * @return text
     * @param <type> $testID
     * @return <type>
     */
    function getReferenceHelpByID($testID)
    {
        if($this->getTestByID($testID))
        {
            return $this->results['TEST_REFERENCE_HELP'];
        }
        else
        {
            return false;
        }
    }

    /**
     * @description Get all Question By the TestCategory
     * @sets MySQLDB::results
     * @param <type> $testID
     * @param <type> $testCat
     * @return <type>
     */
    function getQuestionByCategory($testID,$testCat)
    {
        switch($testCat)
        {
            case "MultiChoise":
                $this->getMQ($testID);
                break;
            case "RightWrong":
                $this->getRWQ($testID);
                break;
            case "Puzzle":
                $this->getPuzzleQ($testID);
                break;
            default:
                return false;
        }
    }

    /**
     * Gets all Multi Choise Questions from a Test
     * @sets MySQLDB::results
     */
    function getMQ($testID)
    {
        $this->showcontent('question_multiple_choise', 'TEST_ID', $testID);
    }
    
    /**
     * Gets all Righ Wrong Questions from a Test
     * @sets MySQLDB::results
     */
    function getRWQ($testID)
    {
        $this->showcontent('question_right_wrong', 'TEST_ID', $testID);
    }

    /**
     * Gets all Puzzle Questions from a Test
     * @sets MySQLDB::results
     */
    function getPuzzleQ($testID)
    {
        $this->showcontent('question_puzzle', 'TEST_ID', $testID);
    }

    /**
     * @desc Set a new Error
     * @return -
     */
    function setError($msg)
    {
        $this->err[] = $msg;
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

$test = new Test($dtbs['host'], $dtbs['username'], $dtbs['password'], $dtbs['db'], $dtbs['prefix']);
?>
