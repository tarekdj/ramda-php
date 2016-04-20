<?php
require_once './ramda.php';


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
        $incomplete = (R::$filter)((R::$whereEq)(['complete'=> false]));
        $sortByDate = (R::$sortBy)((R::$prop)('dueDate'));
        $sortByDateDescend = (R::$compose)((R::$reverse), $sortByDate);
        $importantFields = (R::$project)(['title', 'dueDate']);
        $groupByUser = (R::$groupBy)((R::$prop)('username'));
        $activeByUser = (R::$compose)($groupByUser, $incomplete);
        $topDataAllUsers = (R::$compose)((R::$mapObjIndexed)((R::$compose)($importantFields, 
            (R::$take)(5), $sortByDateDescend)), $activeByUser);

        $tasks = [
            ['username' => 'Michael', 'dueDate' => '2014-06-22', 'title' => 'Integrate types with main code', 'complete' => false],
            ['username' => 'Michael', 'dueDate' => '2014-06-15', 'title' => 'Finish algebraic types', 'complete' => false],
            ['username' => 'Michael', 'dueDate' => '2014-06-06', 'title' => 'Types infrastucture', 'complete' => false],
            ['username' => 'Michael', 'dueDate' => '2014-05-24', 'title' => 'Separating generators', 'complete' => false],
            ['username' => 'Michael', 'dueDate' => '2014-05-17', 'title' => 'Add modulo function', 'complete' => true],
            ['username' => 'Richard', 'dueDate' => '2014-06-22', 'title' => 'API documentation', 'complete' => true],
            ['username' => 'Richard', 'dueDate' => '2014-06-15', 'title' => 'Overview documentation', 'complete' => false],
            ['username' => 'Scott', 'dueDate' => '2014-06-22', 'title' => 'Complete build system', 'complete' => false],
            ['username' => 'Scott', 'dueDate' => '2014-06-15', 'title' => 'Determine versioning scheme', 'complete' => false],
            ['username' => 'Scott', 'dueDate' => '2014-06-09', 'title' => 'Add `mapObj`', 'complete' => false],
            ['username' => 'Scott', 'dueDate' => '2014-06-05', 'title' => 'Fix `and`/`or`/`not`', 'complete' => true],
            ['username' => 'Scott', 'dueDate' => '2014-06-01', 'title' => 'Fold algebra branch back in', 'complete' => true]
        ];

        $sorted_tasks = $sortByDate($tasks);
        $this->assertEquals($sorted_tasks[0]['dueDate'], '2014-05-17');
        $this->assertEquals($sorted_tasks[count($sorted_tasks)-1]['dueDate'], '2014-06-22');
        $sorted_tasks = $sortByDateDescend($tasks);
        $this->assertEquals($sorted_tasks[0]['dueDate'], '2014-06-22');
        $this->assertEquals($sorted_tasks[count($sorted_tasks)-1]['dueDate'], '2014-05-17');
        $incomplete_tasks = $incomplete($tasks);
        $this->assertEquals(count($incomplete_tasks), 8);
        $groups = $topDataAllUsers($tasks);
        $this->assertEquals(array_keys($groups), ['Michael', 'Richard', 'Scott']);
        $this->assertEquals(count($groups['Michael']), 4);
        $this->assertEquals(count($groups['Richard']), 1);
        $this->assertEquals(count($groups['Scott']), 3);
        $this->assertEquals($groups['Richard'][0]['title'], 'Overview documentation');
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
                ['id' => 107, 'complete' => false,             'priority' => "high",
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
                          'title' => "Alter all quuxes",  'created' => "10/5/2013"],
                ['id' => 122, 'complete' => false,             'priority' => "high",
                          'dueDate' => "2013-11-01",      'username' => "Mike",
                          'title' => "Fix the bar",       'created' => "9/22/2013"],
                ['id' => 123, 'complete' => true,             'priority' => "high",
                          'dueDate' => "2013-11-22",      'username' => "Mike",
                          'title' => "Fix the foobar",       'created' => "9/22/2013"],

            ]
        ];

        $getIncompleteTaskSummaries = function($membername) {
          return (R::$compose)(
            (R::$sortBy)((R::$prop)('dueDate')),
            (R::$map)((R::$pick)(['id', 'dueDate', 'title', 'priority'])),
            (R::$reject)((R::$propEq)('complete', true)),
            (R::$filter)((R::$propEq)('username', $membername)),
            (R::$prop)('tasks'));
        };

        $tasks = ($getIncompleteTaskSummaries("Mike"))($data);
        $this->assertEquals(count($tasks), 2);
        $this->assertEquals(array_keys($tasks[0]), ['id', 'dueDate', 'title', 'priority']);
        $this->assertEquals($tasks[1]['id'], 107);
    }

}
