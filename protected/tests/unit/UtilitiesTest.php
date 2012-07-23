<?php 

class UtilitiesTest extends CTestCase
{
	// public $fixtures=array(); // No fixtures needed for this test

	public function testIsDevelopment()
	{
		$_SERVER['SERVER_NAME'] = "localhost";
		$this->assertTrue(Utilities::isDevelopment(), 'localhost was not considered local');

		$_SERVER['SERVER_NAME'] = "http://localhost";
		$this->assertTrue(Utilities::isDevelopment(), 'http://localhost was not considered local');

		$_SERVER['SERVER_NAME'] = "test.dev";
		$this->assertTrue(Utilities::isDevelopment(), '.dev was not considered local');

		$_SERVER['SERVER_NAME'] = "www.testsite.com";
		$this->assertFalse(Utilities::isDevelopment(), "www.testsite.com was considered local");
	}

	public function testMergeIncludedArray()
	{
		// Merge non existant file
		$loMerge = Utilities::mergeIncludedArray(array('nonExisting.php'));
		$this->assertTrue(empty($loMerge), 'Array was not empty');

		$loMerge = Utilities::mergeIncludedArray(array(
			dirname(__FILE__).'/Utilities_array1.php'));
		$this->assertTrue(isset($loMerge['item1']) && 
					count($loMerge['item1'])==3 &&
					$loMerge['item1']['subitem3'] == 3, 'The array was not imported correctly');

		$loMerge = Utilities::mergeIncludedArray(array(
			dirname(__FILE__).'/Utilities_array2.php', dirname(__FILE__).'/Utilities_array1.php'));
		$this->assertTrue(isset($loMerge['item1']) && 
						count($loMerge['item1'])==5 && 
						isset($loMerge['item2']) && 
						count($loMerge['item2']) == 0 &&
						$loMerge['item1']['subitem3'] == 0, 'Two arrays not merged correctly');

		$loMerge = Utilities::mergeIncludedArray(array(
			dirname(__FILE__).'/Utilities_array1.php', dirname(__FILE__).'/Utilities_array2.php'));
		$this->assertTrue(isset($loMerge['item1']) && 
						count($loMerge['item1'])==5 && 
						isset($loMerge['item2']) && 
						count($loMerge['item2']) == 0 &&
						$loMerge['item1']['subitem3'] == 3, 'Two arrays not merged correctly');

		$loMerge = Utilities::mergeIncludedArray(array(
			dirname(__FILE__).'/Utilities_array1.php', 
			dirname(__FILE__).'/Utilities_array2.php',
			dirname(__FILE__).'/Utilities_array3.php'));
		$this->assertTrue(isset($loMerge['item1']) && 
						count($loMerge['item1'])==5 && 
						isset($loMerge['item2']) && 
						count($loMerge['item2']) == 0 &&
						$loMerge['item1']['subitem3'] == 3, 'Two arrays and empty file arrays not merged correctly');

		$loMerge = Utilities::mergeIncludedArray(array(
			dirname(__FILE__).'/Utilities_array1.php', 
			dirname(__FILE__).'/Utilities_array2.php',
			dirname(__FILE__).'/Utilities_array4.php'));
		$this->assertTrue(isset($loMerge['item1']) && 
						count($loMerge['item1'])==5 && 
						isset($loMerge['item2']) && 
						count($loMerge['item2']) == 0 &&
						$loMerge['item1']['subitem3'] == 3, 'Two arrays and no return arrays not merged correctly');
	}

	public function testISNULL()
	{
		$loObject1 = "TEST";
		$loObject2 = "TEST1";

		$this->assertNull(Utilities::ISNULL(NULL));
		$this->assertSame(Utilities::ISNULL(NULL, $loObject1), $loObject1);
		$this->assertSame(Utilities::ISNULL(NULL, $loObject1, $loObject2), $loObject1);
		$this->assertSame(Utilities::ISNULL($loObject1, NULL), $loObject1);
		$this->assertSame(Utilities::ISNULL($loObject1, $loObject2), $loObject1);
	}
}

?>