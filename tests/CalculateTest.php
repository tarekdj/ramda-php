<?php
require_once 'r.php';


class CalculateTests extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function test_add() {
        $this->assertEquals((R::$add)(2,3), 5);
        $this->assertEquals((R::$add)(7)(10), 17);
    }

    public function test_sum() {
        $this->assertEquals((R::$sum)([2,4,6,8,100,1]), 121);
    }

    public function test_inc() {
        $this->assertEquals((R::$inc)(42), 43);
    }

    public function test_negate() {
        $this->assertEquals((R::$negate)(42), -42);
    }

    public function test_identical() {
        $o = [];
        $this->assertTrue((R::$identical)($o, $o));
        $this->assertTrue((R::$identical)(1, 1));
        $this->assertFalse((R::$identical)(1, '1'));
        //$this->assertFalse((R::$identical)([], []));
        //$this->assertFalse((R::$identical)(0, -0));
        $this->asserttrue((R::$identical)(NAN, NAN));
    }

    public function test_identity() {
        $this->assertEquals((R::$identity)(1),1);
        $obj = [];
        $this->assertEquals((R::$identity)($obj), $obj);
    }

    public function test_multiply() {
        $double = (R::$multiply)(2);
        $triple = (R::$multiply)(3);
        $this->assertEquals($double(3),6);
        $this->assertEquals($triple(4),12);
        $this->assertEquals((R::$multiply)(2,5),10);
    }

    public function test_min() {
        $this->assertEquals((R::$min)(789, 123), 123);
        $this->assertEquals((R::$min)('a', 'b'), 'a');
    }

    public function test_max() {
        $this->assertEquals((R::$max)(789, 123), 789);
        $this->assertEquals((R::$max)('a', 'b'), 'b');
    }

}
