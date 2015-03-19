<?php

/**
 ** A list of HTTP/1.1 status codes
 **
 ** Can be accessed via the $GLOBALS array:
 **     $GLOBALS['HTTP_OK'] would give 200 or an OK status
 **
 ** if you need something not listed here feel free to add it
 ** Wiki: http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
 **
 **/

// 2xx Success
$HTTP_OK         = 200;
$HTTP_CREATED    = 201;
$HTTP_ACCEPTED   = 202;
$HTTP_NO_CONTENT = 204;

// 3xx Redirection
$HTTP_MULT_CHOICES  = 300;
$HTTP_MOVED         = 301;
$HTTP_FOUND         = 302;
$HTTP_SEE_OTHER     = 303;
$HTTP_USE_PROXY     = 305;
$HTTP_TEMP_REDIRECT = 307;

// 4xx Client Error
$HTTP_BAD_REQUEST  = 400;
$HTTP_UNAUTHORIZED = 401;
$HTTP_FORBIDDEN    = 403;
$HTTP_NOT_FOUND    = 404;
$HTTP_TIMEOUT      = 408;
$HTTP_CONFLICT     = 409;
$HTTP_GONE         = 410;

// 5xx Server Error
$HTTP_INTERNAL_ERROR  = 500;
$HTTP_NOT_IMPLEMENTED = 501;

?>
