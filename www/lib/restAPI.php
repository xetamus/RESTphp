<?php

require_once "httpCodes.php";

/**
 ** Example URI: http://localhost/api/v1/fibonacci/3
 **
 ** Breakdown:
 **  api       : The
 **  v1        : Which version of the API to call
 **  fibonacci : The endpoint (Should be a class that exists in lib/)
 **  3         : The argument to the endpoint
 **
 **  Anything after the endpoint will be considered an argument, and 
 **  multiple arguments can be passed in by separating them with a '/'.
 **
 **  The first argument after the endpoint can very easily be used to 
 **  reference the correct method to be called on the class called by the
 **  API and should be dealt with in the corresponding classes constructor. 
 **
 **/

class restAPI {
    public $method   = '';   # HTTP method
    public $version  = '';
    public $endpoint = '';
    public $args     = array();
    public $response;

    /**
     ** Function: _parse_request
     **
     ** Used to parse arguments passed to API, will fail if any bad
     ** or unrecognized information was passed in and return an
     ** appropriate error message
     **
     **/
    private function _parse_request() {
        $this->version  = array_shift($this->args);
        $this->endpoint = array_shift($this->args);
       
        // Only v1 supported at this point
        if ( $this->version != 'v1' ) {
            $error = array(
                'error'  => array('message' => "Incorrect API version."),
                'status' => $GLOBALS['HTTP_BAD_REQUEST']
            );

            $this->response = $this->formatResponse($error);
            return;
        }

        // Validate the endpoint 
        @include_once "$this->endpoint.php";
        if ( !class_exists($this->endpoint) ) {
            $error = array(
                'error'  => array('message' => "Endpoint ($this->endpoint) "
                                               . "not implemented."),
                'status' => $GLOBALS['HTTP_NOT_IMPLEMENTED']
            );

            $this->response = $this->formatResponse($error);
            return;
        }

        // We should have exactly 1 input
        if ( count($this->args) != 1 ) {
            $error = array(
                'error'  => array('message' => "Wrong number of inputs."),
                'status' => $GLOBALS['HTTP_BAD_REQUEST']
            );

            $this->response = $this->formatResponse($error);
            return;
        }
    }

    /** 
     ** Function: formatResponse
     **
     ** Set the HTTP header, and output the JSON response of the user
     ** request
     **/
    function formatResponse($data) {
        # API will attempt to set a status if one isn't provided, which
        # could be completely wrong, so ALWAYS pass in a status!
        if ( !isset($data['status']) ) {
            if ( isset($data['error']) ) {
                $data['status'] = $GLOBALS['HTTP_INTERNAL_ERROR'];
            } else {
                $data['status'] = $GLOBALS['HTTP_OK'];
            }
        }

        header("HTTP/1.1 " . $data['status']);
        return json_encode($data);
    }

    /**
     ** Function: processRequest
     **
     ** Do the heavy lifting. Call the proper endpoint and perform the correct
     ** action, returning a JSON format response to the user
     **
     **/
    function processRequest() {
        $object = new $this->endpoint();
        $this->response = 
            $this->formatResponse( $object->REST($this->method, $this->args) );
    }

    /**
     ** Constructor: __construct
     **
     ** init function, determine the HTTP method and parse the URI. Returns 
     ** a 0 (FALSE in boolean logic, BASH-like return), if there are no parse 
     ** errors, otherwise return a response (TRUE in boolean logic).
     **
     ** Example:
     **     $API = new restAPI($_GET['request']);
     **     if ( $API ) {
     **         echo $API;
     **     } else {
     **         $response = $API->process();
     **     } 
     **
     **     echo $response;
     **
     **/
    public function __construct($request) {
        header("Content-Type: application/json");

        # Split the request into the args array
        $this->args = explode('/', rtrim($request, '/'));

        # Grab the type of request (GET/POST/PUT/DELETE)
        $this->method = $_SERVER['REQUEST_METHOD'];

        $this->_parse_request();
    }
}

?>
