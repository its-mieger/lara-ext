<?php
	if (!function_exists('buffer')) {
		/**
		 * Creates a buffer which is automatically flushed if it is full.
		 * @param int $size The buffer size
		 * @param callable $flushHandler Handler function which will be called on flush and receive the buffer contents as first parameter
		 * @param callable $collectionResolver Resolver for the underlying collection. This is called each time an empty collection is initialized and must return an
		 * empty collection instance. If omitted an array is used as underlying collection. Also a function name or a class name which is resolved via service container may be passed.
		 * @return \ItsMieger\LaravelExt\Util\FlushingBuffer
		 */
		function buffer($size, callable $flushHandler, $collectionResolver = null) {
			return app(\ItsMieger\LaravelExt\Util\FlushingBuffer::class, [
				'size'               => $size,
				'flushHandler'       => $flushHandler,
				'collectionResolver' => $collectionResolver,
			]);
		}
	}

	if (!function_exists('chunked')) {

		/**
		 * Calls the given handler with chunks of the provided data. The last chunk's size may by less than the
		 * specified chunk size. This function "streams" the data using a flushing buffer internally.
		 * @param \Iterator|IteratorAggregate|ArrayAccess|[]|\Closure $data The source data
		 * @param int $size The chunk size
		 * @param callable $flushHandler Handler function which will receive the chunk data
		 * @param callable|string $collectionResolver Resolver for the underlying collection. This is called each time an empty collection is initialized and must return an
		 * empty collection instance. If omitted an array is used as underlying collection. Also a function name or a class name which is resolved via service container may be passed.
		 */
		function chunked($data, $size, callable $flushHandler, $collectionResolver = null) {

			// setup buffer
			$buffer = app(\ItsMieger\LaravelExt\Util\FlushingBuffer::class, [
				'size'               => $size,
				'flushHandler'       => $flushHandler,
				'collectionResolver' => $collectionResolver,
			]);

			// "stream" to buffer
			foreach(value($data) as $curr) {
				$buffer->add($curr);
			}

			// flush the rest
			$buffer->flush();
		}
	}