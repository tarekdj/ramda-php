<?php
require_once 'r.php';


class WhereTests extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }


    public function test_equals() {
		$this->assertTrue((R::$equals)(1,1));
		$this->assertFalse((R::$equals)(1,'1'));
		$this->assertTrue((R::$equals)([1,2,3],[1,2,3]));
 		
 		$a = [];
 		$a['v'] = $a;
 		$b = [];
 		$b['v'] = $b;
 		$this->assertTrue((R::$equals)($a, $b));
    }

    public function test_gt() {
    	$this->assertTrue((R::$gt)(2,1));
    	$this->assertFalse((R::$gt)(2,2));
    	$this->assertFalse((R::$gt)(2,3));
    	$this->assertFalse((R::$gt)('a','z'));
    	$this->assertTrue((R::$gt)('z','a'));
    }

    public function test_gte() {
    	$this->assertTrue((R::$gte)(2,1));
    	$this->assertTrue((R::$gte)(2,2));
    	$this->assertFalse((R::$gte)(2,3));
    	$this->assertFalse((R::$gte)('a','z'));
    	$this->assertTrue((R::$gte)('z','a'));
    }

    public function test_lt() {
    	$this->assertFalse((R::$lt)(2,1));
    	$this->assertFalse((R::$lt)(2,2));
    	$this->assertTrue((R::$lt)(2,3));
    	$this->assertTrue((R::$lt)('a','z'));
    	$this->assertFalse((R::$lt)('z','a'));
    }

    public function test_lte() {
    	$this->assertFalse((R::$lte)(2,1));
    	$this->assertTrue((R::$lte)(2,2));
    	$this->assertTrue((R::$lte)(2,3));
    	$this->assertTrue((R::$lte)('a','z'));
    	$this->assertFalse((R::$lte)('z','a'));
    }

    public function test_not() {
    	$this->assertFalse((R::$not)(true));
    	$this->assertTrue((R::$not)(false));
    	$this->assertTrue((R::$not)(0));
    	$this->assertFalse((R::$not)(1));
    }



    public function test_whereEq() {
		$pred = (R::$whereEq)(['a'=> 1, 'b'=> 2]);

		$this->assertFalse($pred(['a'=> 1]));
		$this->assertTrue($pred(['a'=> 1, 'b'=>2]));
		$this->assertTrue($pred(['a'=> 1, 'b'=>2, 'c'=>3]));
		$this->assertFalse($pred(['a'=> 1, 'b'=>1]));
    }

    public function test_where() {
    	$incomplete = (R::$filter)((R::$whereEq)(['complete'=>false]));

    	$records = [ ['task'=>'easy', 'complete'=>true],
    				 ['task'=>'hard', 'complete'=>false]];
    	$incomplete_records = $incomplete($records);
    	$this->assertNotNull($incomplete_records);
    	$this->assertEquals(count($incomplete_records), 1);
    	$this->assertEquals(array_values($incomplete_records)[0]['task'], 'hard');
    }

}
