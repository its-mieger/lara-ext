<?php

	namespace ItsMiegerLaravelExtTest\Cases;


	use ItsMieger\Obj\Contracts\Comparable;
	use ItsMieger\Obj\Obj;
	use ItsMieger\Obj\ObjectHelper;

	class CollectionMacrosTest extends TestCase
	{

		public function testDiffBy() {
			$collection = collect(['a', 'b', 'c']);

			$res = $collection->diffBy(['a', 'b', 'c'], function($a, $b) {

				switch($a . $b) {
					case 'bb':
						return 0;
					default:
						return 1;
				}
			});

			$this->assertSame([0 => 'a', 2 => 'c'], $res->all());
		}

		public function testDiffAssocBy() {
			$collection = collect([
				'x' => 'a',
				'y' => 'b',
				'z' => 'c'
			]);

			$res = $collection->diffAssocBy(['x' => 'a', 'y' => 'bb', 'zz' => 'c'], function() {
				return 0;
			}, function() use ($collection) {
				return 0;
			});

			$this->assertSame([], $res->all());
		}

		public function testDiffAssocByOnlyKeyCompareFn() {
			$collection = collect([
				'x' => 'a',
				'y' => 'b',
				'z' => 'c'
			]);

			$res = $collection->diffAssocBy(['x' => 'a', 'y' => 'bb', 'zz' => 'c'], function() {
				return 0;
			});

			$this->assertSame(['z' => 'c'], $res->all());
		}

		public function testDiffObj() {


			$objMock = $this->getMockBuilder(ObjectHelper::class)->getMock();
			$objMock
				->expects($this->atLeastOnce())
				->method('compare')
				->willReturn(0);

			Obj::mock($objMock);

			$a = $this->getMockBuilder(Comparable::class)->getMock();
			$b = $this->getMockBuilder(Comparable::class)->getMock();
			$c = $this->getMockBuilder(Comparable::class)->getMock();

			$collection = collect([$a, $b, $c]);


			$res = $collection->diffObj([$a, $b, $c]);

			$this->assertSame([], $res->all());
		}

		public function testDiffObj2() {


			$objMock = $this->getMockBuilder(ObjectHelper::class)->getMock();
			$objMock
				->expects($this->atLeastOnce())
				->method('compare')
				->willReturn(-1);

			Obj::mock($objMock);

			$a = $this->getMockBuilder(Comparable::class)->getMock();
			$b = $this->getMockBuilder(Comparable::class)->getMock();
			$c = $this->getMockBuilder(Comparable::class)->getMock();

			$collection = collect([$a, $b, $c]);


			$res = $collection->diffObj([$a, $b, $c]);

			$this->assertSame([$a, $b, $c], $res->all());
		}

		public function testDiffAssocObjAll() {

			$objMock = $this->getMockBuilder(ObjectHelper::class)->getMock();
			$objMock
				->expects($this->atLeastOnce())
				->method('compare')
				->willReturn(0);

			Obj::mock($objMock);

			$a = $this->getMockBuilder(Comparable::class)->getMock();
			$b = $this->getMockBuilder(Comparable::class)->getMock();
			$c = $this->getMockBuilder(Comparable::class)->getMock();

			$collection = collect([$a]);


			$res = $collection->diffAssocObj([$a, $b, $c], function () {
				return 0;
			});

			$this->assertSame([], $res->all());
		}

		public function testDiffAssocObj() {


			$objMock = $this->getMockBuilder(ObjectHelper::class)->getMock();
			$objMock
				->method('compare')
				->willReturn(0);

			Obj::mock($objMock);

			$a = $this->getMockBuilder(Comparable::class)->getMock();
			$b = $this->getMockBuilder(Comparable::class)->getMock();
			$c = $this->getMockBuilder(Comparable::class)->getMock();

			$collection = collect([$a, $b, $c]);

			$res = $collection->diffAssocObj([$a, $c, $b], function() { return -1; });

			$this->assertSame([$a, $b, $c], $res->all());
		}

		public function testSortCallback() {
			$a = new \stdClass();
			$a->key = 'z';

			$b      = new \stdClass();
			$b->key = 'x';

			$c      = new \stdClass();
			$c->key = 'y';

			$collection = collect([$a, $b, $c]);

			$res = $collection->sortCallback(function($a, $b) {
				return $a->key <=> $b->key;
			});
			$this->assertSame([$b, $c, $a], $res->all());
		}

		public function testSortCallbackReverse() {
			$a = new \stdClass();
			$a->key = 'z';

			$b      = new \stdClass();
			$b->key = 'x';

			$c      = new \stdClass();
			$c->key = 'y';

			$collection = collect([$a, $b, $c]);

			$res = $collection->sortCallback(function($a, $b) {
				return $a->key <=> $b->key;
			}, false, true);
			$this->assertSame([$a, $c, $b], $res->all());
		}

		public function testSortCallbackKeepKeys() {
			$a      = new \stdClass();
			$a->key = 'z';

			$b      = new \stdClass();
			$b->key = 'x';

			$c      = new \stdClass();
			$c->key = 'y';

			$collection = collect([$a, $b, $c]);

			$res = $collection->sortCallback(function ($a, $b) {
				return $a->key <=> $b->key;
			}, true);
			$this->assertSame([1 => $b, 2 => $c, 0 => $a], $res->all());
		}

		public function testSortCallbackKeepKeysReverse() {
			$a      = new \stdClass();
			$a->key = 'z';

			$b      = new \stdClass();
			$b->key = 'x';

			$c      = new \stdClass();
			$c->key = 'y';

			$collection = collect([$a, $b, $c]);

			$res = $collection->sortCallback(function ($a, $b) {
				return $a->key <=> $b->key;
			}, true, true);
			$this->assertSame([0 => $a, 2 => $c, 1 => $b], $res->all());
		}

		public function testSortCallbackDesc() {
			$a      = new \stdClass();
			$a->key = 'z';

			$b      = new \stdClass();
			$b->key = 'x';

			$c      = new \stdClass();
			$c->key = 'y';

			$collection = collect([$a, $b, $c]);

			$res = $collection->sortCallbackDesc(function ($x, $y) {
				return $x->key <=> $y->key;
			});
			$this->assertSame([$a, $c, $b], $res->all());
		}

		public function testSortCallbackDescKeepKeys() {
			$a      = new \stdClass();
			$a->key = 'z';

			$b      = new \stdClass();
			$b->key = 'x';

			$c      = new \stdClass();
			$c->key = 'y';

			$collection = collect([$a, $b, $c]);

			$res = $collection->sortCallbackDesc(function ($x, $y) {
				return $x->key <=> $y->key;
			}, true);
			$this->assertSame([0 => $a, 2 => $c, 1 => $b], $res->all());
		}

		public function testSortObj() {
			$a = $this->getMockBuilder(Comparable::class)->getMock();
			$aClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$a->method('compareTo')->willReturnCallback($aClosure->bindTo($a));
			$a->key = 'z';

			$b = $this->getMockBuilder(Comparable::class)->getMock();
			$bClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$b->method('compareTo')->willReturnCallback($bClosure->bindTo($b));
			$b->key = 'x';

			$c = $this->getMockBuilder(Comparable::class)->getMock();
			$cClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$c->method('compareTo')->willReturnCallback($cClosure->bindTo($c));
			$c->key = 'y';

			$collection = collect([$a, $b, $c]);

			$res = $collection->sortObj();
			$this->assertSame([$b, $c, $a], $res->all());
		}

		public function testSortObjReverse() {
			$a = $this->getMockBuilder(Comparable::class)->getMock();
			$aClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$a->method('compareTo')->willReturnCallback($aClosure->bindTo($a));
			$a->key = 'z';

			$b = $this->getMockBuilder(Comparable::class)->getMock();
			$bClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$b->method('compareTo')->willReturnCallback($bClosure->bindTo($b));
			$b->key = 'x';

			$c = $this->getMockBuilder(Comparable::class)->getMock();
			$cClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$c->method('compareTo')->willReturnCallback($cClosure->bindTo($c));
			$c->key = 'y';

			$collection = collect([$a, $b, $c]);

			$res = $collection->sortObj(false, true);
			$this->assertSame([$a, $c, $b], $res->all());
		}

		public function testSortObjKeepKeys() {
			$a        = $this->getMockBuilder(Comparable::class)->getMock();
			$aClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$a->method('compareTo')->willReturnCallback($aClosure->bindTo($a));
			$a->key = 'z';

			$b        = $this->getMockBuilder(Comparable::class)->getMock();
			$bClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$b->method('compareTo')->willReturnCallback($bClosure->bindTo($b));
			$b->key = 'x';

			$c        = $this->getMockBuilder(Comparable::class)->getMock();
			$cClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$c->method('compareTo')->willReturnCallback($cClosure->bindTo($c));
			$c->key = 'y';

			$collection = collect([$a, $b, $c]);

			$res = $collection->sortObj(true);
			$this->assertSame([1 => $b, 2 => $c, 0 => $a], $res->all());
		}

		public function testSortObjKeepKeysReverse() {
			$a        = $this->getMockBuilder(Comparable::class)->getMock();
			$aClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$a->method('compareTo')->willReturnCallback($aClosure->bindTo($a));
			$a->key = 'z';

			$b        = $this->getMockBuilder(Comparable::class)->getMock();
			$bClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$b->method('compareTo')->willReturnCallback($bClosure->bindTo($b));
			$b->key = 'x';

			$c        = $this->getMockBuilder(Comparable::class)->getMock();
			$cClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$c->method('compareTo')->willReturnCallback($cClosure->bindTo($c));
			$c->key = 'y';

			$collection = collect([$a, $b, $c]);

			$res = $collection->sortObj(true, true);
			$this->assertSame([0 => $a, 2 => $c, 1 => $b], $res->all());
		}

		public function testSortObjDesc() {
			$a        = $this->getMockBuilder(Comparable::class)->getMock();
			$aClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$a->method('compareTo')->willReturnCallback($aClosure->bindTo($a));
			$a->key = 'z';

			$b        = $this->getMockBuilder(Comparable::class)->getMock();
			$bClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$b->method('compareTo')->willReturnCallback($bClosure->bindTo($b));
			$b->key = 'x';

			$c        = $this->getMockBuilder(Comparable::class)->getMock();
			$cClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$c->method('compareTo')->willReturnCallback($cClosure->bindTo($c));
			$c->key = 'y';

			$collection = collect([$a, $b, $c]);

			$res = $collection->sortObjDesc(false);
			$this->assertSame([$a, $c, $b], $res->all());
		}

		public function testSortObjDescKeepKeys() {
			$a        = $this->getMockBuilder(Comparable::class)->getMock();
			$aClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$a->method('compareTo')->willReturnCallback($aClosure->bindTo($a));
			$a->key = 'z';

			$b        = $this->getMockBuilder(Comparable::class)->getMock();
			$bClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$b->method('compareTo')->willReturnCallback($bClosure->bindTo($b));
			$b->key = 'x';

			$c        = $this->getMockBuilder(Comparable::class)->getMock();
			$cClosure = function ($value) {
				return $this->key <=> $value->key;
			};
			$c->method('compareTo')->willReturnCallback($cClosure->bindTo($c));
			$c->key = 'y';

			$collection = collect([$a, $b, $c]);

			$res = $collection->sortObj(true, true);
			$this->assertSame([0 => $a, 2 => $c, 1 => $b], $res->all());
		}

		public function testCompareToValues() {

			$collection = collect(['a' => 1, 2, 3, 4]);

			$this->assertEquals(0, $collection->compareToValues([2, 1, 4, 3]));

			$this->assertEquals(1, $collection->compareToValues([1, 4, 3]));
			$this->assertEquals(1, $collection->compareToValues([1, 4, 3, 0]));


			$this->assertEquals(-1, $collection->compareToValues([2, 1, 4, 3, 5]));
			$this->assertEquals(-1, $collection->compareToValues([1, 4, 3, 5]));


			// check that reverse calls return consistent results

			$this->assertEquals(0, collect([2, 1, 4, 3])->compareToValues($collection));

			$this->assertEquals(-1, collect([1, 4, 3])->compareToValues($collection));
			$this->assertEquals(-1, collect([1, 4, 3, 0])->compareToValues($collection));


			$this->assertEquals(1, collect([2, 1, 4, 3, 5])->compareToValues($collection));
			$this->assertEquals(1, collect([1, 4, 3, 5])->compareToValues($collection));
		}

		public function testCompareToValuesAssoc() {

			$collection = collect([
				'a' => 1,
				'b' => 2,
				'c' => 3,
			]);

			$this->assertEquals(0, $collection->compareToValuesAssoc(['a' => 1, 'c' => 3, 'b' => 2]));

			$this->assertEquals(1, $collection->compareToValuesAssoc(['a' => 1, 'b' => 2]));
			$this->assertEquals(1, $collection->compareToValuesAssoc(['a' => 1, 'c' => 3, 'b' => 1]));
			$this->assertEquals(1, $collection->compareToValuesAssoc(['a' => 1, 'b' => 2, 'a1' => 2]));


			$this->assertEquals(-1, $collection->compareToValuesAssoc(['a' => 1, 'c' => 3, 'b' => 2, 'd' => 1]));
			$this->assertEquals(-1, $collection->compareToValuesAssoc(['a' => 1, 'c' => 3, 'b' => 3]));
			$this->assertEquals(-1, $collection->compareToValuesAssoc(['a' => 1, 'c' => 3, 'd1' => 4]));
			$this->assertEquals(-1, $collection->compareToValuesAssoc(['a' => 1, 'b' => 2, 'a1' => 4]));
			$this->assertEquals(-1, $collection->compareToValuesAssoc(['a' => 1, 2, 'c' => 3, 'd' => 5]));


			// check that reverse calls return consistent results

			$this->assertEquals(0, collect(['a' => 1, 'c' => 3, 'b' => 2])->compareToValuesAssoc($collection));

			$this->assertEquals(-1, collect(['a' => 1, 'b' => 2])->compareToValuesAssoc($collection));
			$this->assertEquals(-1, collect(['a' => 1, 'c' => 3, 'b' => 1])->compareToValuesAssoc($collection));
			$this->assertEquals(-1, collect(['a' => 1, 'b' => 2, 'a1' => 2])->compareToValuesAssoc($collection));

			$this->assertEquals(1, collect(['a' => 1, 'c' => 3, 'b' => 2, 'd' => 1])->compareToValuesAssoc($collection));
			$this->assertEquals(1, collect(['a' => 1, 'c' => 3, 'b' => 3])->compareToValuesAssoc($collection));
			$this->assertEquals(1, collect(['a' => 1, 'c' => 3, 'd1' => 4])->compareToValuesAssoc($collection));
			$this->assertEquals(1, collect(['a' => 1, 'b' => 2, 'a1' => 4])->compareToValuesAssoc($collection));
			$this->assertEquals(1, collect(['a' => 1, 2, 'c' => 3, 'd' => 5])->compareToValuesAssoc($collection));
		}
	}