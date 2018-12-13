<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 13.12.18
	 * Time: 10:41
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Helper;


	use ItsMiegerLaravelExtTest\TestCase;

	class GroupByConsecutiveTest extends TestCase
	{

		public function testGroupByConsecutive_array() {

			$data = [
				[
					'x' => 15,
					'y' => 'a',
				],
				[
					'x' => 16,
					'y' => 'a',
				],
				[
					'x' => 17,
					'y' => 'b',
				],
				[
					'x' => 18,
					'y' => 'c',
				],
			];


			$ret = group_by_consecutive($data, 'y');

			$this->assertInstanceOf(\Generator::class, $ret);

			$retData = iterator_to_array($ret);
			$this->assertEquals([
				[
					$data[0],
					$data[1],
				],
				[
					$data[2]
				],
				[
					$data[3]
				],
			], $retData);

		}

		public function testGroupByConsecutive_objects() {

			$c1 = new \stdClass();
			$c1->x = 15;
			$c1->y = 'a';

			$c2 = new \stdClass();
			$c2->x = 16;
			$c2->y = 'a';

			$c3 = new \stdClass();
			$c3->x = 17;
			$c3->y = 'ab';

			$c4 = new \stdClass();
			$c4->x = 18;
			$c4->y = 'c';

			$data = [
				$c1,
				$c2,
				$c3,
				$c4,
			];


			$ret = group_by_consecutive($data, 'y');

			$this->assertInstanceOf(\Generator::class, $ret);

			$retData = iterator_to_array($ret);
			$this->assertEquals([
				[
					$data[0],
					$data[1],
				],
				[
					$data[2]
				],
				[
					$data[3]
				],
			], $retData);

		}

		public function testGroupByConsecutive_groupByClosure() {

			$data = [
				[
					'x' => 15,
					'y' => 'a',
				],
				[
					'x' => 16,
					'y' => 'a',
				],
				[
					'x' => 17,
					'y' => 'b',
				],
				[
					'x' => 18,
					'y' => 'c',
				],
			];


			$ret = group_by_consecutive($data, function($v) { return $v['y']; });

			$this->assertInstanceOf(\Generator::class, $ret);

			$retData = iterator_to_array($ret);
			$this->assertEquals([
				[
					$data[0],
					$data[1],
				],
				[
					$data[2]
				],
				[
					$data[3]
				],
			], $retData);

		}

		public function testGroupByConsecutive_groupByPath() {

			$data = [
				[
					'x' => 15,
					'y' => [
						'z' => 'a'
					]
				],
				[
					'x' => 16,
					'y' => [
						'z' => 'a'
					]
				],
				[
					'x' => 17,
					'y' => [
						'z' => 'b'
					]
				],
				[
					'x' => 18,
					'y' => [
						'z' => 'c'
					]
				],
			];


			$ret = group_by_consecutive($data, 'y.z');

			$this->assertInstanceOf(\Generator::class, $ret);

			$retData = iterator_to_array($ret);
			$this->assertEquals([
				[
					$data[0],
					$data[1],
				],
				[
					$data[2]
				],
				[
					$data[3]
				],
			], $retData);

		}


		public function testGroupByConsecutive_groupValuesAreComparedStrict() {

			$data = [
				[
					'x' => 15,
					'y' => null,
				],
				[
					'x' => 16,
					'y' => 0,
				],
				[
					'x' => 17,
					'y' => 1,
				],
				[
					'x' => 18,
					'y' => '1',
				],
			];


			$ret = group_by_consecutive($data, 'y');

			$this->assertInstanceOf(\Generator::class, $ret);

			$retData = iterator_to_array($ret);
			$this->assertEquals([
				[
					$data[0],
				],
				[
					$data[1],
				],
				[
					$data[2]
				],
				[
					$data[3]
				],
			], $retData);

		}

	}