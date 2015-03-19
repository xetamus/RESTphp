<?php

class fibonacciTest extends PHPUnit_Framework_TestCase {

    public function testNegativePassedIn() {
        $fibonacci = new fibonacci();
        $output    = $fibonacci->calculate(-1);
        $this->assertEquals(NULL, $output);
    }

    public function testZeroPassedIn() {
        $fibonacci = new fibonacci();
        $output    = $fibonacci->calculate(0);
        $this->assertEquals(NULL, $output);
    }

    public function testOnePassedIn() {
        $fibonacci = new fibonacci();
        $expected  = array(0);
        $output    = $fibonacci->calculate(1);
        $this->assertEquals($output, $expected);
    }

    public function testOtherInputs() {
        $fibonacci = new fibonacci();
	    $expected  = array(0, 1);
        $output    = $fibonacci->calculate(2);
        $this->assertEquals($output, $expected);
        
        array_push($expected, 1, 2, 3, 5, 8);
        $output = $fibonacci->calculate(7);
        $this->assertEquals($output, $expected);

	    array_push($expected, 13, 21, 34, 55, 89, 144, 233, 377);
        $output = $fibonacci->calculate(15);
        $this->assertEquals($output, $expected);

	    array_push($expected, 610, 987, 1597, 2584, 4181, 6765, 10946,
            17711, 28657, 46368, 75025, 121393, 196418, 317811,
            514229);
        $output = $fibonacci->calculate(30);
        $this->assertEquals($output, $expected);

        // Test a much larger number just in case
        $output = $fibonacci->calculate(500);
        // Assert returns false negative unless typecast to int
        $this->assertEquals((int)end($output),
                            (int)8.6168291600238E+103);
    }

}

?>
