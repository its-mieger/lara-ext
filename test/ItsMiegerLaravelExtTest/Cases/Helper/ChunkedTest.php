<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 17.09.18
	 * Time: 14:51
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Helper;


	use ItsMieger\LaravelExt\Util\FlushingBuffer;
	use ItsMiegerLaravelExtTest\Cases\TestCase;

	class ChunkedTest extends TestCase
	{
		public function testChunked_Array() {
			$f1 = function () {
			};
			$f2 = function () {
			};

			app()->bind(FlushingBuffer::class, function($app, $args) use ($f1, $f2) {

				$this->assertEquals(2, $args['size']);
				$this->assertSame($f1, $args['flushHandler']);
				$this->assertSame($f2, $args['collectionResolver']);


				$bufferMock = $this->getMockBuilder(FlushingBuffer::class)->disableOriginalConstructor()->getMock();

				$bufferMock->expects($this->atLeastOnce())
					->method('add')
					->withConsecutive(
						[1],
						[2],
						[3]
					);

				$bufferMock->expects($this->once())
					->method('flush');

				return $bufferMock;
			});




			$data = [1, 2, 3];

			chunked($data, 2, $f1, $f2);
		}

		public function testChunked_Generator() {
			$f1 = function () {
			};
			$f2 = function () {
			};

			app()->bind(FlushingBuffer::class, function($app, $args) use ($f1, $f2) {

				$this->assertEquals(2, $args['size']);
				$this->assertSame($f1, $args['flushHandler']);
				$this->assertSame($f2, $args['collectionResolver']);


				$bufferMock = $this->getMockBuilder(FlushingBuffer::class)->disableOriginalConstructor()->getMock();

				$bufferMock->expects($this->atLeastOnce())
					->method('add')
					->withConsecutive(
						[1],
						[2],
						[3]
					);

				$bufferMock->expects($this->once())
					->method('flush');

				return $bufferMock;
			});




			$generator = function() {
				foreach([1, 2, 3] as $curr) {
					yield $curr;
				}
			};

			chunked($generator(), 2, $f1, $f2);
		}
	}