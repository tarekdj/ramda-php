<?php
require_once 'r.php';


class WhyNotTests extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function test_example1() {
    	$incomplete = (R::$filter)((R::$whereEq)(['complete'=>false]));

    	$records = [ ['task'=>'easy', 'complete'=>true],
    				 ['task'=>'hard', 'complete'=>false]];
    	$incomplete_records = $incomplete($records);
    	$this->assertNotNull($incomplete_records);
    	$this->assertEquals(count($incomplete_records), 1);
    	$this->assertEquals(array_values($incomplete_records)[0]['task'], 'hard');
    }

}
