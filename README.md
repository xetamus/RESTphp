 RESTphul
==========

A PHP REST API implementation. RESTphul will allow a user to plug their current PHP objects to expose a REST interface to end users by adding a simple REST function. This function should take care of handling the proper HTTP response requested by the user. 

A fibonacci class implementation has been provided which given an input, n, will return a fibonacci sequence with n number of elements. This class shows a sample of implementing the REST function and handling request and argument parsing. Upon installation, the fibonacci endpoint will be ready to use and can be queried using the following API call:

>   http://serveraddress/api/v1/fibonacci/\<n\>

Where \<n\> is a numeric value > 0


 Requirements
--------------
- PHP
- Apache
- mod_rewrite
- mod_php

###### *** Note: Everything was tested and developed using Linux, and any provided instruction will assume you are using Linux. RESTphul should work on Windows though if your Apache and module configurations are correct. ***


 Installation
--------------
Install Apache and PHP using your distros package manager. You should make sure mod_rewrite and mod_php are also installed, though this should be the case if a package manager is used for installation.

Enable mod_rewrite if necessary. I won't go into detail about this because instruction will vary based on how your Apache configs are set up.

The URI passing is done via the .htaccess file in the root directory and to work properly the AllowOverride All must be set in the httpd.conf or the apache.conf file. The following must be added in your DocumentRoot config block (It could be set to None by default, which will keep the URI redirection from working):

   AllowOverride All
    
Add the following to your configuration file if it isn't already there to deny users from accessing .ht* files:

```
<Files ".ht*">
    Require all denied
</Files>
```

It is also a good idea to go ahead and disallow access to the lib directory by adding the following to your configuration file:

```
<Directory "/<DocumentRoot>/lib">
    deny from all
</Directory>
```

Where <DocumentRoot> is the path to the web servers root directory.

Copy the contents of the www/ directory to your webservers DocumentRoot directory (usually /var/www or /srv/http). DocumentRoot can usually be found in the apache.conf or httpd.conf file. 

Fire up or restart your Apache server if it isn't already running and you should be able to make requests using the new api:
>   http://server/api/v1/fibonacci/5

This should return the following message:
>   {"status":200,"message":[0,1,1,2,3]}


 Usage
-------
The API will redirect all traffic to the index.php file to process the users request. If a user tries to access this page directly, they will get the proper error response and message. The same thing should happen if the API is called incorrectly.

When a new API object is created, a response will be returned if there are any errors in the URI. The following code is used in index.php to handle this:

```php
$API = new restAPI($_GET['request']);

if ( !$API->response ) {
    $API->processRequest();
}

echo $API->response(); 
```

It's left up to the endpoint class to deal with the REQUEST_METHOD properly and return the proper response. The class should have a REST function which is what will be called by the API and performs whatever method the user requested. It should take 2 parameters $method and $args, which are the method called by which the user called the endpoint (GET, POST, PUT, DELETE) and any args passed in. Argument validation should obviously be taken care of by the class as well.

```php
public function REST ($method, $args) {
    ...
}
```

Output from the REST function of the class should be in the following form:

```php
array( status  => HTTP_STATUS,
       message => array( ) )
```

or

```php
array( status => ERROR_STATUS,
       error  => array( message => ' ') )
```

The output MUST include an HTTP status and could potentially return the wrong status to the user. If no status is defined and no error string is found, the API will default to 200 OK status. If an error string is found a generic 500 INTERNAL SERVER ERROR will be returned (which helps NOONE).

Check httpCodes.php for a list of known HTTP return statuses. These can be used by requiring the library and using the $GLOBALS variable:

```
require_once "httpCodes.php";
# This will set our status to a success. 200 (OK)
$data = array( 'status' => $GLOBALS['HTTP_OK'] ); 
```

The API is designed in such a way as to allow production code to easily be plugged into the api by simply implementing the REST function and handling the different HTTP methods within that function based on the functionality that already exists (GET, POST, PUT, DELETE).


 Testing
---------
All of the unit tests are written using PHPUnit. More information about PHPUnit can be found via the PHPUnit website:

> https://phpunit.de

I've included the set-up instructions from the PHPUnit getting started guide (https://phpunit.de/getting-started.html).

- wget https://phar.phpunit.de/phpunit.phar
- chmod +x phpunit.phar
- sudo mv phpunit.phar /usr/local/bin/phpunit
- phpunit --version

If you receive the following error:
> PHP Warning:  realpath(): open_basedir restriction in effect. File(/usr/local/bin/phpunit) is not within the allowed path(s): (/srv/http/:/home/:/tmp/:/usr/share/pear/:/usr/share/webapps/) in /usr/local/bin/phpunit on line 3

It can be fixed by adding /usr/local/bin to the open_basedir parameter in the php.ini file (lives in /etc/php).

```
open_basedir = /srv/http/:/home/:/tmp/:/usr/share/pear/:/usr/share/webapps/:/usr/local/bin/
```

If you recieve the following error:
> PHP Fatal error:  Class 'Phar' not found in /usr/local/bin/phpunit on line 714

It can be fixed by uncommenting the extension=phar.so line in the php.ini file.

```
extension=phar.so
```

> A note for those who have never used PHPUnit before: test functions must be named test<Something> to be picked up by PHPUnit.

###### Running tests:
```bash
phpunit -v --tap --bootstrap autoload.php tests/ (Run full testsuite from root directory)
phpunit -v --tap --bootstrap autoload.php fibonacciTest.php (Run the fibonacci class testsuite from the tests/fibonacci directory)
```
> Running with the --tap will give output in the Test Anything Protocol output, which is handy because otherwise output is only displayed on error. This allows us to see the progress of all tests and also let's us see that the tests we planned are actually being run.
 
###### autoload
The autoload.php file in root is used to include all libs necessary for the entire testsuite. Individual autoload files are included in the testing subdirectories to run the individual test suites.
