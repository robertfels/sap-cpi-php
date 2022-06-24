<?php

namespace contiva\sapcpiphp;

class Compositor {

protected $composite = array();
protected $use_reference; 
protected $first_precedence;

/**
 * __construct, Constructor
 *
 * Used to set options.
 *
 * @param bool $use_reference whether to use a reference (TRUE) or to copy the object (FALSE) [default]
 * @param bool $first_precedence whether the first entry takes precedence (TRUE) or last entry takes precedence (FALSE) [default]
 */
public function __construct($use_reference = FALSE, $first_precedence = FALSE) {
    // Use a reference
    $this->use_reference = $use_reference === TRUE ? TRUE : FALSE;
    $this->first_precedence = $first_precedence === TRUE ? TRUE : FALSE;

}

/**
 * Merge, used to merge multiple objects stored in an array
 *
 * This is used to *start* the merge or to merge an array of objects.
 * It is not needed to start the merge, but visually is nice.
 *
 * @param object[]|object $objects array of objects to merge or a single object
 * @return object the instance to enable linking
 */

public function & merge() {
    $objects = func_get_args();
    // Each object
    foreach($objects as &$object) $this->with($object);
    // Garbage collection
    unset($object);

    // Return $this instance
    return $this;
}

/**
 * With, used to merge a singluar object
 *
 * Used to add an object to the composition
 *
 * @param object $object an object to merge
 * @return object the instance to enable linking
 */
public function & with(&$object) {
    // An object
    if(is_object($object)) {
        // Reference
        if($this->use_reference) {
            if($this->first_precedence) array_push($this->composite, $object);
            else array_unshift($this->composite, $object);
        }
        // Clone
        else {
            if($this->first_precedence) array_push($this->composite, clone $object);
            else array_unshift($this->composite, clone $object);
        }
    }

    // Return $this instance
    return $this;
}

/**
 * __get, retrieves the psudo merged object
 *
 * @param string $name name of the variable in the object
 * @return mixed returns a reference to the requested variable
 *
 */
public function & __get($name) {
    $return = NULL;
    foreach($this->composite as &$object) {
        if(isset($object->$name)) {
            $return =& $object->$name;
            break;
        }
    }
    // Garbage collection
    unset($object);

    return $return;
}
}