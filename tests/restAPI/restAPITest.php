<?php

class restAPITest extends PHPUnit_Framework_TestCase {

    // Have to runInSeparateProcess because we are manipulating headers
    /**
     ** @runInSeparateProcess
     **/
    public function testCreateAPIWithInvalidVersion() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $API = new restAPI('v2/unittest/args');
        $this->assertEquals('v2', $API->version);
        $this->assertEquals(
            '{"error":{"message":"Incorrect API version."},"status":400}',
            $API->response
        );
    }

    /**
     ** @runInSeparateProcess
     **/
    public function testCreateAPIWithInvalidEndpoint() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $API = new restAPI('v1/unittest/args');
        $this->assertEquals('v1', $API->version);
        $this->assertEquals('unittest', $API->endpoint);
        $this->assertEquals(
            '{"error":{"message":"Endpoint (unittest) not implemented."},"status":501}',
            $API->response
        );
    }

    /**
     ** @runInSeparateProcess
     **/
    public function testCreateAPI() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $API = new restAPI('v1/fibonacci/1');
        $this->assertEquals('v1', $API->version);
        $this->assertEquals('fibonacci', $API->endpoint);
        $this->assertContains('1', $API->args);
        // No need to test response, we will do this in endpoint testing
    }

    /**
     ** @runInSeparateProcess
     **/
    public function testformatResponseWithoutInput() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $API = new restAPI('');

        $response = $API->formatResponse(array());
        // Return success code with no message
        $this->assertEquals('{"status":200}', $response);
    }
    
    /**
     ** @runInSeparateProcess
     **/
    public function testformatResponseWithMessage() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $API = new restAPI('');

        $response = $API->formatResponse(array(
                            'message' => 'Hey, I worked')
                    );
        // Return success code with no message
        $this->assertEquals('{"message":"Hey, I worked","status":200}',
                            $response);
    }

    /**
     ** @runInSeparateProcess
     **/
    public function testformatResponseWithStatus() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $API = new restAPI('');

        $response = $API->formatResponse(array( 'status' => 401));
        // Return success code with no message
        $this->assertEquals('{"status":401}', $response);
    }

    /**
     ** @runInSeparateProcess
     **/
    public function testformatResponseWithStatusAndMessage() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $API = new restAPI('');

        $response = $API->formatResponse(array(
                            'status'  => 200,
                            'message' => 'Hey, I worked')
                    );
        // Return success code with no message
        $this->assertEquals('{"status":200,"message":"Hey, I worked"}',
                            $response);
    }

    /**
     ** @runInSeparateProcess
     **/
    public function testformatResponseWithError() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $API = new restAPI('');

        $response = $API->formatResponse( array( 'error' => 
                        array('message' => 'Oh god, what has science done!?')
                    ) );

        // Return success code with no message
        $this->assertEquals('{"error":{"message":"Oh god, what has science '
                            . 'done!?"},"status":500}',
                            $response);
    }

    /**
     ** @runInSeparateProcess
     **/
    public function testformatResponseWithStatusAndError() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $API = new restAPI('');

        $response = $API->formatResponse( array( 'status' => 401,
                        'error' => 
                            array('message' => 'Oh god, what has science done!?')
                        )
                    );

        // Return success code with no message
        $this->assertEquals('{"status":401,"error":{"message":"Oh god, what '
                            . 'has science done!?"}}',
                            $response);
    }

}

?>
