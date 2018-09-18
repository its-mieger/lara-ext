<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 18.09.18
	 * Time: 09:42
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Helper;


	use Illuminate\Database\Query\Builder;
	use ItsMiegerLaravelExtTest\Cases\TestCase;
	use ItsMiegerLaravelExtTest\Mock\WhereInMock;

	class JoinedTest extends TestCase
	{

		public function testArrayArray() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$left = [
				['a' => 5, 'b' => 2],
				['a' => 6, 'b' => 3],
				['a' => 7, 'b' => 4],
			];

			$right = [
				['c' => 5, 'd' => -1],
				['c' => 5, 'd' => -2],
				['c' => 9, 'd' => -3],
				['c' => 7, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(3))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined($left, 'a', $right, 'c', $shouldBeCalled);
		}

		public function testArrayArray_all() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$left = [
				['a' => 5, 'b' => 2],
				['a' => 6, 'b' => 3],
				['a' => 7, 'b' => 4],
			];

			$right = [
				['c' => 5, 'd' => -1],
				['c' => 5, 'd' => -2],
				['c' => 9, 'd' => -3],
				['c' => 7, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(4))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[0], $right[1]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined($left, 'a', $right, 'c', $shouldBeCalled, true);
		}

		public function testArrayArray_canNotFlip() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$v1 = ['v' => 1];
			$v2 = ['v' => 2];
			$v3 = ['v' => 3];
			$v4 = ['v' => 4];

			$left = [
				['a' => $v1, 'b' => 2],
				['a' => $v2, 'b' => 3],
				['a' => $v4, 'b' => 4],
			];

			$right = [
				['c' => $v1, 'd' => -1],
				['c' => $v1, 'd' => -2],
				['c' => $v3, 'd' => -3],
				['c' => $v4, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(3))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined($left, 'a', $right, 'c', $shouldBeCalled);
		}


		public function testArrayCollection() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$left = [
				['a' => 5, 'b' => 2],
				['a' => 6, 'b' => 3],
				['a' => 7, 'b' => 4],
			];

			$right = collect([
				['c' => 5, 'd' => -1],
				['c' => 5, 'd' => -2],
				['c' => 9, 'd' => -3],
				['c' => 7, 'd' => -4],
			]);


			$shouldBeCalled->expects($this->exactly(3))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined($left, 'a', $right, 'c', $shouldBeCalled);
		}

		public function testArrayCollection_all() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$left = [
				['a' => 5, 'b' => 2],
				['a' => 6, 'b' => 3],
				['a' => 7, 'b' => 4],
			];

			$right = collect([
				['c' => 5, 'd' => -1],
				['c' => 5, 'd' => -2],
				['c' => 9, 'd' => -3],
				['c' => 7, 'd' => -4],
			]);


			$shouldBeCalled->expects($this->exactly(4))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[0], $right[1]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined($left, 'a', $right, 'c', $shouldBeCalled, true);
		}

		public function testArrayCollection_canNotFlip() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$v1 = ['v' => 1];
			$v2 = ['v' => 2];
			$v3 = ['v' => 3];
			$v4 = ['v' => 4];

			$left = [
				['a' => $v1, 'b' => 2],
				['a' => $v2, 'b' => 3],
				['a' => $v4, 'b' => 4],
			];

			$right = collect([
				['c' => $v1, 'd' => -1],
				['c' => $v1, 'd' => -2],
				['c' => $v3, 'd' => -3],
				['c' => $v4, 'd' => -4],
			]);


			$shouldBeCalled->expects($this->exactly(3))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined($left, 'a', $right, 'c', $shouldBeCalled);
		}


		public function testClosureClosure() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$left = [
				['a' => 5, 'b' => 2],
				['a' => 6, 'b' => 3],
				['a' => 7, 'b' => 4],
			];

			$right = [
				['c' => 5, 'd' => -1],
				['c' => 5, 'd' => -2],
				['c' => 9, 'd' => -3],
				['c' => 7, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(3))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined(function () use ($left) {
				return $left;
			}, 'a', function () use ($right) {
				return $right;
			}, 'c', $shouldBeCalled);
		}

		public function testClosureClosure_all() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$left = [
				['a' => 5, 'b' => 2],
				['a' => 6, 'b' => 3],
				['a' => 7, 'b' => 4],
			];

			$right = [
				['c' => 5, 'd' => -1],
				['c' => 5, 'd' => -2],
				['c' => 9, 'd' => -3],
				['c' => 7, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(4))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[0], $right[1]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined(function () use ($left) {
				return $left;
			}, 'a', function () use ($right) {
				return $right;
			}, 'c', $shouldBeCalled, true);
		}

		public function testClosureClosure_canNotFlip() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$v1 = ['v' => 1];
			$v2 = ['v' => 2];
			$v3 = ['v' => 3];
			$v4 = ['v' => 4];

			$left = [
				['a' => $v1, 'b' => 2],
				['a' => $v2, 'b' => 3],
				['a' => $v4, 'b' => 4],
			];

			$right = [
				['c' => $v1, 'd' => -1],
				['c' => $v1, 'd' => -2],
				['c' => $v3, 'd' => -3],
				['c' => $v4, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(3))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined(function () use ($left) {
				return $left;
			}, 'a', function () use ($right) {
				return $right;
			}, 'c', $shouldBeCalled);
		}

		public function testClosureGeneratorClosureGenerator() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$left = [
				['a' => 5, 'b' => 2],
				['a' => 6, 'b' => 3],
				['a' => 7, 'b' => 4],
			];

			$right = [
				['c' => 5, 'd' => -1],
				['c' => 5, 'd' => -2],
				['c' => 9, 'd' => -3],
				['c' => 7, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(3))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined(function () use ($left) {
				foreach ($left as $curr) {
					yield $curr;
				}
			}, 'a', function () use ($right) {
				foreach ($right as $curr) {
					yield $curr;
				}
			}, 'c', $shouldBeCalled);
		}

		public function testClosureGeneratorClosureGenerator_all() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$left = [
				['a' => 5, 'b' => 2],
				['a' => 6, 'b' => 3],
				['a' => 7, 'b' => 4],
			];

			$right = [
				['c' => 5, 'd' => -1],
				['c' => 5, 'd' => -2],
				['c' => 9, 'd' => -3],
				['c' => 7, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(4))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[0], $right[1]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined(function () use ($left) {
				foreach($left as $curr) {
					yield $curr;
				}
			}, 'a', function () use ($right) {
				foreach ($right as $curr) {
					yield $curr;
				}
			}, 'c', $shouldBeCalled, true);
		}

		public function testClosureGeneratorClosureGenerator_canNotFlip() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$v1 = ['v' => 1];
			$v2 = ['v' => 2];
			$v3 = ['v' => 3];
			$v4 = ['v' => 4];

			$left = [
				['a' => $v1, 'b' => 2],
				['a' => $v2, 'b' => 3],
				['a' => $v4, 'b' => 4],
			];

			$right = [
				['c' => $v1, 'd' => -1],
				['c' => $v1, 'd' => -2],
				['c' => $v3, 'd' => -3],
				['c' => $v4, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(3))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined(function () use ($left) {
				foreach ($left as $curr) {
					yield $curr;
				}
			}, 'a', function () use ($right) {
				foreach ($right as $curr) {
					yield $curr;
				}
			}, 'c', $shouldBeCalled);
		}


		public function testArrayArray_dotNotationField() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$v1 = ['v' => 1];
			$v2 = ['v' => 2];
			$v3 = ['v' => 3];
			$v4 = ['v' => 4];

			$left = [
				['a' => $v1, 'b' => 2],
				['a' => $v2, 'b' => 3],
				['a' => $v4, 'b' => 4],
			];

			$right = [
				['c' => $v1, 'd' => -1],
				['c' => $v1, 'd' => -2],
				['c' => $v3, 'd' => -3],
				['c' => $v4, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(3))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined($left, 'a.v', $right, 'c.v', $shouldBeCalled);
		}

		public function testArrayArray_dotNotationField_all() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$v1 = ['v' => 1];
			$v2 = ['v' => 2];
			$v3 = ['v' => 3];
			$v4 = ['v' => 4];

			$left = [
				['a' => $v1, 'b' => 2],
				['a' => $v2, 'b' => 3],
				['a' => $v4, 'b' => 4],
			];

			$right = [
				['c' => $v1, 'd' => -1],
				['c' => $v1, 'd' => -2],
				['c' => $v3, 'd' => -3],
				['c' => $v4, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(4))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[0], $right[1]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined($left, 'a.v', $right, 'c.v', $shouldBeCalled, true);
		}

		public function testArrayArray_dotNotationField_canNotFlip() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$v1 = ['v' => 1];
			$v2 = ['v' => 2];
			$v3 = ['v' => 3];
			$v4 = ['v' => 4];

			$left = [
				['a' => $v1, 'b' => 2],
				['a' => $v2, 'b' => 3],
				['a' => $v4, 'b' => 4],
			];

			$right = [
				['c' => $v1, 'd' => -1],
				['c' => $v1, 'd' => -2],
				['c' => $v3, 'd' => -3],
				['c' => $v4, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(3))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			joined($left, 'a.v', $right, 'c.v', $shouldBeCalled);
		}


		public function testArrayQueryBuilder() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$left = [
				['a' => 5, 'b' => 2],
				['a' => 6, 'b' => 3],
				['a' => 7, 'b' => 4],
			];

			$right = [
				['c' => 5, 'd' => -1],
				['c' => 5, 'd' => -2],
				['c' => 9, 'd' => -3],
				['c' => 7, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(3))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			$qb = $this->getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();
			$qb->expects($this->once())
				->method('whereIn')
				->with('c', [5, 6, 7])
				->willReturnSelf();
			;

			$qb->expects($this->once())
				->method('get')
				->willReturn(collect($right));
			;


			joined($left, 'a', $qb, 'c', $shouldBeCalled);
		}



		public function testArrayEloquentQueryBuilder() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$left = [
				['a' => 5, 'b' => 2],
				['a' => 6, 'b' => 3],
				['a' => 7, 'b' => 4],
			];

			$right = [
				['c' => 5, 'd' => -1],
				['c' => 5, 'd' => -2],
				['c' => 9, 'd' => -3],
				['c' => 7, 'd' => -4],
			];


			$shouldBeCalled->expects($this->exactly(3))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[1], null],
					[$left[2], $right[3]]
				);


			$qb = $this->getMockBuilder(\Illuminate\Database\Eloquent\Builder::class)->disableOriginalConstructor()->setMethods(['whereIn', 'get'])->getMock();
			$qb->expects($this->once())
				->method('whereIn')
				->with('c', [5, 6, 7])
				->willReturnSelf();
			;

			$qb->expects($this->once())
				->method('get')
				->willReturn(collect($right));
			;


			joined($left, 'a', $qb, 'c', $shouldBeCalled);
		}




		public function testArrayStaticWhereIn() {

			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$left = [
				['a' => 5, 'b' => 2],
				['a' => 6, 'b' => 3],
				['a' => 7, 'b' => 4],
			];

			$right = [
				['c' => 5, 'd' => -1],
				['c' => 5, 'd' => -2],
				['c' => 9, 'd' => -3],
				['c' => 7, 'd' => -4],
			];

			WhereInMock::prepare(function($field, $values) use ($right) {
				$this->assertEquals('c', $field);
				$this->assertEquals([5,6,7], $values);

				return [$right[0], $right[1], $right[3]];
			});


			$shouldBeCalled->expects($this->exactly(3))
				->method('__invoke')
				->withConsecutive(
					[$left[0], $right[0]],
					[$left[1], null],
					[$left[2], $right[3]]
				);



			joined($left, 'a', WhereInMock::class, 'c', $shouldBeCalled);
		}




	}