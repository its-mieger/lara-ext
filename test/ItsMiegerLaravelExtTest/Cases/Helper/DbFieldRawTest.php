<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 26.11.18
	 * Time: 10:07
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Helper;


	use ItsMiegerLaravelExtTest\Cases\TestCase;
	use ItsMiegerLaravelExtTest\Mock\TestModel;

	class DbFieldRawTest extends TestCase
	{
		public function testWithClassName() {

			$this->assertEquals('`test_table`.`id`', db_field_raw(TestModel::class, 'id'));
		}

		public function testWithInstance() {
			$this->assertEquals('`test_table`.`id`', db_field_raw(new TestModel(), 'id'));
		}
	}