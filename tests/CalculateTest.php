<?php
require_once 'ramda.php';


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

    public function test_dec() {
        $this->assertEquals((R::$dec)(42), 41);
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
        $this->assertTrue((R::$identical)(NAN, NAN));
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

    public function test_divide() {
        $this->assertEquals((R::$divide)(71,100), 0.71);
        $half = (R::$divide)(R::$_, 2);
        $this->assertEquals($half(42), 21);
        $reciprocal = (R::$divide)(1);
        $this->assertEquals($reciprocal(4), 0.25);
    }

    public function test_modulo() {
        $this->assertEquals((R::$modulo)(17, 3),2);
        $isOdd = (R::$modulo)(R::$_, 2);
        $this->assertFalse((bool)($isOdd(42)));
        $this->assertTrue((bool)($isOdd(21)));
    }

    public function test_min() {
        $this->assertEquals((R::$min)(789, 123), 123);
        $this->assertEquals((R::$min)('a', 'b'), 'a');
    }

    public function test_max() {
        $this->assertEquals((R::$max)(789, 123), 789);
        $this->assertEquals((R::$max)('a', 'b'), 'b');
    }

    public function test_all() {
        $lessThan2 = (R::$lt)(R::$_, 2);
        $lessThan3 = (R::$lt)(R::$_, 3);
        $this->assertFalse(((R::$all)($lessThan2))([1,2]));
        $this->assertTrue(((R::$all)($lessThan3))([1,2]));
    }

    public function test_any() {
        $lessThan0 = (R::$lt)(R::$_, 0);
        $lessThan2 = (R::$lt)(R::$_, 2);
        $this->assertFalse(((R::$any)($lessThan0))([1,2]));
        $this->assertTrue(((R::$any)($lessThan2))([1,2]));
    }

    public function test_always() {
        $t = (R::$always)("Tee");
        $this->assertEquals($t(), "Tee");
    }

    public function test_aperture() {
        $this->assertEquals((R::$aperture)(2,[1,2,3,4,5]), [[1, 2], [2, 3], [3, 4], [4, 5]]);
        $this->assertEquals((R::$aperture)(3,[1,2,3,4,5]), [[1, 2, 3], [2, 3, 4], [3, 4, 5]]);
        $this->assertEquals((R::$aperture)(7,[1,2,3,4,5]), []);
    }

    public function test_and() {
        $this->assertTrue((R::$and)(true, true));
        $this->assertFalse((R::$and)(true, false));
        $this->assertFalse((R::$and)(false, true));
        $this->assertFalse((R::$and)(false, false));
    }

    public function test_or() {
        $this->assertTrue((R::$or)(true, true));
        $this->assertTrue((R::$or)(true, false));
        $this->assertTrue((R::$or)(false, true));
        $this->assertFalse((R::$or)(false, false));
    }

    public function test_apply() {
        $nums = [1, 41];
        $this->assertEquals((R::$apply)(function($a,$b) {return $a+$b;}, $nums),42);
    }

    public function test_assoc() {
        $this->assertEquals((R::$assoc)('c', 3, ["a"=> 1, "b" => 2]), ["a"=>"1", "b"=>"2", "c"=>"3"]);
    }

    public function test_either_test_both() {
        $gt10 = function($x) {return $x>10;};
        $even = function($x) {return $x%2 ===0;};
        $f = (R::$either)($gt10, $even);
        $this->assertTrue($f(101));
        $this->assertTrue($f(8));

        $f = (R::$both)($gt10, $even);
        $this->assertTrue($f(100));
        $this->assertFalse($f(101));
    }

    public function test_comparator() {
        $cmp = (R::$comparator)(function($a, $b) {return $a['age'] < $b['age'];});
        $people = [['age'=>100], ['age'=>5]];
        $sorted = (R::$sort)($cmp, $people);
        $this->assertEquals($sorted, [['age'=>5], ['age'=>100]]);
    }

    public function test_sort() {
        $diff = function($a, $b) { return $a - $b; };
        $this->assertEquals((R::$sort)($diff, [4,2,7,5]), [2, 4, 5, 7]);
    }

    public function test_once() {
        $addOneOnce = (R::$once)(R::$inc);
        $this->assertEquals($addOneOnce(10), 11);
        $this->assertEquals($addOneOnce(50), 11);
    }
}
