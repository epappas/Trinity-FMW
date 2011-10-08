<?
/* Do not remove or alter this section***************************

************************Class Description************************
A simple class that allows the extensive manipulation of
a string.
The class is loosely based on the java StringBuffer class!

The class also has some 'enumeration' functions to enable
easy scanning operation over the string.

The functions will not accept out of range requests. So no error will occur within the code,
 However
no warning is given when an out of range request is provided. So therefore the
onus is on the user of this class to supply correct parameters

This class has not been fully tested,
though it has been used in a commercial application for sometime.
It was written in June 2004 under PHP v4

*****************************************************************

 ***KNOW ISSUES******************************************
 Avoid using a backward iteration whilst deleting,
 as I would expect this to cause problems in some cases!
 ********************************************************

*********************Contact and Bug report***********************
 ----David Johns www.javaservices.co.uk ---
 --- open source page http://www.javaservices.co.uk/example.htm?p=9
 ----e-mail saltash5@nildram.co.uk ---------

********************Licence****************************************
This software is covered by The GNU General Public License (GPL)
Author David Johns
Version 1.0
Date December 2004

*****************************************************************

**************End of do not remove or alter section*************************
*/





class string_buffer {

var $buffer;
var $pointer = 0;

var $f_flag = false;
var $b_flag = false;


function string_buffer($buffer){

 $this->buffer = $buffer;


}

//returns the size of the string
function buflen(){

 return strlen($this->buffer);

}

/*
Deletes a character at position x,
If the pointer is at the deleted position
it removes it along one
*/

function delete_char_at($pos){
        for ($i = 0; $i < strlen($this->buffer); $i++){

        if($pos!=$i){
       $chr_state=$chr_state.$this->buffer[$i];
       }
       }

  $this->buffer = $chr_state;

     if($pos==$this->pointer){
          $this->pointer++;
        }

 return $this->buffer;



}

/*
returns the current string

*/
function to_string(){
if(strlen($this->buffer)>0){
return $this->buffer;
}
else {
return "";
}
}

/*
returns the current position of
the pointer
*/
function get_current_pos(){
 if($this->f_flag){
return $this->pointer-1;
}
else{
 return $this->pointer;
 }
}



/* allows insertion of a string at a given point
within a string.
It also allows a string to be placed on the -
end of the current string.
*/

function insert($pos,$string){

 if($pos<=strlen($this->buffer) and $pos >=0){
 
        if($pos<$this->pointer){
          $this->pointer=$this->pointer+strlen($string);
        }

   $left= substr($this->buffer,0,$pos);

   $right = substr($this->buffer,$pos,strlen($this->buffer));

   $this->buffer = $left.$string.$right;

   }

 return $this->buffer;

}



/*
returns the char at the position specified
*/

function get_char_at($pos){
 if($pos<=strlen($this->buffer) and $pos >=0){
 
  $chr= $this->buffer[$pos];
  

  }

 return $chr;
}

/*
returns the current char
as dictated by the pointer

*/
function get_current_char(){


  $chr= $this->buffer[$this->pointer];


 return $chr;
}

/*
returns the current string
after a char has been appended
The position must be valid -
however adding a char to the
end of the string is allowed.
*/
function set_char_at($pos,$chr){
 if($pos<=strlen($this->buffer) and $pos >=0){
    $this->buffer[$pos]=$chr;
     }

 return $this->buffer;


}
/*
iterates in +ve direction through the string
returns the char at that position.
The first time this function is called it returns
the first char, the the second.
*/



function get_next_char(){


   if($this->pointer<strlen($this->buffer) and  $this->pointer >=0){

   if($this->b_flag){
    $this->pointer++;
   }

    $this->f_flag = true;
      $this->b_flag = false;
   
   $chr= $this->buffer[ $this->pointer];

   $this->pointer++;
   }
   




return $chr;
}

/*
iterates in -ve direction through the string
returns the char at that position.
The first time this function is called it returns
the first char, then the second from the cursor postion.

*/
function get_prev_char(){

     if($this->f_flag) {
    $this->pointer--;
     }
          $this->f_flag=false;
          $this->b_flag=true;
          
     if($this->pointer<=strlen($this->buffer) and  $this->pointer >0){
          $this->pointer--;
          $chr= $this->buffer[$this->pointer];
           }

    return $chr;
}

/*
Enumeration
returns true if within the bounderies of the string
use- while(this->buffer){} for +ve iteration
*/

 function has_more(){

            if($this->pointer<strlen($this->buffer)) {
        return true;
        }

        else{
        return false;
        }
 
 }
 /*
 Enumeration
returns true if within the bounderies of the string
use- while(this->buffer){} for -ve iteration
*/
 function has_less(){

            if($this->pointer>0) {
        return true;
        }

        else{
        return false;
        }

 }

 /*
 resets the buffer to allow new string to be created
 best to create a new instance!
 */
function reset_buffer($buffer){

$this->buffer = $buffer;
 $this->pointer = 0;

 $this->f_flag = false;
$this->b_flag = false;

}
 /*
 resets the buffer pointer
 */
 function pointer_reset(){
 $this->pointer = 0;
 $this->f_flag = false;
 $this->b_flag = false;
 }
 


}
?>
