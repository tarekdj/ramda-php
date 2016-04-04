<?php
require_once 'r.php';


class OtherTests extends PHPUnit_Framework_TestCase
{
    private static $value = null;

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function test_tail() {
        $this->assertEquals(R::tail([1,2,3]), [2,3]);
        $this->assertEquals(R::tail([1,2]), [2]);
        $this->assertEquals(R::tail([1]), []);
        $this->assertEquals(R::tail([]), []);

        $this->assertEquals(R::tail('abc'), 'bc');
        $this->assertEquals(R::tail('ab'), 'b');
        $this->assertEquals(R::tail('a'), '');
        $this->assertEquals(R::tail(''), '');
    }

    public function test_tap() {
        $m = 0;
        $setX = function($x) use($m){
            self::$value = $x;
        };
        $this->assertEquals((R::$tap)($setX, 100), 100);
        $this->assertEquals(self::$value, 100);
    }

    public function test_identity() {
        $this->assertEquals((R::$times)(R::$identity, 5), [0,1,2,3,4]);
    }
/*
    public function test_pipe() {
        $f = R::pipe(R::$pow, R::$negate, R::$inc);
        $this->assertEquals($f(3,4), -80);
    }

    public function test_chain() {
        $duplicate = function($n) {
            return [$n, $n];
        };

        $this->assertEquals((R::$chain)($duplicate, [1,2,3]), [1,1,2,2,3,3]);
    }
    */
}
