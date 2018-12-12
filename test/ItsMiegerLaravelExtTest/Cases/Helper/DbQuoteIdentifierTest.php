<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 26.11.18
	 * Time: 11:13
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Helper;


	use Illuminate\Support\Facades\DB;
	use ItsMiegerLaravelExtTest\TestCase;
	use ItsMiegerLaravelExtTest\Mock\TestModel;

	class DbQuoteIdentifierTest extends TestCase
	{
		public function testWithNull() {
			$this->assertEquals("\"my_table\"", db_quote_identifier('my_table'));
			$this->assertEquals("\"my_table\".\"id\"", db_quote_identifier('my_table.id'));
		}

		public function testWithString() {
			$this->assertEquals("\"my_table\"", db_quote_identifier('my_table', DB::connection()->getName()));
			$this->assertEquals("\"my_table\".\"id\"", db_quote_identifier('my_table.id', DB::connection()->getName()));
		}

		public function testWithModel() {
			$this->assertEquals("\"my_table\"", db_quote_identifier('my_table', new TestModel()));
			$this->assertEquals("\"my_table\".\"id\"", db_quote_identifier('my_table.id', new TestModel()));
		}

		// TODO: test with relation
	}