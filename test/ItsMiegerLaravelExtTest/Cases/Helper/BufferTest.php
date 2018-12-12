<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 11.09.18
	 * Time: 23:12
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Helper;


	use Illuminate\Support\Collection;
	use ItsMieger\LaravelExt\Util\FlushingBuffer;
	use ItsMiegerLaravelExtTest\TestCase;

	class BufferTest extends TestCase
	{
		public function testBuffer() {
			$shouldBeCalled = $this->getMockBuilder(\stdClass::class)
				->setMethods(['__invoke'])
				->getMock();

			$shouldBeCalled->expects($this->exactly(1))
				->method('__invoke')
				->with($this->isInstanceOf(Collection::class));

			$buffer = buffer(2, $shouldBeCalled, function () {
				return collect();
			});

			$this->assertInstanceOf(FlushingBuffer::class, $buffer);

			$buffer->add('A');
			$buffer->flush();
		}
	}