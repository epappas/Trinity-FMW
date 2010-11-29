<?php

/**
 * A Class that Generaly Describes each HttpRequest
 * @author Pappas Evangelos - papas.evagelos@gmail.com
 * @copyright 2010
 * @constructor HttpRequest($method,$protocol,$url,$port=null,$path="",$file="",$data=array(),$optHeaders = array(),$userName="",$password="")
 */
abstract class HttpRequest extends HttpObject
{
    /**
     * Content Data
     * @var Array|String
     */
    public $data;
    
    private $protocol;
    private $url;
    private $port;
    private $path;
    private $file;
    private $method;
    private $optHeaders;
    private $userName;
    private $password;
    private $httpParms;
    private $error;

    /**
     *
     * @param String $protocol
     * @param String $url
     * @param int $port
     * @param String $path
     * @param String $file
     * @param String $method
     * @param Array $data
     * @param Array $optional_headers
     * @param String $userName
     * @param String $password
     */
    function  __construct(
            $method,
            $protocol,
            $url,
            $port=null,
            $path="",
            $file="",
            $data=array(),
            $optHeaders = array(),
            $userName="",
            $password="")
    {
        $this->protocol = strtolower( ( empty($protocol)? "http" : $protocol ) );
        //remove the last ( / ) if exist
        $this->url = ( $url[strlen($url)-1] == "/" ? $url[strlen($url)-1] == "" : "" ).$url;
        $this->port = ( ( $this->protocol == "http" && empty($port) )? "80" : $port)  ;
        //adds the first and last ( / ) if not exist e.g /user/webpage/
        $this->path =  (!empty($path) && $path[0] != "/" ? "/" : "" ). $path . (!empty($path) && $path[strlen($path)-1] != "/" ? "/" : "" );
        $this->file = $file;
        $this->method = strtoupper($method);
        $this->userName = $userName;
        $this->password = $password;

        $this->data = array();
        foreach($data as $k => $v)
        {
            $this->data[$k] = $v;
        }
        
        foreach($optHeaders as $k => $v)
        {
            $this->optHeaders[$k] = $v;
        }
    }

    /**
     * @param Array $data
     */
    function addData($data)
    {
        if(is_array($data))
        {
            foreach($data as $k => $v)
            {
                $this->data[$k] = $v;
            }
        }
    }

    /**
     * @param String $varName The Name of the Variable Pointer of the Array
     * @param String $data The Value of this row
     */
    function addData($varName, $data)
    {
        $tehmp=array();
        $tehmp[$varName] = $data;
        $this->addData($tehmp[$varName]);
    }

    /**
     * Converts $this->data Array to an encoded-URL-String
     * @return String encoded-URL
     */
    function convertDataToURL()
    {
        $postdata="";
        foreach($this->data as $k=>$v)
        {
            $postdata.= $k . "=" . urlencode($v) . "&";
        }
        return $postdata;
    }

    /**
     * Generate A Whole URL with the given parametrs
     * @param boolean $alsoRealData to include in the URL Given Data
     * @return String
     */
    function getFullURL($alsoRealData=false)
    {
        return
            (empty($this->protocol)? "http://" : $this->protocol . "://") .
            (!empty($this->userName)? $this->userName . ":". (!empty($this->password)? $this->password : ""). "@" : "") .
            $this->url .
            (!empty($this->port)? ":" . $this->port : "") .
            (!empty($this->path)? $this->path . (!empty($this->file)? $this->file : "") : "") .
            ($alsoRealData ? "?".$this->convertDataToURL() : "");
    }

    abstract function setHeader($headerParam);

    abstract function connect();

    abstract function close();

    abstract function getError();

    abstract function setError($err);
}
?>
