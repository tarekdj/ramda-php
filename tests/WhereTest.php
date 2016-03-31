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


    public function test_comparisons() {
		$this->assertTrue((R::$equals)(1,1));
		$this->assertFalse((R::$equals)(1,'1'));
		$this->assertTrue((R::$equals)([1,2,3],[1,2,3]));
 		
 		$a = [];
 		$a['v'] = $a;
 		$b = [];
 		$b['v'] = $b;
 		$this->assertTrue((R::$equals)($a, $b));
    }
/*
    public function test_whereEq() {
		$pred = (R::$whereEq)(['a'=> 1, 'b'=> 2]);

		$this->assertFalse($pred(['a'=> 1]));
		$this->assertTrue($pred(['a'=> 1, 'b'=>2]));
		$this->assertTrue($pred(['a'=> 1, 'b'=>2, 'c'=>3]));
		$this->assertFalse($pred(['a'=> 1, 'b'=>1]));
    }

    public function test_where() {
    	$incomplete = R::$filter(R::$whereEq([complete=>false]));

    	$records = [ [task=>'easy', complete=>true],
    				 [task=>'hard', complete=>false]];
    	$incomplete_records = $incomplete($records);
    	$this->assertNotNull($incomplete_records);
    	$this->assertEquals(count($incomplete_records), 1);
    	$this->assertEquals($incomplete_records[0]['task'], 'hard');
    }
*/
}
