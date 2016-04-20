<?php
require_once './ramda.php';


class StringTests extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function test_toLower() {
        $this->assertEquals((R::$toLower)('XYZ'), 'xyz');
    }

    public function test_toUpper() {
        $this->assertEquals((R::$toUpper)('abc'), 'ABC');
    }

    public function test_join() {
        $this->assertEquals(((R::$join)(' '))(['a',2,3.4]), 'a 2 3.4');
        $this->assertEquals(((R::$join)('|'))([1,2,3]), '1|2|3');
    }

    public function test_split() {
        $this->assertEquals(((R::$split)('.'))('a.b.c.xyz.d'), ['a', 'b', 'c', 'xyz', 'd']);
    }

    public function test_trim() {
        $this->assertEquals((R::$trim)('   xyz  '), 'xyz');
        $this->assertEquals((R::$map)(R::$trim, (R::$split)(',', 'x, y, z')), ['x', 'y', 'z']);
    }



}
