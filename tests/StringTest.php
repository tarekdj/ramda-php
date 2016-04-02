<?php
require_once 'r.php';


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



}
