<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 04.10.18
	 * Time: 13:01
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Helper;


	use Illuminate\Support\Facades\DB;
	use ItsMiegerLaravelExtTest\Cases\TestCase;
	use ItsMiegerLaravelExtTest\Mock\TestModel;

	class DbConnectionTest extends TestCase
	{
		public function testWithClassName() {
			$connection = DB::connection();

			$this->assertSame($connection, db_connection(TestModel::class));
		}

		public function testWithInstance() {
			$connection = DB::connection();

			$this->assertSame($connection, db_connection(new TestModel()));
		}
	}