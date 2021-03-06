<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 11.12.18
	 * Time: 19:41
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Util;


	use Illuminate\Support\Collection;
	use ItsMieger\LaravelExt\Util\ChunkedGenerator;
	use ItsMiegerLaravelExtTest\TestCase;

	class ChunkedGeneratorTest extends TestCase
	{
		public function testGetBufferSize() {
			$gen = new ChunkedGenerator([], 3, function ($x) {
				yield $x;
			});

			$this->assertEquals(3, $gen->getBufferSize());
		}

		public function testConsume() {

			$invokedArgs = [];
			$fn = function($arr) use (&$invokedArgs) {
				$invokedArgs[] = $arr;

				foreach($arr as $curr) {
					yield strtoupper($curr);
				}
			};

			$gen = new ChunkedGenerator(['a', 'b', 'c', 'd', 'e'], 2, $fn);

			$res = $gen->consume();

			// nothing should be invoked yet
			$this->assertEquals([], $invokedArgs);

			$this->assertEquals(['A', 'B', 'C', 'D', 'E'], iterator_to_array($res));
			$this->assertInstanceOf(\Generator::class, $res);

			// now we expect 3 iterations
			$this->assertEquals([['a', 'b'], ['c', 'd'], ['e']], $invokedArgs);

		}

		public function testConsume_fromIterator() {

			$invokedArgs = [];
			$fn = function($arr) use (&$invokedArgs) {
				$invokedArgs[] = $arr;

				foreach($arr as $curr) {
					yield strtoupper($curr);
				}
			};

			$gen = new ChunkedGenerator(new \ArrayIterator(['a', 'b', 'c', 'd', 'e']), 2, $fn);

			$res = $gen->consume();

			// nothing should be invoked yet
			$this->assertEquals([], $invokedArgs);

			$this->assertEquals(['A', 'B', 'C', 'D', 'E'], iterator_to_array($res));
			$this->assertInstanceOf(\Generator::class, $res);

			// now we expect 3 iterations
			$this->assertEquals([['a', 'b'], ['c', 'd'], ['e']], $invokedArgs);

		}

		public function testConsume_fromEmptyIterator() {

			$invokedArgs = [];
			$fn = function($arr) use (&$invokedArgs) {
				$invokedArgs[] = $arr;

				foreach($arr as $curr) {
					yield strtoupper($curr);
				}
			};

			$gen = new ChunkedGenerator(new \EmptyIterator(), 2, $fn);

			$res = $gen->consume();

			// nothing should be invoked yet
			$this->assertEquals([], $invokedArgs);

			$this->assertEquals([], iterator_to_array($res));
			$this->assertInstanceOf(\Generator::class, $res);

			// now we expect 0 iterations
			$this->assertEquals([], $invokedArgs);

		}

		public function testConsume_fromClosure() {

			$invokedArgs = [];
			$fn = function($arr) use (&$invokedArgs) {
				$invokedArgs[] = $arr;

				foreach($arr as $curr) {
					yield strtoupper($curr);
				}
			};

			$gen = new ChunkedGenerator(function() {
				return ['a', 'b', 'c', 'd', 'e'];
			}, 2, $fn);

			$res = $gen->consume();

			// nothing should be invoked yet
			$this->assertEquals([], $invokedArgs);

			$this->assertEquals(['A', 'B', 'C', 'D', 'E'], iterator_to_array($res));
			$this->assertInstanceOf(\Generator::class, $res);

			// now we expect 3 iterations
			$this->assertEquals([['a', 'b'], ['c', 'd'], ['e']], $invokedArgs);

		}

		public function testConsume_callbackReturningEmptyIterator() {

			$invokedArgs = [];
			$fn          = function ($arr) use (&$invokedArgs) {
				$invokedArgs[] = $arr;

				if (false)
					yield 'A';
			};

			$gen = new ChunkedGenerator(new \ArrayIterator(['a', 'b', 'c', 'd', 'e']), 2, $fn);

			$res = $gen->consume();

			// nothing should be invoked yet
			$this->assertEquals([], $invokedArgs);

			$this->assertEquals([], iterator_to_array($res));
			$this->assertInstanceOf(\Generator::class, $res);

			// now we expect 3 iterations
			$this->assertEquals([['a', 'b'], ['c', 'd'], ['e']], $invokedArgs);

		}

		public function testConsume_callbackReturningSometimesEmptyIterator() {

			$invokedArgs = [];
			$fn          = function ($arr) use (&$invokedArgs) {
				$invokedArgs[] = $arr;

				if (count($invokedArgs) != 2) {
					foreach ($arr as $curr) {
						yield strtoupper($curr);
					}
				}
			};

			$gen = new ChunkedGenerator(new \ArrayIterator(['a', 'b', 'c', 'd', 'e']), 2, $fn);

			$res = $gen->consume();

			// nothing should be invoked yet
			$this->assertEquals([], $invokedArgs);

			$this->assertEquals(['A', 'B', 'E'], iterator_to_array($res));
			$this->assertInstanceOf(\Generator::class, $res);

			// now we expect 3 iterations
			$this->assertEquals([['a', 'b'], ['c', 'd'], ['e']], $invokedArgs);

		}

		public function testConsume_withCustomCollection_fromClass() {

			$invokedArgs = [];
			$fn = function($arr) use (&$invokedArgs) {
				$this->assertInstanceOf(Collection::class, $arr);

				$invokedArgs[] = $arr->toArray();

				foreach($arr as $curr) {
					yield strtoupper($curr);
				}
			};

			$gen = new ChunkedGenerator(function() {
				return ['a', 'b', 'c', 'd', 'e'];
			}, 2, $fn, Collection::class);

			$res = $gen->consume();

			// nothing should be invoked yet
			$this->assertEquals([], $invokedArgs);

			$this->assertEquals(['A', 'B', 'C', 'D', 'E'], iterator_to_array($res));
			$this->assertInstanceOf(\Generator::class, $res);

			// now we expect 3 iterations
			$this->assertEquals([['a', 'b'], ['c', 'd'], ['e']], $invokedArgs);

		}

		public function testConsume_withCustomCollection_fromResolverFunction() {

			$invokedArgs = [];
			$fn = function($arr) use (&$invokedArgs) {
				$this->assertInstanceOf(Collection::class, $arr);

				$invokedArgs[] = $arr->toArray();

				foreach($arr as $curr) {
					yield strtoupper($curr);
				}
			};

			$gen = new ChunkedGenerator(function() {
				return ['a', 'b', 'c', 'd', 'e'];
			}, 2, $fn, function() {
				return new Collection();
			});

			$res = $gen->consume();

			// nothing should be invoked yet
			$this->assertEquals([], $invokedArgs);

			$this->assertEquals(['A', 'B', 'C', 'D', 'E'], iterator_to_array($res));
			$this->assertInstanceOf(\Generator::class, $res);

			// now we expect 3 iterations
			$this->assertEquals([['a', 'b'], ['c', 'd'], ['e']], $invokedArgs);

		}

	}