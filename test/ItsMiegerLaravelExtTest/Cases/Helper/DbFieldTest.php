<?php

	namespace ItsMiegerLaravelExtTest\Cases\Helper;


	use ItsMiegerLaravelExtTest\Cases\TestCase;
	use ItsMiegerLaravelExtTest\Mock\TestModel;

	class DbFieldTest extends TestCase
	{
		public function testWithClassName() {
			$this->assertEquals('test_table.id', db_field(TestModel::class, 'id'));
		}

		public function testWithInstance() {
			$this->assertEquals('test_table.id', db_field(new TestModel(), 'id'));
		}
	}