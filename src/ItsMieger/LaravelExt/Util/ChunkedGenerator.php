<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 11.12.18
	 * Time: 17:31
	 */

	namespace ItsMieger\LaravelExt\Util;


	/**
	 * Implements a generator yielding the generated items from a given callback. The callback receives the data in chunks of given size.
	 * @package ItsMieger\LaravelExt\Util
	 */
	class ChunkedGenerator
	{
		/**
		 * @var int
		 */
		protected $bufferSize;

		/**
		 * @var \Traversable|array|\Generator
		 */
		protected $data;

		/**
		 * @var callable
		 */
		protected $flushHandler;

		/**
		 * @var callable
		 */
		protected $collectionResolver;

		/**
		 * Creates a new instance
		 * @param \Traversable|array|\Closure $data The data to split into chunks. May also be a closure returning the data
		 * @param int $size The buffer size. You may pass 0 or a negative value if the buffer should not be flushed automatically.
		 * @param callable $generator Handler function which will be called for each chunk. It will receive buffer contents as first parameter and is expected to return a generator.
		 * @param callable $collectionResolver Resolver for the underlying collection. This is called each time an empty collection is initialized and must return an
		 * empty collection instance. If omitted an array is used as underlying collection.
		 */
		public function __construct($data, $size, callable $generator, $collectionResolver = null) {
			$this->data               = $data;
			$this->bufferSize         = $size;
			$this->flushHandler       = $generator;
			$this->collectionResolver = $collectionResolver;
		}

		/**
		 * Gets the size of the buffer
		 * @return int The buffer size
		 */
		public function getBufferSize() {
			return $this->bufferSize;
		}

		/**
		 * Creates a new buffer
		 * @return array|mixed The new collection
		 */
		protected function newBuffer() {

			if ($resolver = $this->collectionResolver) {
				// use collection resolver

				if (is_string($resolver) && !function_exists($resolver)) {
					// string, but not function name => resolve via service container
					return app($resolver, ['size' => $this->bufferSize]);
				}
				else {
					// resolve callable
					return call_user_func($resolver);
				}
			}

			// simply use array
			return [];
		}


		/**
		 * Consumes all elements from the generator
		 * @return \Generator
		 */
		public function consume() {
			$flushHandler = $this->flushHandler;
			$bufferSize = $this->bufferSize;

			$buffer = $this->newBuffer();

			$i = 0;
			foreach(value($this->data) as $curr) {
				$buffer[] = $curr;

				++$i;
				if ($i == $bufferSize) {
					foreach(call_user_func($flushHandler, $buffer) as $currValue) {
						yield $currValue;
					};

					$buffer = $this->newBuffer();
					$i = 0;
				}
			}

			if ($i > 0) {
				foreach (call_user_func($flushHandler, $buffer) as $currValue) {
					yield $currValue;
				};
			}

		}
	}