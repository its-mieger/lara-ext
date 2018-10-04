<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 04.10.18
	 * Time: 12:20
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Helper;


	use ItsMiegerLaravelExtTest\Cases\TestCase;
	use ItsMiegerLaravelExtTest\Mock\TestModel;

	class DbTableTest extends TestCase
	{

		public function testWithClassName() {
			$this->assertEquals('test_table', db_table(TestModel::class));
		}

		public function testWithInstance() {
			$this->assertEquals('test_table', db_table(new TestModel()));
		}

	}