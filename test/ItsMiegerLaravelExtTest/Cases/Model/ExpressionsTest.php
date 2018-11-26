<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 26.11.18
	 * Time: 12:41
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Model;

	use ItsMiegerLaravelExtTest\Cases\TestCase;
	use ItsMiegerLaravelExtTest\Mock\TestModel;

	class ExpressionsTest extends TestCase
	{

		public function testSumExpr() {
			$this->assertEquals('sum(`test_table`.`id`) AS `id`', TestModel::sumExpr('id')->getValue());
			$this->assertEquals('sum(`test_table`.`id`) AS `myAlias`', TestModel::sumExpr('id', 'myAlias')->getValue());
			$this->assertEquals('sum(1)', TestModel::sumExpr(\DB::raw('1'))->getValue());
			$this->assertEquals('sum(1) AS `myAlias`', TestModel::sumExpr(\DB::raw('1'), 'myAlias')->getValue());
		}

		public function testAvgExpr() {
			$this->assertEquals('avg(`test_table`.`id`) AS `id`', TestModel::avgExpr('id')->getValue());
			$this->assertEquals('avg(`test_table`.`id`) AS `myAlias`', TestModel::avgExpr('id', 'myAlias')->getValue());
			$this->assertEquals('avg(1)', TestModel::avgExpr(\DB::raw('1'))->getValue());
			$this->assertEquals('avg(1) AS `myAlias`', TestModel::avgExpr(\DB::raw('1'), 'myAlias')->getValue());
		}

		public function testMinExpr() {
			$this->assertEquals('min(`test_table`.`id`) AS `id`', TestModel::minExpr('id')->getValue());
			$this->assertEquals('min(`test_table`.`id`) AS `myAlias`', TestModel::minExpr('id', 'myAlias')->getValue());
			$this->assertEquals('min(1)', TestModel::minExpr(\DB::raw('1'))->getValue());
			$this->assertEquals('min(1) AS `myAlias`', TestModel::minExpr(\DB::raw('1'), 'myAlias')->getValue());

		}

		public function testMaxExpr() {
			$this->assertEquals('max(`test_table`.`id`) AS `id`', TestModel::maxExpr('id')->getValue());
			$this->assertEquals('max(`test_table`.`id`) AS `myAlias`', TestModel::maxExpr('id', 'myAlias')->getValue());
			$this->assertEquals('max(1)', TestModel::maxExpr(\DB::raw('1'))->getValue());
			$this->assertEquals('max(1) AS `myAlias`', TestModel::maxExpr(\DB::raw('1'), 'myAlias')->getValue());
		}

		public function testLowerExpr() {
			$this->assertEquals('lower(`test_table`.`id`) AS `id`', TestModel::lowerExpr('id')->getValue());
			$this->assertEquals('lower(`test_table`.`id`) AS `myAlias`', TestModel::lowerExpr('id', 'myAlias')->getValue());
			$this->assertEquals('lower(1)', TestModel::lowerExpr(\DB::raw('1'))->getValue());
			$this->assertEquals('lower(1) AS `myAlias`', TestModel::lowerExpr(\DB::raw('1'), 'myAlias')->getValue());
		}

		public function testUpperExpr() {
			$this->assertEquals('upper(`test_table`.`id`) AS `id`', TestModel::upperExpr('id')->getValue());
			$this->assertEquals('upper(`test_table`.`id`) AS `myAlias`', TestModel::upperExpr('id', 'myAlias')->getValue());
			$this->assertEquals('upper(1)', TestModel::upperExpr(\DB::raw('1'))->getValue());
			$this->assertEquals('upper(1) AS `myAlias`', TestModel::upperExpr(\DB::raw('1'), 'myAlias')->getValue());
		}


		public function testCountExpr() {
			$this->assertEquals('count(`test_table`.`id`) AS `id`', TestModel::countExpr('id')->getValue());
			$this->assertEquals('count(`test_table`.`id`, `test_table`.`name`)', TestModel::countExpr(['id', 'name'])->getValue());
			$this->assertEquals('count(`test_table`.`id`, `test_table`.`name`) AS `myAlias`', TestModel::countExpr(['id', 'name'], 'myAlias')->getValue());
			$this->assertEquals('count(`test_table`.`id`) AS `myAlias`', TestModel::countExpr('id', 'myAlias')->getValue());
			$this->assertEquals('count(1)', TestModel::countExpr(\DB::raw('1'))->getValue());
			$this->assertEquals('count(1) AS `myAlias`', TestModel::countExpr(\DB::raw('1'), 'myAlias')->getValue());
			$this->assertEquals('count(1, 2)', TestModel::countExpr([\DB::raw('1'), \DB::raw('2')])->getValue());
			$this->assertEquals('count(1, 2) AS `myAlias`', TestModel::countExpr([\DB::raw('1'), \DB::raw('2')], 'myAlias')->getValue());
			
			$this->assertEquals('count(DISTINCT `test_table`.`id`) AS `id`', TestModel::countExpr('id', null,true)->getValue());
			$this->assertEquals('count(DISTINCT `test_table`.`id`, `test_table`.`name`)', TestModel::countExpr(['id', 'name'], null,true)->getValue());
			$this->assertEquals('count(DISTINCT `test_table`.`id`, `test_table`.`name`) AS `myAlias`', TestModel::countExpr(['id', 'name'], 'myAlias', true)->getValue());
			$this->assertEquals('count(DISTINCT `test_table`.`id`) AS `myAlias`', TestModel::countExpr('id', 'myAlias', true)->getValue());
			$this->assertEquals('count(DISTINCT 1)', TestModel::countExpr(\DB::raw('1'), null,true)->getValue());
			$this->assertEquals('count(DISTINCT 1) AS `myAlias`', TestModel::countExpr(\DB::raw('1'), 'myAlias', true)->getValue());
			$this->assertEquals('count(DISTINCT 1, 2)', TestModel::countExpr([\DB::raw('1'), \DB::raw('2')], null,true)->getValue());
			$this->assertEquals('count(DISTINCT 1, 2) AS `myAlias`', TestModel::countExpr([\DB::raw('1'), \DB::raw('2')], 'myAlias', true)->getValue());
		}

		public function testCountDistinctExpr() {
			$this->assertEquals('count(DISTINCT `test_table`.`id`) AS `id`', TestModel::countDistinctExpr('id')->getValue());
			$this->assertEquals('count(DISTINCT `test_table`.`id`, `test_table`.`name`)', TestModel::countDistinctExpr(['id', 'name'])->getValue());
			$this->assertEquals('count(DISTINCT `test_table`.`id`, `test_table`.`name`) AS `myAlias`', TestModel::countDistinctExpr(['id', 'name'], 'myAlias')->getValue());
			$this->assertEquals('count(DISTINCT `test_table`.`id`) AS `myAlias`', TestModel::countDistinctExpr('id', 'myAlias')->getValue());
			$this->assertEquals('count(DISTINCT 1)', TestModel::countDistinctExpr(\DB::raw('1'))->getValue());
			$this->assertEquals('count(DISTINCT 1) AS `myAlias`', TestModel::countDistinctExpr(\DB::raw('1'), 'myAlias')->getValue());
			$this->assertEquals('count(DISTINCT 1, 2)', TestModel::countDistinctExpr([\DB::raw('1'), \DB::raw('2')])->getValue());
			$this->assertEquals('count(DISTINCT 1, 2) AS `myAlias`', TestModel::countDistinctExpr([\DB::raw('1'), \DB::raw('2')], 'myAlias')->getValue());
		}

		public function testFunctionExpr() {
			$this->assertEquals('fn(`test_table`.`id`) AS `id`', TestModel::functionExpr('fn', 'id')->getValue());
			$this->assertEquals('fn(`test_table`.`id`, `test_table`.`name`)', TestModel::functionExpr('fn', ['id', 'name'])->getValue());
			$this->assertEquals('fn(`test_table`.`id`, `test_table`.`name`) AS `myAlias`', TestModel::functionExpr('fn', ['id', 'name'], 'myAlias')->getValue());
			$this->assertEquals('fn(`test_table`.`id`) AS `myAlias`', TestModel::functionExpr('fn', 'id', 'myAlias')->getValue());
			$this->assertEquals('fn(1)', TestModel::functionExpr('fn', \DB::raw('1'))->getValue());
			$this->assertEquals('fn(1) AS `myAlias`', TestModel::functionExpr('fn', \DB::raw('1'), 'myAlias')->getValue());
			$this->assertEquals('fn(1, 2)', TestModel::functionExpr('fn', [\DB::raw('1'), \DB::raw('2')])->getValue());
			$this->assertEquals('fn(1, 2) AS `myAlias`', TestModel::functionExpr('fn', [\DB::raw('1'), \DB::raw('2')], 'myAlias')->getValue());
			$this->assertEquals('fn(PRE 1, 2) AS `myAlias`', TestModel::functionExpr('fn', [\DB::raw('1'), \DB::raw('2')], 'myAlias', \DB::raw('PRE'))->getValue());
		}



	}