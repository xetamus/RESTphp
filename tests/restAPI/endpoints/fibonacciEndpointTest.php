<?php

class fibonacciEndpointTest extends PHPUnit_Framework_TestCase {

    // Have to runInSeparateProcess because we are manipulating headers
    /**
     ** @runInSeparateProcess
     **/
    public function testCallWithValidArgs() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $API = new restAPI('v1/fibonacci/3');

        $this->assertEquals('v1', $API->version);
        $this->assertEquals('fibonacci', $API->endpoint);
        $this->assertContains('3', $API->args);

        $API->processRequest();

        $this->assertEquals(
            '{"status":200,"message":[0,1,1]}',
            $API->response
        );
    }

    /**
     ** @runInSeparateProcess
     **/
    public function testCallWithString() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $API = new restAPI('v1/fibonacci/string');

        $this->assertEquals('v1', $API->version);
        $this->assertEquals('fibonacci', $API->endpoint);
        $this->assertContains('string', $API->args);

        $API->processRequest();

        $this->assertEquals(
            '{"status":400,"0":{"error":"Bad request.","message":"Incorrect '
            . 'call to fibonacci. I only accept numeric inputs."}}', 
            $API->response
        );
    }

    /**
     ** @runInSeparateProcess
     **/
    public function testCallWithZero() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $API = new restAPI('v1/fibonacci/0');

        $this->assertEquals('v1', $API->version);
        $this->assertEquals('fibonacci', $API->endpoint);
        $this->assertContains('0', $API->args);

        $API->processRequest();

        $this->assertEquals(
            '{"status":400,"0":{"error":"Bad request.","message":"Incorrect '
            . 'call to fibonacci. An invalid parameter was passed in. I only '
            . 'accept numbers > 1."}}',
            $API->response
        );
    }

    /**
     ** @runInSeparateProcess
     **/
    public function testCallWithNegativeNumber() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $API = new restAPI('v1/fibonacci/-2');

        $this->assertEquals('v1', $API->version);
        $this->assertEquals('fibonacci', $API->endpoint);
        $this->assertContains('-2', $API->args);

        $API->processRequest();

        $this->assertEquals(
            '{"status":400,"0":{"error":"Bad request.","message":"Incorrect '
            . 'call to fibonacci. An invalid parameter was passed in. I only '
            . 'accept numbers > 1."}}',
            $API->response
        );
    }
}

?>
