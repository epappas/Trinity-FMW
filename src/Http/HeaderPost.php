<?php

/**
 * 
 * @author Pappas Evangelos - papas.evagelos@gmail.com
 * @copyright 2010
 * @constructor HttpPost()
 */
class HeaderPost extends Header
{

//$post_data = 'var1=123&var2=456';
//$content_length = strlen($post_data);
//
//header('POST /test/test.php HTTP/1.1');
//header('Host: localhost');
//header('Connection: close');
//header('Content-type: application/x-www-form-urlencoded');
//header('Content-length: ' . $content_length);
//header('');
//header($post_data);
//
//exit();
    
    var $postData=array();
    var $postHeader=array();

    public function __construct( $host, $datAr)
    {
        
    }

    public function __construct()
    {
        
    }

    public function setContentType($v)
    {

    }

    public function setConnection( $v )
    {
        
    }

    public function setHTTPMessage( $v )
    {
        
    }

    public function setExitOnClose( $v )
    {
        
    }

    public function setDatAr( $v )
    {
        
    }
}
?>
