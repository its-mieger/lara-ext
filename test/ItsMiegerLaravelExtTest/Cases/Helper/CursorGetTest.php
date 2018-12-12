<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 18.09.18
	 * Time: 12:53
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Helper;


	use ItsMiegerLaravelExtTest\TestCase;

	class CursorGetTest extends TestCase
	{

		public function testArray() {
			$arr = [
				['x' => 5],
				['x' => 9],
				['x' => 12],
				[],
			];

			$ret = cursor_get($arr, 'x', 'z');

			$this->assertInstanceOf(\Generator::class, $ret);

			$this->assertEquals([5,9,12,'z'], iterator_to_array($ret));
		}

		public function testArray_fieldClosure() {
			$arr = [
				['x' => 5],
				['x' => 9],
				['x' => 12],
				[],
			];

			$ret = cursor_get($arr, function($v) { return $v['x'] ?? null; }, 'z');

			$this->assertInstanceOf(\Generator::class, $ret);

			$this->assertEquals([5, 9, 12, null], iterator_to_array($ret));
		}

		public function testArray_dotSyntax() {
			$arr = [
				['x' => ['v' => 5]],
				['x' => ['v' => 9]],
				['x' => ['v' => 12]],
				[],
			];

			$ret = cursor_get($arr, 'x.v', 'z');

			$this->assertInstanceOf(\Generator::class, $ret);

			$this->assertEquals([5, 9, 12, 'z'], iterator_to_array($ret));
		}

		public function testCollection() {
			$arr = collect([
				['x' => 5],
				['x' => 9],
				['x' => 12],
				[],
			]);

			$ret = cursor_get($arr, 'x', 'z');

			$this->assertInstanceOf(\Generator::class, $ret);

			$this->assertEquals([5,9,12,'z'], iterator_to_array($ret));
		}

		public function testClosure() {
			$arr = [
				['x' => 5],
				['x' => 9],
				['x' => 12],
				[],
			];

			$ret = cursor_get(function() use ($arr) { return $arr; }, 'x', 'z');

			$this->assertInstanceOf(\Generator::class, $ret);

			$this->assertEquals([5,9,12,'z'], iterator_to_array($ret));
		}

		public function testGenerator() {
			$arr = [
				['x' => 5],
				['x' => 9],
				['x' => 12],
				[],
			];

			$ret = cursor_get(function() use ($arr) { foreach($arr as $curr) yield $curr; }, 'x', 'z');

			$this->assertInstanceOf(\Generator::class, $ret);

			$this->assertEquals([5,9,12,'z'], iterator_to_array($ret));
		}

	}