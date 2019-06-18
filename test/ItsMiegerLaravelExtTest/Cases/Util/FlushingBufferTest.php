<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 11.09.18
	 * Time: 14:32
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Util;


	use Illuminate\Support\Collection;
	use ItsMieger\LaravelExt\Util\FlushingBuffer;
	use ItsMiegerLaravelExtTest\TestCase;

	class FlushingBufferTest extends TestCase
	{
		public function testArray_autoFlush() {
			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$shouldBeCalled->expects($this->exactly(2))
				->method('__invoke')
				->withConsecutive(
					[['A', 'B']],
					[['C', 'D']]
				)
			;

			$buffer = new FlushingBuffer(2, $shouldBeCalled);

			$buffer->add('A');
			$buffer->add('B');
			$buffer->add('C');
			$buffer->add('D');
			$buffer->add('E');
		}

		public function testArray_NoAutoFlush() {
			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$shouldBeCalled->expects($this->never())
				->method('__invoke')
			;

			$buffer = new FlushingBuffer(0, $shouldBeCalled);

			$buffer->add('A');
			$buffer->add('B');
			$buffer->add('C');
			$buffer->add('D');
			$buffer->add('E');
		}

		public function testArray_manualFlush() {
			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$shouldBeCalled->expects($this->exactly(1))
				->method('__invoke')
				->with(['A'])
			;

			$buffer = new FlushingBuffer(2, $shouldBeCalled);

			$buffer->add('A');
			$buffer->flush();
		}

		public function testArray_manualFlushEmpty() {
			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$shouldBeCalled->expects($this->never())
				->method('__invoke')
			;

			$buffer = new FlushingBuffer(2, $shouldBeCalled);

			$buffer->flush();
		}

		public function testArray_manualFlushEmptyForced() {
			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$shouldBeCalled->expects($this->exactly(1))
				->method('__invoke')
				->with([]);

			$buffer = new FlushingBuffer(2, $shouldBeCalled);

			$buffer->flush(true);
		}

		public function testArray_count() {

			$buffer = new FlushingBuffer(2, function() { });

			$this->assertEquals(0, $buffer->count());
			$buffer->add('A');
			$this->assertEquals(1, $buffer->count());
			$buffer->add('B');
			$this->assertEquals(0, $buffer->count());
			$buffer->add('C');
			$this->assertEquals(1, $buffer->count());
			$buffer->add('D');
			$this->assertEquals(0, $buffer->count());
			$buffer->add('E');
			$this->assertEquals(1, $buffer->count());
			$buffer->flush();
			$this->assertEquals(0, $buffer->count());

		}

		public function testCollection_autoFlush() {
			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$shouldBeCalled->expects($this->exactly(2))
				->method('__invoke')
				->withConsecutive(
					[$this->isInstanceOf(Collection::class)],
					[$this->isInstanceOf(Collection::class)]
				);

			$buffer = new FlushingBuffer(2, $shouldBeCalled, function () {
				return collect();
			});

			$buffer->add('A');
			$buffer->add('B');
			$buffer->add('C');
			$buffer->add('D');
			$buffer->add('E');
		}

		public function testCollection_manualFlush() {
			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$shouldBeCalled->expects($this->exactly(1))
				->method('__invoke')
				->with($this->isInstanceOf(Collection::class));

			$buffer = new FlushingBuffer(2, $shouldBeCalled, function () {
				return collect();
			});

			$buffer->add('A');
			$buffer->flush();
		}

		public function testCollection_manualFlushEmpty() {
			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$shouldBeCalled->expects($this->never())
				->method('__invoke');

			$buffer = new FlushingBuffer(2, $shouldBeCalled, function () {
				return collect();
			});

			$buffer->flush();
		}

		public function testCollection_manualFlushEmptyForced() {
			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$shouldBeCalled->expects($this->exactly(1))
				->method('__invoke')
				->with($this->isInstanceOf(Collection::class));

			$buffer = new FlushingBuffer(2, $shouldBeCalled, function () {
				return collect();
			});

			$buffer->flush(true);
		}

		public function testCollection_count() {

			$buffer = new FlushingBuffer(2, function () {
			}, function () {
				return collect();
			});

			$this->assertEquals(0, $buffer->count());
			$buffer->add('A');
			$this->assertEquals(1, $buffer->count());
			$buffer->add('B');
			$this->assertEquals(0, $buffer->count());
			$buffer->add('C');
			$this->assertEquals(1, $buffer->count());
			$buffer->add('D');
			$this->assertEquals(0, $buffer->count());
			$buffer->add('E');
			$this->assertEquals(1, $buffer->count());
			$buffer->flush();
			$this->assertEquals(0, $buffer->count());

		}

		public function testFunctionName_autoFlush() {
			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$shouldBeCalled->expects($this->exactly(1))
				->method('__invoke')
				->withConsecutive(
					[$this->isInstanceOf(Collection::class)]
				);

			$buffer = new FlushingBuffer(2, $shouldBeCalled, 'collect');

			$buffer->add('A');
			$buffer->add('B');
		}

		public function testClassName_autoFlush() {
			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$shouldBeCalled->expects($this->exactly(1))
				->method('__invoke')
				->withConsecutive(
					[$this->isInstanceOf(Collection::class)]
				);

			$buffer = new FlushingBuffer(2, $shouldBeCalled, Collection::class);

			$buffer->add('A');
			$buffer->add('B');
		}

		public function testAddWithKey() {
			$buffer = new FlushingBuffer(3, function () {
			});

			$this->assertEquals(0, $buffer->count());
			$buffer->add('A', 'k-1');
			$this->assertEquals(1, $buffer->count());
			$buffer->add('B', 'k-2');
			$this->assertEquals(2, $buffer->count());
			$buffer->add('C', 'k-2');
			$this->assertEquals(2, $buffer->count());
			$buffer->add('D', 'k-3');
			$this->assertEquals(0, $buffer->count());
			$buffer->add('E', 'k-1');
			$this->assertEquals(1, $buffer->count());
			$buffer->flush();
			$this->assertEquals(0, $buffer->count());
		}
	}