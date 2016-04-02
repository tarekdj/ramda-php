<?php
require_once 'r.php';


class ArrayTests extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function test_keys() {
        $this->assertEquals((R::$keys)(['a'=>1, 'b'=>2, 'c'=>3]), ['a','b','c']);
    }

    public function test_values() {
        $this->assertEquals((R::$values)(['a'=>1, 'b'=>2, 'c'=>3]), [1,2,3]);
    }

    public function test_prop() {

    }

    public function test_filter() {
        $isEven = function($n) {
          return $n % 2 === 0;  
        };
        $this->assertEquals(array_values((R::$filter)($isEven, [1, 2, 3, 4])), [2,4]);
        $this->assertEquals((R::$filter)($isEven, ['a'=>1, 'b'=>2, 'c'=>3, 'd'=>4]), ['b'=>2, 'd'=>4]);
    }

    public function test_map() {
        $double = function($x) {
          return $x * 2;
        };
        $this->assertEquals((R::$map)($double, [1, 2, 3]), [2, 4, 6]);
        $this->assertEquals((R::$map)($double, ['x'=>1, 'y'=>2, 'z'=>3]), ['x'=>2, 'y'=>4, 'z'=>6]);
    }

    public function test_reduce() {
        $numbers = [1, 2, 3];
        $this->assertEquals((R::$reduce)(R::$add, 10, $numbers), 16);
    }
/*
    public function test_concat() {
        $this->assertEquals((R::$concat)([],[]), []);
        $this->assertEquals((R::$concat)([4,5,6],[1,2,3]), [4,5,6,1,2,3]);
        $this->assertEquals((R::$concat)('ABC', 'DEF'), 'ABCDEF');
    }
*/
}
