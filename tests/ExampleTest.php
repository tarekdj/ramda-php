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
                ['dueDate' => '2014-06-22', 'title' => 'Integrate types with main code', 'complete' => false],
                ['dueDate' => '2014-06-15', 'title' => 'Finish algebraic types', 'complete' => false],
                ['dueDate' => '2014-06-06', 'title' => 'Types infrastucture', 'complete' => false],
                ['dueDate' => '2014-05-24', 'title' => 'Separating generators', 'complete' => false],
                ['dueDate' => '2014-05-17', 'title' => 'Add modulo function', 'complete' => true]
            ],
            'Richard' => [
                ['dueDate' => '2014-06-22', 'title' => 'API documentation', 'complete' => true],
                ['dueDate' => '2014-06-15', 'title' => 'Overview documentation', 'complete' => false]
            ],
            'Scott' => [
                ['dueDate' => '2014-06-22', 'title' => 'Complete build system', 'complete' => false],
                ['dueDate' => '2014-06-15', 'title' => 'Determine versioning scheme', 'complete' => false],
                ['dueDate' => '2014-06-09', 'title' => 'Add `mapObj`', 'complete' => false],
                ['dueDate' => '2014-06-05', 'title' => 'Fix `and`/`or`/`not`', 'complete' => true],
                ['dueDate' => '2014-06-01', 'title' => 'Fold algebra branch back in', 'complete' => true]
            ]
        ];

        // TODO: add assertion
        //$this->assertEquals($incomplete($people), []);
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

    // Example from: http://fr.umio.us/favoring-curry/
    public function test_flavoring_curry() {
        $numbers = [1, 2, 3, 4, 5];
        $this->assertEquals((R::$reduce)(R::$add, 0, $numbers), 15);

        $data = [
            'result' => "SUCCESS",
            'interfaceVersion' => "1.0.3",
            'requested' => "10/17/2013 15:31:20",
            'lastUpdated' => "10/16/2013 10:52:39",
            'tasks' => [
                ['id' => 104, 'complete' => false,            'priority' => "high",
                          'dueDate' => "2013-11-29",      'username' => "Scott",
                          'title' => "Do something",      'created' => "9/22/2013"],
                ['id' => 105, 'complete' => false,            'priority' => "medium",
                          'dueDate' => "2013-11-22",      'username' => "Lena",
                          'title' => "Do something else", 'created' => "9/22/2013"],
                ['id' => 107, 'complete' => true,             'priority' => "high",
                          'dueDate' => "2013-11-22",      'username' => "Mike",
                          'title' => "Fix the foo",       'created' => "9/22/2013"],
                ['id' => 108, 'complete' => false,            'priority' => "low",
                          'dueDate' => "2013-11-15",      'username' => "Punam",
                          'title' => "Adjust the bar",    'created' => "9/25/2013"],
                ['id' => 110, 'complete' => false,            'priority' => "medium",
                          'dueDate' => "2013-11-15",      'username' => "Scott",
                          'title' => "Rename everything", 'created' => "10/2/2013"],
                ['id' => 112, 'complete' => true,             'priority' => "high",
                          'dueDate' => "2013-11-27",      'username' => "Lena",
                          'title' => "Alter all quuxes",  'created' => "10/5/2013"]
            ]
        ];

        $getIncompleteTaskSummaries = function($membername) {
          (R::$compose)(
            (R::$sortBy)((R::$prop)('dueDate')),
            (R::$map)((R::$pick)(['id', 'dueDate', 'title', 'priority'])),
            (R::$reject)((R::$propEq)('complete', true)),
            (R::$filter)((R::$propEq)('username', $membername)),
            (R::$prop)('tasks'));
        };

        // TODO: add assertion
        // print_r(($getIncompleteTaskSummaries)("Mike")($data));
        //$this->assertEquals(($getIncompleteTaskSummaries("Mike"))($data), []);
    }

}
