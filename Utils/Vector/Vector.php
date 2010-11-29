<?php

//Basics Includes
//---

/**
 * @author Pappas Evangelos - papas.evagelos@gmail.com
 * @copyright 2009
 * @constructor Vector()
 * @constructor Vector($MAX)
 * @constructor Vector($items,$tos,$MAX=0)
 */
class Vector
{
    /**
     * @var array The array buffer into which the components of the vector are stored.
     */
    private $elementData;
    /**
     * @var int The number of valid components in this Vector object.
     */
    private $vectorSize;
    /**
     * @var int the Top Of The Stack ID -  TOS -> The Last Pointed Object
     */
    private $tos;
    /**
     * @var int the Max Index Vector Row - 0 -> Unlimited, Default
     */
    private $mivr;

    /**
     * Vector Constructor - Initiate
     */
    function __construct()
    {
        $this->mivr = 0;
        $this->elementData = array();
        $this->vectorSize = count($this->elementData);
    }

    /**
     * Vector Constructor - Initiate
     * Set a Max index Value for this Vector
     * @param int $MAX $this->mivr Max Index Vector Row
     */
    function __construct($MAX)
    {
        $this->mivr = $MAX;
        $this->elementData = array();
        $this->vectorSize = count($this->elementData);
    }

    /**
     * Vector Constructor - Initiate
     * Initiate a new vector with its First Elements,
     * and its Values
     * @param array $items The First Items of the Vector
     * @param int $tos the pointer Value of the Stack
     * @param int $MAX $this->mivr Max Index Vector Row 0 -> Unlimited, Default
     */
    function __construct($items,$tos,$MAX=0)
    {
        if($MAX > 0)
        {
            $this->mivr = $MAX;
        }

        $this->elementData = array();
        
        if(is_array($items))
        {
            for($i=0; i<count($items); $i++)
            {
                $this->push($items[$i]);
            }
        }
        else //in case $items is not an array but a simple Object
        {
            $this->push($items);
        }
        $this->tos = $tos;
        $this->vectorSize = count($this->elementData);
    }

    /**
     * Get the Top of the Stack Element without POP
     * The TOS-Element is the Last Object
     * @return Object|null
     */
    function getTOSObject()
    {
        return $this->getObject($this->getTOSID());
    }

    /**
     * Get the Top of the Stack Element ID without POP
     * The TOS-Element is the Last Object
     * @return int
     */
    function getTOSID()
    {
        return $this->tos;
    }

    /**
     * Adds the Object in the Vector as "Last Object"
     * and point by TOS
     * @return boolean
     */
    function push($object)
    {
        if($this->addAt($object, $this->getTOSID()+1))
        {
            $this->tos++; // Points the inserted Object
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Removes the TOS pointed object
     * and re-initiate the TOS pointer to the last (under) Element
     */
    function pop()
    {
        return $this->removeAt($this->getTOSID());
    }

    /**
     * Adds an ellement after the pointed index and resize all IDs and ReCounts
     *
     * 5.  ___  <- $this->getSize()
     * 4. | # | <- $this->getLastID()
     * 3. | # | <- e.g. $index
     * 2. | # | <- e.g. $this->tos
     * 1. | # |
     * 0. -----
     * @param object $object
     * @param int $index The pointer
     * @return boolean
     */
    function addAt($object, $index)
    {
        if( !empty($object) && $this->checkBound($index) )
        {
            if($index <= $this->getLastID() )
            {
                for($i=$this->getLastID(); $i>=$index; $i--)
                {
                    $this->elementData[$i+1] = $this->elementData[$i];
                }
                $this->elementData[$index] = $object;
                $this->vectorSize = count($this->elementData);
            }
            elseif($index == $this->getSize())
            {
                $this->elementData[$index] = $object;
                $this->vectorSize = count($this->elementData);
            }

            // If index is under or equal with TOS,
            // TOS Should always Point the Last Pointed ID
            if($index <= $this->getTOSID())
            {
                $this->tos++;
            }
            return true;
        }
        else
        {
            return false;
        }
    }

        /**
     * Removes element at $index pointer
     *
     * 5.  ___  <- $this->getSize()
     * 4. | # | <- $this->getLastID()
     * 3. | # | <- e.g. $index
     * 2. | # | <- e.g. $this->tos
     * 1. | # |
     * 0. -----
     * @param int $index The pointer
     * @return object|null
     */
    function removeAt($index)
    {
        if($this->isInRage($index))
        {
            $tempObject = $this->elementData[$index];

            for($i=$index; $i<=$this->getLastID(); $i++)
            {
                $this->elementData[$i] = $this->elementData[$i+1];
            }

            $this->destroyLast();

            // If index is under or equal with TOS,
            // TOS Should always Point the Last Pointed ID
            if($index <= $this->getTOSID())
            {
                $this->tos--;
            }
            return $tempObject;
        }
        else
        {
            return null;
        }
    }
    
    /**
     * Set TOS to point the REAL LastID
     */
    function appendTOS()
    {
        $this->tos = $this->getLastID();
    }

    /**
     * Set TOS to Show the Selected Object,
     * Every After Object will be marked as Non-Existed
     * But NOT DELETED,
     * Use $this->appendTOS() to reset TOS.
     * if $index is not Real - false will be returned!
     * @param int $index The Pointer
     * @return boolean
     */
    function setTOS($index)
    {
        if($this->isInRage($index))
        {
            $this->tos = $index;
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns true of Vector is empty false otherwise
     * @return boolean
     */
    function isEmpty()
    {
        return ($this->vectorSize == 0);
    }


    /**
     * Returns the current size of this Vector
     * @return int
     */
    function getSize()
    {
        return $this->vectorSize;
    }

    /**
     * Returns the Last ID in the Stack
     * *Not Necesarly the TOS object
     * @return int
     */
    function getLastID()
    {
        return ( $this->getSize()-1 > 0 ? $this->getSize()-1 : 0 );
    }

    /**
     * Searches for the first occurence of the given argument
     * @return int|-1
     */
    function getIndexOf($object)
    {
        if (($index = array_search($object, $this->elementData)) !== false)
        {
            return $index;
        } 
        else
        {
            return -1;
        }
    }

    /**
     * This function will retain TRUE if $object is contained
     * within the vector else FALSE
     * @return boolean
     */
    function contains($object)
    {
        return ($this->getIndexOf($object) >= 0);
    }

    /**
     * Returns Vector Object at index $index
     * Error : Null is returned
     * @return object|null
     */
    function getObject($index)
    {
        if ($this->isInRage($index))
        {
            return ($this->elementData[$index]);
        }
        return null;
    }

    /**
     * Sets the object at $index to be $object
     * @param object $object
     * @param int $index The pointer
     * @return boolean
     */
    function setObject($object, $index )
    {
        if ($this->isInRage($index))
        {
            $this->elementData[$index] = $object ;
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Removes all elements from the Vector.  Note that this does not
     * resize the internal data array.
     */
    function clear()
    {
        for ($i = 0; $i < $this->size(); $i++)
        {
            $this->setObject($i, NULL);
        }
    }

    /**
     * Destroy Vector and each Data Row.
     */
    function destroy()
    {
        $this->clear();
        for ($i = 0; $i < $this->size(); $i++)
        {
            unset($this->elementData[i]);
        }
        unset($this->elementData);
        unset($this->vectorSize);
        unset($this->mivr);
    }

    /**
     * Release and permanly Delete ( unset() ) the last ID os the Vector
     * And Recount the Stack
     */
    function destroyLast()
    {
        unset($this->elementData[$this->getLastID()]);
        $this->vectorSize = count($this->elementData);
    }

    /**
     * Checks whether index is within the array bound
     * If MIVR is not Set then returns True
     * @return boolean
     */
    function checkBound($index)
    {
        if($this->mivr > 0)
        {
            if ( $index >= $this->mivr || $index < 0)
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }
    
    /**
     * Checks whether index is within the array Range
     * @return boolean
     */
    function isInRage($index)
    {
        if($index <= $this->getLastID() || $index >= 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>