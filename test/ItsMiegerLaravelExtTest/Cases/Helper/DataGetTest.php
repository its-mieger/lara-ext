<?php

	namespace ItsMiegerLaravelExtTest\Cases\Helper;


	use ArrayAccess;
	use ItsMiegerLaravelExtTest\Cases\TestCase;

	class DataGetTest extends TestCase
	{
		public function testDataGet() {
			$object      = (object)['users' => ['name' => ['Taylor', 'Otwell']]];
			$array       = [(object)['users' => [(object)['name' => 'Taylor']]]];
			$dottedArray = ['users' => ['first.name' => 'Taylor', 'middle.name' => null]];
			$arrayAccess = new SupportTestArrayAccess(['price' => 56, 'user' => new SupportTestArrayAccess(['name' => 'John']), 'email' => null]);
			$this->assertEquals('Taylor', data_get($object, 'users.name.0'));
			$this->assertEquals('Taylor', data_get($array, '0.users.0.name'));
			$this->assertNull(data_get($array, '0.users.3'));
			$this->assertEquals('Not found', data_get($array, '0.users.3', 'Not found'));
			$this->assertEquals('Not found', data_get($array, '0.users.3', function () {
				return 'Not found';
			}));
			$this->assertEquals('Taylor', data_get($dottedArray, ['users', 'first.name']));
			$this->assertNull(data_get($dottedArray, ['users', 'middle.name']));
			$this->assertEquals('Not found', data_get($dottedArray, ['users', 'last.name'], 'Not found'));
			$this->assertEquals(56, data_get($arrayAccess, 'price'));
			$this->assertEquals('John', data_get($arrayAccess, 'user.name'));
			$this->assertEquals('void', data_get($arrayAccess, 'foo', 'void'));
			$this->assertEquals('void', data_get($arrayAccess, 'user.foo', 'void'));
			$this->assertNull(data_get($arrayAccess, 'foo'));
			$this->assertNull(data_get($arrayAccess, 'user.foo'));
			$this->assertNull(data_get($arrayAccess, 'email', 'Not found'));
		}

		public function testDataGetWithNestedArrays() {
			$array = [
				['name' => 'taylor', 'email' => 'taylorotwell@gmail.com'],
				['name' => 'abigail'],
				['name' => 'dayle'],
			];
			$this->assertEquals(['taylor', 'abigail', 'dayle'], data_get($array, '*.name'));
			$this->assertEquals(['taylorotwell@gmail.com', null, null], data_get($array, '*.email', 'irrelevant'));
			$array = [
				'users' => [
					['first' => 'taylor', 'last' => 'otwell', 'email' => 'taylorotwell@gmail.com'],
					['first' => 'abigail', 'last' => 'otwell'],
					['first' => 'dayle', 'last' => 'rees'],
				],
				'posts' => null,
			];
			$this->assertEquals(['taylor', 'abigail', 'dayle'], data_get($array, 'users.*.first'));
			$this->assertEquals(['taylorotwell@gmail.com', null, null], data_get($array, 'users.*.email', 'irrelevant'));
			$this->assertEquals('not found', data_get($array, 'posts.*.date', 'not found'));
			$this->assertNull(data_get($array, 'posts.*.date'));
		}

		public function testDataGetWithDoubleNestedArraysCollapsesResult() {
			$array = [
				'posts' => [
					[
						'comments' => [
							['author' => 'taylor', 'likes' => 4],
							['author' => 'abigail', 'likes' => 3],
						],
					],
					[
						'comments' => [
							['author' => 'abigail', 'likes' => 2],
							['author' => 'dayle'],
						],
					],
					[
						'comments' => [
							['author' => 'dayle'],
							['author' => 'taylor', 'likes' => 1],
						],
					],
				],
			];
			$this->assertEquals(['taylor', 'abigail', 'abigail', 'dayle', 'dayle', 'taylor'], data_get($array, 'posts.*.comments.*.author'));
			$this->assertEquals([4, 3, 2, null, null, 1], data_get($array, 'posts.*.comments.*.likes'));
			$this->assertEquals([], data_get($array, 'posts.*.users.*.name', 'irrelevant'));
			$this->assertEquals([], data_get($array, 'posts.*.users.*.name'));
		}

		public function testDataGetGetter() {
			$mock = $this->getMockBuilder('MyClass')->setMethods(['getA'])->getMock();
			$mock
				->expects($this->once())
				->method('getA')
				->willReturn(7);

			$res = data_get($mock, 'getA()');

			$this->assertEquals(7, $res);
		}

		public function testDataGetGetterRecursive() {
			$mock2 = $this->getMockBuilder('MyClass')->setMethods(['getB'])->getMock();
			$mock2
				->expects($this->once())
				->method('getB')
				->willReturn(9);

			$mock1 = $this->getMockBuilder('MyClass')->setMethods(['getA'])->getMock();
			$mock1
				->expects($this->once())
				->method('getA')
				->willReturn($mock2);

			$res = data_get($mock1, 'getA().getB()');

			$this->assertEquals(9, $res);
		}

		public function testDataGetGetterNotAccessible() {

			$mock1 = $this->getMockBuilder('MyClass')->getMock();

			$res = data_get($mock1, 'getA()', 17);

			$this->assertEquals(17, $res);
		}

	}

	class SupportTestArrayAccess implements ArrayAccess
	{
		protected $attributes = [];

		public function __construct($attributes = []) {
			$this->attributes = $attributes;
		}

		public function offsetExists($offset) {
			return array_key_exists($offset, $this->attributes);
		}

		public function offsetGet($offset) {
			return $this->attributes[$offset];
		}

		public function offsetSet($offset, $value) {
			$this->attributes[$offset] = $value;
		}

		public function offsetUnset($offset) {
			unset($this->attributes[$offset]);
		}
	}