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
        $this->assertEquals((R::$prop)('x', ['x'=> 100]), 100);
        $this->assertNull((R::$prop)('x', []));
    }

    public function test_propOr() {
        $alice = [
            "name"=> "ALICE",
            "age"=> 101
        ];
        $favorite = (R::$prop)('favoriteLibrary');
        $favoriteWithDefault = (R::$propOr)('Ramda', 'favoriteLibrary');
        $this->assertNull($favorite($alice));
        $this->assertEquals($favoriteWithDefault($alice), 'Ramda');
    }

    public function test_propSatisfies() {
        $this->assertTrue((R::$propSatisfies)(function($x) {return $x>0;}, 'x', ['x'=>1, 'y'=>2]));
    }

    public function test_propEq() {
        $abby = ['name' => 'Abby', 'age' => 7, 'hair' => 'blond'];
        $fred = ['name' => 'Fred', 'age' => 12, 'hair' => 'brown'];
        $rusty = ['name' => 'Rusty', 'age' => 10, 'hair' => 'brown'];
        $alois = ['name' => 'Alois', 'age' => 15, 'disposition' => 'surly'];
        $kids = [$abby, $fred, $rusty, $alois];
        $hasBrownHair = (R::$propEq)('hair', 'brown');
        $this->assertEquals(array_values((R::$filter)($hasBrownHair, $kids)), [$fred, $rusty]);
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

    public function test_concat() {
        $this->assertEquals((R::$concat)([],[]), []);
        $this->assertEquals((R::$concat)([4,5,6],[1,2,3]), [4,5,6,1,2,3]);
        $this->assertEquals((R::$concat)('ABC', 'DEF'), 'ABCDEF');
    }

    // public function test_revserse() {
    //     $this->assertEquals((R::$reverse)([1,2,3]), [3,2,1]);
    //     $this->assertEquals((R::$reverse)([1,2]), [2,1]);
    //     $this->assertEquals((R::$reverse)([1]), [1]);
    //     $this->assertEquals((R::$reverse)([]), []);

    //     $this->assertEquals((R::$reverse)('abc'), 'cba');
    //     $this->assertEquals((R::$reverse)('ab'), 'ba');
    //     $this->assertEquals((R::$reverse)('a'), 'a');
    //     $this->assertEquals((R::$reverse)(''), '');
    // }

    public function test_pair() {
        $this->assertEquals((R::$pair)('foo', 'bar'), ['foo', 'bar']);
    }

    public function test_pick() {
        $this->assertEquals((R::$pick)(['a', 'd'], ['a'=>1, 'b'=>2, 'c'=>3, 'd'=>4]), ['a'=>1, 'd'=>4]);
        $this->assertEquals((R::$pick)(['a', 'e', 'f'], ['a'=>1, 'b'=>2, 'c'=>3, 'd'=>4]), ['a'=>1]);
    }

    public function test_pickAll() {
        $this->assertEquals((R::$pickAll)(['a', 'd'], ['a'=>1, 'b'=>2, 'c'=>3, 'd'=>4]), ['a'=>1, 'd'=>4]);
        $this->assertEquals((R::$pickAll)(['a', 'e', 'f'], ['a'=>1, 'b'=>2, 'c'=>3, 'd'=>4]), ['a'=>1, 'e'=>null, 'f'=>null]);
    }

    public function test_flatten() {
        $this->assertEquals((R::$flatten)([1,2,[3,4],5,[6,[7,8,[9,10,11], 12]]]),
                            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);
    }

    public function test_nth() {
        $list = ["foo", "bar", "baz", "quux"];
        $this->assertEquals((R::$nth)(1, $list), 'bar');
        $this->assertEquals((R::$nth)(-1, $list), 'quux');
        $this->assertEquals((R::$nth)(-99, $list), null);

        $this->assertEquals((R::$nth)(2, 'abc'), 'c');
        $this->assertEquals((R::$nth)(3, 'abc'), '');
    }

    public function test_head() {
        $this->assertEquals((R::$head)(['fi', 'fo', 'fum']), 'fi');
        $this->assertNull((R::$head)([]));
        $this->assertEquals((R::$head)('abc'), 'a');
        $this->assertEquals((R::$head)(''), '');
    }


    public function test_last() {
        $this->assertEquals((R::$last)(['fi', 'fo', 'fum']), 'fum');
        $this->assertNull((R::$last)([]));
        $this->assertEquals((R::$last)('abc'), 'c');
        $this->assertEquals((R::$last)(''), '');
    }

    public function test_slice() {
        $this->assertEquals((R::$slice)(1,3,['a', 'b', 'c', 'd']), ['b','c']);
        $this->assertEquals((R::$slice)(0, -1, ['a', 'b', 'c', 'd']), ['a','b','c']);
        $this->assertEquals((R::$slice)(-3, -1, ['a', 'b', 'c', 'd']), ['b','c']);
        $this->assertEquals((R::$slice)(0, 3, 'ramda'), 'ram');
    }

    public function test_init() {
        $this->assertEquals((R::$init)([1,2,3]), [1,2]);
        $this->assertEquals((R::$init)([1,2]), [1]);
        $this->assertEquals((R::$init)([1]), []);
        $this->assertEquals((R::$init)([]), []);

        $this->assertEquals((R::$init)('abc'), 'ab');
        $this->assertEquals((R::$init)('ab'), 'a');
        $this->assertEquals((R::$init)('a'), '');
        $this->assertEquals((R::$init)(''), '');
    }

    public function test_tail() {
        $this->assertEquals((R::$tail)([1,2,3]), [2,3]);
        $this->assertEquals((R::$tail)([1,2]), [2]);
        $this->assertEquals((R::$tail)([1]), []);
        $this->assertEquals((R::$tail)([]), []);

        $this->assertEquals((R::$tail)('abc'), 'bc');
        $this->assertEquals((R::$tail)('ab'), 'b');
        $this->assertEquals((R::$tail)('a'), '');
        $this->assertEquals((R::$tail)(''), '');
    }

    public function test_zip() {
        $this->assertEquals((R::$zip)([1,2,3],['a','b','c']), [[1,'a'],[2,'b'],[3,'c']]);
    }

    public function test_adjust() {
        $this->assertEquals(array_diff((R::$adjust)((R::$add)(10), 1, [0,1,2]), [0,11,2]), []);
        //$this->assertEquals(array_diff((R::$adjust)((R::$add)(10))(1)([0,1,2]), [0,11,2]), []);   // TODO
    }

    public function test_prepend() {
        $this->assertEquals((R::$prepend)('fee', ['fi', 'fo', 'fum']), ['fee', 'fi', 'fo', 'fum']);
    }

    public function test_of() {
        $this->assertEquals((R::$of)(1), [1]);
    }

    public function test_take() {
         $this->assertEquals((R::$take)(1, ['foo', 'bar', 'baz']), ['foo']);
         $this->assertEquals((R::$take)(2, ['foo', 'bar', 'baz']), ['foo', 'bar']);
         $this->assertEquals((R::$take)(3, ['foo', 'bar', 'baz']), ['foo', 'bar', 'baz']);
         $this->assertEquals((R::$take)(4, ['foo', 'bar', 'baz']), ['foo', 'bar', 'baz']);
         $this->assertEquals((R::$take)(3, 'ramda')              , 'ram');
     }

     public function test_contains() {
        $this->assertTrue((R::$contains)(3, [1, 2, 3]));
        $this->assertFalse((R::$contains)(4, [1, 2, 3]));
        $this->assertTrue((R::$contains)([42], [[42]]));
     }

     // public function test_partition() {
     //    $this->assertEquals(((R::$partition)((R::$contains)('s'), ['sss', 'ttt', 'foo', 'bars'])), [['sss', 'bars'],['ttt','foo']]);
     // }

     public function test_groupBy() {
        $byGrade = (R::$groupBy)(function($student) {
            $score = $student['score'];
            return $score < 65 ? 'F' :
                   ($score < 70 ? 'D' :
                   ($score < 80 ? 'C' :
                   ($score < 90 ? 'B' : 'A')));
        });
        $students = [['name' => 'Abby', 'score' => 84],
                      ['name' => 'Eddy', 'score' => 58],
                      ['name' => 'Jack', 'score' => 69]];

        $expected = ['D' => [['name' => 'Jack', 'score' => 69]],
                     'B' => [['name' => 'Abby', 'score' => 84]],
                     'F' => [['name' => 'Eddy', 'score' => 58]]];
        $this->assertEquals($byGrade($students), $expected);
     }

     public function test_indexOf() {
        $this->assertEquals((R::$indexOf)(3, [1,2,3,4]), 2);
        $this->assertEquals((R::$indexOf)(10, [1,2,3,4]), -1);
     }

    // public function test_flip() {
    //     $mergeThree = function($a, $b, $c) {
    //         return [$a, $b, $c];
    //     };
    //     $this->assertEquals(((R::$flip)($mergeThree))(1,2,3), [2,1,3]);
    // }

/*
    public function test_sortBy() {
        $sortByFirstItem = (R::$sortBy)((R::$prop)(0));
        $sortByNameCaseInsensitive = (R::$sortBy)((R::$compose)(R::$toLower, (R::$prop)('name')));
        $pairs = [[-1, 1], [-2, 2], [-3, 3]];
        $result = sortByFirstItem($pairs);
        $this->assertEquals($result, [[-3, 3], [-2, 2], [-1, 1]]);

        $alice = ['name'=>'ALICE', 'age' =>101];
        $bob = ['name'=>'Bob', 'age' =>-10];
        $clara = ['name'=>'clara', 'age' =>314.159];
        $people = [$clara, $bob, $alice];
        $result = $sortByNameCaseInsensitive($people);
        $this->assertEquals($result, [$alice, $bob, $clara]);

    }
*/
}
