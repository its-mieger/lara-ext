<?php
	namespace ItsMieger\LaravelExt\Util;


	/**
	 * Implements a buffer which is automatically flushed if it is full.
	 * @package ItsMieger\LaravelExt\Util
	 */
	class FlushingBuffer
	{
		/**
		 * @var int
		 */
		protected $bufferSize;

		/**
		 * @var array
		 */
		protected $data = [];

		/**
		 * @var callable
		 */
		protected $flushHandler;

		/**
		 * @var int The data count
		 */
		protected $dataCount;

		/**
		 * @var callable
		 */
		protected $collectionResolver;

		/**
		 * Creates a new instance
		 * @param int $size The buffer size
		 * @param callable $flushHandler Handler function which will be called on flush and receive the buffer contents as first parameter
		 * @param callable $collectionResolver Resolver for the underlying collection. This is called each time an empty collection is initialized and must return an
		 * empty collection instance. If omitted an array is used as underlying collection.
		 */
		public function __construct($size, callable $flushHandler, $collectionResolver = null) {
			$this->bufferSize         = $size;
			$this->flushHandler       = $flushHandler;
			$this->collectionResolver = $collectionResolver;

			// initialize the collection
			$this->initCollection();
		}

		/**
		 * Adds the given item to the buffer
		 * @param mixed $item The item
		 * @return $this This instance
		 */
		public function add($item) {

			// add item
			$this->data[] = $item;
			++$this->dataCount;

			// do we have to flush the buffer?
			if ($this->dataCount >= $this->bufferSize)
				$this->flush();

			return $this;
		}

		/**
		 * Returns if the buffer item count
		 * @return int The number of items in the buffer
		 */
		public function count() {
			return $this->dataCount;
		}

		/**
		 * Gets the size of the buffer
		 * @return int The buffer size
		 */
		public function getBufferSize() {
			return $this->bufferSize;
		}

		/**
		 * Flushes all data in the buffer
		 * @param bool $flushEmpty If true, the flush handle is invoked event if the buffer is empty.
		 * @return mixed The flush handler return if called. Else null.
		 */
		public function flush($flushEmpty = false) {
			$ret = null;

			if ($flushEmpty || $this->dataCount > 0) {
				$ret = call_user_func($this->flushHandler, $this->data);

				// re-init collection
				$this->initCollection();
			}

			return $ret;
		}

		/**
		 * Initializes the collection
		 */
		protected function initCollection() {

			if ($resolver = $this->collectionResolver) {
				// use collection resolver

				if (is_string($resolver) && !function_exists($resolver)) {
					// string, but not function name => resolve via service container
					$this->data = app($resolver, ['size' => $this->bufferSize]);
				}
				else {
					// resolve callable
					$this->data = call_user_func($resolver);
				}
			}
			else {
				// simply use array
				$this->data = [];
			}

			$this->dataCount = 0;
		}
	}