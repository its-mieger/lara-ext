<?php
	if (!function_exists('buffer')) {
		/**
		 * Creates a buffer which is automatically flushed if it is full.
		 * @param int $size The buffer size
		 * @param callable $flushHandler Handler function which will be called on flush and receive the buffer contents as first parameter
		 * @param callable $collectionResolver Resolver for the underlying collection. This is called each time an empty collection is initialized and must return an
		 * empty collection instance. If omitted an array is used as underlying collection.
		 * @return \ItsMieger\LaravelExt\Util\FlushingBuffer
		 */
		function buffer($size, callable $flushHandler, $collectionResolver = null) {
			return new \ItsMieger\LaravelExt\Util\FlushingBuffer($size, $flushHandler, $collectionResolver);
		}
	}