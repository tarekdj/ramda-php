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

}
