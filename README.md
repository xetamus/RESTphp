# RESTphul

A PHP REST API implementation. RESTphul will allow a user to plug their current PHP objects to expose a REST interface to end users by adding a simple REST function. This function should take care of handling the proper HTTP response requested by the user. A fibonacci class implementation has been provided which given an input, n, will return a fibonacci sequence with n number of elements. This class shows a sample of implementing the REST function and handling request and argument parsing. Upon installation, the fibonacci endpoint will be ready to use and can be queried using the following API call:

http://serveraddress/api/v1/fibonacci/<n>

Where \<n\> is a numeric value > 0


## Requirements
- PHP
- Apache
- mod_rewrite
- mod_php

*** Note: Everything was tested and developed using Linux, and any provided instruction will assume you are using Linux. RESTphul should work on Windows though if your Apache and module configurations are correct. ***


## Installation
Install Apache and PHP, making sure mod_rewrite and mod_php are also installed for Apache.

Enable mod_rewrite if it isnt' already. I won't go into detail about this because instruction will vary based on how your Apache configs are set up.

Copy the contents of the www/ directory to your webservers DocumentRoot directory (usually /var/www or /srv/http). DocumentRoot can usually be found in the apache.conf or httpd.conf file. 

The URI passing is done via the .htaccess file in the root directory and to work properly the AllowOverride All must be set in the httpd.conf or the apache.conf file. The following must be added in your DocumentRoot config block (It could be set to None, which will keep the URI redirection from working):

    AllowOverride All
    

Add the following to your configuration file if it isn't already there:

    <Files ".ht*">
        Require all denied
    </Files>

It is also a good idea to go ahead and disallow access to the lib directory by adding the following to your configuration file:

<Directory "/<DocumentRoot>/lib">
    deny from all
</Directory>

Where <DocumentRoot> is the path to the web servers root directory.

Fire up or restart your Apache server if it isn't already running and you should be able to make requests using the new api:

    http://server/api/v1/fibonacci/5


## Usage
The API will redirect all traffic to the index.php file to process the users 
request. If a user tries to access this page directly, they will get the proper
error response and message. The same thing will currently happen if the verb is
not a GET or the API is called incorrectly.


When a new API object is created, a response will be returned if there are any
errors in the URI.	


Assumption: Leaving it up to the endpoint class to deal with the REQUEST_METHOD
properly and return the proper response. The class should have a REST function
that will be called by the API and performs whatever method the user requested.
It should take 2 parameters, the request method and an args array. Argument 
validation is left up to the class.

Output from the REST function of the class should be in the following form:
array( status  => HTTP_STATUS,
       message => array( ) )

or

array( status => ERROR_STATUS,
       error  => array( message => ' ') )

The output MUST include an HTTP status and could potentially return the wrong
status to the user otherwise. If no status is defined and no error string is 
found, the API will default to 200 OK status. If an error string is found a 
generic 500 INTERNAL SERVER ERROR will be returned (which helps NOONE).


Why the API is designed this way. This will allow production code to easily be
plugged into the api by simply implementing the REST function and handling 
the different HTTP methods within that function based on the functionality that already exists (GET, POST, PUT, DELETE).


Check httpCodes.php for a list of known HTTP return statuses. These can be used by requiring the library and using the $GLOBALS variable:

require_once "httpCodes.php";
# This will set our status to a success. 200 (OK)
$data = array( 'status' => $GLOBALS['HTTP_OK'] ); 


For fibonacci, assumed that 0 was a negative number since we wanted to return
n number of outputs in the sequence. [ 0 ] for 1, [ 0, 1 ] for 2, [ 0, 1, 1 ] 
for 3, etc...


## Testing
All of the unit tests are written using PHPUnit. More information about PHPUnit can be found here at https://phpunit.de .

I've included the set-up instructions from the PHPUnit getting started guide (https://phpunit.de/getting-started.html)

- wget https://phar.phpunit.de/phpunit.phar
- chmod +x phpunit.phar
- sudo mv phpunit.phar /usr/local/bin/phpunit
- phpunit --version

If you receive the following error:
PHP Warning:  realpath(): open_basedir restriction in effect. File(/usr/local/bin/phpunit) is not within the allowed path(s): (/srv/http/:/home/:/tmp/:/usr/share/pear/:/usr/share/webapps/) in /usr/local/bin/phpunit on line 3

it can be fixed by adding /usr/local/bin to the open_basedir parameter in the php.ini file (lives in /etc/php).

The following error

PHP Fatal error:  Class 'Phar' not found in /usr/local/bin/phpunit on line 714

can be fixed by uncommenting the extension=phar.so line in the php.ini file. 

A note for those who have never used PHPUnit before: test functions must be named test<Something> to be picked up by PHPUnit.
 
autoload is used to include all libs necessary for the tests (this could potentially be split out to multiple files, but by including any libs up front we can run through every test simultaneously. Individual autoload files are included in the testing subdirectories.

Running tests:
phpunit -v --tap --bootstrap autoload.php tests/ (Run full testsuite from root directory)
phpunit --bootstrap autoload.php fibonacciTest.php (Run an individual test from its own subdirectory)
