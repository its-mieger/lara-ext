<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 09.04.18
	 * Time: 11:55
	 */

	namespace ItsMiegerLaravelExtTest\Cases;


	use Illuminate\Support\Str;

	class StrMacrosTest extends TestCase
	{

		public function testExtractLeft() {

			[$vA, $vB, $vC] = Str::extract('a:b:c', ':', 3);

			$this->assertSame('a', $vA);
			$this->assertSame('b', $vB);
			$this->assertSame('c', $vC);
		}

		public function testExtractLeftMoreWanted() {

			[$vA, $vB, $vC, $vD, $vE] = Str::extract('a:b:c', ':', 5);

			$this->assertSame('a', $vA);
			$this->assertSame('b', $vB);
			$this->assertSame('c', $vC);
			$this->assertSame(null, $vD);
			$this->assertSame(null, $vE);
		}

		public function testExtractLeftLessWanted() {

			[$vA, $vB, $vC] = Str::extract('a:b:c:d', ':', 3);

			$this->assertSame('a', $vA);
			$this->assertSame('b', $vB);
			$this->assertSame('c', $vC);
		}

		public function testExtractLeftLessWantedAppend() {

			[$vA, $vB, $vC] = Str::extract('a:b:c:d', ':', 3, null, true);

			$this->assertSame('a', $vA);
			$this->assertSame('b', $vB);
			$this->assertSame('c:d', $vC);
		}

		public function testExtractLeftDefaultValue() {

			[$vA, $vB, $vC, $vD, $vE] = Str::extract('a:b:c', ':', 5, 'def');

			$this->assertSame('a', $vA);
			$this->assertSame('b', $vB);
			$this->assertSame('c', $vC);
			$this->assertSame('def', $vD);
			$this->assertSame('def', $vE);
		}


		public function testExtractRight() {

			[$vC, $vD, $vE] = Str::extract('a:b:c', ':', -3);

			$this->assertSame('a', $vC);
			$this->assertSame('b', $vD);
			$this->assertSame('c', $vE);
		}

		public function testExtractRightMoreWanted() {

			[$vA, $vB, $vC, $vD, $vE] = Str::extract('a:b:c', ':', -5);

			$this->assertSame(null, $vA);
			$this->assertSame(null, $vB);
			$this->assertSame('a', $vC);
			$this->assertSame('b', $vD);
			$this->assertSame('c', $vE);
		}

		public function testExtractRightLessWanted() {

			[$vC, $vD, $vE] = Str::extract('a:b:c:d', ':', -3);

			$this->assertSame('a', $vC);
			$this->assertSame('b', $vD);
			$this->assertSame('c', $vE);
		}

		public function testExtractRightLessWantedAppend() {

			[$vC, $vD, $vE] = Str::extract('a:b:c:d', ':', -3, null, true);

			$this->assertSame('a', $vC);
			$this->assertSame('b', $vD);
			$this->assertSame('c:d', $vE);
		}

		public function testExtractRightDefaultValue() {

			[$vA, $vB, $vC, $vD, $vE] = Str::extract('a:b:c', ':', -5, 'def');

			$this->assertSame('def', $vA);
			$this->assertSame('def', $vB);
			$this->assertSame('a', $vC);
			$this->assertSame('b', $vD);
			$this->assertSame('c', $vE);
		}
	}