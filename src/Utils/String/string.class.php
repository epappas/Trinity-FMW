<?php
/*
 * String.class.php
 *
 *
 * This LICENSE is in the BSD license style.
 *
 *
 * Copyright (c) 2010, Elyess Zouaghi (elyess@zouaghi.net)
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   Redistributions of source code must retain the above copyright
 *   notice, this list of conditions and the following disclaimer.
 *
 *   Redistributions in binary form must reproduce the above copyright
 *   notice, this list of conditions and the following disclaimer in the
 *   documentation and/or other materials provided with the distribution.
 *
 *   Neither the name of Elyess Zouaghi nor the names of his contributors
 *   may be used to endorse or promote products derived from this software
 *   without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE REGENTS OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * Author: 		Elyess Zouaghi (elyess@zouaghi.net)
 * Contributor: Joaquín Gatica (joaquingatica@gmail.com)
 */
 
 /*
 * TODO: Implements all string manipulation functions, 
 *		 decide which functions modify the value and which return a new value ....
 */

/**
* This class can be used to manipulate text strings.
*
* It performs several types of instructions to manipulate text strings like: 
* comparing values, get the length, get parts of the string, concatenate, search text,
* map the case of letters, convert to number values, etc.. 
* @package String
 */
    class String {
        
		/**
		* Original value of String
		* @access private
		* @var string
		*/
		private $original;
		
		/**
		* Value of String
		* @access private
		* @var string
		*/
        private $value;
        
        /**
		*/
        public function getOriginal() {
        	return $this->original;
        }
		
		/**
		*/
        public function setOriginal($original) {
        	$this->original = $original;
        }
		
		/**
		*/
        public function getValue() {
        	return $this->value;
        }
		
		/**
		*/
        public function setValue($value) {
        	$this->value = $value;
        }
        
        /**
		*/
        public function __construct($value = "") {
            $this->setOriginal($value);
            $this->setValue($value);
        }
		
		/**
		*/
        public function __toString() {
            return (string) $this->getValue();
        }
		
		/**
		*/
        public function __call($fun, $pars) {
        }
		
		/**
		*/
        public function __get($v) {
            return $this->getValue();
        }
        
        /**
		*/
        public function saveAndReturnStr($value) {
        	return $this->saveAndReturn($value, "str");
        }
		
		/**
		*/
        public function saveAndReturnObj($value) {
        	return $this->saveAndReturn($value, "obj");
        }
		
		/**
		*/
        public function saveAndReturn($value, $type = "str") {
        	$this->setValue($value);
        	return ($type == "obj")? new String($value) : $value;
        }
		
		/**
		*/
        public function isNull() {
            return is_null($this->getValue());
        }
		
		/**
		*/
        public function isEmpty() {
            return empty($this->getValue());
        }
		
		/**
		*/
        public function eq($val) {
            return $val == $this->getValue();
        }
		
		/**
		*/
        public function not($val) {
            return $val != $this->getValue();
        }
		
		/**
		*/
        public function length() {
            return strlen($this->getValue());
        }
		
		/**
		*/
        public function charAt($i = 0) {
            $str = "";
            if(($i > 0) && ($i < $this->length())) {
            	$str = substr($this->getValue(), $i, 1);
            }
            return new String($str);
        }
		
		/**
		*/
        public function concat() {
            $str = $this->getValue();
            $args = func_get_args();
            if (is_array($args) && (count($args) > 0)) {
            	foreach($args as $s) {
                	$str .= $s;
            	}
            }
            return $this->saveAndReturnObj($str);
        }
		
		/**
		*/
        public function indexOf($str, $stat = 0) {
           	return strpos($this->getValue(), $str, $start);
        }
		
		/**
		*/
        public function lastIndexOf($str, $start = 0) {
            return strrpos($this->getValue(), $str, $start);
        }
		
		/**
		* replace portion of {@link $value} with provided string $replace
		* @param string $search
		* @param string $replace
		* @return integer
		*/
        public function replace($search, $replace) {
            return $this->saveAndReturnObj(str_replace($search, $replace, $this->getValue()));
        }
		
		/**
		* Search for position of $for into {@see $value}
		* @param string $for
		* @param integer $start
		* @return numeric position of $for
		*/
        public function search($for, $start = 0) {
			return strpos($this->getvalue(), $start);
        }
		
		/**
		* @see substr()
		*/
        public function slice($start = 0, $length = 1) {
			return $this>substr($start, $length);
        }
		
		/**
		* Split {@link $value} into array based on $separator
		* @param string $separator
		* @param integer $limit
		* @return an Array composed from substring of {@link $value}
		*/
        public function split($separator, $limit=null) {
			return is_null($limit) ? explode($this->getValue()) : explode($this->getValue(), $limit);
        }
		
		/**
		* Returns the portion of {@see $value} specified by the $start and $length parameters. 
		* @param integer $start
		* @param integer $length
		*/
        public function substr($start =  0, $length = 1) {
            return new String(substr($this->getValue(), $start, $length));
        }
		
		/**
		* Converts {@see $value} to lower case
		*/
        public function toLower() {
            return $this->saveAndReturnObj(strtolower($this->getValue()));
        }
		
		/**
		* Converts {@see $value} to upper case
		*/
        public function toUpper() {
            return $this->saveAndReturnObj(strtoupper($this->getValue()));
        }
		
		/**
		* @return string {@link $value}
		*/		
        public function valueOf() {
            return $this->__toString();
        }
		
		/**
		* test {@link $value} for regular expression $regex
		* @param $regex
		*/		
        public function test($regex) {
            return preg_match($regex, $this->getValue()) > 0;
        }
		
		/**
		* check if {@link $value} contains $str
		* @param string $str
		*/		
        public function contains($str) {
            return $this->test('/'.$str.'/');
        }
		
		/**
		* Trim {@link $value}
		*/		
        public function trim() {
            return $this->saveAndReturnObj(trim($this->getValue()));
        }
		
		/**
		* Escape and convert to lower case {@link $value}
		* @return new String object
		*/		
        public function clean() {
            $this->toLower();
            $this->escape();
            return new String($this->getValue());
        }

		/**
		* converts {@link $value} to camel case form
		* @return new String instanse on camel case form
		*/		
        public function camelCase() {
            $str = $this->getValue();
            $words = explode(" ", $str);
            if(is_array($words) && (count($words) > 0)) {
            	foreach($words as &$word) {
            		$word = ucfirst($word);
            	}
            }
            $str = implode(" ", $words);
            return $this->saveAndReturnObj($str);
        }
		
		/**
		* replace white spaces into '-'
		* @todo converts unicode chars into ascii
		* @return new String instanse
		*/		
        public function hyphenate() {
            return $this->saveAndReturnObj($this->replace(" ", "-"));
        }
		
		/**
		* Converts first word on {@link $value} into upper case
		* @return object
		*/		
        public function capitalize() {
            return saveAndReturnObj(ucfirst($this->getValue()));
        }
		
		/**
		*/				
        public function escapeRegExp() {
            $chars = array("[", html_entity_decode_("&92"), "^", "$", ".", "|", "?", "*", "+", "(", ")");
            return $this->saveAndReturnObj(addcslashes($this->getValue(), $chars));
        }
		
		/**
		* Escape {@link $value}
		*/		
        public function escape() {
        	return $this->saveAndReturnObj(addslashes($this->getValue()));
        }
		
		/**
		* Returns integer value of {@link $value}
		* @return integer
		*/		
        public function toInt() {
            return (int) $this->getValue();
        }

		/**
		* Returns float value of {@link $value}
		* @return float
		*/				
        public function toFloat() {
            return (float) $this->getValue();
        }
		
		/**
		* @see replace()
		*/
        public function substitute($search, $replace) {
            return $this->replace($search, $replace);
        }
        
    }
    

?>