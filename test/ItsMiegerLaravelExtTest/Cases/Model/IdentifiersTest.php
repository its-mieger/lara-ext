<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 26.11.18
	 * Time: 10:42
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Model;


	use ItsMiegerLaravelExtTest\Cases\TestCase;
	use ItsMiegerLaravelExtTest\Mock\TestModel;

	class IdentifiersTest extends TestCase
	{
		public function testTable() {
			$this->assertEquals('test_table', TestModel::table());
		}

		public function testTableRaw() {
			$this->assertEquals('`test_table`', TestModel::tableRaw());
		}

		public function testField() {
			$this->assertEquals('test_table.id', TestModel::field('id'));
		}

		public function testFieldRaw() {
			$this->assertEquals('`test_table`.`id`', TestModel::fieldRaw('id'));
		}

	}