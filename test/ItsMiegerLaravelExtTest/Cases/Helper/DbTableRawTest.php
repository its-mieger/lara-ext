<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 26.11.18
	 * Time: 10:22
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Helper;


	use ItsMiegerLaravelExtTest\Cases\TestCase;
	use ItsMiegerLaravelExtTest\Mock\TestModel;

	class DbTableRawTest extends TestCase
	{
		public function testWithClassName() {
			$this->assertEquals('`test_table`', db_table_raw(TestModel::class));
		}

		public function testWithInstance() {
			$this->assertEquals('`test_table`', db_table_raw(new TestModel()));
		}
	}