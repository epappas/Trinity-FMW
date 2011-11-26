<?php
/**
 * This Class is designed nad developed to make simple appending string
 * Appending strings with "." is very slow, so this class uses sprint function
 * to concat strings on lower level that will give moe ms to application
 *  
 * Now you can pass your strings like objects.
 * 
 * @version 0.0.0
 * @author Luna.com |Senad Meškin
 * @link www.lunacom.ba
 * @access Public
 * @name StringBuilder
 *
 *
 */
class StringBuilder
{
	/**
	 * This variable holds value of StringBuilder
	 *
	 * @var unknown_type
	 */
	private $value = null;
	/**
	 * This string appends current string
	 *
	 * @param string $string
	 */
	public function append($string)
	{
		$this->value = sprintf('%s%s', $this->value, $string);
	}
	/**
	 * This method will append line to current string
	 * $html mean is new line in html format
	 *
	 * @param string $string
	 * @param string $html
	 */
	public function appendLine($string = "", $html = false)
	{
		if($html)
		{
			$this->value = sprintf("%s%s%s", $this->value, '<br>', $string);
		}
		else
		{
			$this->value = sprintf("%s%s%s", $this->value, '\r\n', $string);
		}
	}
	/**
	 * This method will append string in fowarded method
	 * Just pass as many arguments as you wish...
	 * appendFormat("%s|%s", $val1, $val2);
	 *
	 * @param string $format
	 */
	public function appendFormat($format)
	{
		$val = "";
		$function = '$val = sprintf($format';
		$written = false;
		$args = func_get_args();
		for($i=1; $i<count($args);$i++)
		{
				$function = sprintf("$function, %s[%s]", '$args', $i);
		}
		$function  = sprintf("%s);", $function);
		eval($function);
		$this->append($val);
	}
	/**
	 * This method reset value to null
	 *
	 */
	public function reset()
	{
		$this->value = null;
	}
	/**
	 * This method set value of StringBuilder
	 *
	 * @param unknown_string $value
	 */
	public function set($value)
	{
		$this->value = $value;
	}
	/**
	 * This method returns value of StringBuilder
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->value;
	}
}
?>