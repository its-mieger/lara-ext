<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 12.12.18
	 * Time: 14:08
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Helper;


	use ItsMiegerLaravelExtTest\TestCase;

	class IteratorForTest extends TestCase
	{

		public function testIteratorFor_iterator() {
			$iter = $this->getMockBuilder(\Iterator::class)->getMock();

			$this->assertSame($iter, iterator_for($iter));
		}

		public function testIteratorFor_array() {
			$v = ['a', 'b'];

			$iter = iterator_for($v);

			$this->assertInstanceOf(\ArrayIterator::class, $iter);
			$this->assertEquals($v, iterator_to_array($iter));
		}

		public function testIteratorFor_scalar() {
			$v = 'a';

			$iter = iterator_for($v);

			$this->assertInstanceOf(\ArrayIterator::class, $iter);
			$this->assertEquals([$v], iterator_to_array($iter));
		}

		public function testIteratorFor_object() {
			$v = new \stdClass();

			$iter = iterator_for($v);

			$this->assertInstanceOf(\ArrayIterator::class, $iter);
			$this->assertSame([$v], iterator_to_array($iter));
		}

		public function testIteratorFor_null() {
			$iter = iterator_for(null);

			$this->assertInstanceOf(\EmptyIterator::class, $iter);
		}
	}