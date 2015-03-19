<?php

require_once "httpCodes.php";

/**
 ** Class: fibonacci
 **
 ** Class for calculating the fibonacci sequence. Provides a method for
 ** retrieving all numbers in the sequence, with the possibility of
 ** expanding functionality in the future
 **/

class fibonacci {

    /**
     ** Function: REST
     **
     ** Return a proper response to the restAPI call. fibonacci only handles
     ** the GET method. If it is called with anything else return a
     ** 400 BAD REQUEST status.
     **
     **/
    function REST($method, $args) {
        if ( $method != 'GET' ) {
            $response = array(
                'status' => $GLOBALS['HTTP_BAD_REQUEST'],
                array ( 'error'   => "Bad request.",
                        'message' => "Incorrect call to fibonacci. I only "
                                     . "respond to GET requests." )
            );

            return $response;
        }

        $n = array_shift($args);

        if ( is_numeric($n) ) {
            $result = $this->calculate($n);

            if( $result == NULL ) {
                $response = array(
                    'status' => $GLOBALS['HTTP_BAD_REQUEST'],
                    array ( 'error'   => "Bad request.",
                            'message' => "Incorrect call to fibonacci. An "
                                         . "invalid parameter was passed in. "
                                         . "I only accept numbers > 1." )
                );
            } else {
                $response = array(
                    'status' => $GLOBALS['HTTP_OK'],
                    'message' => $result
                );
            }
        } else {
            $response = array(
                'status' => $GLOBALS['HTTP_BAD_REQUEST'],
                array ( 'error'   => "Bad request.",
                        'message' => "Incorrect call to fibonacci. I only "
                                     . "accept numeric inputs." )
            );
        }

        return $response;
    }

    /**
     ** Function: GET
     **
     ** GET call for API.
     ** Calculates <n> number of the fibonacci sequence (defined by $input) 
     ** then return all the values in an array in order, if passed in 0 or
     ** a negative number, we will return NULL.
     **/
    public function calculate($n) {
        if ( $n <= 0 ) {
            return;
        } elseif ( $n == 1 ) {
            $sequence = array(0);
        } else {
	    $sequence = array(0, 1);

            for ( $i = 0; $i < $n - 2; $i++ ) {
                // Add the last two elements of the array and push
                $sequence[] = end($sequence) + prev($sequence);
            }
        }

        # Return sequence
        return $sequence;
    }
}

?>
