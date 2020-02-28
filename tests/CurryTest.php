<?php
require_once './ramda.php';


class CurryTests extends \PHPUnit\Framework\TestCase
{
  protected static $f1;
  protected static $f2;
  protected static $f3;

    protected function setUp(): void
    {
      self::$f1 = function($a) {
          return 2 * $a;
      };
      self::$f2 = function($a, $b) {
          return $a + 2*$b;
      };

      self::$f3 = function($a, $b, $c) {
          return $a*2+$b*3+$c*4;
      };
    }

    public function test_curry1() {
         $c = R::_curry1(self::$f1);
         $this->assertEquals($c(6), 12);
    }

    public function test_curry2() {
         $c = R::_curry2(self::$f2);
         $this->assertEquals($c(1,3), 7);
         $cc = $c(1);
         $this->assertEquals($cc(3), 7);
         $cc2 = $c(R::$_, 3);
         $this->assertEquals($cc2(1), 7);
    }

     public function test_curryN() {
         $c = R::_curryN(3, [], self::$f3);
         $this->assertEquals($c(1,2,3), 20);
         $cc = $c(1,2,R::$_);
         $this->assertEquals($cc(3), 20);
         $cc2 = $c(R::$_,2,R::$_);
         $this->assertEquals($cc2(1,3), 20);
     }

     public function testCurryN() {
         $c = R::curryN(3, self::$f3);
         $this->assertEquals($c(1,2,3), 20);
         $c = R::curryN(1, self::$f1);
         $this->assertEquals($c(14), 28);
     }

     public function testCurry() {
         $c = R::curry(self::$f1);
         $this->assertEquals($c(15), 30);

         $c = R::curry(self::$f3);
         $this->assertEquals($c(1,2,3), 20);
         $cc = $c(1,2,R::$_);
         $this->assertEquals($cc(3), 20);
         $cc = $c(R::$_,2,R::$_);
         $this->assertEquals($cc(1,3), 20);
     }

}
