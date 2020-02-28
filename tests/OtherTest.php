<?php
require_once './ramda.php';


class OtherTests extends \PHPUnit\Framework\TestCase
{
    private static $value = null;

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

    public function test_pipe() {
        $f = R::pipe(R::$negate, R::$inc);
        $this->assertEquals($f(3), -2);
        $double = function ($x) { return $x*2; };
        $f2 = R::pipe($double, R::$negate, R::$inc);
        $this->assertEquals($f2(3, 4), -5);
    }

    public function test_useWith() {
        $this->assertEquals(((R::$useWith)(R::$multiply, [R::$identity, R::$identity]))(3, 4), 12);
        $this->assertEquals((((R::$useWith)(R::$multiply, [R::$identity, R::$identity]))(3))(4), 12);
        $this->assertEquals(((R::$useWith)(R::$multiply, [R::$dec, R::$inc]))(3, 4), 10);
        $this->assertEquals((((R::$useWith)(R::$multiply, [R::$dec, R::$inc]))(3))(4), 10);
    }

    public function test_projection() {
        $abby = ["name"=> 'Abby', "age"=> 7, "hair"=> 'blond', "grade"=> 2];
        $fred = ["name"=> 'Fred', "age"=> 12, "hair"=> 'brown', "grade"=> 7];
        $kids = [$abby, $fred];
        $this->assertEquals((R::$project)(['name', 'grade'], $kids), [["name"=> 'Abby', "grade"=> 2], ["name"=> 'Fred', "grade"=> 7]]);

    }

    public function test_mapObjIndexed() {
        $values = ["x" => 1, "y" => 2, "z" => 3];
        $prependKeyAndDouble = function($num, $key, $obj) {return $key . ($num*2);};
        $this->assertEquals((R::$mapObjIndexed)($prependKeyAndDouble, $values),
            ["x" => 'x2', "y" => 'y4', "z" => 'z6']);
    }

    public function test_compose() {
        $f = (R::$compose)(R::$inc, R::$negate);
        $this->assertEquals($f(3), -2);
    }

    public function test_chain() {
        $duplicate = function($n) {
            return [$n, $n];
        };

        $this->assertEquals((R::$chain)($duplicate, [1,2,3]), [1,1,2,2,3,3]);
    }

    public function test_cond() {
        $fn = (R::$cond)([
            [(R::$equals)(0), (R::$always)("water freezes at 0°C")],
            [(R::$equals)(100), (R::$always)("water boils at 100°C")],
            [R::$T, function($temp) {return "nothing special at $temp °C";}],
        ]);
        $this->assertEquals(($fn)(0), "water freezes at 0°C");
        $this->assertEquals(($fn)(100), "water boils at 100°C");
        $this->assertEquals(($fn)(50), "nothing special at 50 °C");
    }

    public function test_objOf() {
      $matchPhrases = (R::$compose)((R::$objOf)('must'), (R::$map)((R::$objOf)('match_phrase')));
      $this->assertEquals(($matchPhrases)(['foo', 'bar', 'baz']),
      ['must'=>[['match_phrase'=>'foo'],['match_phrase'=>'bar'],['match_phrase'=>'baz']]]);

    }

    public function test_defaultTo() {
      $defaultTo42 = (R::$defaultTo)(42);
      $this->assertEquals(($defaultTo42)(null),42);
      $this->assertEquals(($defaultTo42)(100),100);
      $this->assertEquals(($defaultTo42)('Ramda'),'Ramda');
      $this->assertEquals(($defaultTo42)((float) 'NaN'),42);
    }
/*
    public function test_lens() {
        $xLens = (R::$lens)((R::$prop)('x', (R::$assoc)('x')));
        $this->assertEquals((R::$view)($xLens, ['x'=>1, 'y'=>2]), 1);
        $this->assertEquals((R::$set)($xLens, 4, ['x'=>1, 'y'=>2]), ['x'=>4, 'y'=>2]);
        $this->assertEquals((R::$view)($xLens, R::$negate, ['x'=>1, 'y'=>2]), ['x'=>-1, 'y'=>2]);
    }
*/
}
