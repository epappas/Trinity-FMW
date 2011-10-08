<?php

/* USAGE: Query String Manipulation
 *
 * DESCRIPTION: A class for get values,
 * set values, delete variables, count
 * variables ocurrences in a given
 * query string.
 *
 * AUTHOR: Olaf Reitmaier <olafrv@gmail.com>
 * VERSION: 1.1 (July 19th, 2005)
 * TESTED ON: PHP 4.3.8
 */
class QueryString{

  function QueryString(){
	  /* Only the class constructor */  
  }

  /* USAGE: Delete all the ocurrence of
   * a variable $name declared in the
   * query string $qs (one or more times).
   *
   * RETURNS: A new query string.
   */
  function delVar($qs, $name = ""){
		$qs = trim($qs);
		$name = trim($name);
		$del = $qs;
		if ($name!=""){
			while($this->numVar($del, $name)>0){
				$var = $this->getVar($del, $name);
				$del = str_replace(("&" . trim($var)), "", $del);
				$del = str_replace(("?" . trim($var)), "?", $del);
				if ($del == $var || trim($del) == "?"){
					$del = "";
					break;
				}
			}
		}
		return $this->revAmpersand($del);
  }

  /* USAGE: Get the value of the variable
   * $name in the query string $qs
   *
   * RETURNS: A string value.
	 *          Is null if the var is undefined,
	 *				  (It is not in the query string).
   */
  function getVarValue($qs, $name){
    $spec = $this->getVar($qs, $name);
	  if ($spec==""){
	 	 $value=null;
	  }else{
	 	  $parts = explode("=", $spec);
		  $value = $parts[1];
	  }	 
	  return $value;
  }

  /* USAGE: Get the value of the variable
   * in the $pos position in the query string $qs
   *
   * RETURNS: A string value.
	 *          Is null if the var is undefined,
	 *				  (It is not in the query string).
   */
  function getVarPosValue($qs, $pos){
		$arr = $this->toArray($qs);
		return $arr[$pos];
  }

  /* USAGE: Get the declaration string
   * of the variable $name in the
   * query string $qs 
   *
   * RETURNS: A string (var=value).
   */
  function getVar($qs, $name = ""){
    $qs = trim($qs);
    $name = trim($name);
    $spec = "";
    if ($name!=""){
      $inicio = strpos($qs, $name . "=", 0);
      $validVar = true;
      if ($inicio>0){
        $validVar = (substr($qs, $inicio-1, 1)=="&");
      }
      if ($validVar){
        $igual = strpos($qs, "=", $inicio);
        $fin = strpos($qs, "&", $igual);
        if (!$fin) $fin = strlen($qs);
        if ($inicio < $igual && $igual <= $fin){
          $spec = substr($qs, $inicio, $fin-$inicio);
        }
      }
    }
    return $spec;
  }

  /* USAGE: Determine the number of time
   * the variable $name appears in the
   * query string $qs.
   *
   * RETURNS: A new query string.
   */
  function numVar($qs, $name){
     $num = 0;
     $name = trim($name);
     if ($name!=""){
      $num = substr_count($qs, "&" . $name . "=");
     }
     if (substr($qs, 0, strlen($name."=")) == $name."="){
        $num++;
     }
     return $num;
  }


  /* USAGE: Eliminate double &&
   * in the query string $qs.
   *
   * RETURNS: A new query string.
   */
  function revAmpersand($qs){
     while(substr_count($qs, "&&")>0){
         $qs = str_replace("&&","&", $qs);
     }
     if (substr($qs, strlen($qs)-1, 1)=="&"){
        $qs = substr($qs, 0, strlen($qs)-1);
     }
     if (substr($qs, 0, 1)=="&"){
        $qs = substr($qs, 1, strlen($qs));
     }
     return $qs;
  }

  /* USAGE: Change the value of a variable
   * $name in the query string $qs.
   *
   * RETURNS: A new query string.
   */
  function setVar($qs, $name = "", $value=""){
		$qs = trim($qs);
		$name = trim($name);
		$value = trim($value);

		$set = $qs;
		if ($name!=""){
			while($this->numVar($set, $name)>1){
				$set = $this->delVar($set, $name);
			}
			if ($this->numVar($set, $name)==1){
				$var = $this->getVar($set, $name);
				$set = str_replace($var, $name . "=" . $value, $set);
			}else{
				$set = $this->revAmpersand($set) . "&" . $name . "=" . $value;
			}
		}
		return $this->revAmpersand($set);
  }

  /* USAGE: Change the value of the variables
   * with the new values in the array. The array
   * must pairs (name, value) of n-length.
   *
   * RETURNS: A new query string.
   */
  function setVarArray($qs, $var_array){
    foreach($var_array as $name => $value) {
      $qs = $this->setVar($qs, $name, $value);
    }
    return $qs;
  }
	
	/* USAGE: Returns an associative array (Indexes of the array
	 *        can be the name or the position of the variable
	 *				in the given query string
	 *
	 * RETURNS: An Associative Array (Names and Positions of Vars)
	 */
    function toArray($qs)
    {
        $qs = $this->revAmpersand($qs);
        $variable_equals_value = explode("&", $qs);
        $num_vars = sizeof($variable_equals_value);
        $variables = Array();
        for($i=0;$i<$num_vars;$i++){
                $variable_value = explode("=", $variable_equals_value[$i]);
                $var_name  = $variable_value[0];
                $var_value = $variable_value[1];
                $variables[$var_name] = $var_value;
                $variables[$i] = $var_value;
        }
        return $variables;
    }
}
?>