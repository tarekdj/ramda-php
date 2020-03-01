<?php
use RamdaPhp\R;

class OtherTests extends \PHPUnit\Framework\TestCase
{
    private static $value = null;

    public function testConcat() {

      $this->assertEquals(R::_concat([1, 2], [3, 4]), [1, 2, 3, 4]);
    }

    public function testAddIndex() {

      $times2 = function ($x) {
        return $x * 2;
      };
      $addIndexParam = function ($x, $idx) {
        return (int)$x + (int)$idx;
      };
      $squareEnds = function ($x, $idx, $list) {
        return ($idx === 0 || $idx === count($list) - 1) ? $x * $x : $x;
      };

      $mapIndexed = R::addIndex(R::$map);

      $this->assertEquals($mapIndexed($times2, [1, 2, 3]), [2, 4, 6]);
      $this->assertEquals($mapIndexed($addIndexParam, [8, 6, 7]), [8, 7, 9]);
      $this->assertEquals($mapIndexed($squareEnds, [8, 6, 7, 5, 3, 0, 9]), [64, 6, 7, 5, 3, 0, 81]);

      $makeSquareEnds = $mapIndexed($squareEnds);
      $this->assertEquals($makeSquareEnds([8, 6, 7, 5, 3, 0, 9]), [64, 6, 7, 5, 3, 0, 81]);

      $addIndex = R::$addIndex;
      $mapidx = $addIndex(R::$map);
      $this->assertEquals($mapidx($times2, [1, 2, 3]), [2, 4, 6]);
      $this->assertEquals($mapidx($addIndexParam, [8, 6, 7]), [8, 7, 9]);

    }

    public function testPaths() {
      $obj = json_decode('{
        "a": {
          "b": {
            "c": 1,
            "d": 2
          }
        },
        "p": [{"q": 3}, "Hi"],
        "x": {
          "y": "Alice",
          "z": [[{}]]
        }
      }');

      // Takes paths and returns values at those paths.
      $this->assertEquals(R::paths([['a', 'b', 'c'], ['x', 'y']], $obj), [1, 'Alice']);
      $this->assertEquals(R::paths([['a', 'b', 'd'], ['p', 'q']], $obj), [2, null]);

      // Takes a paths that contains indices into arrays.
      $this->assertEquals(R::paths([['p', 0, 'q'], ['x', 'z', 0, 0]], $obj), [3, (new stdClass())]);
      $this->assertEquals(R::paths([['p', 0, 'q'], ['x', 'z', 2, 1]], $obj), [3, null]);

      // Takes a path that contains negative indices into arrays.
      $this->assertEquals(R::paths([['p', -2, 'q'], ['p', -1]], $obj), [3, 'Hi']);
      $this->assertEquals(R::paths([['p', -4, 'q'], ['x', 'z', -1, 0]], $obj), [null, (new stdClass())]);

      // Gets a deep property's value from objects.
      $this->assertEquals(R::paths([['a', 'b']], $obj), [$obj->a->b]);
      $this->assertEquals(R::paths([['p', 0]], $obj), [$obj->p[0]]);

      // Returns undefined for items not found.
      $this->assertEquals(R::paths([['a', 'x', 'y']], $obj), [null]);
      $this->assertEquals(R::paths([['p', 2]], $obj), [null]);

      // Other tests.
      $paths = R::$paths;
      $this->assertEquals($paths([['a', 'x', 'y']], $obj), [null]);
      $this->assertEquals($paths([['p', 0]], $obj), [$obj->p[0]]);
      $this->assertEquals($paths([['p', -2, 'q'], ['p', -1]], $obj), [3, 'Hi']);
      $this->assertEquals($paths([['a', 'b', 'c'], ['x', 'y']], $obj), [1, 'Alice']);
      $this->assertEquals($paths([['a', 'b', 'd'], ['p', 'q']], $obj), [2, null]);
      $this->assertEquals($paths([['p', 0, 'q'], ['x', 'z', 0, 0]], $obj), [3, (new stdClass())]);

    }

    public function testPath() {
      // Takes a path and an object and returns the value at the path or undefined.
      $obj = json_decode('{
          "a": {
            "b": {
              "c": 100,
              "d": 200
            },
            "e": {
              "f": [100, 101, 102],
              "g": "G"
            },
            "h": "H"
          },
          "i": "I",
          "j": ["J"]
      }');

      $this->assertEquals(R::path(['a', 'b', 'c'], $obj), 100);
      $this->assertEquals(R::path([], $obj), $obj);
      $this->assertEquals(R::path(['a', 'e', 'f', 1], $obj), 101);
      $this->assertEquals(R::path(['j', 0], $obj), 'J');
      $this->assertEquals(R::path(['j', 1], $obj), null);

      // takes a path that contains indices into arrays.
      $obj = json_decode('{
          "a": [[{}], [{"x": "first"}, {"x": "second"}, {"x": "third"}, {"x": "last"}]]
      }');
      $this->assertEquals(R::path(['a', 0, 0], $obj), (new stdClass()));
      $this->assertEquals(R::path(['a', 0, 1], $obj), null);
      $this->assertEquals(R::path(['a', 1, 0, 'x'], $obj), 'first');
      $this->assertEquals(R::path(['a', 1, 1, 'x'], $obj), 'second');
      $this->assertEquals(R::path([0], ['A']), 'A');

      //Takes a path that contains negative indices into arrays.
      $this->assertEquals(R::path(['x', -2], json_decode('{"x": ["a", "b", "c", "d"]}')), 'c');
      $this->assertEquals(R::path([-1, 'y'], json_decode('[{"x": 1, "y": 99}, {"x": 2, "y": 98}, {"x": 3, "y": 97}]')), 97);

      $deepObject = json_decode('{"a": {"b": {"c": "c"}}, "falseVal": false, "nullVal": null, "undefinedVal": "undefined", "arrayVal": ["arr"]}');

      // Gets a deep property's value from objects.
      $this->assertEquals(R::path(['a', 'b', 'c'], $deepObject), 'c');
      $this->assertEquals(R::path(['a'], $deepObject), $deepObject->a);

      // Returns undefined for items not found.
      $this->assertEquals(R::path(['a', 'b', 'foo'], $deepObject), null);
      $this->assertEquals(R::path(['bar'], $deepObject), null);
      $this->assertEquals(R::path(['a', 'b'], json_decode('{"a": null}')), null);

      // Works with falsy items'.
      $this->assertEquals(R::path(['toString'], false), '');

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
