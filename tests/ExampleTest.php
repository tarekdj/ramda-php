<?php
require_once 'r.php';


class ExampleTests extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    // Example from: http://fr.umio.us/why-ramda/
    public function test_why_ramda_example1() {
    	$incomplete = (R::$filter)((R::$whereEq)(['complete'=>false]));

    	$records = [ ['task'=>'easy', 'complete'=>true],
    				 ['task'=>'hard', 'complete'=>false]];
    	$incomplete_records = $incomplete($records);
    	$this->assertNotNull($incomplete_records);
    	$this->assertEquals(count($incomplete_records), 1);
    	$this->assertEquals(array_values($incomplete_records)[0]['task'], 'hard');
    }

    public function test_why_ramda_example2() {
        $incomplete = (R::$filter)((R::$where)(['complete'=> false]));
        $sortByDate = (R::$sortBy)((R::$prop)('dueDate'));
        $sortByDateDescend = (R::$compose)((R::$reverse), $sortByDate);
        $importantFields = (R::$project)(['title', 'dueDate']);
        $groupByUser = (R::$partition)((R::$prop)('username'));
        $activeByUser = (R::$compose)($groupByUser, $incomplete);
        $topDataAllUsers = (R::$compose)((R::$mapObjIndexed)((R::$compose)($importantFields, 
            (R::$take)(5), $sortByDateDescend)), $activeByUser);

        $people = [
            'Michael' => [
                ['dueDate' => '2014-06-22', 'title' => 'Integrate types with main code'],
                ['dueDate' => '2014-06-15', 'title' => 'Finish algebraic types'],
                ['dueDate' => '2014-06-06', 'title' => 'Types infrastucture'],
                ['dueDate' => '2014-05-24', 'title' => 'Separating generators'],
                ['dueDate' => '2014-05-17', 'title' => 'Add modulo function']
            ],
            'Richard' => [
                ['dueDate' => '2014-06-22', 'title' => 'API documentation'],
                ['dueDate' => '2014-06-15', 'title' => 'Overview documentation']
            ],
            'Scott' => [
                ['dueDate' => '2014-06-22', 'title' => 'Complete build system'],
                ['dueDate' => '2014-06-15', 'title' => 'Determine versioning scheme'],
                ['dueDate' => '2014-06-09', 'title' => 'Add `mapObj`'],
                ['dueDate' => '2014-06-05', 'title' => 'Fix `and`/`or`/`not`'],
                ['dueDate' => '2014-06-01', 'title' => 'Fold algebra branch back in']
            ]
        ];

        // TODO: add assertion
    }

    //Example from: http://buzzdecafe.github.io/code/2014/05/16/introducing-ramda
    public function test_introducing_ramda() {
        $validUsersNamedBuzz = (R::$filter)((R::$where)(['name' => 'Buzz', 'errors' => (R::$isEmpty)]));
        
        // `prop` takes two arguments. If I just give it one, I get a function back
        $moo = (R::$prop)('moo');
        // when I call that function with one argument, I get the result.
        $this->assertEquals($moo(['moo' => 'cow']), 'cow');


        $amtAdd1Mod7 = (R::$compose)((R::$modulo)(R::$_, 7), (R::$add)(1), (R::$prop)('amount'));

        // we can use that as is:
        $this->assertEquals($amtAdd1Mod7(['amount' => 17]),4);
        $this->assertEquals($amtAdd1Mod7(['amount' => 987]),1);
        $this->assertEquals($amtAdd1Mod7(['amount' => 68]),6);
        // etc. 
        
        // But we can also use our composed function on a list of objects, e.g. to `map`:
        $amountObjects = [
          ['amount' => 903], ['amount' => 2875654], ['amount' => 6]
        ];
        $this->assertEquals((R::$map)($amtAdd1Mod7, $amountObjects), [1,6,0]);

        // of course, `map` is also curried, so you can generate a new function 
        // using `amtAdd1Mod7` that will wait for a list of "amountObjects" to 
        // get passed in:
        $amountsToValue = (R::$map)($amtAdd1Mod7);
        $this->assertEquals($amountsToValue($amountObjects), [1, 6, 0]);
    }

}
